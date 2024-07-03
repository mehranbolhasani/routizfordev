<?php

namespace Routiz\Inc\Src;

use \Routiz\Inc\Src\Listing\Views;
use \Routiz\Inc\Src\Listing\Listing;

class Setup {

    use \Routiz\Inc\Src\Traits\Singleton;

    function __construct() {

        // scripts
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

        // thumbnails
        add_action( 'after_setup_theme', [ $this, 'add_image_sizes' ] );

        // forgery
        add_action( 'wp', [ $this, 'forgery' ] );

        // content
        add_action( 'the_content', [ $this, 'content' ] );

        // templates
        add_filter( 'template_include', [ $this, 'template_include' ] );

        // render modal
        add_action( 'wp_footer', [ $this, 'modal' ] );

        // listing views
        add_action( 'wp_head', [ $this, 'views' ] );

        // listing json-ld
        add_action( 'wp_head', [ $this, 'json_ld' ] );

        // admin menu bar
        add_action( 'admin_bar_menu', [ $this, 'toolbar_add_listing_type_to_listing' ], 80 );
        add_action( 'admin_bar_menu', [ $this, 'toolbar_add_listing_type_to_submission' ], 80 );
        add_action( 'admin_bar_menu', [ $this, 'toolbar_add_explore_to_listing_type' ], 80 );

        // add master class to body
        add_filter( 'body_class', function( $classes ) {
            return array_merge( $classes, $this->classes(), [ 'routiz' ] );
        });

        add_filter( 'script_loader_tag', [ $this, 'script_loader_tag' ], 10, 2 );

    }

    protected function classes() {

        $classes = [];

        if( Rz()->is_explore() ) {

            $classes[] = 'rz-is-explore';

            global $rz_explore;

            $rz_search_form = $rz_explore->type ? $rz_explore->type->get('rz_search_form') : get_option('rz_global_search_form');

            $classes[] = sprintf( 'rz-explore-type--%s', esc_html( $rz_explore->get_display_type() ) );

            if( $rz_search_form ) {
                $classes[] = 'rz-explore--has-filters';
            }

            if( ! $rz_explore->request->is_empty('geo') ) {
                $classes[] = 'rz--explore-geo';
            }

        }

        if( Rz()->is_submission() ) {

            $classes[] = 'rz-is-submission';

        }

        return $classes;

    }

    public function script_loader_tag( $tag, $handle ) {

        if( $handle == 'googleapis-platform' ) {
            if( stripos( $tag, 'async' ) === false ) {
                $tag = str_replace(' src', ' async="async" src', $tag);
            }
            if( stripos( $tag, 'defer' ) === false ) {
                $tag = str_replace('<script ', '<script defer ', $tag);
            }
        }

        return $tag;

    }

    public function toolbar_add_listing_type_to_listing( $admin_bar ) {

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        if( ! is_single() or get_post_type() !== 'rz_listing' ) {
            return;
        }

        $post_obj = get_queried_object();
        $listing = new \Routiz\Inc\Src\Listing\Listing( $post_obj->ID );

        if( $listing->type->id ) {
            $admin_bar->add_menu([
                'id' => 'rz-edit-listing-type',
                'title' => 'Edit Listing Type',
                'href' => get_edit_post_link( $listing->type->id ),
                'meta' => [
                    'class' => 'rz-admin-bar-edit-listing-type',
                ]
            ]);
        }

    }

    public function toolbar_add_listing_type_to_submission( $admin_bar ) {

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        if( get_post_type() !== 'page' ) {
            return;
        }

        if( ! function_exists('routiz') or ! Rz()->is_submission() ) {
            return;
        }

        global $rz_submission;

        if( $rz_submission->listing_type->id ) {
            $admin_bar->add_menu([
                'id' => 'rz-edit-listing-type',
                'title' => 'Edit Listing Type',
                'href' => get_edit_post_link( $rz_submission->listing_type->id ),
                'meta' => [
                    'class' => 'rz-admin-bar-edit-listing-type',
                ]
            ]);
        }

    }

