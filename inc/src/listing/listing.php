<?php

namespace Routiz\Inc\Src\Listing;

use \Routiz\Inc\Src\Listing_Type\Listing_Type;
use \Routiz\Inc\Src\Listing\Booking;
use \Routiz\Inc\Src\Woocommerce\Packages\Plan;
use \Routiz\Inc\Src\Woocommerce\Packages\Promotion;

class Listing {

    public $id;
    public $post;
    public $type;
    public $booking;
    public $reviews;
    public $class = [];
    public $attrs = [];

    function __construct( $id = false ) {

        $this->id = $id ? $id : get_the_ID();
        $this->post = get_post( $this->id );
        $this->booking = new Booking( $this->id );
        $this->type = new Listing_Type( $this->get('rz_listing_type') );
        $this->reviews = new Reviews( $this->id );

    }

    public function get_status() {
        return get_post_status( $this->id );
    }

    public function get_author() {
        $author_id = get_post_field( 'post_author', $this->id );

        $author = [
            'id' => $author_id,
            'name' => get_the_author_meta( 'display_name', $author_id ),
            'url' => get_author_posts_url( $author_id ),
        ];

        return (object) $author;
    }

    public function get( $key ) {

        if( $key == 'post_title' ) {
            return get_the_title( $this->id );
        }

        if( $key == 'post_content' ) {
            return get_the_content( null, false, $this->id );
        }

        return Rz()->get_meta( $key, $this->id );

    }

    public function get_gallery( $size = 'rz_listing', $id = 'rz_gallery' ) {

        $gallery_attrs = Rz()->jsoning( Rz()->prefix( $id ), $this->id );
        $gallery = [];

        foreach( $gallery_attrs as $image_attrs ) {
            if( isset( $image_attrs->id ) ) {
                if( is_array( $size ) ) {
                    $gallery_items = [];
                    foreach( $size as $size_item ) {
                        if( $image = Rz()->get_image( $image_attrs->id, $size_item ) ) {
                            $gallery_items[ $size_item ] = $image;
                        }
                    }
                    $gallery[] = $gallery_items;
                }else{
                    if( $image = Rz()->get_image( $image_attrs->id, $size ) ) {
                        $gallery[] = $image;
                    }
                }
            }
        }

        return $gallery;

    }

    public function get_first_from_gallery( $size = 'rz_listing' ) {

        $gallery = $this->get_gallery( $size );

        if( isset( $gallery[0] ) ) {
            return $gallery[0];
        }

        return null;

    }

    public function get_details( $key ) {

        $details = [];

        foreach( Rz()->jsoning( $key, $this->type->id ) as $detail ) {

            // taxonomy
            if( $detail->template->id == 'taxonomy' ) {

                $value = Rz()->get_meta( Rz()->prefix( $detail->fields->key ), $this->id, false );

                if( is_array( $value ) ) {
                    foreach( $value as $val ) {

                        $term = get_term( $val, $detail->fields->key );

                        if( is_object( $term ) and ! is_wp_error( $term ) ) {

                            $term_icon = get_term_meta( $term->term_id, 'rz_icon', true );

                            $details[] = (object) [
                                'content' => $term->name,
                                'icon' => ( $detail->fields->display_icons and $term_icon ) ? $term_icon : null
                            ];

                        }
                    }
                }
            }

            // label
            elseif( $detail->template->id == 'label' ) {

                if( $value = $this->get( Rz()->prefix( $detail->fields->key ) ) ) {

                    if( isset( $detail->fields->render_format ) ) {
                        switch( $detail->fields->render_format ) {
                            case 'price':
                                $value = Rz()->format_price( $value );
                                break;
                        }
                    }

                    $content = $detail->fields->format ? str_replace( '{field}', $value, $detail->fields->format ) : $value;

                    $details[] = (object) [
                        'content' => $content,
                        'icon' => isset( $detail->fields->icon ) ? $detail->fields->icon : null
                    ];
                }
            }

        }

        return $details;

    }

    public function get_field_item( $key ) {

        foreach( Rz()->json_decode( $this->type->get('rz_fields') ) as $field ) {
            if( isset( $field->fields->key ) and Rz()->prefix( $field->fields->key ) == $key ) {
                return $field;
            }
        }

        return null;

    }

    public function awaits_payment() {

        wp_update_post([
            'ID' => $this->id,
            'post_status' => 'pending_payment'
        ]);

    }

