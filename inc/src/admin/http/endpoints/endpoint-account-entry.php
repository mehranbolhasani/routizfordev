<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\User;
use \Routiz\Inc\Src\Listing\Listing;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Account_Entry extends Endpoint {

	public $action = 'rz_account_entry';

    public function action() {

		$request = Request::instance();

		$entry_id = (int) $request->get('id');
		$entry_type = Rz()->get_meta( 'rz_entry_type', $entry_id );

		if( ! $entry_id ) {
			return;
		}

		$current_user = get_current_user_id();
		$user = new User( Rz()->get_meta('rz_request_user_id', $entry_id) );
		$user_owner_id = get_post_field( 'post_author', $entry_id );
		$listing = new Listing( Rz()->get_meta( 'rz_listing', $entry_id ) );

		// bail if the user doesn't participate in the entry
		if( ! ( $current_user == $user->id or $current_user == $user_owner_id ) ) {
			return;
		}

		switch( $request->get('type') ) {

			/*
			 * approve
			 *
			 */
			case 'approve':

				// only owner can approve
				if( $current_user == $user_owner_id ) {

					$is_current_user = ( $user->id == $current_user );

					// send notification
					routiz()->notify->distribute( 'entry-approved', [
				        'user_id' => $is_current_user ? $user_owner_id : $user->id,
				        'meta' => [
							'entry_id' => $entry_id,
							'listing_id' => $listing->id,
							'from_user_id' => ! $is_current_user ? $user->id : $user_owner_id,
						],
				    ]);

					$pricing = Rz()->json_decode( Rz()->get_meta( 'rz_pricing', $entry_id ) );

					// due
					if( $listing->type->get('rz_allow_pricing') and isset( $pricing->processing ) and floatval( $pricing->processing ) > 0 ) {

						wp_update_post([
							'ID' => $entry_id,
							'post_status' => 'pending_payment'
						]);

					}
					// no due
					else{

						wp_update_post([
							'ID' => $entry_id,
							'post_status' => 'publish'
						]);

					}

				}

				break;

			/*
			 * decline
			 *
			 */
			case 'decline';

				$is_current_user = ( $user->id == $current_user );

				// send notification
				routiz()->notify->distribute( 'entry-declined', [
					'user_id' => $is_current_user ? $user_owner_id : $user->id,
					'meta' => [
						'entry_id' => $entry_id,
						'listing_id' => $listing->id,
						'from_user_id' => ! $is_current_user ? $user->id : $user_owner_id,
					],
				]);

				wp_update_post([
					'ID' => $entry_id,
					'post_status' => 'declined'
				]);

				break;

			/*
			 * payment process
			 *
			 */
			case 'payment-process';

				if( ! $listing->id ) {
					return;
				}

				if( ! $product = $listing->booking->get_booking_product( Rz()->get_meta( 'rz_entry_type', $entry_id ) ) ) {
					return;
				}

				$pricing = Rz()->json_decode( Rz()->get_meta( 'rz_pricing', $entry_id ) );

				if( ! isset( $pricing->processing ) or floatval( $pricing->processing ) <= 0 ) {
					return;
				}

				$cart_item = [
					'listing_id' => [ $listing->id ],
					'entry_id' => $entry_id,
					'request_user_id' => $current_user,
					'checkin' => Rz()->get_meta( 'rz_checkin_date', $entry_id ),
					'checkout' => Rz()->get_meta( 'rz_checkout_date', $entry_id ),
					'pricing' => $pricing,
					'booking_price' => $pricing->processing,
				];

				if( $guests = (int) Rz()->get_meta( 'rz_guests', $entry_id ) ) {
					$cart_item['guests'] = Rz()->get_meta( 'rz_guests', $entry_id );
				}

				if( apply_filters('routiz/cart/empty_cart', true) ) {
					WC()->cart->empty_cart();
				}

				WC()->cart->add_to_cart( $product->get_id(), 1, '', '', $cart_item );

				wp_send_json([
					'success' => true,
					'redirect_url' => wc_get_checkout_url(),
				]);

		}

		wp_send_json([
			'success' => true,
			'html' => Rz()->get_template('routiz/account/entries/modal/content')
		]);

	}

}
