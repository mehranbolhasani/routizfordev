<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Field_Term extends Endpoint {

	public $action = 'rz_term';

    public function action() {

		$request = \Routiz\Inc\Src\Request\Request::instance();

		$terms = [];

		if( ! $request->is_empty('taxonomy') ) {

			$the_terms = get_terms( Rz()->prefix( $request->get('taxonomy') ), [
				'hide_empty' => false,
			]);

			if( ! is_wp_error( $the_terms ) ) {
				$terms = $the_terms;
			}

		}

		wp_send_json([
			'success' => true,
			'terms' => $terms
		]);

	}

}
