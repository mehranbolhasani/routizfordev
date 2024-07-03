<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Validation;
use \Routiz\Inc\Src\User;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Signin_Google extends Endpoint {

	public $action = 'rz_signin_google';

    public function action() {

		$request = Request::instance();

		// if( ! wp_verify_nonce( $request->get('security'), 'ajax-nonce' ) ) {
		// 	return;
		// }

		$validation = new Validation();
		$response = $validation->validate( $request->params, [
			'id' => 'required',
			// 'email' => 'email',
			'first_name' => 'required',
			'last_name' => 'required',
		]);

		if( $response->success ) {

			$user_id = User::create([
				'user_login' => $request->get('id'),
	            'user_email' => $request->get('email'),
	            'user_pass' => md5( $request->get('id') . get_option('rz_facebook_app_secret') ),
	            'first_name' => $request->get('first_name'),
	            'last_name' => $request->get('last_name')
			]);

			if( $user_id ) {

				update_user_meta( $user_id, 'rz_is_media_signin', true );
				update_user_meta( $user_id, 'rz_media_signin', 'google' );

				// get option role
				$role = get_option('rz_default_user_role');
				if( ! in_array( $role, [ 'customer', 'business' ] ) ) {
					$role = 'customer';
				}

				// set role
				add_user_meta( $user_id, 'rz_role', $role );

				$picture = $request->get('picture');
				if( ! empty( $picture ) ) {

					$remote = wp_remote_get( $picture, [
						'timeout' => 8
					]);

					if( ! is_wp_error( $remote ) ) {

						$body = wp_remote_retrieve_body( $remote );
						$upload = wp_upload_bits( sprintf( '%s.jpg', $request->get('id') ), null, $body );
						$attach_id = wp_insert_attachment(
							[
								'guid' => $upload['url'],
								'post_mime_type' => 'image/jpeg',
								'post_title' => $request->get('id')
							],
							$upload['file']
						);

						require_once( ABSPATH . 'wp-admin/includes/image.php' );
						wp_generate_attachment_metadata( $attach_id, $upload['file'] );

						if( ! is_wp_error( $attach_id ) ) {
							// update_post_meta( $attach_id, 'rz_attach_by_media', true );
							// update_user_meta( $user_id, 'rz_media_picture', $attach_id );
							update_user_meta( $user_id, 'user_avatar', "[{\"id\":{$attach_id}}]" );
						}
					}
				}
			}

			/*
			 * signon
			 *
			 */
			/*$vsessionid = session_id();
	        if( empty( $vsessionid ) ) {
	            session_name('PHPSESSID');
	            session_start();
	        }*/

	        wp_clear_auth_cookie();

	        $signon = wp_signon([
				'user_login' => $request->get('id'),
				'user_password' => md5( $request->get('id') . get_option('rz_facebook_app_secret') ),
				'remember' => true,
			], false );

	        if( ! is_wp_error( $signon ) ) {

				// wp_set_current_user( $signon->ID );
				// wp_set_auth_cookie( $signon->ID );
	            // do_action('set_current_user');

				wp_send_json([
					'success' => true,
				]);

	        }

		}

		wp_send_json( $response );

	}

}