    public function pack_away( $user_plan_id ) {

        /*
         * attach listing to plan
         * and +1 to plan counter
         *
         */
        add_post_meta( $user_plan_id, 'rz_attached', $this->id );
        update_post_meta( $user_plan_id, 'rz_count', (int) get_post_meta( $user_plan_id, 'rz_count', true ) + 1 );

        /*
         * attach plan to listing
         *
         */
        delete_post_meta( $this->id, 'rz_plan' );
        add_post_meta( $this->id, 'rz_plan', $user_plan_id );

        // set expiration
        $this->set_expiration();

        // set priority
        $priority = (int) get_post_meta( $user_plan_id, 'rz_priority', true );
        if( $priority > 0 ) {
            update_post_meta( $this->id, 'rz_priority', $priority );
        }

        // change listing status
        wp_update_post([
            'ID' => $this->id,
            'post_status' => Rz()->get_meta( 'rz_requires_admin_approval', $this->type->id ) ? 'pending' : 'publish'
        ]);

    }

    static public function calculate_expiration( $duration ) {

        if ( $duration ) {
            return date( 'Y-m-d', strtotime( "+{$duration} days", current_time( 'timestamp' ) ) );
        }

        return '';

    }

    public function set_expiration() {

        $listing_plan_id = $this->get( 'rz_plan' );

        if( $listing_plan_id ) {

            $plan_id = Rz()->get_meta( 'rz_product_id', $listing_plan_id );
            $plan = new Plan( $plan_id );

            if( $plan->id ) {

                $duration = $plan->get_duration();
                if( $duration > 0 ) {

                    delete_post_meta( $this->id, 'rz_listing_expires' );
                    add_post_meta( $this->id, 'rz_listing_expires', Listing::calculate_expiration( $duration ) );

                }
            }
        }
    }

    public function get_author_id() {
        return $this->post->post_author;
    }

    public function is_owner() {

        if( get_current_user_id() == $this->get_author_id() ) {
            return true;
        }

        return;

    }

    public function promote( $user_promotion_id ) {

        $promotion = new Promotion( Rz()->get_meta( 'rz_product_id', $user_promotion_id ) );
        if( ! $promotion->id ) {
            return;
        }

        $expires = Rz()->get_meta( 'rz_expires', $user_promotion_id );
        if( $expires <= time() ) {
            return;
        }

        $duration = $promotion->get_duration();
        if( $duration == 0 ) {
            $duration = 30;
        }

        $priority = $promotion->get_priority();
        if( $priority == 0 ) {
            $priority = 2;
        }

        // attach listing to promotion
        update_post_meta( $user_promotion_id, 'rz_attached', $this->id );

        // expiration
        update_post_meta( $this->id, 'rz_promotion_expires', $expires );

        // priority
        $old_priority = (int) get_post_meta( $this->id, 'rz_priority', true );
        update_post_meta( $this->id, 'rz_old_priority', $old_priority );
        update_post_meta( $this->id, 'rz_priority', $priority );
        delete_post_meta( $this->id, 'rz_priority_custom' );

        switch( $priority ) {
            case 0: update_post_meta( $this->id, 'rz_priority_selection', 'normal' ); break;
            case 1: update_post_meta( $this->id, 'rz_priority_selection', 'featured' ); break;
            case 2: update_post_meta( $this->id, 'rz_priority_selection', 'promoted' ); break;
            default:
                update_post_meta( $this->id, 'rz_priority_selection', 'custom' );
                update_post_meta( $this->id, 'rz_priority_custom', $priority );
        }

    }

    public function the_classes() {

        global $rz_listing;

        $class = $this->class;
        $priority = (int) $this->get('rz_priority');

        switch( $priority ) {
            case 0: $class[] = 'rz--normal'; break;
            case 1: $class[] = 'rz--featured'; break;
            case 2: $class[] = 'rz--promoted'; break;
        }

        $cover_type = $rz_listing->type->get('rz_display_listing_cover');
        $class[] = sprintf( 'rz-cover--%s', $cover_type );

        echo ' ' . implode( ' ', apply_filters( 'routiz/explore/listing/classes', $class ) );

    }

    public function the_attrs() {

        $attrs = $this->attrs;
        $attrs['data-id'] = $this->id;
        $attrs['data-priority'] = (int) $this->get('rz_priority');

        echo Rz()->attrs( apply_filters( 'routiz/explore/listing/attributes', $attrs ) );

    }

