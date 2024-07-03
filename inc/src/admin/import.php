<?php

namespace Routiz\Inc\Src\Admin;

use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Wallet;

class Import {

    use \Routiz\Inc\Src\Traits\Singleton;

    function __construct() {

        // add import page to rz_listing
        add_action( 'admin_menu' , [ $this, 'add_submenu_page' ] );

    }

    public function enqueue_menu_scripts() {
        Panel::instance();
    }

    public function add_submenu_page() {

        /*add_submenu_page(
            'edit.php?post_type=rz_listing_type', // parent slug
            esc_html__('Demo Import', 'routiz'), // page title
            esc_html__('Demo Import', 'routiz'), // menu title
            'manage_options', // capability
            'rz_import', // menu slug
            [ $this, 'import_page_output' ]
        );*/

        add_action( sprintf( 'load-%s',
            add_submenu_page(
                'edit.php?post_type=rz_listing_type', // parent slug
                esc_html__('Demo Import', 'routiz'), // page title
                esc_html__('Demo Import', 'routiz'), // menu title
                'manage_options', // capability
                'rz_import', // menu slug
                [ $this, 'import_page_output' ]
            )),
            [ $this, 'enqueue_menu_scripts' ]
        );

    }

    public function import_page_output() {

        Rz()->the_template('admin/import/import');

    }

}
