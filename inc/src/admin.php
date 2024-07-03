<?php

namespace Routiz\Inc\Src;

// use \Routiz\Inc\Src\Form\Modules;

class Admin {

    use \Routiz\Inc\Src\Traits\Singleton;

    function __construct() {

        if( is_admin() ) {

            Admin\Settings::instance();
            Admin\Metabox::instance();
            Admin\Term_Metabox::instance();
            Admin\Comments::instance();
            Admin\Import::instance();
            Admin\Columns\Listing_Type::instance();
            Admin\Columns\Listing::instance();
            Admin\Columns\Entry::instance();
            Admin\Columns\Report::instance();
            Admin\Columns\Plan::instance();
            Admin\Columns\Promotion::instance();
            Admin\Columns\Claim::instance();
            Admin\Http\Xhr::instance();

            add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

            add_action( 'image_size_names_choose', [ $this, 'default_image_sizes' ] );

            add_action( 'display_post_states', [ $this, 'page_states' ], 10, 2 );

            add_action( 'admin_head-post.php', [ $this, 'listing_type_duplicate' ] );

        }

    }

    function default_image_sizes( $sizes ) {
        return array_merge( $sizes, [
            'rz_thumbnail' => esc_html__( 'Routiz Thumbnail', 'routiz' ),
        ]);
    }

    function get_admin_vars() {

        $vars = [
            'post_id' => null,
            'debug' => Rz()->debug,
            'is_admin' => true,
            'admin_ajax' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ajax-nonce'),
            'site_url' => site_url('/'),
            'uri_assets' => RZ_URI,
            'localize' => [],
            'sdk' => [
                'map_provider' => Brk()->get_map_provider(),
            ],
            'map' => [
                'style' => get_option('rz_google_map_style', 'default'),
                'geolocation_restrictions' => Rz()->get_geolocation_restrictions()
            ],
        ];

        if( Rz()->get_map_provider() == 'mapbox' ) {
            $vars['sdk']['mapbox'] = get_option( 'rz_mapbox_access_token' );
            $vars['sdk']['mapbox_style_url'] = get_option( 'rz_mapbox_style_url' );
        }

        global $post;
        if( isset( $post->ID ) ) {
            $vars['post_id'] = $post->ID;
        }

        return $vars;

    }

