<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

use \Routiz\Inc\Src\Request\Request;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Trigger_Webhook extends Endpoint {

	public $action = 'rz_trigger_webhook';

    public function action() {

		$request = Request::instance();

		$webhook_id = $request->get('webhook_id');
		$webhook_url = $request->get('webhook_url');

        // check url
        if( empty( $webhook_url ) or filter_var( $webhook_url, FILTER_VALIDATE_URL ) === false ) {
            return;
        }

		$data = [
			// receiver
			'user_id' => 1,
			'user_first_name' => 'John',
			'user_last_name' => 'Doe',
			'user_display_name' => 'John Doe',
			'user_email' => 'user@email.com',
			'user_billing_email' => 'user@email.com',
			'user_billing_phone' => '4445551234',
			'user_billing_country' => 'ES',
			'user_billing_city' => 'Barcelona',
			'user_billing_postcode' => '08800',
			// sender
			'from_user_id' => 1,
			'from_user_first_name' => 'Brian',
			'from_user_last_name' => 'Harris',
			'from_user_display_name' => 'Brian Harris',
			'from_user_email' => 'sender@email.com',
			'from_user_billing_phone' => '4445551234',
			// listing
			'listing_id' => 1,
			'listing_name' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit',
			'listing_url' => 'https://example.com'
		];

		if( $webhook_id == 'reservation_webhook' ) {

			$data['guests'] = 3;

			$unix_checkin = strtotime('+1 day');
			$unix_checkout = strtotime('+2 day');
			$date_format = get_option('date_format');
			$time_format = get_option('time_format');

			// unix
			$data['unix_checkin'] = $unix_checkin;
			$data['unix_checkout'] = $unix_checkout;

			// date
			$data['date_checkin'] = date( $date_format, $unix_checkin );
			$data['date_checkout'] = date( $date_format, $unix_checkout );

			// time
			$data['time_checkin'] = date( $time_format, $unix_checkin );
			$data['time_checkout'] = date( $time_format, $unix_checkout );

			// date time
			$data['datetime_checkin'] = date( sprintf( '%s %s', $date_format, $time_format ), $unix_checkin );
			$data['datetime_checkout'] = date( sprintf( '%s %s', $date_format, $time_format ), $unix_checkout );

		}

        $ch = curl_init( $webhook_url );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, [
            'Content-type: application/json'
        ]);
        curl_setopt( $ch, CURLOPT_POST, 1);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ));
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt( $ch, CURLOPT_HEADER, 0);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec( $ch );

        curl_close( $ch );

		wp_send_json([
			'success' => true,
			'output' => $response
		]);

	}

}
