<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Listing\Listing;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Action_Claim extends Endpoint {

	public $action = 'rz_action_claim';

    public function action() {

		$request = Request::instance();

		if( ! $request->has('listing_id') ) {
			return;
		}

		if( ! is_user_logged_in() ) {
			return;
		}

		$listing = new Listing( $request->get('listing_id') );

		if( ! $listing->id ) {
			return;
		}

		wp_send_json([
			'success' => true,
			'html' => Rz()->get_template('routiz/single/modal/action-claim-form')
		]);

	}

}
