<?php

namespace Routiz\Inc\Utils;

class Register {

    use \Routiz\Inc\Src\Traits\Singleton;

    // Define properties explicitly to avoid deprecation warnings
    public $helper;
    public $icon;
    public $notify;
    public $wc;

    function __construct() {
        // ..
    }

    public function register( $name, $inst = false ) {
        // Use variable variables to assign the property dynamically
        $this->{$name} = $inst;
    }

    public function __call( $method, $params ) {
        // Check if the property exists and return its value
        if ( property_exists( $this, $method ) ) {
            return $this->{$method};
        }

        return;
    }

}
