<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

use \Routiz\Inc\Src\Request\Request;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Example extends Endpoint {

	public $action = 'rz_example';

    public function action() {

		$request = Request::instance();
		$data = (object) Rz()->sanitize( $_POST );

		$validation = new \Routiz\Inc\Src\Validation();
		$response = $validation->validate( $data, $terms );

		if( $response->success ) {

		}

		wp_send_json([
			'success' => true
		]);

	}

}
