<?php

namespace Routiz\Inc\Src\Explore\Filter\Modules\Booking_Calendar;

use \Routiz\Inc\Src\Explore\Filter\Modules\Module;
use \Routiz\Inc\Src\Explore\Filter\Comparison;

class Booking_Calendar extends Module {

    public function get_dates_between_range( $start, $end ) {

        $stop = 0;

        if( $start >= $end ) {
            return [];
        }

        $dates = [];

        $check_in = new \DateTime();
        $check_in->setTimestamp( $start );
        $check_in_unix = $check_in->getTimestamp();

        $check_out = new \DateTime();
        $check_out->setTimestamp( $end );
        $check_out_unix = $check_out->getTimestamp();

        while( $check_in_unix < $check_out_unix ) {

            // safe
            if( $stop > 999 ) {
                break;
            }

            $dates[] = $check_in->getTimestamp();

            $check_in->modify('tomorrow');
            $check_in_unix = $check_in->getTimestamp();

            $stop++;

        }

        return $dates;

    }

    public function main_query() {

        $d = $_GET['booking_dates'];
        $listing_ids = [];

        if( isset( $d[0] ) and ! empty( $d[0] ) ) {
            if( ! isset( $d[1] ) or empty( $d[1] ) ) {

                $timezone = new \DateTimeZone( wp_timezone_string() );
                $datetime = new \DateTime();
                $datetime->setTimestamp( $d[0] )->setTimezone( $timezone );

                $d[1] = $datetime->modify('tomorrow')->getTimestamp();

            }

            $dates = $this->get_dates_between_range( $d[0], $d[1] );

            $args = [
                'post_type' => 'rz_listing',
                'post_status' => 'publish',
                'posts_per_page' => -1,
            ];

            $the_query = new \WP_Query( $args );

            if( $the_query->have_posts() ) {
                while( $the_query->have_posts() ) {
                    $the_query->the_post();
                    $is_unavailable = false;
                    if( $unavailable = Rz()->get_meta('rz_booking_unavailable') ) {
                        if( is_array( $unavailable ) ) {
                            foreach( $unavailable as $unv ) {
                                if( in_array( $unv, $dates ) ) {
                                    $is_unavailable = true;
                                    break;
                                }
                            }
                        }
                    }
                    if( $is_unavailable ) {
                        $listing_ids[] = get_the_ID();
                    }
                }
            }

            wp_reset_postdata();

        }

        return [
            'post__not_in' => $listing_ids
        ];

    }

    public function query() {

        return [];

    }

    public function get_label() {
        return $this->props->value;
    }

    public function get() {

        $this->before_get();

        $field = $this->component->form->create( array_merge( (array) $this->props, [
            'type' => 'calendar',
            'range' => isset( $this->props->single_selection ) ? ! $this->props->single_selection : false,
        ]));

        if( ! $field instanceof \Routiz\Inc\Src\Error ) {
            return sprintf( $this->wrapper(), $this->props->type, $field->get() );
        }

    }

}