    function enqueue_scripts( $hook ) {

        // media
        wp_enqueue_media();
        // colorpicker
        // wp_enqueue_style( 'wp-color-picker' );

        /*
         * google fonts
         *
         */
        wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Open+Sans:400,600,700|Poppins:400,700,800', false );

        $map_provider = Brk()->get_map_provider();

        /*
         * map provider
         *
         */
        if( $map_provider == 'google' ) {
            wp_register_script( 'map-apis', sprintf( '%s://maps.googleapis.com/maps/api/js?&key=%s&libraries=places', 'https', get_option( 'rz_google_api_key' ) ), [], RZ_VERSION, true );
        }else{
            wp_register_script( 'map-apis', sprintf( '%s://api.tiles.mapbox.com/mapbox-gl-js/v1.9.0/mapbox-gl.js', 'https' ), [], RZ_VERSION, true );
            wp_add_inline_script('map-apis', 'window.mapboxgl.accessToken = window.rz_vars.sdk.mapbox');
            wp_enqueue_style( 'map-apis-css', sprintf( '%s://api.tiles.mapbox.com/mapbox-gl-js/v1.9.0/mapbox-gl.css', 'https' ), [], RZ_VERSION );
        }

        /*
         * icons
         *
         */
        wp_enqueue_style( 'font-awesome-5', RZ_URI . 'assets/dist/fonts/font-awesome/css/all.min.css', [], RZ_VERSION );
        wp_enqueue_style( 'material-icons', RZ_URI . 'assets/dist/fonts/material-icons/style.css', [], RZ_VERSION );
        wp_enqueue_style( 'amenities-icons', RZ_URI . 'assets/dist/fonts/amenities/style.css', [], RZ_VERSION );

        // enqueue custom icon sets
		foreach( routiz()->icon->custom_sets as $set_id => $set ) {
			if( isset( $set['css_url'] ) && $set['css_url'] !== '' ) {
				wp_enqueue_style( 'rz-custom-icons-' . $set_id, $set['css_url'], [], RZ_VERSION, 'all' );
			}
		}

        /*
         * admin
         *
         */
        wp_register_script( 'rz-admin', RZ_URI . 'assets/dist/js/admin/admin.js', ['jquery'], RZ_VERSION, true );
        wp_localize_script( 'rz-admin', 'rz_vars', $this->get_admin_vars() );
        wp_enqueue_script( 'rz-admin' );
        wp_enqueue_style( 'rz-admin', RZ_URI . 'assets/dist/css/admin/admin.css', [], RZ_VERSION );

        /*global $post;

        if( $hook == 'post-new.php' or $hook == 'post.php' ) {
            if( $post->post_type === 'rz_listing' ) {

            }
        }*/

        /*
         * form
         *
         */
        wp_register_script( 'rz-form', RZ_URI . 'assets/dist/js/form.js', ['jquery', 'jquery-ui-sortable', 'select2', 'map-apis'], RZ_VERSION, true );
        wp_register_style( 'rz-form', RZ_URI . 'assets/dist/css/form.css', ['select2'], RZ_VERSION );

        /*
         * panel
         *
         */
        wp_register_script( 'rz-panel', RZ_URI . 'assets/dist/js/admin/panel.js', ['rz-form', 'vuejs'], RZ_VERSION, true );
        wp_register_style( 'rz-panel', RZ_URI . 'assets/dist/css/admin/panel.css', ['rz-form'], RZ_VERSION );

        /*
         * vuejs
         *
         */
        wp_register_script( 'vuejs', RZ_URI . 'assets/dist/lib/vuejs/vue' . Rz()->assets_min . '.js', [], RZ_VERSION, true );

        /*
         * select2
         *
         */
        wp_register_script( 'select2', RZ_URI . 'assets/dist/lib/select2/js/select2' . Rz()->assets_min . '.js', [], RZ_VERSION, true );
        wp_register_style( 'select2', RZ_URI . 'assets/dist/lib/select2/css/select2' . Rz()->assets_min . '.css' );

    }

    public function listing_type_duplicate() {

        global $post;

        if( current_user_can( 'edit_post', $post->ID ) ) {
            if( $post->post_type == 'rz_listing_type' and isset( $_GET['duplicate'] ) ) {

                $title = get_the_title( $post->ID );

                $duplicate_args = [
                    'post_title' => sprintf( '%s (copy)', $title ),
                    'post_status' => 'publish',
                    'post_type' => 'rz_listing_type',
                    'post_author' => get_current_user_id()
                ];

                $duplicate_post_id = wp_insert_post( $duplicate_args );

                // meta
                $data = get_post_custom( $post->ID );

                foreach( $data as $key => $values ) {
                    foreach( $values as $value ) {
                        add_post_meta( $duplicate_post_id, $key, $value );
                    }
                }

                update_post_meta( $duplicate_post_id, 'rz_name', sprintf( '%s (copy)', esc_html( Rz()->get_meta( 'rz_name', $post->ID ) ) ) );
                update_post_meta( $duplicate_post_id, 'rz_name_plural', sprintf( '%s (copy)', esc_html( Rz()->get_meta( 'rz_name_plural', $post->ID ) ) ) );
                update_post_meta( $duplicate_post_id, 'rz_slug', sprintf( '%s-copy', esc_html( Rz()->get_meta( 'rz_slug', $post->ID ) ) ) );

                wp_redirect( admin_url('edit.php?post_type=rz_listing_type') );
                exit;

            }
        }

    }

    static function page_states( $post_states, $post ) {

    	if( $post->post_type == 'page' ) {
            switch( $post->ID ) {
                case get_option('rz_page_explore'):
                    $post_states[] = esc_html__( 'Explore Page', 'routiz' );
                        break;
                case get_option('rz_page_submission'):
                    $post_states[] = esc_html__( 'Submission Page', 'routiz' );
                        break;
            }
    	}

    	return $post_states;

    }

}
