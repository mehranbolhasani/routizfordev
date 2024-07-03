<?php

namespace Routiz\Inc\Src;

use \Routiz\Inc\Src\Traits\Singleton;

class Importer {

    use Singleton;

    // Define the property explicitly to avoid deprecation warning
    public $version = '1.0';
    public $is_active_envato_market = false;
    public $themes;
    public $menus = [
        'primary' => 'Primary',
    ];
    public $static_pages = [
        'home_page_name' => 'Homepage',
        'blog_page_name' => 'Tips and Tricks'
    ];
    public $requirements;

    function __construct() {

        $this->init_globals();

    }

    protected function init_globals() {

        $this->requirements = [
            'max_execution_time' => ini_get('max_execution_time'),
            'memory_limit' => ini_get('memory_limit'),
            'post_max_size' => ini_get('post_max_size'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
        ];

    }

    public function get_themes() {

        if( $this->is_active_envato_market = is_plugin_active('envato-market/envato-market.php') ) {
            return get_site_transient( envato_market()->get_option_name() . '_themes' );
        }

        return;

    }

    public function is_envato_verified() {

        if( defined('RZ_VERIFIED') and RZ_VERIFIED == true ) {
            return true;
        }

        $themes = $this->get_themes();

        if( isset( $themes['purchased'] ) ) {
            foreach( $themes['purchased'] as $purchased ) {
                if( $purchased['name'] == 'Brikk' ) {
                    return true;
                }
            }
        }

        return;

    }

    public function get_demos() {

        return apply_filters( 'routiz/importer/demos', [] );

    }

    public function wp_importer() {

        require_once( RZ_PATH . 'inc/lib/wordpress-importer/wordpress-importer.php' );

        $wp_importer = new \WP_RZ_Import();
        $wp_importer->fetch_attachments = true;

        return $wp_importer;
    }

}
