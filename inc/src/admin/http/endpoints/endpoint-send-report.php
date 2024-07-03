<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Listing\Listing;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Send_Report extends Endpoint {

	public $action = 'rz_send_report';

    public function action() {

		$request = Request::instance();

		// security
		if ( $request->is_empty('security') || ! wp_verify_nonce( $request->get('security'), 'routiz_report_nonce' ) ) {
			return;
		}

		// required
		if( $request->is_empty('listing_id') or $request->is_empty('report_reason') ) {
			wp_send_json([
				'success' => false,
				'errors' => [
					'report_reason' => esc_html__( 'Please select a reason', 'routiz' )
				]
			]);
		}

		$data = (object) Rz()->sanitize( $_POST );

		// listing
		$listing = new Listing( $data->listing_id );
		if( ! $listing->id ) {
			return;
		}

		$listing->report( $data->report_reason );

		wp_send_json([
			'success' => true
		]);

	}

}
