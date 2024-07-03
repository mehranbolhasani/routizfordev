<?php

namespace Routiz\Inc\Src\Listing;

use \Routiz\Inc\Extensions\Component\Init as Main_Init;
use \Routiz\Inc\Src\Traits\Singleton;
use \Routiz\Inc\Src\Listing\Listing;
use \Routiz\Inc\Src\Listing\iCal;
use \Routiz\Inc\Src\Request\Request;

class Init extends Main_Init {

    use Singleton;

    function __construct() {

        parent::__construct();

        // single listing, main query
        if( is_single() and is_main_query() ) {

            // get the listing
            $listing = new Listing( get_the_ID() );
            if( $listing->id and $listing->type->get('rz_enable_ical') ) {

                // get the action types
                if( $listing->type->get_action()->has('booking') ) {

                    // if, feed has been requested
                    if( Request::instance()->has('ical') ) {
                        $ical = new iCal( get_the_ID() );
                        $ical->generate_feed();
                    }
                }
            }
        }
    }

    public function enqueue_scripts() {

        wp_enqueue_script( 'rz-listing' );
        wp_enqueue_style( 'rz-listing' );

    }

}
