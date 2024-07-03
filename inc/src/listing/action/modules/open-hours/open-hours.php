<?php

namespace Routiz\Inc\Src\Listing\Action\Modules\Open_Hours;

use \Routiz\Inc\Src\User;
use \Routiz\Inc\Src\Listing\Action\Modules\Module;

class Open_Hours extends Module {

    public function get() {

        global $rz_listing;

        $value = $rz_listing->get('rz_open_hours');

        // handle for serialized value
        if( is_serialized( $value ) ) {
            $value = maybe_unserialize( $value );

            $new = [];
            foreach( $value as $k => $v ) {
                $new[ $k ] = (object) $v;
            }

            $value = $new;
        }

        // check if hours empty
        $is_empty = true;
        $hours = is_array( $value ) ? $value : (array) json_decode( $value );
        if( is_array( $hours ) ) {
            foreach( $hours as $hour ) {
                if( isset( $hour->type ) ) {
                    if(
                        $hour->type == 'open' or
                        $hour->type == 'closed' or
                        (
                            $hour->type == 'range' and
                            ! empty( $hour->start ) and
                            ! empty( $hour->end )
                        )
                    ) {
                        $is_empty = false;
                        break;
                    }
                }
            }
        }

        if( $is_empty ) {
            return;
        }

        $this->before_get();
        return sprintf( $this->wrapper(), $this->props->type, $this->template() );

    }

    public function my_gmt_to_local_timestamp( $gmt_timestamp ) {

    	$iso_date = date( 'Y-m-d H:i:s', $gmt_timestamp );
    	$local_timestamp = get_date_from_gmt( $iso_date, 'U' );

    	return $local_timestamp;

    }

    public function controller() {

        global $rz_listing;

        /*
         * check if is open
         *
         */
        $is_open = false;
        $value = $rz_listing->get('rz_open_hours');

        // handle for serialized value
        if( is_serialized( $value ) ) {
            $value = maybe_unserialize( $value );

            $new = [];
            foreach( $value as $k => $v ) {
                $new[ $k ] = (object) $v;
            }

            $value = $new;
        }

        $hours = is_array( $value ) ? $value : (array) json_decode( $value );
        $week = [ 'sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat' ];
        $day_key = $week[ date('w') ];
        $has_hours = false;
        $type = 'range';

        /*
         * calculate days
         *
         */
        $today_unix = strtotime( 'today', time() );

        foreach( $hours as &$h ) {

            $has_hours = true;

            $h->unix_start = null;
            $h->unix_end = null;

            if( isset( $h->start ) and isset( $h->start ) and ! empty( $h->start ) and ! empty( $h->end ) ) {
                $h->unix_start = $h->start + $today_unix;
                $h->unix_end = $h->end + $today_unix;
            }

        }

        if( isset( $hours[ $day_key ] ) ) {

            if( isset( $hours[ $day_key ]->type ) ) {
                $type = $hours[ $day_key ]->type;
            }

            /*
             * range
             *
             */
            if( $type == 'range' ) {

                $hour = $hours[ $day_key ];

                if( ! empty( $hour->unix_start ) and ! empty( $hour->unix_end ) ) {

                    $timezone = new \DateTimeZone( wp_timezone_string() );
                    // $local_timezone = new \DateTimeZone();
                    $now = new \DateTime( 'now', $timezone );

                    $obj_start = \DateTime::createFromFormat( 'Y-m-d H:m:i', date( 'Y-m-d H:m:i', $hour->unix_start ), $timezone );
                    $obj_end = \DateTime::createFromFormat( 'Y-m-d H:m:i', date( 'Y-m-d H:m:i', $hour->unix_end ), $timezone );

                    if( $now > $obj_start and $now < $obj_end ) {
                        $is_open = true;
                    }

                    $local = new \DateTime( 'now', $timezone );
                    $local->setTimestamp( $hour->unix_start );

                }
            }
            /*
             * open
             *
             */
            elseif( $type == 'open' ) {

                $is_open = true;

            }
            /*
             * close
             *
             */
            else{



            }

        }

        return array_merge( (array) $this->props, [
            'type' => $type,
            'has_hours' => $has_hours,
            'open_hours' => $hours,
            'time_format' => get_option('time_format'),
            'current_start' => ( isset( $hours[ $day_key ] ) and ! empty( $hours[ $day_key ]->unix_start ) ) ? $hours[ $day_key ]->unix_start : '',
            'current_end' => ( isset( $hours[ $day_key ] ) and ! empty( $hours[ $day_key ]->unix_end ) ) ? $hours[ $day_key ]->unix_end : '',
            'week_days' => [
                'mon' => esc_html__('Monday', 'routiz'),
                'tue' => esc_html__('Tuesday', 'routiz'),
                'wed' => esc_html__('Wednesday', 'routiz'),
                'thu' => esc_html__('Thursday', 'routiz'),
                'fri' => esc_html__('Friday', 'routiz'),
                'sat' => esc_html__('Saturday', 'routiz'),
                'sun' => esc_html__('Sunday', 'routiz'),
            ],
            'is_open' => $is_open,
            'strings' => (object) [
                'open' => esc_html__('Open', 'routiz'),
                'open_now' => esc_html__('Open now', 'routiz'),
                'closed' => esc_html__('Closed', 'routiz'),
                'all_day_open' => esc_html__('Open', 'routiz'),
                'all_day_closed' => esc_html__('Closed', 'routiz'),
                'local_time' => esc_html__('All the time ranges are in local time', 'routiz'),
            ]
        ]);

    }

}
