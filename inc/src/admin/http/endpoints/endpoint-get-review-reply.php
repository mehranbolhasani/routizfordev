<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

use \Routiz\Inc\Src\Request\Request;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Get_Review_Reply extends Endpoint {

	public $action = 'rz_get_review_reply';

    public function action() {

		$request = Request::instance();

		if( ! $request->has('comment_id') ) {
			return;
		}

		wp_send_json([
			'success' => true,
			'html' => Rz()->get_template('routiz/single/reviews/form-reply')
		]);

	}

}
