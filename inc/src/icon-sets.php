<?php

namespace Routiz\Inc\Src;

use \Routiz\Inc\Src\Request\Request;

class Icon_Sets {

    use \Routiz\Inc\Src\Traits\Singleton;

    function __construct() {

        // register post type
        add_action( 'init', [ $this, 'register_post_types' ], 20 );

        // metabox
        add_action( 'add_meta_boxes' , [ $this, 'register_meta_boxes' ] );

        // save metabox
        add_action('save_post', [ $this, 'upload_package' ], 10, 3);
        add_action('post_edit_form_tag', [ $this, 'edit_form_tag' ]);

    }

    function register_post_types() {

        $singular = esc_html__( 'Icon Set', 'routiz' );
		$plural = esc_html__( 'Icon Sets', 'routiz' );

		$rewrite = [
			'slug' => 'icon-set',
			'with_front' => false,
			'feeds' => false,
			'pages' => false
		];

		register_post_type( 'rz_icon_set',
			apply_filters( 'routiz/post_type/icon-set', [
				'labels' => [
					'name' 					=> $plural,
					'singular_name' 		=> $singular,
					'menu_name'             => $plural,
					'all_items'             => sprintf( esc_html__( '%s', 'routiz' ), $plural ),
					'add_new' 				=> esc_html__( 'Add New', 'routiz' ),
					'add_new_item' 			=> sprintf( esc_html__( 'Add %s', 'routiz' ), $singular ),
					'edit' 					=> esc_html__( 'Edit', 'routiz' ),
					'edit_item' 			=> sprintf( esc_html__( 'Edit %s', 'routiz' ), $singular ),
					'new_item' 				=> sprintf( esc_html__( 'New %s', 'routiz' ), $singular ),
					'view' 					=> sprintf( esc_html__( 'View %s', 'routiz' ), $singular ),
					'view_item' 			=> sprintf( esc_html__( 'View %s', 'routiz' ), $singular ),
					'search_items' 			=> sprintf( esc_html__( 'Search %s', 'routiz' ), $plural ),
					'not_found' 			=> sprintf( esc_html__( 'No %s found', 'routiz' ), $plural ),
					'not_found_in_trash' 	=> sprintf( esc_html__( 'No %s found in trash', 'routiz' ), $plural ),
					'parent' 				=> sprintf( esc_html__( 'Parent %s', 'routiz' ), $singular ),
				],
                'taxonomies'            => [],
                'show_in_menu'          => 'edit.php?post_type=rz_listing_type',
				'public' 				=> false,
				'show_ui' 				=> true,
				'publicly_queryable' 	=> false,
				'exclude_from_search' 	=> true,
				'hierarchical' 			=> false,
				'rewrite' 				=> $rewrite,
				'query_var' 			=> true,
				'supports' 				=> [ 'title' ],
				'has_archive' 			=> false,
				'show_in_nav_menus' 	=> true,
                'capability_type'       => 'post',
                'map_meta_cap'          => true,
			])
		);

    }

    public function register_meta_boxes() {

        // listing types
        add_meta_box(
            'rz-icon-set-options',
            _x( 'Icon Set Options', 'Icon set options in wp-admin', 'routiz' ),
            [ $this, 'meta_boxes_icon_set' ],
            'rz_icon_set'
        );

    }

    static function meta_boxes_icon_set( $post ) {
        Rz()->the_template('admin/metabox/icon-set');
    }

    static function edit_form_tag( $post ) {
        if( $post->post_type == 'rz_icon_set' ) {
            echo ' enctype="multipart/form-data"';
        }
    }

