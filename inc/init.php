<?php

namespace Routiz\Inc;

use \Routiz\Inc\Src\Traits\Singleton;

class Init {

    use Singleton;

    function __construct() {

        Src\Admin::instance();
        Src\Setup::instance();
        Src\Install::instance();
        Src\Icon_Sets::instance();
        Src\Shortcodes::instance();
        Src\Post_Types::instance();
        Src\Taxonomies::instance();
        Src\Widgets::instance();
        Src\Woocommerce\Init::instance();

    }

}
