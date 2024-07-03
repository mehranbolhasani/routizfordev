<?php

namespace Routiz\Inc\Src\Woocommerce;

use Routiz\Inc\Src\Listing\Listing;
use \Routiz\Inc\Src\Traits\Singleton;

class Cart {

    use Singleton;

    function __construct() {

        // display listing name in cart item
        add_filter( 'woocommerce_get_item_data', [ $this, 'get_item_data' ], 10, 2 );
        // on create order, add meta to cart items
        add_action( 'woocommerce_checkout_create_order_line_item', [ $this, 'checkout_create_order_line_item' ], 10, 4 );

    }

    // display listing name in cart item
    public function get_item_data( $data, $cart_item ) {

        if( isset( $cart_item['listing_id'] ) and is_array( $cart_item['listing_id'] ) ) {

            foreach( $cart_item['listing_id'] as $listing_id ) {

                $listing = new Listing( $listing_id );

                if( $listing->post ) {
                    $data[] = [
        				'key' => __( 'Listing', 'routiz' ),
        				'value' => $listing->post->post_title ? $listing->post->post_title : esc_html__( '( no title )', 'routiz' ),
        			];
                }

            }

		}

		return $data;

	}

    // on create order, add meta to cart items
    public function checkout_create_order_line_item( $item, $key, $data, $order ) {

        if( isset( $data['listing_id'] ) and is_array( $data['listing_id'] ) ) {
            $item->update_meta_data( '_listing_id', json_encode( $data['listing_id'] ) );
        }

        if( isset( $data['entry_id'] ) and !empty( $data['entry_id'] ) ) {
            $item->update_meta_data( '_entry_id', (int) $data['entry_id'] );
        }

        if( isset( $data['request_user_id'] ) and (int) $data['request_user_id'] > 0 ) {
            $item->update_meta_data( '_request_user_id', (int) $data['request_user_id'] );
        }

        if( isset( $data['pricing'] ) and ! empty( $data['pricing'] ) ) {
            $item->update_meta_data( '_pricing', $data['pricing'] );
        }

        if( isset( $data['checkin'] ) and ! empty( $data['checkin'] ) ) {
            $item->update_meta_data( '_checkin', $data['checkin']);
        }

        if( isset( $data['guests'] ) and ! empty( $data['guests'] ) ) {
            $item->update_meta_data( '_adults', (int) $data['guests'] );
        }
        if( isset( $data['children'] ) and ! empty( $data['children'] ) ) {
            $item->update_meta_data( '_children', (int) $data['children'] );
        }

        if( isset( $data['checkout'] ) and ! empty( $data['checkout'] ) ) {
            $item->update_meta_data( '_checkout', $data['checkout']);
        }

        if( isset( $data['claim_comment'] ) and ! empty( $data['claim_comment'] ) ) {
            $item->update_meta_data( '_claim_comment', esc_html( $data['claim_comment'] ) );
        }

        if( isset( $data['appt_period_id'] ) and ! empty( $data['appt_period_id'] ) ) {
            $item->update_meta_data( '_appt_period_id', esc_html( $data['appt_period_id'] ) );
        }

    }

}
