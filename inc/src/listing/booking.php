<?php

namespace Routiz\Inc\Src\Listing;

use \Routiz\Inc\Src\Listing_Type\Listing_Type;

class Booking {

    public $id;

    public function __construct( $id ) {

        $this->id = $id;

    }

    public function get_all_pending_days() {

        $days_ago = time() - 3 * 24 * 60 * 60 ;

        $pending_dates_arr = [];

        $entries = new \WP_Query([
            'post_type' => 'rz_entry',
            'post_status' => [ 'pending', 'pending_payment' ],
            'posts_per_page' => -1,
            'meta_query' => [
                'relation' => 'AND',
                [
                    'key' => 'rz_listing',
                    'value' => $this->id,
                    'type' => 'NUMERIC',
                    'compare' => '='
                ],
            ]
        ]);

        if ( $entries->have_posts() ) {

            while ( $entries->have_posts() ) { $entries->the_post();

                $check_in_date = get_post_meta( get_the_ID(), 'rz_checkin_date', true );
                $check_out_date = get_post_meta( get_the_ID(), 'rz_checkout_date', true );

                if ( $check_in_date > $days_ago ) {

                    $check_in = new \DateTime( '@' . $check_in_date );
                    $check_in_unix = $check_in->getTimestamp();

                    $check_out = new \DateTime( '@' . $check_out_date );
                    $check_out_unix = $check_out->getTimestamp();

                    $check_in_unix = $check_in->getTimestamp();

                    while( $check_in_unix < $check_out_unix ) {
                        $pending_dates_arr[] = $check_in_unix;
                        $check_in->modify('tomorrow');
                        $check_in_unix = $check_in->getTimestamp();
                    }
                }

            }
            wp_reset_postdata();
        }

        return $pending_dates_arr;

    }

    public function check_booking_availability( $checkin, $checkout, $guests = 0 ) {

        $listing = new Listing( $this->id );
        $actions = $listing->type->get_action();

        if( ! $listing->type->id ) {
            return (object) [
                'success' => false,
                'message' => esc_html__( 'No listing type selected', 'routiz' )
            ];
        }

        $response = [];

        if( get_post_field( 'post_author', $this->id ) == get_current_user_id() ) {
            return (object) [
                'success' => false,
                'message' => esc_html__( 'You can\'t book your own listing', 'routiz' )
            ];
        }

        if( get_post_field( 'post_author', $this->id ) == get_current_user_id() ) {
            return (object) [
                'success' => false,
                'message' => esc_html__( 'You can\'t book your own listing', 'routiz' )
            ];
        }

        // if guests selection is allows, check for guests
        if( $listing->type->get('rz_allow_guests') ) {
            if( $guests < 1 ) {
                return (object) [
                    'success' => false,
                    'message' => esc_html__( 'Please select guests', 'routiz' )
                ];
            }
        }

        if( empty( $checkin ) && empty( $checkout ) ) {
            return (object) [
                'success' => false,
                'message' => esc_html__( 'Please select dates', 'routiz' )
            ];
        }

        if( empty( $checkout ) ) {
            return (object) [
                'success' => false,
                'message' => esc_html__( 'Choose checkout date', 'routiz' )
            ];
        }

        if( $checkin >= $checkout ) {
            return (object) [
                'success' => false,
                'message' => esc_html__( 'Booking dates are not possible', 'routiz' )
            ];
        }

        $time_difference = abs( $checkin - $checkout );
        $book = $time_difference / ( $actions->has('booking') ? 86400 : 3600 );
        $book = intval( $book );

        // min / max booking length
        if( $listing->type->get('rz_allow_min_max') ) {

            $min_book = $listing->get('rz_reservation_length_min');
            $max_book = $listing->get('rz_reservation_length_max');

            if( $min_book > 0 ) {
                if( $book < $min_book ) {
                    return (object) [
                        'success' => false,
                        'message' => sprintf( $actions->has('booking') ? esc_html__( '%s days is the minimum length of reservation', 'routiz' ) : esc_html__( '%s hours is the minimum length of reservation', 'routiz' ), $min_book )
                    ];
                }
            }

            if( $max_book > 0 ) {
                if( $book > $max_book ) {
                    return (object) [
                        'success' => false,
                        'message' => sprintf( $actions->has('booking') ? esc_html__( '%s days is the maximum length of reservation', 'routiz' ) : esc_html__( '%s hours is the maximum length of reservation', 'routiz' ), $max_book )
                    ];
                }
            }
        }

        $booked_dates = Rz()->get_array( $this->id, 'rz_booking_booked', true );
        $pending_dates = Rz()->get_array( $this->id, 'rz_booking_pending', true );
        $unavailable_dates = Rz()->get_array( $this->id, 'rz_booking_unavailable', true );

        $check_in = new \DateTime();
        $check_in->setTimestamp( $checkin );
        $check_in_unix = $check_in->getTimestamp();

        $check_out = new \DateTime();
        $check_out->setTimestamp( $checkout );
        $check_out->modify('yesterday');
        $check_out_unix = $check_out->getTimestamp();

        while( $check_in_unix <= $check_out_unix ) {

            // not available dates
            if(
                array_key_exists( $check_in_unix, $booked_dates ) ||
                array_key_exists( $check_in_unix, $pending_dates ) ||
                array_key_exists( $check_in_unix, $unavailable_dates )
            ) {
                return (object) [
                    'success' => false,
                    'message' => esc_html__( 'The selected dates are not available', 'routiz' )
                ];
            }

            $check_in->modify('tomorrow');
            $check_in_unix = $check_in->getTimestamp();

        }

        // dates are available
        return (object) [
            'success' => true,
            'message' => esc_html__( 'Your reservation was sent', 'routiz' )
        ];

    }

