<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Validation;
use \Routiz\Inc\Src\Listing\Listing;
use \Routiz\Inc\Src\Form\Component as Form;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Action_Booking_Pricing extends Endpoint {

	public $action = 'rz_action_booking_pricing';

    public function action() {

		$request = Request::instance();

		if( ! $request->is_empty('listing_id') ) {

			$listing = new Listing( $request->get('listing_id') );
			$action = $listing->type->get_action('booking');
			$checkin = $request->get('checkin');
			$checkout = $request->get('checkout');

			/*
			 * handle bookings with single date selection and set checkout date
			 *
			 */
			if( $action->get_field('selection_type') == 'single' ) {
				if( $checkin ) {
					$checkout = $checkin + 86400; // add one day based on checkin
				}
			}

			if(
				! empty( $checkin ) and
				! empty( $checkout )
			) {

				if( $listing->id ) {

					wp_send_json([
						'success' => true,
						'html' => Rz()->get_template('routiz/single/pricing')
					]);

				}
			}
		}

		wp_send_json([
			'success' => false
		]);

		wp_send_json( $response );

	}

}
