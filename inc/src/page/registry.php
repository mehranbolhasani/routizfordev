<?php

namespace Routiz\Inc\Src\Page;

use \Routiz\Inc\Extensions\Component\Registry as Registry_Main;

abstract class Registry extends Registry_Main {

    static function register( $id ) {

        $collector = Collector::instance();
        $collector->add( $id, [
            'name' => static::class,
        ]);

    }

}
