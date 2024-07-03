<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

use \Routiz\Inc\Src\User;
use \Routiz\Inc\Src\Validation;
use \Routiz\Inc\Src\Listing\Listing;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Submit_Review_Reply extends Endpoint {

	public $action = 'rz_submit_review_reply';

    public function action() {

		if( ! isset( $_POST['reply'] ) ) { return; }

		$data = (object) Rz()->sanitize( $_POST['reply'] );

		$comment = get_comment( $data->comment_id );

		if( ! $comment instanceof \WP_Comment ) {
			return;
		}

		$user = User::instance();
		$userdata = $user->get_userdata();
		$validation = new Validation();
		$listing = new Listing( $comment->comment_post_ID );

		if( ! $listing->id ) {
			return;
		}

		if( ! comments_open( $listing->id ) ) {
			return;
		}

		if( get_current_user_id() !== (int) $listing->post->post_author ) {
			return;
		}

		$response = $validation->validate( $data, [
			'comment' => 'required|min:10|max:1000',
		]);

		if( $response->success ) {

			// insert comment
			$comment_id = wp_insert_comment([
				'comment_post_ID' => $listing->id,
	            'comment_content' => stripslashes( esc_textarea( $_POST['reply']['comment'] ) ),
	            'comment_approved' => $listing->type->get('rz_review_moderation') ? 0 : 1,
	            'comment_type' => 'rz-review',
				'comment_author_IP' => $user->get_user_ip(),
				'comment_author' => $userdata->display_name,
				'user_id' => $user->id,
				'comment_parent' => $comment->comment_ID
			]);

		}

		wp_send_json( $response );

	}

}