    public function report( $reason ) {

        $report_id = wp_insert_post([
            'post_title' => '',
            'post_status' => 'pending',
            'post_type' => 'rz_report',
            'post_author' => get_current_user_id(),
            'meta_input'  => [
                'rz_listing_id' => $this->id,
                'rz_report_reason' => $reason,
            ]
        ]);
        $existing_user_ids = get_post_meta($this->id, 'report_user_id', true);
        $current_user_id = get_current_user_id();
        
        if ($existing_user_ids) {
            // Append the current user ID to the existing IDs
            $new_user_ids = $existing_user_ids . ',' . $current_user_id;
        } else {
            // Create a new list of user IDs
            $new_user_ids = $current_user_id;
        }

        update_post_meta($this->id, 'report_user_id', $new_user_ids);
        wp_update_post([
            'ID' => $report_id,
            'post_title' => sprintf( '#%s, %s', $report_id, get_the_title( $this->id ) ),
        ]);

        return $report_id;

    }

    public function has_reported() {

        $query = new \WP_Query([
            'post_status' => 'any',
            'post_type' => 'rz_report',
            'post_author' => get_current_user_id(),
            'meta_query' => [
                [
                    'key' => 'rz_listing_id',
                    'value' => $this->id,
                    'compare' => '=',
                ]
            ]
        ]);

        return $query->found_posts > 0;

    }

    public function get_priority() {
        return $this->get('rz_priority');
    }

    public function get_priority_label() {

        $label = $this->get_priority();
        $labels = Rz()->get_priority_labels();

        if ( isset( $labels[ $label ] ) ) {
            return $labels[ $label ];
        }

    }

