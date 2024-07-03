<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

use \Routiz\Inc\Src\Listing\Listing;
use \Routiz\Inc\Src\Validation;
use \Routiz\Inc\Src\User;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Submit_Review extends Endpoint {

	public $action = 'rz_submit_review';

    public function action() {

		if( ! isset( $_POST['review'] ) ) { return; }

		$data = (object) Rz()->sanitize( $_POST['review'] );
		$user = User::instance();
		$userdata = $user->get_userdata();
		$validation = new Validation();
		$listing = new Listing( (int) $data->listing_id );

		if( ! $listing->id ) {
			return;
		}

		if( ! is_user_logged_in() ) {
			return;
		}

		$response = $validation->validate( $data, [
			'comment' => 'required|min:10|max:1000',
		]);

		if( $response->success ) {

			// insert comment
			$comment_id = wp_insert_comment([
				'comment_post_ID' => $listing->id,
	            'comment_content' => stripslashes( esc_textarea( $_POST['review']['comment'] ) ),
	            'comment_approved' => $listing->type->get('rz_review_moderation') ? 0 : 1,
	            'comment_type' => 'rz-review',
				'comment_author_IP' => $user->get_user_ip(),
				'comment_author' => $userdata->display_name,
				'user_id' => $user->id,
			]);

			// add rating
	        if( $listing->type->get('rz_enable_review_ratings') ) {
				if( isset( $data->ratings ) and ! empty( $data->ratings ) ) {

					// add ratings
		            add_comment_meta( $comment_id, 'rz_ratings', $data->ratings );

					// add average
					$sum = $count = 0;
					foreach( $data->ratings as $rating ) {
						if( (int) $rating > 0 ) {
							$sum += (int) $rating;
							$count++;
						}
					}

					if( $count > 0 ) {
						add_comment_meta( $comment_id, 'rz_rating_average', number_format( $sum / $count, 2 ) );
					}

		        }
			}

			// add gallery
			if( $listing->type->get('rz_enable_review_media') ) {
				if( isset( $data->gallery ) and ! empty( $data->gallery ) ) {
	            	// TODO: improve this with after_save
		           	add_comment_meta( $comment_id, 'rz_gallery', is_array( $data->gallery ) ? json_encode( $data->gallery ) : $data->gallery );
		        }
			}

			// notify
			routiz()->notify->distribute( 'new-comment', [
				'user_id' => $listing->post->post_author,
				'meta' => [
					'listing_id' => $listing->id,
					'from_user_id' => $user->id,
					'comment_id' => $comment_id,
				],
			]);

			do_action( 'routiz/listing/insert_comment', $comment_id );

		}

		wp_send_json( $response );

	}

}
