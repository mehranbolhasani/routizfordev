<?php

namespace Routiz\Inc\Src;

use \Routiz\Inc\Src\Listing\Views;

class Widgets {

    use \Routiz\Inc\Src\Traits\Singleton;

    function __construct() {

        $this->load();

        add_filter('dynamic_sidebar_params', [ $this, 'add_widget_wrapper_class' ] );

    }

    function add_widget_wrapper_class( $params ) {

        $params[0]['before_widget'] = str_replace( '>', ' data-id="' . preg_replace( "/(\-\d+)$/", '', $params[0]['widget_id'] ) . '">', $params[0]['before_widget'] );

        return $params;

    }

    protected function load() {

        include RZ_PATH . 'inc/src/widgets/widget-listings.php';

    }

}
