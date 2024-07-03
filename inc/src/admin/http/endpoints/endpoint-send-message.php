<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Listing\Conversation;
use \Routiz\Inc\Src\User;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Send_Message extends Endpoint {

	public $action = 'rz_send_message';

    public function action() {

		global $rz_conversation;

		$request = Request::instance();

		// security
		if ( $request->is_empty('security') || ! wp_verify_nonce( $request->get('security'), 'routiz_message_nonce' ) ) {
			return;
		}

		if( $request->is_empty('listing_id') ) {
			return;
		}

		// required
		if( $request->is_empty('message') ) {
			wp_send_json([
				'success' => false,
				'error' => esc_html__( 'Please enter a message', 'routiz' )
			]);
		}

		$data = (object) Rz()->sanitize( $_POST );

		/*
		 * send message
		 *
		 */
		$id = ! $request->is_empty('conversation_id') ? (int) $request->get('conversation_id') : (int) $request->get('listing_id');
		$rz_conversation = new Conversation( $id );
		$rz_conversation->send( $_POST['message'] );

		/*
		 * notify message receiver
		 *
		 */
		$args = [
			'from_user_id' => get_current_user_id()
		];
		if( $listing_id = $request->get('listing_id') ) {
			$args['listing_id'] = (int) $listing_id;
		}
		routiz()->notify->distribute( 'new-message', [
	        'user_id' => $rz_conversation->receiver_id,
			'meta' => $args
	    ]);

		wp_send_json([
			'success' => true,
			'count' => $rz_conversation->count_messages(),
			'html' => Rz()->get_template('routiz/globals/conversation/modal/messages'),
		]);

	}

}
