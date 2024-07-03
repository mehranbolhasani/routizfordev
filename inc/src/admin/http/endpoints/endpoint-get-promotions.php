<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Get_Promotions extends Endpoint {

	public $action = 'rz_get_promotions';

    public function action() {

		wp_send_json([
			'success' => true,
			'html' => Rz()->get_template('routiz/account/listings/promotion/modal/content')
		]);

	}

}
