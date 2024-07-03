<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Listing\Listing;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Listing_Calendar_Toggle_Day extends Endpoint {

	public $action = 'rz_listing_calendar_toggle_day';

    public function action() {

		$request = Request::instance();

		if( $request->is_empty('id') or $request->is_empty('dates') ) {
			return;
		}

		$listing = new Listing( $request->get('id') );

		if( ! $listing->id ) {
			return;
		}

		if( (int) $listing->post->post_author !== get_current_user_id() ) {
			return;
		}

		$listing->booking->add_unavailable_days( $request->get('dates'), true );
		// $unavailable_days_array = [];
		// dd( $listing );
		// update_post_meta( $listing->id, 'rz_booking_unavailable', $unavailable_days_array );

		wp_send_json([
			'success' => true,
		]);

	}

}