    public function toolbar_add_explore_to_listing_type( $admin_bar ) {

        global $pagenow;

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        if( $pagenow !== 'post.php' or get_post_type() !== 'rz_listing_type' ) {
            return;
        }

        if( ! $explore_page = Rz()->get_explore_page_url() ) {
            return;
        }

        $admin_bar->add_menu([
            'id' => 'rz-edit-explore-page',
            'title' => 'Edit Explore Page',
            'href' => add_query_arg( 'type', Rz()->get_meta( 'rz_slug', get_the_ID() ), $explore_page ),
            'meta' => [
                'class' => 'rz-admin-bar-edit-explore-page',
            ]
        ]);

    }

    public function add_image_sizes() {

        add_image_size( 'rz_thumbnail', 200, 9999 );
        add_image_size( 'rz_listing', 800, 528, true );
        add_image_size( 'rz_listing_temp', 50, 33, true );
        add_image_size( 'rz_gallery_preview', 800 );
        add_image_size( 'rz_gallery_large', 1920 );

    }

    public function get_vars() {

        global $rz_explore;

        $vars = [
            'post_id' => null,
            'debug' => Rz()->debug,
            'is_admin' => false,
            'admin_ajax' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ajax-nonce'),
            'site_url' => site_url('/'),
            'uri_assets' => RZ_URI,
            'date_format' => get_option('date_format'),
            'sdk' => [
                'map_provider' => Brk()->get_map_provider(),
                'facebook' => [
                    'app_id' => get_option('rz_facebook_app_id')
                ],
                'google' => [
                    'client_id' => get_option('rz_google_client_id')
                ],
            ],
            'localize' => [
                'select' => esc_html__( 'Select', 'routiz' ),
                'listing_views' => esc_html__( 'Listing views', 'routiz' )
            ],
            'pages' => [
                'explore' => Rz()->get_explore_page_url(),
                'submission' => Rz()->get_submission_page_url()
            ],
            'map' => [
                'style' => get_option('rz_google_map_style', 'default'),
                'geolocation_restrictions' => Rz()->get_geolocation_restrictions(),
                'style_custom' => Rz()->json_decode( get_option('rz_google_map_style_custom', '[]') ),
                'lat' => get_option('rz_global_map_lat'),
                'lng' => get_option('rz_global_map_lng'),
            ],
            'explore' => [
                'is_type' => ( is_object( $rz_explore ) && isset( $rz_explore->type ) ) ? boolval( $rz_explore->type ) : null,
            ],
            'chart' => [
                'colors' => [
                    'main' => apply_filters( 'routiz/chart/colors/main', '#333' )
                ]
            ]
        ];

        if( Rz()->get_map_provider() == 'mapbox' ) {
            $vars['sdk']['mapbox'] = get_option( 'rz_mapbox_access_token' );
            $vars['sdk']['mapbox_style_url'] = get_option( 'rz_mapbox_style_url' );
        }

        $page_object = get_queried_object();
        if( isset( $page_object->ID ) ) {
            $vars['post_id'] = $page_object->ID;
        }

        return $vars;
    }

