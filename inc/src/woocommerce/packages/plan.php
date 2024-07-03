<?php

namespace Routiz\Inc\Src\Woocommerce\Packages;

use \Routiz\Inc\Src\Request\Custom_Request;
use \Routiz\Inc\Src\Listing\Listing;
use \Routiz\Inc\Src\Listing_Type\Listing_Type;

class Plan extends Package {

    public $slug = 'rz_plan';

    public function get_duration() {
        return (int) $this->product->get_meta('_rz_plan_duration');
    }

    public function get_limit() {
        return (int) $this->product->get_meta('_rz_plan_limit');
    }

    public function get_priority() {
        return (int) $this->product->get_meta('_rz_plan_priority');
    }

    public function is_one_time_obtainable() {
        return $this->product->get_meta('_rz_plan_disable_repeat_purchase') == 'yes';
    }

    public function create( $order_id = null, $user_id = null ) {

        $plans = get_posts([
            'post_status' => ['publish'],
            'post_type' => $this->slug,
            'post_author' => $user_id,
            'meta_query' => [
                'relation' => 'AND',
                [
                    'key' => 'rz_product_id',
                    'value' => $this->id,
                ],
                [
                    'key' => 'rz_duration',
                    'value' => $this->get_duration(),
                ],
                [
                    'key' => 'rz_limit',
                    'value' => $this->get_limit(),
                ],
                [
                    'key' => 'rz_priority',
                    'value' => $this->get_priority(),
                ],
            ],
            'posts_per_page' => 1
        ]);

        

        $post_id = wp_insert_post([
            'post_title' => '',
            'post_status' => 'publish',
            'post_type' => $this->slug,
            'post_author' => $user_id,
            'meta_input'  => [
                'rz_product_id' => $this->id,
                'rz_duration' => $this->get_duration(),
                'rz_limit' => $this->get_limit(),
                'rz_priority' => $this->get_priority(),
            ]
        ]);

        // set title
        $userdata = get_userdata( $user_id );

        wp_update_post([
            'ID' => $post_id,
            'post_title' => $userdata->display_name
        ]);

        // set order
        if( $order_id ) {
            add_post_meta( $post_id, 'rz_order_id', $order_id );
        }

        return $post_id;

    }

    public function delete( $order_id = null, $user_id = null ) {

        $plans = get_posts([
            'post_status' => ['publish', 'used'],
            'post_type' => $this->slug,
            'post_author' => $user_id,
            'meta_query' => [
                'relation' => 'AND',
                [
                    'key' => 'rz_product_id',
                    'value' => $this->id,
                ],
                [
                    'key' => 'rz_duration',
                    'value' => $this->get_duration(),
                ],
                [
                    'key' => 'rz_limit',
                    'value' => $this->get_limit(),
                ],
                [
                    'key' => 'rz_priority',
                    'value' => $this->get_priority(),
                ],
            ],
            'posts_per_page' => 1
        ]);

        if( isset( $plans[0] ) ) {
            wp_delete_post( $plans[0]->ID );
        }

    }

    public function is_limit_reached() {

        // free
        if( ! $this->is_purchasable() ) {

            if( $this->is_one_time_obtainable() ) {

                $request = new Custom_Request('input');
                $listing_type = new Listing_Type( $request->get('type') );
                $listing_limit = $this->get_limit();

                if( ! $listing_type->id ) {
                    return;
                }

                // has limit
                if( $listing_limit > 0 ) {

                    $listings = new \WP_Query([
                        'post_type' => 'rz_listing',
                        'post_status' => [ 'publish', 'pending', 'pending_payment', 'expired' ],
                        'author' => get_current_user_id(),
                        'meta_query' => [
                            [
                                'key' => 'rz_listing_type',
                                'value' => $listing_type->id
                            ]
                        ]
                    ]);

                    return $listing_limit <= (int) $listings->found_posts;

                }

            }

            return;

        }

        // not free
        if( $this->is_one_time_obtainable() ) {
            return $this->get_available('used');
        }

        return;

    }

    public function get_first_available() {

        $availability = $this->get_available();

        if( isset( $availability[0] ) ) {
            return $availability[0]->ID;
        }

        return null;

    }

