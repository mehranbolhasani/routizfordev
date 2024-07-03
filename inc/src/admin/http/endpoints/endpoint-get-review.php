<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Listing\Listing;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Get_Review extends Endpoint {

	public $action = 'rz_get_review';

    public function action() {

		$request = Request::instance();

		if( ! $request->has('listing_id') ) {
			return;
		}

		$listing = new Listing( $request->get('listing_id') );

		if( ! $listing->id ) {
			return;
		}

		wp_send_json([
			'success' => true,
			'html' => Rz()->get_template('routiz/single/reviews/form')
		]);

	}

}
