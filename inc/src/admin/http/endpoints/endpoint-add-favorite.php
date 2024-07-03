<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

use \Routiz\Inc\Src\Listing\Listing;
use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\User;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Add_Favorite extends Endpoint {

	public $action = 'rz_add_favorite';

    public function action() {

		if( ! is_user_logged_in() ) {
			$this->error();
		}

		$request = Request::instance();

		if( $request->is_empty('id') ) {
			$this->error();
		}

		$listing = new Listing( $request->get('id') );

		if( ! $listing->id ) {
			$this->error();
		}

		/*
		 * add to user meta
		 *
		 */
		$user = new User();
		$user->add_to_favorite( $listing->id );

		wp_send_json([
			'success' => true
		]);

	}

	public function error() {
		wp_send_json([
			'success' => false
		]);
	}

}