    public function get_first_available_id() {

        if( $availability = $this->get_first_available() ) {
            return $availability->ID;
        }

        return null;

    }

    public function get_available( $status = 'publish' ) {

        global $wpdb;

        $listing_limit = $this->get_limit();

        $results = $wpdb->get_results(
            $wpdb->prepare("
                    SELECT *, m1.meta_value as lim, m2.meta_value as cnt
                    FROM {$wpdb->prefix}posts p
                        LEFT JOIN {$wpdb->prefix}postmeta m1 ON m1.post_id = p.ID AND m1.meta_key = 'rz_limit'
                        LEFT JOIN {$wpdb->prefix}postmeta m2 ON m2.post_id = p.ID AND m2.meta_key = 'rz_count'
                        LEFT JOIN {$wpdb->prefix}postmeta m3 ON m3.post_id = p.ID AND m3.meta_key = 'rz_product_id'
                    WHERE post_type = 'rz_plan'
                    AND post_status = %s
                    AND post_author = %d
                    AND m3.meta_value = %d
                ",
                $status,
                get_current_user_id(),
                $this->product->get_id()
            )
        );

        $available = [];

        if( $status == 'publish' ) {
            foreach( $results as $plan ) {
                if( $plan->lim > $plan->cnt ) {
                    $available[] = $plan;
                }
            }
        }else{
            $available = $results;
        }

        return $available;

    }

    public function availability() {

        global $wpdb;

        $results = $wpdb->get_results(
            $wpdb->prepare("
                    SELECT *, m1.meta_value as lim, m2.meta_value as cnt
                    FROM {$wpdb->prefix}posts p
                        LEFT JOIN {$wpdb->prefix}postmeta m1 ON m1.post_id = p.ID AND m1.meta_key = 'rz_limit'
                        LEFT JOIN {$wpdb->prefix}postmeta m2 ON m2.post_id = p.ID AND m2.meta_key = 'rz_count'
                        LEFT JOIN {$wpdb->prefix}postmeta m3 ON m3.post_id = p.ID AND m3.meta_key = 'rz_product_id'
                    WHERE post_type = %s
                    AND post_status = %s
                    AND post_author = %d
                    AND m3.meta_value = %d
                ",
                'rz_plan',
                'publish',
                get_current_user_id(),
                $this->id
            )
        );

        $available = 0;

        if( $results ) {
            foreach( $results as $plan ) {

                if( $plan->lim == 0 ) {
                    return 'unlimited';
                }

                $plan_available = (int) $plan->lim - (int) $plan->cnt;
                if( $plan_available > 0 ) {
                    $available += $plan_available;
                }

            }
        }

        return $available;

    }

    public function add_to_cart( $listing_id ) {

        $listing = new Listing( $listing_id );

        if( ! $listing->id ) {
            return;
        }

        // dd(5555);

        /*
         * use existing cart product
         *
         */
        $cart = WC()->cart->cart_contents;
        foreach( $cart as $item_id => $item ) {

            $cart_package_id = $item['data']->get_id();

            if( $cart_package_id == $this->id ) {

                // cart item limit reached
                if( $this->get_limit() <= count( $item['listing_id'] ) ) {
                    continue;
                }

                if( isset( $item['listing_id'] ) and is_array( $item['listing_id'] ) ) {
                    $item['listing_id'][] = $listing->id;
                }

                WC()->cart->cart_contents[ $item_id ] = $item;
                WC()->cart->set_session();

                return [
                    'listing_id' => $listing->id,
                    'button_url' => get_permalink( wc_get_page_id( 'checkout' ) ),
                    'button_text' => esc_html__( 'Proceed to checkout', 'routiz' )
                ];

            }

        }

        /*
         * add new cart product
         *
         */
        WC()->cart->add_to_cart( $this->id, 1, '', '', [
            'listing_id' => [ $listing->id ],
        ]);

        wc_add_to_cart_message( $this->id );

        return [
            'listing_id' => $listing->id,
            'button_url' => get_permalink( wc_get_page_id( 'checkout' ) ),
            'button_text' => esc_html__( 'Proceed to checkout', 'routiz' )
        ];

    }

}