    public function pricing() {
        return (object) [
            'final' => 10
        ];
    }

    public function get_booking_product( $action = 'booking' ) {

        $listing_type = new Listing_Type( Rz()->get_meta( 'rz_listing_type', $this->id ) );
        $action_booking = $listing_type->get_action_type( $action );

        return wc_get_product( $action_booking->fields->action_type_product );

    }

    static function create_booking_product() {

        $product_args = [
            'post_title' => wc_clean( 'Booking' ),
            'post_status' => 'private',
            'post_type' => 'product',
            'post_excerpt' => '',
            'post_content' => stripslashes( html_entity_decode( 'Auto generated product for booking, please do not delete or update.', ENT_QUOTES, 'UTF-8' ) ),
            'post_author' => 1
        ];

        $product_id = wp_insert_post( $product_args );

        if ( ! is_wp_error( $product_id ) ) {

            $product = wc_get_product( $product_id );

            wp_set_object_terms( $product_id, 'listing_booking', 'product_type' );

            update_post_meta( $product_id, '_stock_status', 'instock' );
            update_post_meta( $product_id, 'total_sales', '0' );
            update_post_meta( $product_id, '_downloadable', 'yes' );
            update_post_meta( $product_id, '_virtual', 'yes' );
            update_post_meta( $product_id, '_regular_price', '0' );
            update_post_meta( $product_id, '_sale_price', '' );
            update_post_meta( $product_id, '_purchase_note', '' );
            update_post_meta( $product_id, '_featured', 'no' );
            update_post_meta( $product_id, '_weight', '' );
            update_post_meta( $product_id, '_length', '' );
            update_post_meta( $product_id, '_width', '' );
            update_post_meta( $product_id, '_height', '' );
            update_post_meta( $product_id, '_sku', '' );
            update_post_meta( $product_id, '_product_attributes', array() );
            update_post_meta( $product_id, '_sale_price_dates_from', '' );
            update_post_meta( $product_id, '_sale_price_dates_to', '' );
            update_post_meta( $product_id, '_price', '' );
            update_post_meta( $product_id, '_sold_individually', 'yes' );
            update_post_meta( $product_id, '_manage_stock', 'no' );
            update_post_meta( $product_id, '_backorders', 'no' );
            update_post_meta( $product_id, '_stock', '' );

            if( version_compare( WC_VERSION, '3.0', '>=' ) ) {
                $product->set_reviews_allowed( false );
                $product->set_catalog_visibility( 'hidden' );
                $product->save();
            }

        }
    }

    public function add_booked_days( $entry_id ) {

        $check_in_date = Rz()->get_meta( 'rz_checkin_date', $entry_id );
        $check_out_date = Rz()->get_meta( 'rz_checkout_date', $entry_id );

        $reservation_dates_array = Rz()->get_array( 'rz_booking_booked', $this->id );

        $check_in = new \DateTime();
        $check_in->setTimestamp( $check_in_date );
        $check_in_unix = $check_in->getTimestamp();

        $check_out = new \DateTime();
        $check_out->setTimestamp( $check_out_date );
        $check_out_unix = $check_out->getTimestamp();

        while( $check_in_unix < $check_out_unix ) {

            if( ! in_array( $check_in_unix, $reservation_dates_array ) ) {
                $reservation_dates_array[] = $check_in_unix;
            }
            $check_in->modify('tomorrow');
            $check_in_unix = $check_in->getTimestamp();

        }

        return $reservation_dates_array;

    }

