<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Listing\Listing;
use \Routiz\Inc\Src\Woocommerce\Packages\Promotion;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Promote_Listing extends Endpoint {

	public $action = 'rz_promote_listing';

    public function action() {

		$request = Request::instance();

		// security
		if ( $request->is_empty('security') || ! wp_verify_nonce( $request->get('security'), 'routiz_promote_listing_nonce' ) ) {
			return;
		}

		// required
		if( $request->is_empty('listing_id') ) {
			return;
		}

		// listing
		$listing = new Listing( $request->get('listing_id') );
		if( ! $listing->id ) {
			return;
		}

		if( $request->is_empty('package_id') ) {
			wp_send_json([
				'success' => false,
				'error' => esc_html__( 'Please select a package', 'routiz' )
			]);
		}

		// package
		$promotion = new Promotion( $request->get('package_id') );
		if( ! $promotion->id ) {
			return;
		}

		// only listing owner
		if( (int) $listing->get_author()->id !== get_current_user_id() ) {
			return;
		}

		// only published listings
		if( $listing->get_status() !== 'publish' ) {
			return;
		}

		// add to cart
		$promotion->add_to_cart( $listing->id );

		wp_send_json([
			'success' => true,
			'cart_url' => get_permalink( wc_get_page_id( 'checkout' ) ),
		]);

	}

}