    public function get_booking_date_range_price( $checkin, $checkout, $guests = 0, $children = 0, $addons = [] ) {

        $nights = $this->booking->get_nights( $checkin, $checkout );

        $check_in = new \DateTime();
        $check_in->setTimestamp( $checkin );
        $check_in_unix = $check_in->getTimestamp();

        $check_out = new \DateTime();
        $check_out->setTimestamp( $checkout );
        $check_out_unix = $check_out->getTimestamp();

        $output = [
            'nights' => $nights,
            'base' => 0,
            'guests' => 0,
            'children' => 0,
            'guest_price' => 0,
            'child_price' => 0,
            'child_sep_price' => 0,
            'long_term' => 0,
            'security_deposit' => 0,
            'service_fee' => 0,
            'extras' => [],
            'extras_total' => 0,
            'addons' => [],
            'addons_total' => 0,
            'payment_processing' => 0,
            'payment_processing_name' => '',
            'total' => 0,
            'processing' => 0,
        ];

        // get base, apply weekend and seasonal pricing
        while( $check_in_unix < $check_out_unix ) {

            $day_price = $this->get_day_price( $check_in );
            $output['base'] += $day_price->base;

            $check_in->modify('tomorrow');
            $check_in_unix = $check_in->getTimestamp();

        }

        $output['base'] = round( $output['base'], 2 );

        // guests
        if( $guests ) {
            $output['guests'] = $guests;
        }

        // guest price
        if( $this->type->get('rz_allow_guest_pricing') and $guests > 1 ) {
            if( $guest_price = $this->get('rz_guest_price') ) {
                $output['guest_price'] = $guest_price * ( $guests - 1 ) * $nights;
            }
        }

        // children
        if( $children ) {
            $output['children'] = $children;
        }

        // child price
        if( $this->type->get('rz_allow_child_pricing') ) {
            if( $children_price = $this->get('rz_child_price') ) {
                $output['child_price'] = $children_price * $children * $nights;
            }
        }

        // child sep price
        if( $this->type->get('rz_allow_child_based_pricing') ) {
            if( $children_sep_price = $this->get('rz_child_sep_price') ) {
                $output['child_sep_price'] = $children_sep_price * $children * $nights;
            }
        }

        // long term discounts month
        if( $nights >= 30 ) {
            $long_term_month = floatval( $this->get('rz_long_term_month') );
            if( $long_term_month > 0 and $long_term_month <= 100 ) {
                $output['long_term'] = round( $output['base'] * ( $long_term_month / 100 ), 2 );
            }
        }

        // long term discounts week
        if( $nights >= 7 and ! $output['long_term'] ) {
            $long_term_week = floatval( $this->get('rz_long_term_week') );
            if( $long_term_week > 0 and $long_term_week <= 100 ) {
                $output['long_term'] = round( $output['base'] * ( $long_term_week / 100 ), 2 );
            }
        }

        // extra services: fee, per day
        $extras = Rz()->json_decode( $this->get('rz_extra_pricing') );
        if( $extras ) {
            foreach( $extras as $extra ) {
                $output['extras'][] = $extra->fields;
                $extra_price = floatval( $extra->fields->price );
                $output['extras_total'] += $extra->fields->type == 'single_fee' ? $extra_price : $extra_price * $nights;
            }
        }

        $output['extras_total'] = round( $output['extras_total'], 2 );

        // addons
        if( $this->type->get('rz_allow_addons') ) {
            if( is_array( $addons ) ) {
                $available_addons = Rz()->jsoning( 'rz_addons', $this->id );
                // dd( $available_addons );
                foreach( $available_addons as $addon_item ) {
                    if( in_array( $addon_item->fields->key, $addons ) ) {
                        $addon_price = floatval( $addon_item->fields->price );
                        $output['addons'][ $addon_item->fields->key ] = (object) [
                            'name' => $addon_item->fields->name,
                            'price' => $addon_price,
                        ];
                        $output['addons_total'] += $addon_price;
                    }
                }
            }
        }

        // service fee
        $service_fee_type = $this->type->get('rz_service_fee_type');
        switch( $service_fee_type ) {
            // fixed
            case 'fixed':
                $output['service_fee'] = round( $this->type->get('rz_service_fee_amount_fixed'), 2 );
                break;
            // percentage
            case 'percentage':
                $service_fee_amount_percentage = floatval( $this->type->get('rz_service_fee_amount_percentage') );
                if( $service_fee_amount_percentage > 0 ) {
                    $output['service_fee'] =
                        round( ( $service_fee_amount_percentage / 100 ) * $output['base'], 2 ) +
                        round( ( $service_fee_amount_percentage / 100 ) * $output['extras_total'], 2 ) +
                        round( ( $service_fee_amount_percentage / 100 ) * $output['addons_total'], 2 );
                }
                break;
        }

        // security deposit
        $security_deposit = floatval( $this->get('rz_security_deposit') );
        if( $security_deposit > 0 ) {
            $output['security_deposit'] = $security_deposit;
        }

        // payment processing info
        $payment_processing = $this->type->get('rz_payment_processing');
        $payment_processing_types = Rz()->get_payment_processing_types();
        $output['payment_processing'] = $payment_processing;
        $output['payment_processing_name'] = $payment_processing_types[ $payment_processing ];

        // total
        $output['total'] =
            $output['base'] +
            $output['guest_price'] -
            $output['child_price'] +
            $output['child_sep_price'] -
            $output['long_term'] +
            $output['security_deposit'] +
            $output['service_fee'] +
            $output['extras_total'] +
            $output['addons_total'];

        // processing
        switch( $payment_processing ) {
            case 'full':
                $output['processing'] = $output['total'];
                break;
            case 'percentage':
                $payment_processing_percentage = floatval( $this->type->get('rz_payment_processing_percentage') );
                if( $payment_processing_percentage > 0 ) {
                    $output['processing'] = round( ( $payment_processing_percentage / 100 ) * $output['total'], 2 );
                }else{
                    $output['processing'] = $output['total'];
                }
                break;
            case 'security_deposit':
                $output['processing'] = $output['security_deposit'];
                break;
            case 'service_fee':
                $output['processing'] = $output['service_fee'];
                break;
            case 'locally':
                break;
        }

        return (object) $output;

    }