    public function enqueue_scripts() {

        // icons
        wp_enqueue_style( 'font-awesome-5', RZ_URI . 'assets/dist/fonts/font-awesome/css/all.min.css', [], RZ_VERSION );
        wp_enqueue_style( 'material-icons', RZ_URI . 'assets/dist/fonts/material-icons/style.css', [], RZ_VERSION );
        wp_enqueue_style( 'amenities-icons', RZ_URI . 'assets/dist/fonts/amenities/style.css', [], RZ_VERSION );

        // enqueue custom icon sets
		foreach( routiz()->icon->get_custom_sets() as $set_id => $set ) {
			if( isset( $set['css_url'] ) and $set['css_url'] !== '' ) {
				wp_enqueue_style( 'rz-custom-icons-' . $set_id, $set['css_url'], [], RZ_VERSION, 'all' );
			}
		}

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
         * main
         *
         */
        wp_register_script( 'rz-main', RZ_URI . 'assets/dist/js/main.js', ['jquery', 'gsap'], RZ_VERSION, true );
        wp_localize_script( 'rz-main', 'rz_vars', $this->get_vars() );
        wp_enqueue_script( 'rz-main' );
        wp_enqueue_style( 'rz-style', RZ_URI . 'assets/dist/css/main.css', [], RZ_VERSION );

        /*
         * form
         *
         */
        wp_register_script( 'rz-form', RZ_URI . 'assets/dist/js/form.js', ['jquery', 'jquery-ui-sortable', 'select2', 'map-apis'], RZ_VERSION, true );
        wp_register_style( 'rz-form', RZ_URI . 'assets/dist/css/form.css', ['select2'], RZ_VERSION );

        /*
         * submission
         *
         */
        wp_register_script( 'rz-submission', RZ_URI . 'assets/dist/js/submission.js', ['jquery', 'rz-form'], RZ_VERSION, true );
        wp_register_style( 'rz-submission', RZ_URI . 'assets/dist/css/submission.css', ['rz-form'], RZ_VERSION );

        /*
         * explore
         *
         */
        wp_register_script( 'rz-explore', RZ_URI . 'assets/dist/js/explore.js', ['jquery', 'rz-form', 'map-apis', 'flickity'], RZ_VERSION, true );
        wp_register_style( 'rz-explore', RZ_URI . 'assets/dist/css/explore.css', ['rz-form'], RZ_VERSION );

        /*
         * listing
         *
         */
        wp_register_script( 'rz-listing', RZ_URI . 'assets/dist/js/listing.js', ['jquery', 'rz-form', 'map-apis'], RZ_VERSION, true );
        wp_register_style( 'rz-listing', RZ_URI . 'assets/dist/css/listing.css', ['rz-form'], RZ_VERSION );

        /*
         * gsap
         *
         */
        wp_register_script( 'gsap', RZ_URI . 'assets/dist/lib/gsap/gsap.min.js', [], RZ_VERSION, true );

        /*
         * vuejs
         *
         */
        wp_register_script( 'vuejs', RZ_URI . 'assets/dist/lib/vuejs/vue' . Rz()->assets_min . '.js', [], RZ_VERSION, true );

        /*
         * flickity
         *
         */
        wp_register_script( 'flickity', RZ_URI . 'assets/dist/lib/flickity/flickity.pkgd.min.js', [], RZ_VERSION, true );

        /*
         * select2
         *
         */
        wp_register_script( 'select2', RZ_URI . 'assets/dist/lib/select2/js/select2' . Rz()->assets_min . '.js', [], RZ_VERSION, true );
        wp_register_style( 'select2', RZ_URI . 'assets/dist/lib/select2/css/select2' . Rz()->assets_min . '.css', [], RZ_VERSION );

        /*
         * woocommerce
         *
         */
        wp_register_script( 'rz-account', RZ_URI . 'assets/dist/js/account.js', ['jquery'], RZ_VERSION, true );
        wp_register_style( 'rz-account', RZ_URI . 'assets/dist/css/account.css', [], RZ_VERSION );

        if( class_exists( 'WooCommerce' ) ) {
            \Routiz\Inc\Src\Woocommerce\Init::enqueue_script();
        }

        // facebook sdk
        $enable_fb_auth = get_option('rz_enable_facebook_auth');
        $fb_app_id = get_option('rz_facebook_app_id');
        $fb_app_secret = get_option('rz_facebook_app_secret');

        if( $enable_fb_auth and ! empty( $fb_app_id ) and ! empty( $fb_app_secret ) ) {
            wp_enqueue_script( 'facebook-sdk', 'https://connect.facebook.net/en_US/sdk.js', ['rz-main'], '1.0', true );
            add_action( 'wp_footer', [ $this, 'fb_root' ] );
        }

        // google sdk
        $enable_gg_auth = get_option('rz_enable_google_auth');
        $gg_client_id = get_option('rz_google_client_id');
        $gg_client_secret = get_option('rz_google_client_secret');

        if( ! is_user_logged_in() and $enable_gg_auth and ! empty( $gg_client_id ) and ! empty( $gg_client_secret ) ) {
            wp_enqueue_script( 'googleapis-platform', 'https://apis.google.com/js/platform.js?onload=rz_gapis_init', ['rz-main'], '1.0', true );
            add_action( 'wp_head', [ $this, 'gg_meta_api_key' ] );
        }

    }

