<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Validation;
use \Routiz\Inc\Src\User;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Signup extends Endpoint {

	public $action = 'rz_signup';

    public function action() {

		if( ! get_option( 'users_can_register' ) ) {
			return;
		}

		$request = Request::instance();

		// if( ! wp_verify_nonce( $request->get('security'), 'ajax-nonce' ) ) {
		// 	return;
		// }

		if( is_user_logged_in() ) {
			return;
		}

		$args = [
			'username' => 'required|username_not_exists',
			'email' => 'required|email|email_not_exists'
		];
		
		if (get_option('rz_enable_signup_phone') && get_option('rz_is_signup_phone_required') == 1) {
		    $args['phone'] = 'required';
		}

		$args['first_name'] = 'required';
		$args['last_name'] = 'required';

		if( get_option('rz_enable_standard_pass') ) {
			$args['password'] = 'required|min:6|max:40';
			$args['repeat_password'] = 'required|match:password';
		}
		if( get_option('rz_enable_signup_captcha') ) {
			$args['rcaptcha'] = 'required';
		}
		
		if( get_option('rz_enable_signup_terms') ) {
			$args['terms'] = 'required';
		}

		$validation = new Validation();
		$response = $validation->validate( $request->params, $args );

		if( $response->success ) {

			$user_args = [
				'user_login' => $request->get('username'),
				'user_email' => $request->get('email'),
				'first_name' => $request->get('first_name'),
				'last_name' => $request->get('last_name'),
				'user_pass' => wp_generate_password(),
			];

			$user_args['user_pass'] = get_option('rz_enable_standard_pass') ? $request->get('password') : wp_generate_password();

			$user_id = User::creates( $user_args );

			if( ! is_wp_error( $user_id ) ) {

				// update wc billing phone
				if( get_option('rz_enable_signup_phone') ) {
					update_user_meta( $user_id, 'billing_phone', $request->get('phone') );
				}

				// get option role
				$role = get_option('rz_default_user_role');
				if( ! in_array( $role, [ 'customer', 'business' ] ) ) {
					$role = 'customer';
				}

				// get request role
				if( $request_role = $request->get('role') ) {
					$role = $request_role;
				}

				// set role
				add_user_meta( $user_id, 'rz_role', $role );

				// send email if user pass input is not enabled
				if( ! get_option('rz_enable_standard_pass') ) {
					wp_new_user_notification( $user_id, null, 'user' );
				}
				// if password input is allowed, signin ..
				else{
					wp_clear_auth_cookie();

					wp_set_current_user( $user_id );
					wp_set_auth_cookie( $user_id );
		            do_action('set_current_user');
				}

				routiz()->notify->distribute('welcome', [
                    'user_id' => $user_id
                ]);

			}

		}
		// error
		else{

			$validation_results = $validation->get_result();

			$error_fields = [
				'username' => [
					'required' => esc_html__( 'Email is required', 'routiz' ),
					'username_not_exists' => esc_html__( 'This username was already used', 'routiz' ),
				],
				'email' => [
					'required' => esc_html__( 'Email is required', 'routiz' ),
					'email' => esc_html__( 'Please enter a valid email', 'routiz' ),
					'email_not_exists' => esc_html__( 'This email was already used', 'routiz' ),
				],
				'phone' => [
					'required' => esc_html__( 'Phone number is required', 'routiz' ),
				],
				'first_name' => [
					'required' => esc_html__( 'First name is required', 'routiz' ),
				],
				'last_name' => [
					'required' => esc_html__( 'Last name is required', 'routiz' ),
				],
				'password' => [
					'required' => esc_html__( 'Password is required', 'routiz' ),
					'min' => esc_html__( 'Password must be at least 6 characters', 'routiz' ),
					'max' => esc_html__( 'Password must be maximum 40 characters', 'routiz' ),
				],
				'repeat_password' => [
					'required' => esc_html__( 'Repeat password is required', 'routiz' ),
					'match' => esc_html__( 'Passwords should match', 'routiz' ),
				],
				'rcaptcha' => [
					'required' => esc_html__( 'Please verify you are human!', 'routiz' ),
				],
				'terms' => [
					'required' => esc_html__( 'Please agree with the terms and conditions', 'routiz' ),
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
