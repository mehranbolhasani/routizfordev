<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

use \Routiz\Inc\Src\Request\Request;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Get_Comment extends Endpoint {

	public $action = 'rz_action_get_comment';

    public function action() {

		$request = Request::instance();

		if( $request->is_empty('comment_id') ) {
			return;
		}

		$comment_text = get_comment_text( $request->get('comment_id') );

		if( ! $comment_text ) {
			return;
		}

		$html = wpautop( $comment_text );

		$children = get_comments([
			// 'post_id' => get_the_ID(),
		    'status' => 'approve',
		    'order' => 'DESC',
		    'parent' => $request->get('comment_id'),
		]);

		if( $children ) {
			foreach( $children as $child ) {
				$html .= '<div class="rz-comment-child"><p class="rz--author">' . esc_attr( $child->comment_author ) . '</p>' . wpautop( $child->comment_content ) . '</div>';
			}
		}

		wp_send_json([
			'success' => true,
			'comment_text' => $html
		]);

	}

}
