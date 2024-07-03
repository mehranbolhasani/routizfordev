<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

use \Routiz\Inc\Src\Listing\Views;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Account_Get_Chart_Views extends Endpoint {

	public $action = 'rz_account_get_chart_views';

    public function action() {

		$all_views = Views::get_all_views_by_day();

		$dates = $views = [];
		foreach( $all_views as $view ) {
			$dates[] = date( 'Y/m/d', strtotime( $view->date ) );
			$views[] = $view->total;
		}

		/*$views = [
			5,
			7,
			5,
			10,
			12,
			7,
			18,
			25,
			22,
			19,
			22,
			22,
			19,
			16,
			37
		];*/

		/*$views = [
			0,
			0,
			0,
			0,
			0,
			0,
			0,
			1,
			0,
			0,
			0,
			4,
			0,
			4,
			2
		];*/

		wp_send_json([
			'success' => true,
			'dates' => $dates,
			'views' => $views
		]);

	}

}