    public function get_booking_hour_range_price( $checkin, $checkout = 0, $guests = 0, $children = 0, $addons = [] ) {

        $action = $this->type->get_action('booking_hourly');

        $hours = $this->booking->get_hours( $checkin, $checkout );

        $check_in = new \DateTime();
        $check_in->setTimestamp( $checkin );
        $check_in_unix = $check_in->getTimestamp();

        $check_out = new \DateTime();
        $check_out->setTimestamp( $checkout );
        $check_out_unix = $check_out->getTimestamp();

        $output = [
            'hours' => $hours,
            'base' => 0,
            'guests' => 0,
            'children' => 0,
            'guest_price' => 0,
            'child_price' => 0,
            'child_sep_price' => 0,
            'long_term' => 0,
            'security_deposit' => 0,
            'service_fee' => 0,
            'extras' => [],
            'extras_total' => 0,
            'addons' => [],
            'addons_total' => 0,
            'payment_processing' => 0,
            'payment_processing_name' => '',
            'total' => 0,
            'processing' => 0,
        ];

        // get base
        $day_price = $this->get_day_price( $check_in );
        $output['base'] = round( $day_price->base * $hours, 2 );
        $output['base'] = round( $output['base'], 2 );

        // guests
        if( $guests ) {
            $output['guests'] = $guests;
        }

        // guest price
        if( $this->type->get('rz_allow_guest_pricing') and $guests > 1 ) {
            if( $guest_price = $this->get('rz_guest_price') ) {
                $output['guest_price'] = $guest_price * ( $guests - 1 ) * $hours;
            }
        }

        // Children
        if( $children ) {
            $output['children'] = $children;
        }

        // child price
        if( $this->type->get('rz_allow_child_pricing')) {
            if( $children_price = $this->get('rz_child_price') ) {
                $output['child_price'] = $children_price * $children  * $hours;
            }
        }

        // child sep price
        if( $this->type->get('rz_allow_child_based_pricing')) {
            if( $children_sep_price = $this->get('rz_child_sep_price') ) {
                $output['child_sep_price'] = $children_sep_price * $children  * $hours;
            }
        }

        // long term discounts month
        if( $hours >= 30 ) {
            $long_term_month = floatval( $this->get('rz_long_term_month') );
            if( $long_term_month > 0 and $long_term_month <= 100 ) {
                $output['long_term'] = round( $output['base'] * ( $long_term_month / 100 ), 2 );
            }
        }

        // long term discounts week
        if( $hours >= 7 and ! $output['long_term'] ) {
            $long_term_week = floatval( $this->get('rz_long_term_week') );
            if( $long_term_week > 0 and $long_term_week <= 100 ) {
                $output['long_term'] = round( $output['base'] * ( $long_term_week / 100 ), 2 );
            }
        }

        // extra services: fee, per day
        $extras = Rz()->json_decode( $this->get('rz_extra_pricing') );
        if( $extras ) {
            foreach( $extras as $extra ) {
                $output['extras'][] = $extra->fields;
                $extra_price = floatval( $extra->fields->price );
                $output['extras_total'] += $extra->fields->type == 'single_fee' ? $extra_price : $extra_price * $hours;
            }
        }

        $output['extras_total'] = round( $output['extras_total'], 2 );

        // addons
        if( $this->type->get('rz_allow_addons') ) {
            if( is_array( $addons ) ) {
                $available_addons = Rz()->jsoning( 'rz_addons', $this->id );
                // dd( $available_addons );
                foreach( $available_addons as $addon_item ) {
                    if( in_array( $addon_item->fields->key, $addons ) ) {
                        $addon_price = floatval( $addon_item->fields->price );
                        $output['addons'][ $addon_item->fields->key ] = (object) [
                            'name' => $addon_item->fields->name,
                            'price' => $addon_price,
                        ];
                        $output['addons_total'] += $addon_price;
                    }
                }
            }
        }

        // service fee
        $service_fee_type = $this->type->get('rz_service_fee_type');
        switch( $service_fee_type ) {
            // fixed
            case 'fixed':
                $output['service_fee'] = round( $this->type->get('rz_service_fee_amount_fixed'), 2 );
                break;
            // percentage
            case 'percentage':
                $service_fee_amount_percentage = floatval( $this->type->get('rz_service_fee_amount_percentage') );
                if( $service_fee_amount_percentage > 0 ) {
                    $output['service_fee'] = round( ( $service_fee_amount_percentage / 100 ) * $output['base'], 2 );
                }
                break;
        }

        // security deposit
        $security_deposit = floatval( $this->get('rz_security_deposit') );
        if( $security_deposit > 0 ) {
            $output['security_deposit'] = $security_deposit;
        }

        // payment processing info
        $payment_processing = $this->type->get('rz_payment_processing');
        $payment_processing_types = Rz()->get_payment_processing_types();
        $output['payment_processing'] = $payment_processing;
        $output['payment_processing_name'] = $payment_processing_types[ $payment_processing ];

         // total
    $output['total'] = $output['base'] + $output['guest_price'] - $output['child_price'] + $output['child_sep_price'] -
        $output['long_term'] + $output['security_deposit'] + $output['service_fee'] + $output['extras_total'] +
        $output['addons_total'];


        // processing
        switch( $payment_processing ) {
            case 'full':
                $output['processing'] = $output['total'];
                break;
            case 'percentage':
                $payment_processing_percentage = floatval( $this->type->get('rz_payment_processing_percentage') );
                if( $payment_processing_percentage > 0 ) {
                    $output['processing'] = round( ( $payment_processing_percentage / 100 ) * $output['total'], 2 );
                }else{
                    $output['processing'] = $output['total'];
                }
                break;
            case 'security_deposit':
                $output['processing'] = $output['security_deposit'];
                break;
            case 'service_fee':
                $output['processing'] = $output['service_fee'];
                break;
            case 'locally':
                break;
        }

        return (object) $output;

    }

