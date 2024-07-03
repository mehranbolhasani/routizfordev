<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Listing_Edit extends Endpoint {

	public $action = 'rz_listing_edit';

    public function action() {

		wp_send_json([
			'success' => true,
			'html' => Rz()->get_template('routiz/account/listings/modal/content')
		]);

	}

}
