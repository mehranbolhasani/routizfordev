<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Validation;
use \Routiz\Inc\Src\User;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Signin_Standard extends Endpoint {

	public $action = 'rz_signin_standard';

    public function action() {

		$request = Request::instance();

		// if( ! wp_verify_nonce( $request->get('security'), 'ajax-nonce' ) ) {
		// 	return;
		// }

		$validation = new Validation();
		$is_signin_by_email = boolval( filter_var( $request->get('user_email'), FILTER_VALIDATE_EMAIL ) );
		$args = [
			'user_password' => 'required',
			'user_email' => $is_signin_by_email ? 'required|email|email_exists' : 'required|username_exists',
		];

		$response = $validation->validate( $request->params, $args );
		if( $response->success ) {

			// check auth
			if( is_wp_error( wp_authenticate( $request->get('user_email'), $request->get('user_password') ) ) ) {
				wp_send_json([
					'success' => false,
					'error_string' => esc_html__( 'Wrong user email or password', 'routiz' )
				]);
			}

			// success, log user
			wp_clear_auth_cookie();

	        $signon = wp_signon([
				'user_login' => $request->get('user_email'),
				'user_password' => $request->get('user_password'),
				'remember' => true,
			], is_ssl() );

		}
		// error
		else{

			$validation_results = $validation->get_result();

			$error_fields = [
				'user_email' => [
					'required' => esc_html__( 'Email is required', 'routiz' ),
					'email' => esc_html__( 'Please enter a valid email', 'routiz' ),
					'email_exists' => esc_html__( 'Wrong user email or password', 'routiz' ),
					'username_exists' => esc_html__( 'Wrong user email or password', 'routiz' ),
				],
				'user_password' => [
					'required' => esc_html__( 'Password is required', 'routiz' ),
				],
			];

			foreach( $validation_results as $key => $tarm ) {
				$response->error_string = $error_fields[ $key ][ $tarm ];
				break;
			}

		}

		wp_send_json( $response );

	}

}
