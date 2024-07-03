<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Importer;

if ( ! defined('ABSPATH') ) {
	exit;
}

// TODO: move methods to importer class
class Endpoint_Demo_Import extends Endpoint {

	public $action = 'rz_demo_import';

    public function action() {

		set_time_limit(0);

		$request = Request::instance();
		$perform = $request->get('perform');

		// TODO: use method __call
		switch( $perform ) {
			case 'prepare':
				$this->prepare(); break;
			case 'posts':
				$this->posts(); break;
			case 'options':
				$this->options(); break;
			case 'menus':
				$this->menus(); break;
			case 'static-pages':
				$this->static_pages(); break;
			case 'permalink':
				$this->permalink(); break;
			case 'finalize':
				$this->finalize(); break;
		}

	}

	protected function prepare() {

		$request = Request::instance();
		$importer = Importer::instance();

		$demo_id = $request->get('demo');
		$demos = $importer->get_demos();

		if( $request->is_empty('demo') or ! array_key_exists( $demo_id, $demos ) ) {
			wp_send_json([
				'success' => false,
				'error' => esc_html__( 'Please select demo', 'routiz' )
			]);
		}

		$queue = [];
		$path_to_posts = $demos[ $demo_id ]['path'] . 'posts/';
		$xmls = glob( $path_to_posts . '*.xml' );

		if( ! $xmls ) {
			wp_send_json([
				'success' => false,
				'error' => esc_html__( 'Something went wrong', 'routiz' )
			]);
		}

		$queue[] = (object) [
			'perform' => 'options',
			'message' => esc_html__( 'Importing options', 'routiz' )
		];

		foreach( $xmls as $xml ) {
			$queue[] = (object) [
				'perform' => 'posts',
				'message' => sprintf( esc_html__( 'Importing content from %s', 'routiz' ), basename( $xml ) )
			];
		}

		$queue[] = (object) [
			'perform' => 'menus',
			'message' => esc_html__( 'Assigning menus', 'routiz' )
		];

		$queue[] = (object) [
			'perform' => 'static-pages',
			'message' => esc_html__( 'Adding static pages', 'routiz' )
		];

		$queue[] = (object) [
			'perform' => 'permalink',
			'message' => esc_html__( 'Re-saving permalink format', 'routiz' )
		];

		$queue[] = (object) [
			'perform' => 'finalize',
			'message' => esc_html__( 'Finalizing', 'routiz' )
		];

		wp_send_json([
			'success' => true,
			'queue' => $queue
		]);

	}

	protected function get_demo() {

		$importer = Importer::instance();
		$request = Request::instance();

		$demos = $importer->get_demos();
		return $demos[ $request->get('demo') ];

	}

	protected function posts() {

        ob_start();

		$request = Request::instance();
		$importer = Importer::instance();

		$index = (int) $request->get('index');
		$demo = $this->get_demo();

		$path_to_posts = $demo['path'] . 'posts/';
		$post_files = glob( $path_to_posts . '*.xml' );

        $importer->wp_importer()->import( $post_files[ $index - 1 ] );

		wp_send_json([
			'success' => true,
			'output' => ob_get_clean()
		]);

	}

	protected function options() {

		$demo = $this->get_demo();
		$path_to_options = $demo['path'] . 'options.json';

		if( file_exists( $path_to_options ) ) {
			ob_start();
		    include $path_to_options;
		    $json = ob_get_clean();
			$data = json_decode( $json );
			foreach( (array) $data as $key => $value ) {
				if( Rz()->is_prefixed( $key ) ) {
					update_option( $key, $value );
				}
			}
		}

		wp_send_json([
			'success' => true
		]);

	}

	protected function menus() {

		$importer = Importer::instance();

		$locations = [];

        foreach( $importer->menus as $menu_location => $menu_name ) {

            $menu = get_term_by( 'name', $menu_name, 'nav_menu' );

            if( is_object( $menu ) ) {
                $locations[ $menu_location ] = $menu->term_id;
            }
        }

        if( count( $locations ) ) {
            set_theme_mod( 'nav_menu_locations', $locations );
        }

		wp_send_json([
			'success' => true
		]);

	}

	protected function static_pages() {

		$importer = Importer::instance();

        update_option( 'show_on_front', 'page' );

        // static front page
        $about = get_page_by_title( $importer->static_pages['home_page_name'] );
        if( is_object( $about ) ) {
            update_option( 'page_on_front', $about->ID );
        }

        // static blog page
        $blog = get_page_by_title( $importer->static_pages['blog_page_name'] );
        if( is_object( $blog ) ) {
            update_option( 'page_for_posts', $blog->ID );
        }

		wp_send_json([
			'success' => true
		]);

	}

	protected function permalink() {

		add_action('init', function() {

			global $wp_rewrite;

	        $wp_rewrite->set_permalink_structure('/%postname%/');
			update_option( 'rewrite_rules', false );
	        $wp_rewrite->flush_rules( true );

		});

		wp_send_json([
			'success' => true
		]);

	}

	// TODO: improve this
	protected function replace_image_ids() {

		$the_query = new \WP_Query([
		    'post_type' => [
		        'page',
		        'post',
		        'rz_listing',
		        'rz_listing_type',
		    ],
		    'posts_per_page' => -1
		]);

		if( $the_query->have_posts() ) {

		    $import_attachment_id = (int) get_option('rz_import_attachment_id', true);

		    while( $the_query->have_posts() ) { $the_query->the_post();

		        $post_id = get_the_ID();
		        $post_metas = get_post_meta( $post_id, '', true );

		        if( is_array( $post_metas ) ) {
		            foreach( $post_metas as $key => $post_meta ) {

		                $meta_single_value = $post_meta[0];

		                if( ! empty( $meta_single_value ) and ! empty( $import_attachment_id ) and Rz()->is_prefixed( $key ) ) {

		                    if( substr( $meta_single_value, 0, 3 ) === '[{"' ) {

		                        $new_meta_value = preg_replace( '/{"id":"?([0-9]+)"?}/i', '{"id":"' . $import_attachment_id . '"}', $meta_single_value );
		                        update_post_meta( $post_id, $key, $new_meta_value );

		                    }
		                }
		            }
		        }
		    }
		}

		wp_reset_postdata();

	}

	protected function finalize() {

		// set explore page
		$explore_page = get_page_by_title('Explore');
		if( isset( $explore_page->ID ) ) {
			update_option( 'rz_page_explore', (int) $explore_page->ID );
		}

		// set submission page
		$submission_page = get_page_by_title('Submission');
		if( isset( $submission_page->ID ) ) {
			update_option( 'rz_page_submission', (int) $submission_page->ID );
		}

		$this->replace_image_ids();

		// enable user registration
		update_option( 'users_can_register', true );

		// set demo finish flag
		update_option( 'rz_is_demo_imported', true );

		do_action('routiz/importer/finalize');

		wp_send_json([
			'success' => true
		]);

	}

}
