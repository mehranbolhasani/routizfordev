<?php

use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Listing\Listing;
use \Routiz\Inc\Src\User;

class Calendar {

    public $unavailable_start = false;
    public $unavailable_end = false;

    public $booked_dates = [];
    public $pending_dates = [];
    public $unavailable_dates = [];

    public $listing;
    public $props;

    function __construct() {

        $request = Request::instance();

        if( $request->is_empty('id') ) {
            return;
        }

        $this->listing = new Listing( $request->get('id') );
        $this->booking_type = in_array( 'booking', $this->listing->type->get_action()->types ) ? 'booking' : 'booking_hourly';
        $this->action = $this->listing->type->get_action('booking');

        if( ! $this->listing->id ) {
            return;
        }

        $this->props = (object) [
           'format' => 'd-m-Y',
           'months' => 1,
           'week_start' => 1,
           'range' => false,
           'large' => false,
           'readonly' => false
       ];
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

        if( is_array( $this->props->value ) and count( $this->props->value ) == 2 ) {
            if( ! empty( $this->props->value[0] ) and ! empty( $this->props->value[1] ) ) {
                $date = [
                    'ts_start' => $this->props->value[0],
                    'ts_end' => $this->props->value[1],
                    'date_start' => date( $this->props->format, $this->props->value[0] ),
                    'date_end' => date( $this->props->format, $this->props->value[1] )
                ];
            }
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

        $request = Request::instance();
        $month = (int) $request->get('month');

		$now = current_time( 'timestamp' );
	    $date = new \DateTime();

	    $current_month = gmdate( 'm', $now );
	    $current_year = gmdate( 'Y', $now );
	    $unix_month = mktime( 0, 0 , 0, $current_month, 1, $current_year );

        if( $this->booking_type == 'booking' and ! $this->action->get_field('reservation_overlap') ) {
            $this->booked_dates = Rz()->get_array( 'rz_booking_booked', $this->listing->id, true );
            $this->pending_dates = Rz()->get_array( 'rz_booking_pending', $this->listing->id, true );
        }else{
            $this->booked_dates = $this->pending_dates = [];
        }
        $this->unavailable_dates = Rz()->get_array( 'rz_booking_unavailable', $this->listing->id, true );

        $this->all_unavailable = array_merge( $this->booked_dates, $this->pending_dates, $this->unavailable_dates );

        $months = [];

        $date->modify( "first day of +{$month} month" );
        $current_month = $date->format( 'm' );
        $current_year = $date->format( 'Y' );
        $unix_month = mktime(0, 0, 0, $current_month, 1, $current_year);

        $months[] = (object) [
            'id' => 1,
            'month' => $current_month,
            'year' => $current_year,
            'days' => $this->generate_calendar_days( $unix_month, $current_month, $current_year )
        ];

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
            $exclude = false;

            if( $is_past_day ) {
                array_push( $class, 'rz--past-day', 'rz--not-available' );
                $exclude = true;
            }

            if( in_array( $timestamp, $this->booked_dates ) ) {
                array_push( $class, 'rz--day-booked', 'rz--not-available' );
                $exclude = true;
            }elseif( in_array( $timestamp, $this->pending_dates ) ) {
                array_push( $class, 'rz--day-pending', 'rz--not-available' );
                $exclude = true;
            }elseif( in_array( $timestamp, $this->unavailable_dates ) ) {
                array_push( $class, 'rz--not-available', 'rz--day-unavailable' );
                $exclude = true;
            }

            if( ! $is_past_day and ! $exclude ) {
                $class[] = 'rz--available';
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
