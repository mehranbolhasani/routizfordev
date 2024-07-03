<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Validation;
use \Routiz\Inc\Src\Listing\Listing;
use \Routiz\Inc\Src\Form\Component as Form;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Action_Claim_Send extends Endpoint {

	public $action = 'rz_action_claim_send';

    public function action() {

		if( ! is_user_logged_in() ) {
			return;
		}

		$request = new Request();
		$listing = new Listing( (int) $_POST['listing_id'] );

		if( ! $listing->type->id ) {
			return;
		}

		$action_claim = $listing->type->get_action_type( 'claim' );

		if( ! $listing->id ) {
			return;
		}

		if( $action_claim->fields ) {

			// check if pending free claim
			$pendings_free = get_posts([
				'post_type' => 'rz_claim',
				'post_status' => [ 'pending' ],
				'author' => get_current_user_id(),
				'meta_query' => [
					[
						'key' => 'rz_listing',
						'value' => $listing->id
					]
				]
			]);

			if( ! empty( $pendings_free ) ) {
				return wp_send_json([
					'success' => false,
					'error' => esc_html__( 'You already claimed this business', 'routiz' )
				]);
			}

			/*
			 * payed claim
			 *
			 */
			if( $action_claim->fields->action_product ) {

				$product = wc_get_product( $action_claim->fields->action_product );

				if( $product and $product->get_status() == 'publish' ) {

					// add to cart with cart item data
					WC()->cart->add_to_cart( $product->get_id(), 1, '', '', [
			            'listing_id' => [ $listing->id ],
			            'request_user_id' => get_current_user_id(),
			            'claim_comment' => $request->get('claim_comment'),
			        ]);

					return wp_send_json([
						'success' => true,
						'redirect_url' => wc_get_checkout_url()
					]);

				}else{

					return wp_send_json([
						'success' => false,
						'error' => esc_html__( 'Claim product is missing', 'routiz' )
					]);

				}

				return wp_send_json([
					'success' => true
				]);

			}
			/*
			 * free claim
			 *
			 */
			else{

				// insert claim post
				$claim_id = wp_insert_post([
	                'post_title' => esc_html__( 'Claim', 'routiz' ),
	                'post_type' => 'rz_claim',
	                'post_status' => 'pending',
	                'post_author' => get_current_user_id(),
					'meta_input' => [
						'rz_listing' => $listing->id,
						'rz_claim_comment' => $request->get('claim_comment'),
					]
	            ]);

				/*
				 * send notification
				 *
				 */
				if( $claim_id ) {
					routiz()->notify->distribute( 'new-claim', [
						'user_id' => $listing->post->post_author,
						'meta' => [
							'claim_id' => $claim_id,
							'listing_id' => $listing->id,
							'from_user_id' => get_current_user_id(),
						],
					]);
				}

				return wp_send_json([
					'success' => true
				]);

			}

		}

		return wp_send_json([
			'success' => false,
			'error' => esc_html__( 'Something went wrong', 'routiz' )
		]);

	}

}