    public function fb_root() {
        echo '<div id="fb-root"></div>';
    }

    public function gg_meta_api_key() {
        $gg_client_id = get_option('rz_google_client_id');
        echo sprintf( '<meta name="google-signin-client_id" content="%s">', esc_html( $gg_client_id ) );
    }

    public function forgery() {

        if( ! is_admin() and ! wp_doing_ajax() ) {

            $post_type = get_post_type();

            global $rz_explore;
            $rz_explore = \Routiz\Inc\Src\Explore\Explore::instance();

            Form\Init::instance();
            Explore\Init::instance();

            switch( $post_type ) {

                case 'rz_listing':
                    \Routiz\Inc\Src\Listing\Init::instance(); break;
                case 'rz_listing_type':
                    // Explore\Init::instance();
                    break;
                case 'page':

                    Page\Init::instance();

                    $post_id = get_the_ID();

                    switch( $post_id ) {

                        case get_option('rz_page_explore'):
                            // Explore\Init::instance();
                            break;
                        case get_option('rz_page_submission'):

                            Submission\Init::instance();

                            break;
                    }

                    break;

            }
        }
    }

    public function template_include( $template ) {

        if( is_singular() ) {
			if( Rz()->is_submission() ) {
				$template = Rz()->get_template_path('routiz/page-templates/submission');
			}
		}

		return $template;

    }

    public function content( $content ) {

        // bail on gutenberg update
        if( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
            return;
        }

        if( ! is_admin() and is_main_query() ) {

            switch( get_post_type() ) {

                case 'page':
                    $page_id = get_the_ID();

                    switch( $page_id ) {

                        case get_option('rz_page_explore'):
                            $content = Rz()->get_template('routiz/explore/explore'); break;
                            break;
                        // case get_option('rz_page_submission'):
                        //     $content = Rz()->get_template('routiz/submission/submission'); break;

                    }

                    break;

                case 'rz_listing_type':
                    $content = Rz()->get_template('routiz/listing-type/single'); break;
                case 'rz_listing':
                    $content = Rz()->get_template('routiz/single/single'); break;

            }

        }

        return $content;

    }

    public function modal() {

        global $post, $rz_explore;

        echo '<span class="rz-overlay"></span>';

        if( ! ( function_exists('is_account_page') and is_account_page() ) ) {
            Rz()->the_template('routiz/globals/signin/modal/modal');
        }

        Rz()->the_template('routiz/globals/conversation/modal/modal');
        Rz()->the_template('routiz/globals/favorites/modal/modal');

        if( is_object( $post ) and $post->post_type == 'rz_listing' ) {
            Rz()->the_template('routiz/single/modal/application');
            Rz()->the_template('routiz/single/modal/action-claim');
            if( get_option('rz_enable_share') ) {
                Rz()->the_template('routiz/single/modal/share');
            }
        }

        // if( Rz()->is_explore() ) {
        //     Rz()->the_template('routiz/explore/more-filters/modal');
        // }

    }

    public function views() {

        if ( ! is_single() ) {
            return;
        }

        if ( get_post_type() !== 'rz_listing' ) {
            return;
        }

        if( ! is_main_query() ) {
            return;
        }

        $views = new Views( get_the_ID() );
        $views->add();

    }

    public function json_ld() {

        if( is_single() and get_post_type() == 'rz_listing' ) {

            $listing = new Listing( get_the_ID() );

            if( $listing->type->get('rz_json_ld') ) {
                if( $json_ld = $listing->get_json_ld() ) {
                    echo sprintf( '<script type="application/ld+json">%s</script>', wp_json_encode( $json_ld ) );
                }
            }

        }

    }

    static function total_views( $user_id = null ) {

        if( is_null( $user_id ) ) {
            $user_id = get_current_user_id();
        }

    }

}
