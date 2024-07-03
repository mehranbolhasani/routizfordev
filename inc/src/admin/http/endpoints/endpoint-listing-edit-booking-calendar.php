<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Listing_Edit_Booking_Calendar extends Endpoint {

	public $action = 'rz_listing_edit_booking_calendar';

    public function action() {

		wp_send_json([
			'success' => true,
			'html' => Rz()->get_template('routiz/account/listings/booking-calendar/modal/content')
		]);

	}

}
