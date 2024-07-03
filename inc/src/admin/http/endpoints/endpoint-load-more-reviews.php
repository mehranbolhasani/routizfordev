<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Listing\Listing;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Load_More_Reviews extends Endpoint {

	public $action = 'rz_action_load_more_reviews';

    public function action() {

		$request = Request::instance();

		if( $request->is_empty('listing_id') or $request->is_empty('onpage') ) {
			return;
		}

		global $rz_listing;
		$rz_listing = new Listing( $request->get('listing_id') );

		if( ! $rz_listing->id ) {
			return;
		}

		$comments_per_page = max( 1, $rz_listing->type->get('rz_reviews_per_page') );

		wp_send_json([
			'success' => true,
			'max_num_pages' => ceil( $rz_listing->reviews->count / $comments_per_page ),
			'html' => Rz()->get_template('routiz/single/reviews/comments/list')
		]);

	}

}
