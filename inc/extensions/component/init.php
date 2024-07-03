<?php

namespace Routiz\Inc\Extensions\Component;

use \Routiz\Inc\Extensions\Blade\Blade;

abstract class Init {

    function __construct() {

        // component level enqueue scripts
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

    }

    public function enqueue_scripts() {}

    public function engine() {

        $reflector = new \ReflectionClass( $this );
        $path = dirname( $reflector->getFileName() );

        return Blade::engine(
            $path . '/modules',
            RZ_PATH_CACHE . basename( $path )
        );

    }

}
