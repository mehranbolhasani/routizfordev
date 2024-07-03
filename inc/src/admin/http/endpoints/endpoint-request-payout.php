<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Wallet;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Request_Payout extends Endpoint {

	public $action = 'rz_action_request_payout';

    public function action() {

		do_action('routiz/account/payout/before_request');

		global $wpdb;

		$request = Request::instance();
		$wallet = new Wallet();
		$data = (object) Rz()->sanitize( $_POST );

		$enable_payouts = get_option( 'rz_enable_payouts', false );
		$payout_methods = get_option( 'rz_payout_methods', [] );
		$min_payout = (float) get_option( 'rz_min_payout', 0 );

		if( ! $enable_payouts ) {
			return;
		}

		if( ! in_array( $data->method, $payout_methods ) ) {
			wp_send_json([
				'success' => false,
				'errors' => [
					'method' => esc_html__( 'Select payout method', 'routiz' )
				]
			]);
		}

		// check amount availability
		if( (float) $data->amount > $wallet->get_balance() ) {
			wp_send_json([
				'success' => false,
				'errors' => [
					'amount' => esc_html__( 'Not enough funds', 'routiz' )
				]
			]);
		}

		if( (float) $data->amount <= 0 ) {
			wp_send_json([
				'success' => false,
				'errors' => [
					'amount' => esc_html__( 'Must be a positive number', 'routiz' )
				]
			]);
		}

		if( $min_payout > 0 and (float) $data->amount < $min_payout ) {
			wp_send_json([
				'success' => false,
				'errors' => [
					'amount' => sprintf( esc_html__( 'The minimum payout amount is %s', 'routiz' ), Rz()->format_price( $min_payout ) )
				]
			]);
		}

		$terms = [
			'amount' => 'required|numeric',
			'method' => 'required'
		];

		switch( $data->method ) {
			case 'paypal':

				$terms = array_merge( $terms, [
					'paypal_email' => 'required|email'
				]);

				break;

			case 'bank_transfer':

				$terms = array_merge( $terms, [
					'bank_account_name' => 'required',
					// 'bank_account_number' => 'required',
					'bank_iban' => 'required',
					'bank_bic' => 'required',
					'bank_name' => 'required',
					// 'bank_routing_number' => 'required',
				]);

				break;

		}

		$validation = new \Routiz\Inc\Src\Validation();
		$response = $validation->validate( $data, $terms );

		if( $response->success ) {

			$address = [];

			switch( $data->method ) {

				case 'paypal':

					$address = serialize([
						'paypal_email' => $data->paypal_email,
					]);

					break;

				case 'bank_transfer':

					$address = serialize([
						'bank_account_name' => $data->bank_account_name,
						'bank_account_number' => $data->bank_account_number,
						'bank_iban' => $data->bank_iban,
						'bank_bic' => $data->bank_bic,
						'bank_name' => $data->bank_name,
						'bank_routing_number' => $data->bank_routing_number,
					]);

					break;

			}

			$wallet->add_funds( $data->amount * -1, null, 'payout' );

			$wpdb->insert( $wpdb->prefix . 'routiz_wallet_payouts', [
				'user_id' => get_current_user_id(),
				'amount' => floatval( $data->amount ),
				'payment_method' => $data->method,
				'address' => $address,
				'status' => 'pending',
			]);

			/*
			 * send notification
			 *
			 */
			routiz()->notify->distribute( 'payout-request', [
				'user_id' => get_current_user_id(),
			]);
		}

		wp_send_json( $response );

	}

}
