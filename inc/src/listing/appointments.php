<?php

namespace Routiz\Inc\Src\Listing;

use \Routiz\Inc\Src\Listing_Type\Listing_Type;

class Appointments {

    public $listing;
    public $periods;
    public $timezone;
    public $results = 3;

    public function __construct( Listing $listing ) {

        if( ! $listing->id ) {
            return;
        }

        $this->listing = $listing;
        $this->periods = Rz()->jsoning( 'rz_time_availability', $this->listing->id );

        $this->timezone = new \DateTimeZone( wp_timezone_string() );

    }

    public function is_valid_date( $time, $format = 'Y-m-d' ) {

        $date_format = date('Y-m-d');
        $d = \DateTime::createFromFormat( "Y-m-d {$format}", "{$date_format} {$time}" );
        return $d && $d->format( $format ) == $time;

    }

    public function parse_period( $data ) {

        $timezone = new \DateTimeZone( wp_timezone_string() );

        // duration
        switch( $data->fields->duration ) {
            case 'custom':
                $duration = floatval( $data->fields->custom_duration_length ) * floatval( $data->fields->custom_duration_entity );
                break;
            default: $duration = floatval( $data->fields->duration );
        }

        // interval
        switch( $data->fields->interval ) {
            case 'none': $interval = 0; break;
            case 'custom':
                $interval = floatval( $data->fields->custom_interval_length ) * floatval( $data->fields->custom_interval_entity );
                break;
            default: $interval = floatval( $data->fields->interval );
        }

        // availability
        $recurring_availability = [];
        $start_date = $end_date = null;
        if( $data->fields->recurring ) {
            $recurring_availability = $data->fields->recurring_availability;
        }else{
            // start date
            if( $data->fields->start and $this->is_valid_date( $data->fields->start ) ) {
                $start_date = \DateTime::createFromFormat( 'Y-m-d', $data->fields->start, $timezone )->modify('today');
            }
            // end date
            if( $data->fields->end and $this->is_valid_date( $data->fields->end ) ) {
                $end_date = \DateTime::createFromFormat( 'Y-m-d', $data->fields->end, $timezone )->modify('tomorrow -1 second');
            }
        }

        return (object) [
            'id' => $data->fields->key,
            'name' => $data->fields->name,
            'duration' => $duration,
            'interval' => $interval,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'start_time' => $data->fields->start_time,
            'end_time' => $data->fields->end_time,
            'recurring' => $data->fields->recurring,
            'recurring_availability' => $recurring_availability,
            'price' => $data->fields->price,
            'price_weekend' => $data->fields->price_weekend,
            'limit' => $data->fields->limit,
        ];

    }

