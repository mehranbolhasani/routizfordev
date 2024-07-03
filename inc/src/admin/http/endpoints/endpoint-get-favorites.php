<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

use \Routiz\Inc\Src\Request\Request;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Get_Favorites extends Endpoint {

	public $action = 'rz_get_favorites';

    public function action() {

		wp_send_json([
			'success' => true,
			'html' => Rz()->get_template('routiz/globals/favorites/modal/content')
		]);

	}

}
