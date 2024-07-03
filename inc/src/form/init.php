<?php

namespace Routiz\Inc\Src\Form;

use \Routiz\Inc\Extensions\Component\Init as Main_Init;
use \Routiz\Inc\Src\Traits\Singleton;

class Init extends Main_Init {

    use Singleton;

    public function __call( $method, $arguments ) {
        throw new Exception("Method {$name} is not supported.");
    }

    public function enqueue_scripts() {

        // wp_enqueue_editor();
        wp_enqueue_script( 'rz-form' );
        wp_enqueue_style( 'rz-form' );

    }

}