    public function query(
        $period,
        \DateTime $today = null,
        $results = null,
        $page = 1,
        $guests = 1,
        $children = 0,
        $addons = []
    ) {

        $upcoming = (object) [
            'dates' => [],
            'found' => 0
        ];

        $now = new \DateTime();
        $now->setTimezone( $this->timezone );

        if( ! $today ) {
            $today = new \DateTime();
            $today->setTimezone( $this->timezone );
        }

        if( $today < $now ) {
            $today = $now;
        }

        if( ! $results ) {
            $results = $this->results;
        }

        // period
        if( $period->start_date and $period->end_date ) {

            // check if passed
            if( $today > $period->end_date ) {
                return $upcoming;
            }

            // start date and time
            $start = clone $period->start_date;
            $start->modify('today')->add( new \DateInterval("PT{$period->start_time}S") );

            // end date and time
            $end = clone $period->end_date;
            $end->modify('today')->add( new \DateInterval("PT{$period->end_time}S") );

            // check if start time is greater that end time
            if( $start > $end ) {
                return $upcoming;
            }

            // loop through time
            while( $start < $end ) {

                if( $upcoming->found >= ( $results * $page ) ) {
                    break;
                }

                $start_day = clone $start;
                $start_day->modify('today');
                $start_day_unix = $start_day->getTimestamp();
                $start_unix = $start->getTimestamp();

                // if start time has passed > go to next stack
                if( $today > $start ) {
                    $start->add( new \DateInterval("PT" . ( $period->duration + $period->interval ) . "S") );
                    continue;
                }

                // if end time has passed > set tommorow's start time
                if( $start_unix - $start_day_unix > $period->end_time ) {
                    $start->modify('tomorrow')->add( new \DateInterval("PT{$period->start_time}S") );
                    continue;
                }

                // if end greater than end time
                if( $start_unix - $start_day_unix + $period->duration > $period->end_time ) {
                    $start->modify('tomorrow')->add( new \DateInterval("PT{$period->start_time}S") );
                    continue;
                }

                // inject date
                if( ! isset( $upcoming->dates[ $start_unix ] ) ) {
                    $upcoming->dates[ $start_unix ] = [];
                }

                $_end = clone $start;
                $_end->add( new \DateInterval("PT{$period->duration}S") );

                $_day_end = clone $start;
                $_day_end->modify('tomorrow -1 second');

                $_same_day = $_end <= $_day_end;

                $date_format = get_option('date_format');
                $time_format = get_option('time_format');

                // short time periods
                if( $_same_day ) {
                    $date = $start->format( $date_format );
                    $time = sprintf('%s - %s', $start->format( $time_format ), $_end->format( $time_format ) );
                }
                // long time periods
                else{
                    $date = sprintf('%s - %s', $start->format( $date_format ), $_end->format( $date_format ) );
                    $time = null;
                }

                $booked = (int) $this->listing->get( sprintf( '%s-%s', $period->id, $start_unix ) );
                $is_available = ( $period->limit == 0 or $booked < $period->limit );

                $upcoming->dates[ $start_unix ][] = [
                    'date' => clone $start,
                    'period' => $period,
                    'booked' => $booked,
                    'is_available' => $is_available,
                    'print' => (object) [
                        'date' => $date,
                        'time' => $time,
                    ],
                    'pricing' => $this->listing->get_booking_appointment_price(
                        $period->price,
                        $period->price_weekend,
                        $start,
                        $guests,
                        $children,
                        $addons
                    )
                ];

                $upcoming->found += 1;

                $start->add( new \DateInterval("PT" . ( $period->duration + $period->interval ) . "S") );

            }

        }
        // recurring
        else{

            // start date and time
            $start = clone $today;
            $start->modify('today')->add( new \DateInterval("PT{$period->start_time}S") );

            $break = 0;

            // loop through time
            while( $start ) {

                // if results are out
                if( $upcoming->found >= ( $results * $page ) ) {
                    break;
                }

                // safe break
                if( $break >= 999 ) {
                    break;
                }
                $break++;

                $start_day = clone $start;
                $start_day->modify('today');
                $start_day_unix = $start_day->getTimestamp();
                $start_unix = $start->getTimestamp();

                // if week day not recurring > set tommorow's start time
                if( ! in_array( $start_day->format('N'), $period->recurring_availability ) ) {
                    $start->modify('tomorrow')->add( new \DateInterval("PT{$period->start_time}S") );
                    continue;
                }

                // if start time has passed > go to next stack
                if( $today > $start ) {
                    $start->add( new \DateInterval("PT" . ( $period->duration + $period->interval ) . "S") );
                    continue;
                }

                // if end time has passed > set tommorow's start time
                if( $start_unix - $start_day_unix > $period->end_time ) {
                    $start->modify('tomorrow')->add( new \DateInterval("PT{$period->start_time}S") );
                    continue;
                }

                // if end greater than end time
                if( $start_unix - $start_day_unix + $period->duration > $period->end_time ) {
                    $start->modify('tomorrow')->add( new \DateInterval("PT{$period->start_time}S") );
                    continue;
                }

                // inject date
                if( ! isset( $upcoming->dates[ $start_unix ] ) ) {
                    $upcoming->dates[ $start_unix ] = [];
                }

                $_end = clone $start;
                $_end->add( new \DateInterval("PT{$period->duration}S") );

                $_day_end = clone $start;
                $_day_end->modify('tomorrow -1 second');

                $_same_day = $_end <= $_day_end;

                $date_format = get_option('date_format');
                $time_format = get_option('time_format');

                // short time periods
                if( $_same_day ) {
                    $date = $start->format( $date_format );
                    $time = sprintf('%s - %s', $start->format( $time_format ), $_end->format( $time_format ) );
                    // $time = sprintf('%s - %s', date_i18n( $time_format, $start->getTimestamp() ), date_i18n( $time_format, $_end->getTimestamp() ) );
                }
                // long time periods
                else{
                    $date = sprintf('%s - %s', $start->format( $date_format ), $_end->format( $date_format ) );
                    $time = null;
                }

                $booked = (int) $this->listing->get( sprintf( '%s-%s', $period->id, $start_unix ) );
                $is_available = ( $period->limit == 0 or $booked < $period->limit );

                $upcoming->dates[ $start_unix ][] = [
                    'date' => clone $start,
                    'period' => $period,
                    'booked' => $booked,
                    'is_available' => $is_available,
                    'print' => (object) [
                        'date' => $date,
                        'time' => $time,
                    ],
                    'pricing' => $this->listing->get_booking_appointment_price(
                        $period->price,
                        $period->price_weekend,
                        $start,
                        $guests,
                        $children,
                        $addons
                    )
                ];

                $upcoming->found += 1;

                $start->add( new \DateInterval("PT" . ( $period->duration + $period->interval ) . "S") );

            }

        }

        if( $page > 1 ) {
            $upcoming->dates = array_slice( $upcoming->dates, $results * ( $page - 1 ), $results, true );
        }

        return $upcoming;

    }

    public function merge( &$ar1, $ar2 ) {

        foreach( $ar2 as $key => $item ) {
            if( ! isset( $ar1[ $key ] ) ) {
                $ar1[ $key ] = [];
            }
            $ar1[ $key ] = array_merge( $ar1[ $key ], $item );
        }
    }

    public function get(
        \DateTime $date = null,
        $results = null,
        $page = 1,
        $guests = 1,
        $children = 0,
        $addons = []
    ) {

        $upcoming = [];

        foreach( $this->periods as $period_data ) {
            $period = $this->parse_period( $period_data );
            $this->merge(
                $upcoming,
                $this->query(
                    $period,
                    $date,
                    $results,
                    $page,
                    $guests,
                    $children,
                    $addons
                )->dates
            );
        }

        if( ! $results ) {
            $results = $this->results;
        }

        // sort array by dates
        ksort( $upcoming, SORT_NUMERIC );

        if( $results == 3 ) {
            return array_slice( $upcoming, 0, $results, true );
        }

        return $upcoming;

    }

    public function get_appointment( $period_id, $checkin, $guests = 0, $children = 0, $addons = [] ) {

        $guests = max( $guests, 1 );
        $children = max( $children, 0 );

        foreach( $this->periods as $period_data ) {
            if( $period_data->fields->key == $period_id ) {

                $period = $this->parse_period( $period_data );

                $start = new \DateTime();
                $start->setTimezone( $this->timezone );
                $start->setTimestamp( $checkin );

                return [
                    'date' => $start,
                    'period' => $period,
                    'pricing' => $this->listing->get_booking_appointment_price(
                        $period->price,
                        $period->price_weekend,
                        $start,
                        $guests,
                        $children,
                        $addons
                    )
                ];

            }
        }

        return [];

    }

}
