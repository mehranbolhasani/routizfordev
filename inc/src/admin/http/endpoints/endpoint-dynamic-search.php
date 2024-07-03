<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Dynamic_Search extends Endpoint {

	public $action = 'rz_dynamic_search';

    public function action() {

		global $rz_explore;
		$rz_explore = \Routiz\Inc\Src\Explore\Search\Explore::instance();

		wp_send_json([
			'success' => true,
			'modules' => Rz()->get_template('routiz/search/modules'),
			'found_posts' => $rz_explore->query()->posts->found_posts,
		]);

	}

}
