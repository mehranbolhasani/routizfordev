<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Dynamic_Explore extends Endpoint {

	public $action = 'rz_dynamic_explore';

    public function action() {

		global $rz_explore;
		$rz_explore = \Routiz\Inc\Src\Explore\Explore::instance();

		$output = [
			'success' => true,
			'type' => $rz_explore->get_display_type(),
			'listings' => [
				'has' => true,
				'content' => Rz()->get_template('routiz/explore/listings')
			],
			'filters' => [
				'has' => true,
				'content' => Rz()->get_template('routiz/explore/filters')
			],
			'markers' => [
				'has' => true,
				'content' => Rz()->get_template('routiz/explore/map/markers')
			],
			'infoboxes' => [
				'has' => true,
				'content' => Rz()->get_template('routiz/explore/map/infoboxes')
			],
			'found_posts' => $rz_explore->type ? $rz_explore->query()->posts->found_posts : 0,
		];

		// primary search form
		if( ! $rz_explore->request->is_empty('search_form_id') ) {
			$output['search_form'] = [
				'form_id' => $rz_explore->request->get('search_form_id'),
				'content' => do_shortcode("[rz-search-form id='{$rz_explore->request->get('search_form_id')}']")
			];
		}

		wp_send_json( $output );

	}

}
