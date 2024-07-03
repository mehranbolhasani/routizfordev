<?php

namespace Routiz\Inc\Src\Woocommerce;

use \Routiz\Inc\Src\Traits\Singleton;
use \Routiz\Inc\Src\Listing\Listing;
use \Routiz\Inc\Src\Listing\Appointments;

class Checkout {

    use Singleton;

    function __construct() {

        add_action( 'woocommerce_after_checkout_validation', [ $this, 'check_entry' ], 10, 2 );

    }

    public function check_entry( $data, $errors ) {

        global $woocommerce;

        $items = $woocommerce->cart->get_cart();

        foreach( $items as $item ) {

            $product = wc_get_product( $item['product_id'] );

            /*
             * check booking availability
             *
             */
            if( $product->get_type() == 'listing_booking' ) {

                if( isset( $item['booking_type'] ) ) {

                    $listing = new Listing( $item['listing_id'][0] );
                    $guests = max( 1, $item['guests'] );

                    // appointment
                    if( $item['booking_type'] == 'appointment' ) {

                        $appointments = new Appointments( $listing );

                        // appointment
                		$appt = $appointments->get_appointment( $item['appt_period_id'], $item['checkin'], $guests );
                		$booked = (int) $listing->get( sprintf( '%s-%s', $appt['period']->id, $data->checkin ) );

                        // check ownership
                		if( $listing->post->post_author == get_current_user_id() ) {
                            $errors->add( 'validation', esc_html__( 'You can\'t book your own listing', 'routiz' ) );
                        }

                        // check availability
                		if( ! ( $appt['period']->limit == 0 or ( $booked + $guests ) <= $appt['period']->limit ) ) {
                            $errors->add( 'validation', $availability->message );
                        }

                    }
                    // daily
                    // hourly
                    else{

                        $availability = $listing->booking->check_booking_availability( $item['checkin'], $item['checkout'], $guests );

                        if( ! $availability->success ) {
                            $errors->add( 'validation', $availability->message );
                        }
                    }

                }

            }
        }
	}

}
