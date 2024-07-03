<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Validation;
use \Routiz\Inc\Src\User;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Signin_Reset_Password extends Endpoint {

	public $action = 'rz_reset_password';

    public function action() {

		$request = Request::instance();

		if( ! wp_verify_nonce( $request->get('security'), 'ajax-nonce' ) ) {
			return;
		}

		$validation = new Validation();
		$response = $validation->validate( $request->params, [
			'user_email' => 'required|email|email_exists',
		]);

		if( $response->success ) {

			$user = get_user_by( 'email', trim( $request->get('user_email') ) );

			// trigger wc reset password
			$reset_key = get_password_reset_key( $user );
			$wc_emails = WC()->mailer()->get_emails();
			$wc_emails['WC_Email_Customer_Reset_Password']->trigger( $user->user_login, $reset_key );

		}
		// error
		else{

			$error_fields = [
				'user_email' => esc_html__( 'Email', 'routiz' ),
			];

			$error_strings = [];

			foreach( $response->errors as $key => $error ) {
				$error_strings[] = sprintf('%s - %s', $error_fields[$key], $error );
			}

			$response->error_strings = $error_strings;

		}

		wp_send_json( $response );

	}

}
