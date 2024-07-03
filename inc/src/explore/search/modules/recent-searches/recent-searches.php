<?php

namespace Routiz\Inc\Src\Explore\Search\Modules\Recent_Searches;

use \Routiz\Inc\Src\Explore\Search\Modules\Module;

class Recent_Searches extends Module {

    public function controller() {

        $recent_searches = [];
        $has_recent_searches = isset( $_COOKIE['rz_recent_searches'] );

        if( $has_recent_searches ) {

            $recent_searches_value = Rz()->json_decode( $_COOKIE['rz_recent_searches'] );

            if( is_array( $recent_searches_value ) ) {
                $recent_searches = array_reverse( $recent_searches_value );
            }

        }

        return array_merge( (array) $this->props, [
            'has_recent_searches' => $has_recent_searches,
            'recent_searches' => $recent_searches,
            'explore_page' => Rz()->is_explore(),
        ]);

    }

    public function wrapper() {

        if( isset( $_COOKIE['rz_recent_searches'] ) ) {
            return '<div class="rz-mod" data-type="%s">%s</div>';
        }

    }

}