    public function get_booking_appointment_price( $price, $price_weekend, \DateTime $checkin, $guests = 0, $children = 0, $addons = [] ) {

        $output = [
            'base' => 0,
            'guests' => 0,
            'children' => 0,
            'guest_price' => 0,
            'child_price' => 0,
            'child_sep_price' => 0,
            'security_deposit' => 0,
            'service_fee' => 0,
            'addons' => [],
            'addons_total' => 0,
            'payment_processing' => 0,
            'payment_processing_name' => '',
            'total' => 0,
            'processing' => 0,
        ];

        // get base
        $day_price = $this->get_day_price( $checkin );
        $output['base'] = round( $day_price->base, 2 );
        $output['base'] = round( $output['base'], 2 );

        // custom appointment price
        if( ! empty( $price ) ) {
            $output['base'] = round( $price, 2 );

            if( Rz()->is_weekend( $checkin ) and $price_weekend > 0 ) {
                $output['base'] = $price_weekend;
            }
        }

        // guests
        if( $guests ) {
            $output['guests'] = $guests;
        }

        // guest price
        if( $this->type->get('rz_allow_guest_pricing') and $guests > 1 ) {
            if( $guest_price = $this->get('rz_guest_price') ) {
                $output['guest_price'] = $guest_price * ( $guests - 1 );
            }
        }

        // children
        if( $children ) {
            $output['children'] = $children;
        }

        // child price
        if( $this->type->get('rz_allow_child_pricing')) {
            if( $children_price = $this->get('rz_child_price') ) {
                $output['child_price'] = $children_price * $children;
            }
        }

        // child sep price
        if( $this->type->get('rz_allow_child_based_pricing')) {
            if( $children_sep_price = $this->get('rz_child_sep_price') ) {
                $output['child_sep_price'] = $children_sep_price * $children;
            }
        }

        // addons
        if( $this->type->get('rz_allow_addons') ) {
            if( is_array( $addons ) ) {
                $available_addons = Rz()->jsoning( 'rz_addons', $this->id );
                // dd( $available_addons );
                foreach( $available_addons as $addon_item ) {
                    if( in_array( $addon_item->fields->key, $addons ) ) {
                        $addon_price = floatval( $addon_item->fields->price );
                        $output['addons'][ $addon_item->fields->key ] = (object) [
                            'name' => $addon_item->fields->name,
                            'price' => $addon_price,
                        ];
                        $output['addons_total'] += $addon_price;
                    }
                }
            }
        }

        // service fee
        $service_fee_type = $this->type->get('rz_service_fee_type');
        switch( $service_fee_type ) {
            // fixed
            case 'fixed':
                $output['service_fee'] = round( $this->type->get('rz_service_fee_amount_fixed'), 2 );
                break;
            // percentage
            case 'percentage':
                $service_fee_amount_percentage = floatval( $this->type->get('rz_service_fee_amount_percentage') );
                if( $service_fee_amount_percentage > 0 ) {
                    $output['service_fee'] = round( ( $service_fee_amount_percentage / 100 ) * $output['base'], 2 );
                }
                break;
        }

        // security deposit
        $security_deposit = floatval( $this->get('rz_security_deposit') );
        if( $security_deposit > 0 ) {
            $output['security_deposit'] = $security_deposit;
        }

        // payment processing info
        $payment_processing = $this->type->get('rz_payment_processing');
        $payment_processing_types = Rz()->get_payment_processing_types();
        $output['payment_processing'] = $payment_processing;
        $output['payment_processing_name'] = $payment_processing_types[ $payment_processing ];

        // total
        $output['total'] =
            $output['base'] +
            $output['guest_price'] -
             $output['child_price'] +
             $output['child_sep_price'] +
            $output['security_deposit'] +
            $output['service_fee'] +
            $output['addons_total'];

        // processing
        switch( $payment_processing ) {
            case 'full':
                $output['processing'] = $output['total'];
                break;
            case 'percentage':
                $payment_processing_percentage = floatval( $this->type->get('rz_payment_processing_percentage') );
                if( $payment_processing_percentage > 0 ) {
                    $output['processing'] = round( ( $payment_processing_percentage / 100 ) * $output['total'], 2 );
                }else{
                    $output['processing'] = $output['total'];
                }
                break;
            case 'security_deposit':
                $output['processing'] = $output['security_deposit'];
                break;
            case 'service_fee':
                $output['processing'] = $output['service_fee'];
                break;
            case 'locally':
                break;
        }

        return (object) $output;

    }

