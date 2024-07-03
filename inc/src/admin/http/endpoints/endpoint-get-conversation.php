<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Listing\Conversation;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Get_Conversation extends Endpoint {

	public $action = 'rz_get_conversation';

    public function action() {

		global $rz_conversation;

		$request = Request::instance();
		$rz_conversation = new Conversation( $request->get('id') );
		$rz_conversation->mark_as_read();

		wp_send_json([
			'success' => true,
			'count' => $rz_conversation->count_messages(),
			'html' => Rz()->get_template('routiz/globals/conversation/modal/content')
		]);

	}

}
