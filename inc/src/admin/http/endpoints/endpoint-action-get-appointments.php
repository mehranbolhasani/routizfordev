<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Listing\Listing;
use \Routiz\Inc\Src\Listing\Appointments;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Action_Get_Appointments extends Endpoint {

	public $action = 'rz_action_get_appointments';

    public function action() {

		$request = Request::instance();

		if( ! $request->is_empty('listing_id') ) {

			$listing = new Listing( $request->get('listing_id') );

			$request = Request::instance();
			$rz_listing = new Listing( $request->get('listing_id') );
			$action = $rz_listing->type->get_action('booking_appointments');
			$checkin = $request->get('checkin');
			$appointments = new Appointments( $rz_listing );
			$checkin_date = null;

			if( $checkin ) {
			    $checkin_date = new \DateTime( date( 'Y-m-d', $checkin ), new \DateTimeZone( wp_timezone_string() ) );
			}

			global $rz_upcoming;
			$rz_upcoming = $appointments->get( $checkin_date, null, 1, $request->get('adults'), $request->get('children'), $request->get('addons') );

			if( $listing->id ) {
				wp_send_json([
					'success' => true,
					'html' => Rz()->get_template('routiz/single/appointments')
				]);
			}
		}

		wp_send_json([
			'success' => false
		]);

		wp_send_json( $response );

	}

}