    public function get_purchase_price( $addons = [] ) {

        $output = [
            'base' => 0,
            'security_deposit' => 0,
            'service_fee' => 0,
            'extras' => [],
            'extras_total' => 0,
            'addons' => [],
            'addons_total' => 0,
            'payment_processing' => 0,
            'payment_processing_name' => '',
            'total' => 0,
            'processing' => 0,
        ];

        // get base
        $base = floatval( $this->get('rz_price') );
        $output['base'] = round( $base, 2 );

        // extra services: fee, per day
        $extras = Rz()->json_decode( $this->get('rz_extra_pricing') );
        if( $extras ) {
            foreach( $extras as $extra ) {
                $output['extras'][] = $extra->fields;
                $extra_price = floatval( $extra->fields->price );
                $output['extras_total'] += $extra_price;
            }
        }

        $output['extras_total'] = round( $output['extras_total'], 2 );

        // addons
        if( $this->type->get('rz_allow_addons') ) {
            if( is_array( $addons ) ) {
                $available_addons = Rz()->jsoning( 'rz_addons', $this->id );
                // dd( $available_addons );
                foreach( $available_addons as $addon_item ) {
                    if( in_array( $addon_item->fields->key, $addons ) ) {
                        $addon_price = floatval( $addon_item->fields->price );
                        $output['addons'][ $addon_item->fields->key ] = (object) [
                            'name' => $addon_item->fields->name,
                            'price' => $addon_price,
                        ];
                        $output['addons_total'] += $addon_price;
                    }
                }
            }
        }

        // service fee
        $service_fee_type = $this->type->get('rz_service_fee_type');
        switch( $service_fee_type ) {
            // fixed
            case 'fixed':
                $output['service_fee'] = round( $this->type->get('rz_service_fee_amount_fixed'), 2 );
                break;
            // percentage
            case 'percentage':
                $service_fee_amount_percentage = floatval( $this->type->get('rz_service_fee_amount_percentage') );
                if( $service_fee_amount_percentage > 0 ) {
                    $output['service_fee'] = round( ( $service_fee_amount_percentage / 100 ) * $output['base'], 2 );
                }
                break;
        }

        // security deposit
        $security_deposit = floatval( $this->get('rz_security_deposit') );
        if( $security_deposit > 0 ) {
            $output['security_deposit'] = $security_deposit;
        }

        // payment processing info
        $payment_processing = $this->type->get('rz_payment_processing');
        $payment_processing_types = Rz()->get_payment_processing_types();
        $output['payment_processing'] = $payment_processing;
        $output['payment_processing_name'] = $payment_processing_types[ $payment_processing ];

        // total
        $output['total'] =
            $output['base'] +
            $output['security_deposit'] +
            $output['service_fee'] +
            $output['extras_total'] +
            $output['addons_total'];

        // processing
        switch( $payment_processing ) {
            case 'full':
                $output['processing'] = $output['total'];
                break;
            case 'percentage':
                $payment_processing_percentage = floatval( $this->type->get('rz_payment_processing_percentage') );
                if( $payment_processing_percentage > 0 ) {
                    $output['processing'] = round( ( $payment_processing_percentage / 100 ) * $output['total'], 2 );
                }else{
                    $output['processing'] = $output['total'];
                }
                break;
            case 'security_deposit':
                $output['processing'] = $output['security_deposit'];
                break;
            case 'service_fee':
                $output['processing'] = $output['service_fee'];
                break;
            case 'locally':
                break;
        }

        return (object) $output;

    }

