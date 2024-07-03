<?php

namespace Routiz\Inc\Src\Listing;

class iCal {

    public $id;

    function __construct( $listing_id ) {

        $this->id = $listing_id;

    }

    public function generate_feed() {

        header( 'Content-type: text/calendar; charset=utf-8' );
        header( 'Content-Disposition: inline; filename=icalendar.ics' );

        echo "BEGIN:VCALENDAR\r\n";
        echo "PRODID:-//Booking Calendar//EN\r\n";
        echo "VERSION:2.0\r\n";
        echo $this->get_entries();
        echo "END:VCALENDAR\r\n";

        exit;

    }

    public function get_entries() {

        $args = [
            'post_type' => 'rz_entry',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_query' => [
                'relation' => 'AND',
                [
                    'key' => 'rz_entry_type',
                    'value' => 'booking',
                ],
                [
                    'key' => 'rz_listing',
                    'value' => $this->id,
                    'type' => 'NUMERIC',
                    'compare' => '='
                ],
            ]
        ];

	    $query = new \WP_Query( $args );

	    if( $query->have_posts() ) {

	        while( $query->have_posts() ) { $query->the_post();

	            $entry_id = get_the_ID();
	            $check_in_date = get_post_meta( $entry_id, 'rz_checkin_date', true );
	            $check_out_date = get_post_meta( $entry_id, 'rz_checkout_date', true );

	            $this->generate_event( $check_in_date, $check_out_date, $entry_id );

	        }

	        wp_reset_postdata();

	    }

    }

    function generate_event( $check_in_unix, $check_out_unix, $entry_id ) {

    	$summary = sprintf( '%s: reservation ID: #%s', get_bloginfo('name'), $entry_id );

    	echo "BEGIN:VEVENT\r\n";
        echo sprintf( "SUMMARY:%s\r\n", $this->escape( $summary ) );
        echo sprintf( "DTSTART:%s\r\n", $this->get_ical_date( $check_in_unix ) );
        echo sprintf( "DTEND:%s\r\n", $this->get_ical_date( $check_out_unix ) );
        echo sprintf( "UID:%s@%s\r\n", md5( uniqid( mt_rand(), true ) ), $_SERVER['HTTP_HOST'] );
        echo sprintf( "DTSTAMP:%s\r\n", $this->get_ical_date( time() ) );
        echo "END:VEVENT\r\n";

	}

    public function get_ical_date( $timestamp ) {
        return date( 'Ymd\THis\Z', $timestamp );
    }

    public function escape( $string ) {
        return preg_replace( '/([\,;])/','\\\$1', $string );
    }

    public function sync() {

        require_once RZ_PATH . 'inc/lib/ics-parser/class.iCalReader.php';

        $sources = Rz()->jsoning( 'rz_ical_import_source', $this->id );

        foreach( $sources as $source ) {
            $this->import_source( esc_url_raw( $source->fields->url ) );
        }

    }

    public function import_source( $source_url ) {

        if( filter_var( $source_url, FILTER_VALIDATE_URL ) === false ) {
            return;
        }

        $ical = new \iCal( $source_url );

        if( ! $ical->cal ) {
            return;
        }

        $events = $ical->events();

        if( is_array( $events ) ) {
            foreach( $events as $event ) {

                $start_time_unix = $end_time_unix = 0;

    	        if( isset( $event['DTSTART'] ) ) {
    	            $start_time_unix = (int) $ical->iCalDateToUnixTimestamp( $event['DTSTART'] );
    	        }

    	        if( isset( $event['DTEND'] ) ) {
    	            $end_time_unix = (int) $ical->iCalDateToUnixTimestamp( $event['DTEND'] );
    	        }

                if( $start_time_unix > 0 and $start_time_unix <= $end_time_unix ) {

                    $listing = new Listing( $this->id );

                    if( ! $listing->id ) {
                        return;
                    }

                    $dates = $listing->booking->get_dates_between_range( $start_time_unix, $end_time_unix );
                    $listing->booking->add_dates( 'unavailable', $dates );

                }
            }
        }
    }
}
