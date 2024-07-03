<?php

namespace Routiz\Inc\Extensions\Blade;

class Blade {

    static function engine( $views, $cache ) {

        // 1: always compile - development
        // 2: never compile - production

        return new Blade_Engine(
            $views,
            $cache,
            ( defined('WP_DEBUG') and WP_DEBUG == true ) ? 1 : 2
        );

    }
}
