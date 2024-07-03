<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

use Routiz\Inc\Src\Listing\Listing;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Notifications_Mark_Read extends Endpoint {

	public $action = 'rz_notifications_mark_read';

    public function action() {

		do_action('routiz/notifications/before_mark_as_read');

		routiz()->notify->mark_site_as_read();

		wp_send_json([
			'success' => true
		]);

	}

}