    public function get_day_price( \DateTime $date ) {

        /*
         * base
         *
         */
        $price = floatval( $this->get('rz_price') );
        $price_weekend = floatval( $this->get('rz_price_weekend') );
        $is_weekend = Rz()->is_weekend( $date );
        $price_seasonals = Rz()->json_decode( $this->get('rz_price_seasonal') );

        // check seasonal pricing
        if( is_array( $price_seasonals ) ) {
            foreach( $price_seasonals as $price_seasonal ) {

                $season_date_start = Rz()->get_field_date( $price_seasonal->fields->start );
                $season_date_end = Rz()->get_field_date( $price_seasonal->fields->end );

                $is_season_match = Rz()->is_date_between( $season_date_start, $season_date_end, $date );

                if( $is_season_match ) {

                    $season_base = floatval( $price_seasonal->fields->price );
                    $season_base_weekend = floatval( $price_seasonal->fields->price_weekend );

                    if( $season_base > 0 ) {
                        $price = $season_base;
                    }

                    if( $season_base_weekend > 0 ) {
                        $price_weekend = $season_base_weekend;
                    }

                    break;
                }

            }
        }

        $base = ( $is_weekend and $price_weekend > 0 ) ? $price_weekend : $price;

        return (object) [
            'base' => $base,
        ];

    }

    public function get_price() {

        // $pricing = $this->pricing();

        $base = floatval( $this->get('rz_price') );
        $service_fee_type = $this->type->get('rz_service_fee_type');
        $fee = 0;

        switch( $service_fee_type ) {
            case 'fixed':
                $fee = floatval( $this->type->get('rz_service_fee_amount_fixed') );
                break;
            case 'percentage':
                $fee = floatval( $this->type->get('rz_service_fee_amount_percentage') ) * $base * 0.01;
                break;
        }

        return [
            'service_fee_type' => $service_fee_type,
            'base' => round( $base, 2 ),
            'fee' => round( $fee, 2 ),
            'final' => round( $base + $fee, 2 )
        ];

    }

    public function get_price_html() {

    }

    /*public function get_location_data() {
        return [
            'address_1' => $this->get_object()->geolocation_street,
            'address_2' => '',
            'street_number' => $this->get_object()->geolocation_street_number,
            'city'  $this->get_object()->geolocation_city,
            'state' => $this->get_object()->geolocation_state_short,
            'full_state' => $this->get_object()->geolocation_state_long,
            'postcode' => $this->get_object()->geolocation_postcode,
            'country' => $this->get_object()->geolocation_country_short,
            'full_country' => $this->get_object()->geolocation_country_long,
            'latitude' => $this->get_object()->geolocation_latitude,
            'longitude' => $this->get_object()->geolocation_longitude,
        ];
    }*/

    public function get_address() {
        return $this->get('rz_location');
    }

    public function get_lat() {
        return $this->get('rz_location__lat');
    }

    public function get_lng() {
        return $this->get('rz_location__lng');
    }

    public function get_coordinates() {
        return $this->get_lat() . ',' . $this->get_lng();
    }

    /*
     * generate google map based on the location data
     *
     */
    public function get_map_url() {

        $base = 'http://maps.google.com/maps';
        $args = [
            'daddr' => urlencode( $this->get_coordinates() ),
        ];

        return esc_url( add_query_arg( $args, $base ) );

    }

    /*
     * generate json-ld data-structured
     *
     */
    public function get_json_ld() {

        /*$markup = [];

        $userdata = get_userdata( $this->post->post_author );

        $markup = [
            '@context' => 'http://schema.org',
            '@type' => 'Review',
            'name' => get_the_title( $this->id ),
            'description' => wp_trim_words( $this->post->post_content, 55 ),
            'url' => [
                '@type' => 'URL',
                '@id' => get_permalink( $this->id ),
            ],
            'itemReviewed' => [
                '@type' => $this->type->get('rz_name'),
                'image' => $this->get_first_from_gallery( 'large' ),
                'name' => get_the_title( $this->id ),
            ],
            'author' => [
                '@type' => 'Person',
                'name' => $userdata->display_name,
            ]
        ];

        // geo location
        if( $this->get_address() ) {
            $markup['address'] = $this->get_address();
        }

        // reviews
        if( $this->reviews->count ) {
            $markup['reviewRating'] = [
                '@type' => 'Rating',
                'ratingValue' => $this->reviews->average,
            ];
        }

        // image
        $image = wp_get_attachment_image_src( get_post_thumbnail_id( $this->id ) );

        if( $image !== false ) {
            $markup['image'] = $image[0];
        }*/

        /*
         * use custom handler through the hook to render any schema
         *
         */

        return apply_filters( 'routiz/listing/jsonld', $this );

    }

    public function get_action( $key = '' ) {

        if( ! $this->type ) {
            return null;
        }

        return $this->type->get_action( $key );

    }

    function is_instant() {
        return boolval( $this->type->get('rz_allow_instant') ) and boolval( $this->get('rz_instant') );
    }

}
