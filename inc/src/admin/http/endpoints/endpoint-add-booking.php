<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

use \Routiz\Inc\Src\Listing\Listing;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Add_Booking extends Endpoint {

	public $action = 'rz_add_booking';

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

		// check if pending booking entry exists
		if( apply_filters('routiz/action/reservation/check-pending', true ) ) {
			$pending = get_posts([
				'post_type' => 'rz_entry',
				'post_status' => [ 'pending', 'pending_payment' ],
				'meta_query' => [
					'relation' => 'AND',
					[
						'key' => 'rz_listing',
						'value' => $data->listing_id,
					],
					[
						'key' => 'rz_request_user_id',
						'value' => get_current_user_id(),
					],
				]
			]);

			if( $pending ) {
				wp_send_json( array_merge( $response, [
					'message' => esc_html__( 'You already have a pending request', 'routiz' )
				]));
			}
		}

		$listing = new Listing( $data->listing_id );
		$action = $listing->type->get_action('booking');

		if( ! $listing->id ) {
			return;
		}

		// check guests
		$guests = 0;
		if( isset( $data->guests ) ) {
			$guests = (int) $data->guests - (int) $data->child;
		}

		// check children
		$children = 0;
		if( isset( $data->child ) ) {
			$children = (int) $data->child;
		}

		/*
		 * handle bookings with single date selection and set checkout date
		 *
		 */
		if( $action->get_field('selection_type') == 'single' ) {
			if( $data->checkin ) {
				$data->checkout = $data->checkin + 86400; // add one day based on checkin
			}
		}

		$check_availability = $listing->booking->check_booking_availability( $data->checkin, $data->checkout, $guests, $children );

		if( $check_availability->success == true ) {

			// booking product
			$product = $listing->booking->get_booking_product();
			if( ! $product ) {
				wp_send_json( array_merge( $response, [
					'message' => esc_html__( 'Booking product is missing', 'routiz' )
				]));
			}

			$pricing = $listing->get_booking_date_range_price( $data->checkin, $data->checkout, $guests, $children, isset( $data->addons ) ? $data->addons : [] );

			/*
			 * instant booking
			 *
			 */
			if( $listing->is_instant() ) {

				// has due amount -> add to cart
				if( $pricing->processing > 0 ) {

					if( apply_filters('routiz/cart/empty_cart', true) ) {
						WC()->cart->empty_cart();
					}

					WC()->cart->add_to_cart( $product->get_id(), 1, '', '', [
			            'booking_type' => 'daily',
			            'listing_id' => [ $listing->id ],
			            'request_user_id' => get_current_user_id(),
			            'guests' => $guests,
			            'children' => $children,
			            'checkin' => $data->checkin,
			            'checkout' => $data->checkout,
						'pricing' => $pricing,
			            'booking_price' => $pricing->processing,
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
						'rz_entry_type' => 'booking',
						'rz_listing' => $listing->id,
						'rz_checkin_date' => $data->checkin,
						'rz_checkout_date' => $data->checkout,
						'rz_request_user_id' => get_current_user_id(),
						'rz_pricing' => json_encode( $pricing ),
					];

					if( $guests ) {
						$entry_meta_input['rz_guests'] = $guests;
					}
					if( $children ) {
						$entry_meta_input['rz_children'] = $children;
					}

					$entry_id = wp_insert_post([
						'post_title' => esc_html__( 'Booking', 'routiz' ),
						'post_status' => 'publish',
						'post_type' => 'rz_entry',
						'post_author' => $listing->post->post_author,
						'meta_input' => $entry_meta_input
					]);

					if( ! is_wp_error( $entry_id ) ) {

						// add booked dates
						$booked_days_array = $listing->booking->add_booked_days( $entry_id );
						update_post_meta( $listing->id, 'rz_booking_booked', $booked_days_array );

						/*
						 * send notification
						 *
						 */
						routiz()->notify->distribute( 'new-booking', [
							'user_id' => $listing->post->post_author,
							'meta' => [
								'entry_id' => $entry_id,
								'listing_id' => $listing->id,
								'from_user_id' => get_current_user_id(),
							],
						], [
							'listing' => $listing,
							'request_user_id' => get_current_user_id(),
							'checkin' => $data->checkin,
							'checkout' => $data->checkout,
							'guests' => $guests,
							'children' => $children,
						]);

						wp_send_json([
							'success' => true,
							'message' => esc_html__( 'Your booking request was send successfully', 'routiz' )
						]);

					}
				}
			}
			/*
			 * booking request
			 *
			 */
			else{

				// create entry to be approved or declined by host
				$entry_meta_input = [
					'rz_entry_type' => 'booking',
					'rz_listing' => $listing->id,
					'rz_checkin_date' => $data->checkin,
					'rz_checkout_date' => $data->checkout,
					'rz_request_user_id' => get_current_user_id(),
					'rz_pricing' => json_encode( $pricing ),
				];

				if( $guests ) {
					$entry_meta_input['rz_guests'] = $guests;
				}
				if( $children ) {
						$entry_meta_input['rz_children'] = $children;
					}

				$entry_id = wp_insert_post([
					'post_title' => esc_html__( 'Booking', 'routiz' ),
					'post_status' => 'pending',
					'post_type' => 'rz_entry',
					'post_author' => $listing->post->post_author,
					'meta_input' => $entry_meta_input
				]);

				if( ! is_wp_error( $entry_id ) ) {

					/*
					 * send notification
					 *
					 */
					routiz()->notify->distribute( 'new-booking-request', [
						'user_id' => $listing->post->post_author,
						'meta' => [
							'entry_id' => $entry_id,
							'listing_id' => $listing->id,
							'from_user_id' => get_current_user_id(),
						],
					]);

					wp_send_json([
						'success' => true,
						'message' => esc_html__( 'Your request has been sent successfully', 'routiz' )
					]);

				}

			}

		}
		// availability failed
		else{

			wp_send_json( $check_availability );

		}

	}

}