    public function add_pending_days( $entry_id ) {

        $check_in_date = Rz()->get_meta( 'rz_checkin_date', $entry_id );
        $check_out_date = Rz()->get_meta( 'rz_checkout_date', $entry_id );

        $reservation_dates_array = Rz()->get_array( 'rz_booking_pending', $this->id );

        $check_in = new \DateTime();
        $check_in->setTimestamp( $check_in_date );
        $check_in_unix = $check_in->getTimestamp();

        $check_out = new \DateTime();
        $check_out->setTimestamp( $check_out_date );
        $check_out_unix = $check_out->getTimestamp();

        while( $check_in_unix < $check_out_unix ) {

            if( ! in_array( $check_in_unix, $reservation_dates_array ) ) {
                $reservation_dates_array[] = $check_in_unix;
            }
            $check_in->modify('tomorrow');
            $check_in_unix = $check_in->getTimestamp();

        }

        return $reservation_dates_array;

    }

    public function add_unavailable_days( $dates_array, $toggle = false ) {

        $unavailable_dates_array = Rz()->get_array( 'rz_booking_unavailable', $this->id );

        if( is_array( $dates_array ) ) {
            foreach( $dates_array as $d ) {

                $date = new \DateTime();
                $date->setTimestamp( $d );
                $date_unix = $date->getTimestamp();

                if( ! in_array( $date_unix, $unavailable_dates_array ) ) {
                    $unavailable_dates_array[] = $date_unix;
                }elseif( $toggle ) {
                    unset( $unavailable_dates_array[ array_search( $date_unix, $unavailable_dates_array ) ] );
                }
            }
        }

        update_post_meta( $this->id, 'rz_booking_unavailable', $unavailable_dates_array );

    }

    public function get_dates_between_range( $start, $end ) {

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

            $dates[] = $check_in->getTimestamp();

            $check_in->modify('tomorrow');
            $check_in_unix = $check_in->getTimestamp();

        }

        return $dates;

    }

    public function add_dates( $status, Array $dates ) {

        $status_array = [
            'pending' => 'rz_booking_pending',
            'booked' => 'rz_booking_booked',
            'unavailable' => 'rz_booking_unavailable'
        ];

        if( ! array_key_exists( $status, $status_array ) ) {
            return;
        }

        $status_id = $status_array[ $status ];
        $dates_array = Rz()->get_array( $status_id, $this->id );

        foreach( $dates as $date ) {
            if( ! in_array( $date, $dates_array ) ) {
                $dates_array[] = $date;
            }
        }

        update_post_meta( $this->id, $status_id, $dates_array );

    }

    public function release_dates( $status, Array $dates ) {

        $status_array = [
            'pending' => 'rz_booking_pending',
            'booked' => 'rz_booking_booked',
            'unavailable' => 'rz_booking_unavailable'
        ];

        if( ! array_key_exists( $status, $status_array ) ) {
            return;
        }

        $status_id = $status_array[ $status ];
        $dates_array = Rz()->get_array( $status_id, $this->id );

        update_post_meta( $this->id, $status_id, array_diff( $dates_array, $dates ) );

    }

    public function set_unavailable( Array $dates ) {
        $availability_dates = get_post_meta( $this->id, 'rz_availability_dates', false );
        foreach( $dates as $date ) {
            if( ! in_array( $date, $availability_dates ) ) {
                add_post_meta( $this->id, 'rz_availability_dates', $date );
            }
        }
    }

    public function set_available( Array $dates ) {
        $availability_dates = get_post_meta( $this->id, 'rz_availability_dates', false );
        foreach( $dates as $date ) {
            if( in_array( $date, $availability_dates ) ) {
                delete_post_meta( $this->id, 'rz_availability_dates', $date );
            }
        }
    }

    public function get_nights( $checkin, $checkout ) {
        return ceil( ( $checkout - $checkin ) / 86400 );
    }

    public function get_hours( $checkin, $checkout ) {
        return round( floatval( ( $checkout - $checkin ) / 3600 ), 2 );
    }

}
