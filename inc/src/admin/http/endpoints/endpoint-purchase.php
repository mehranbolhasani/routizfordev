<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

use \Routiz\Inc\Src\Listing\Listing;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Purchase extends Endpoint {

	public $action = 'rz_purchase';

    public function action() {

		$response = [
			'success' => false
		];

		$data = (object) Rz()->sanitize( $_POST );

		// no woocommerce
		if ( ! class_exists('woocommerce') ) {
			wp_send_json( array_merge( $response, [
				'message' => esc_html__( 'WooCommerce is required bookings', 'routiz' )
			]));
		}

		// check if user
		if ( ! is_user_logged_in() ) {
			wp_send_json( array_merge( $response, [
				'message' => esc_html__( 'You need to login in order to make a booking', 'routiz' )
			]));
		}

		// security check
		if ( ! isset( $data->security ) or ! wp_verify_nonce( $data->security, 'booking-security-nonce' ) ) {
			wp_send_json( array_merge( $response, [
				'message' => esc_html__( 'Security check not passed', 'routiz' )
			]));
		}

		$listing = new Listing( $data->listing_id );
		$action = $listing->type->get_action('purchase');

		if( ! $listing->id ) {
			return;
		}

		// booking product
		$product = $listing->booking->get_booking_product('purchase');
		if( ! $product ) {
			wp_send_json( array_merge( $response, [
				'message' => esc_html__( 'Booking product is missing', 'routiz' )
			]));
		}

		$pricing = $listing->get_purchase_price( isset( $data->addons ) ? $data->addons : [] );

		// has due amount -> add to cart
		if( $pricing->processing > 0 ) {

			if( apply_filters('routiz/cart/empty_cart', true) ) {
				WC()->cart->empty_cart();
			}

			WC()->cart->add_to_cart( $product->get_id(), 1, '', '', [
	            'listing_id' => [ $listing->id ],
	            'request_user_id' => get_current_user_id(),
				'pricing' => $pricing,
				'purchase_price' => $pricing->processing,
	        ]);

			// go send user to pay
			wp_send_json([
				'success' => true,
				'redirect_url' => wc_get_checkout_url()
			]);

		}
		// no due amount -> process
		else{

			// create booking entry
			$entry_meta_input = [
				'rz_entry_type' => 'purchase',
				'rz_listing' => $listing->id,
				'rz_request_user_id' => get_current_user_id(),
				'rz_pricing' => json_encode( $pricing ),
			];

			$entry_id = wp_insert_post([
				'post_title' => esc_html__( 'Purchase', 'routiz' ),
				'post_status' => 'publish',
				'post_type' => 'rz_entry',
				'post_author' => $listing->post->post_author,
				'meta_input' => $entry_meta_input
			]);

			if( ! is_wp_error( $entry_id ) ) {

				/*
				 * send notification
				 *
				 */
				routiz()->notify->distribute( 'new-purchase', [
					'user_id' => $listing->post->post_author,
					'meta' => [
						'entry_id' => $entry_id,
						'listing_id' => $listing->id,
						'from_user_id' => get_current_user_id(),
					],
				]);

				wp_send_json([
					'success' => true,
					'message' => esc_html__( 'Purchased successfully', 'routiz' )
				]);

			}
		}

	}

}
