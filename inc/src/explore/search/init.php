<?php

namespace Routiz\Inc\Src\Explore\Search;

use \Routiz\Inc\Extensions\Component\Init as Main_Init;
use \Routiz\Inc\Src\Traits\Singleton;

class Init extends Main_Init {

    use Singleton;

    function __construct() {

        add_action( 'routiz/search/hiddens', [ $this, 'hiddens' ] );

    }

    public function hiddens() {

        global $rz_explore;

        // for multiple listing types, inject default type
        if( $rz_explore->total_types > 1 ) {

            echo sprintf(
                '<input type="hidden" value="%s" name="type"><input class="rz-none" type="submit">',
                esc_attr( Rz()->get_meta( 'rz_slug', $rz_explore->main_type ) )
            );

        }

    }

}