    function upload_package( $post_id ) {

        if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if( ! current_user_can( 'upload_files' ) ) {
            return;
        }

        add_filter( 'map_meta_cap', [ $this,'unrestricted_upload_filter'], 0, 2 );

        // remove previous set
		$this->delete_set( $post_id );

        if( get_post_type( $post_id ) == 'rz_icon_set' && isset( $_POST ) && ! empty( $_POST ) ) {

            if( ! empty($_FILES['icon-package']['name'] ) ) {

        		// supported file types
        		$supported_types = [
                    'zip',
                    'application/zip',
                    'application/x-zip',
                    'application/x-zip-compressed',
                    'application/zip-compressed',
                    'application/easykaraoke.cdgdownload',
                ];

        		// get the file type
        		$mime_type = mime_content_type($_FILES['icon-package']['tmp_name']);

        		// check if the type is supported
        		if( in_array( $mime_type, $supported_types ) ) {

                    $attachment_id = media_handle_upload( 'icon-package', 0 );

                    if( $attachment_id ) {

                        // create the directory
                		if( ! file_exists( RZ_UPLOAD_PATH . 'utillz-icons/' ) ) {
                			wp_mkdir_p( RZ_UPLOAD_PATH . 'utillz-icons/' );
                		}

                        $package_path = get_attached_file( $attachment_id );
                		$status = false;

                		if( $package_path && file_exists( $package_path ) ) {

                			$icon_dir_name = $this->get_unique_dir_name( pathinfo( $package_path, PATHINFO_FILENAME ), RZ_UPLOAD_PATH . 'utillz-icons/' );
                			$icon_set_path = RZ_UPLOAD_PATH . 'utillz-icons/' . $icon_dir_name;

                			// create icon set directory
                			wp_mkdir_p( $icon_set_path );

                			// extract the zip file
                			if( class_exists( 'ZipArchive' ) ) {
                				$zip = new \ZipArchive();
                				if( true === $zip->open( $package_path ) ) {
                					$zip->extractTo( $icon_set_path );
                					$zip->close();
                					$status = true;
                				}
                			}else{
                				$status = unzip_file( $package_path, $icon_set_path );
                			}
                		}

                		if( $status == true ) {

                			$icon_set['icon_dir_name'] = $icon_dir_name;
                			$parsed_package = $this->parse_icons( $icon_dir_name );

                			foreach( $parsed_package as $key => $value ) {
                				$icon_set[ $key ] = $parsed_package[ $key ];
                			}

                			$icon_set['icon_set_id'] = md5( $post_id . $attachment_id );

                			update_post_meta( $post_id, 'rz_custom_icon_set', $icon_set );
                			update_post_meta( $post_id, 'rz_attachment_id', $attachment_id );
                			update_post_meta( $attachment_id, 'rz_icon_set_id', $icon_set['icon_set_id'] );

                		}

            		}

        		}else{
        			wp_die( esc_html__( 'The file type that you\'ve uploaded is not a ZIP archive.', 'routiz' ) );
        		}

        	}
        }
    }

    public function unrestricted_upload_filter( $caps, $cap ) {

		if( $cap == 'unfiltered_upload' ) {
			$caps = array();
			$caps[] = $cap;
		}

		return $caps;

	}

	public function delete_set( $post_id ) {

		global $wp_filesystem;

        // flush icon sets
        delete_transient('rz_custom_sets');

		if( empty( $wp_filesystem ) ) {
			require_once wp_normalize_path( ABSPATH . '/wp-admin/includes/file.php' );
			WP_Filesystem();
		}

		if( get_post_type( $post_id ) !== 'rz_icon_set' ) {
			return;
		}

		$icon_set = Rz()->get_meta( 'rz_custom_icon_set', $post_id );

		if( isset( $icon_set['icon_dir_name'] ) ) {
			$icon_set_path = RZ_UPLOAD_PATH . 'utillz-icons/' . $icon_set['icon_dir_name'];

			// delete set directory
			$wp_filesystem->rmdir( $icon_set_path, true );
		}

        if( $attachment_id = get_post_meta( $post_id, 'rz_attachment_id', true ) ) {
            wp_delete_attachment( $attachment_id );
        }

	}

	protected function get_unique_dir_name( $name, $parent_path ) {

		$parent_path = trailingslashit( $parent_path );
		$path = $parent_path . $name;

		$counter = 0;
		$tmp_name = $name;

		while( file_exists( $path ) ) {
			$counter++;
			$name = $tmp_name . '-' . $counter;
			$path = $parent_path . $name;
		}

		return $name;

	}

	protected function parse_icons( $icon_dir_name ) {

		// the configuration
		$icons_config = $this->get_package_config( $icon_dir_name );

		if( ! $icons_config ) {
			return [];
		}

		$parsed_package = [];
		$parsed_package['icons'] = [];

		foreach( $icons_config['icons'] as $icon ) {
			$parsed_package['icons'][] = $icon['properties']['name'];
		}

		$parsed_package['prefix'] = $icons_config['preferences']['fontPref']['prefix'];
		$parsed_package['icon_count'] = count( $parsed_package['icons'] );

		return $parsed_package;

	}

	protected function get_package_config( $icon_dir_name ) {

		global $wp_filesystem;

		if( empty( $wp_filesystem ) ) {
			require_once wp_normalize_path( ABSPATH . '/wp-admin/includes/file.php' );
			WP_Filesystem();
		}

		if ( ! isset( $this->package_config[ $icon_dir_name ] ) ) {
			$json_file = $wp_filesystem->get_contents( RZ_UPLOAD_PATH . 'utillz-icons/' . $icon_dir_name . '/selection.json' );
			$this->package_config[ $icon_dir_name ] = json_decode( $json_file, true );
		}

		return $this->package_config[ $icon_dir_name ];

	}

}
