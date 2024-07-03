<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Appointments_Get_More_Dates extends Endpoint {

	public $action = 'rz_appointments_get_more_dates';

    public function action() {

		wp_send_json([
			'success' => true,
			'html' => Rz()->get_template('routiz/single/appointments-more')
		]);

		wp_send_json( $response );

	}

}
