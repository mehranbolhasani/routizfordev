<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

use \Routiz\Inc\Src\Submission\Submission;
use \Routiz\Inc\Src\Request\Custom_Request;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Submission_Next extends Endpoint {

	public $action = 'rz_submission_next';

    public function action() {

		if( ! is_user_logged_in() ) {
			return;
		}

		$data = (object) Rz()->sanitize( $_POST );
		$request = new Custom_Request('input');

		if( ! isset( $data->id ) ) {
			return;
		}

		$submission = new Submission( $request->get('type') );

		if( ! $request->get('type') ) {
			return wp_send_json([
				'success' => false,
				'errors' => [
					'type' => esc_html__('Listing type is missing', 'routiz')
				],
			]);
		}

		$args = [
			'type' => $data->id,
		];

		if( isset( $data->group ) ) {
			$args['group'] = $data->group;
		}

		$current = $submission->component->create( $args );

		return wp_send_json( $current->validation() );

	}

}
