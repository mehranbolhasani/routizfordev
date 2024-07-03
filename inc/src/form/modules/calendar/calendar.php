<?php

namespace Routiz\Inc\Src\Form\Modules\Calendar;

use \Routiz\Inc\Src\Form\Modules\Module;
use \Routiz\Inc\Src\Listing\Listing;

class Calendar extends Module {

    public $listing = null;

    public $unavailable_start = false;
    public $unavailable_end = false;

    public $booked_dates = [];
    public $pending_dates = [];
    public $unavailable_dates = [];
    public $all_unavailable = [];
    public $listing_id;
    public $booking_type;
    public $action;

    public function before_construct() {

        $this->defaults += [
            'format' => 'd-m-Y',
            'months' => 12,
            'week_start' => 1,
            'range' => false,
            'large' => false,
            'readonly' => false
        ];

        global $post;

        if( $post ) {
            $this->listing_id = $post->ID;
            $this->listing = new Listing( $post->ID );
            $this->booking_type = in_array( 'booking', $this->listing->type->get_action()->types ) ? 'booking' : 'booking_hourly';
            $this->action = $this->listing->type->get_action( $this->booking_type );
        }

    }

    public function after_build() {
        $this->attrs += [ 'data-range' => $this->props->range ];
        $this->attrs += [ 'data-large' => $this->props->large ];
        $this->attrs += [ 'data-format' => $this->props->format ];
        $this->attrs += [ 'data-readonly' => $this->props->readonly ];
        $this->class[] = sprintf( 'rz-calendar-picker rz-calendar-large-%s', $this->props->large ? 'yes' : 'no' );
        if( $this->props->id == 'rz_booking' ) {
            $this->class[] = 'rz-calendar-booking';
        }
        if( $this->props->readonly == true ) {
            $this->class[] = 'rz--readonly';
        }
    }

    public function controller() {

        $date = [
            'ts_start' => '',
            'ts_end' => '',
            'date_start' => '',
            'date_end' => ''
        ];

        if( isset( $this->props->value[0] ) ) {
            $date = array_merge( $date, [
                'ts_start' => $this->props->value[0],
                'date_start' => date( $this->props->format, $this->props->value[0] ),
            ]);
        }
        if( isset( $this->props->value[1] ) ) {
            $date = array_merge( $date, [
                'ts_end' => $this->props->value[1],
                'date_end' => date( $this->props->format, $this->props->value[1] ),
            ]);
        }

        return array_merge( (array) $this->props, [
            'months' => $this->generate_calendar_months(),
            'week_days' => $this->generate_week_days(),
            'strings' => (object) [
                'clear_dates' => esc_html__( 'Clear dates', 'routiz' )
            ],
            'date' => (object) $date
        ]);

    }

    public function generate_week_days() {

        global $wp_locale;

        $week_days = [];

        for ( $w = 0; $w <= 6; $w++ ) {
            $week_day = $wp_locale->get_weekday( ( $w + $this->props->week_start ) %7 );
			$week_days[] = (object) [
                'name' => esc_attr( $week_day ),
                'initial' => esc_attr( $wp_locale->get_weekday_initial( $week_day ) ),
            ];
		}

        return $week_days;

    }

    public function get_days_in_month( $month = null, $year = null ) {

	    $timeNow = current_time( 'timestamp' );
	    if( $year == null ) {
	        $year = gmdate( 'Y', $timeNow );
	    }

	    if( $month == null ) {
	        $month = gmdate( 'm', $timeNow );
	    }

	    $unixMonth = mktime( 0, 0 , 0, $month, 1, $year );

	    return date( 't', $unixMonth );

	}

    function get_formatted_date( $timestamp ) {
        return date( $this->props->format, $timestamp );
    }

    public function generate_calendar_months() {

        global $post;

        $month = 1;
		$now = current_time( 'timestamp' );
	    $date = new \DateTime();

	    $current_month = gmdate( 'm', $now );
	    $current_year = gmdate( 'Y', $now );
	    $unix_month = mktime( 0, 0 , 0, $current_month, 1, $current_year );

        if( is_single() and get_post_type() == 'rz_listing' and $this->listing ) {
            if( $this->booking_type == 'booking' and ! $this->action->get_field('reservation_overlap') ) {
                $this->booked_dates = Rz()->get_array( 'rz_booking_booked', $post->ID, true );
                $this->pending_dates = Rz()->get_array( 'rz_booking_pending', $post->ID, true );
            }else{
                $this->booked_dates = $this->pending_dates = [];
            }

            $this->unavailable_dates = Rz()->get_array( 'rz_booking_unavailable', $post->ID, true );
            $this->all_unavailable = array_merge( $this->booked_dates, $this->pending_dates, $this->unavailable_dates );
        }

        $months = [];

        while( $month <= max( 1, $this->props->months ) ) {

            $months[] = (object) [
                'id' => $month,
                'month' => $current_month,
                'year' => $current_year,
                'days' => $this->generate_calendar_days( $unix_month, $current_month, $current_year )
            ];

            $date->modify( 'first day of next month' );
            $current_month = $date->format( 'm' );
            $current_year = $date->format( 'Y' );
            $unix_month = mktime(0, 0 , 0, $current_month, 1, $current_year);

            $month++;

        }

        return $months;

    }

    public function generate_calendar_days( $unix_month, $current_month, $current_year ) {

        $days = [];

        // get previous month days
		$week_mod = calendar_week_mod( date( 'w', $unix_month ) - $this->props->week_start );
		if( $week_mod != 0 ) {
			for( $wm = 1; $wm <= $week_mod; $wm++ ) {
                $days[] = (object) [
                    'class' => [ 'rz--prev-month-day' ],
                    'day' => null
                ];
			}
		}

        // get month days
		$days_in_month = $this->get_days_in_month( $current_month, $current_year );

		for( $day = 1; $day <= $days_in_month; ++$day ) {

            $timestamp = strtotime( $day . '-' . $current_month . '-' . $current_year );

			$class = [];
            $is_past_day = $timestamp < ( time() - 24 * 60 * 60 );
            $is_past_day ? array_push( $class, 'rz--day-disabled', 'rz--past-day' ) : array_push( $class, 'rz--future-day' );

            switch( true ) {
                case in_array( $timestamp, $this->booked_dates ):
                    array_push( $class, 'rz--day-booked', 'rz--not-available' ); break;
                case in_array( $timestamp, $this->pending_dates ):
                    array_push( $class, 'rz--day-pending', 'rz--not-available' ); break;
                case in_array( $timestamp, $this->unavailable_dates ):
                    array_push( $class, 'rz--day-unavailable', 'rz--not-available' ); break;
                default:
                    $class[] = 'rz--available';
            }

            if( $this->listing ) {

                // unavailable start
                if( in_array( $timestamp, $this->all_unavailable ) ) {
                    if( ! in_array( strtotime( '-1 day', $timestamp ), $this->all_unavailable ) ) {
                        $class[] = 'rz--unavailable-start';
                    }
                }

                // unavailable ends
                if( in_array( $timestamp, $this->all_unavailable ) ) {
                    if( ! in_array( strtotime( '+1 day', $timestamp ), $this->all_unavailable ) ) {
                        $class[] = 'rz--unavailable-ends';
                    }
                }

            }

            $days[] = (object) [
                'class' => $class,
                'day' => $day,
                'date' => $this->get_formatted_date( $timestamp ),
                'timestamp' => $timestamp,
            ];

		}

        return $days;

	}

}
