<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

use \Routiz\Inc\Src\Request\Request;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Dev_Export_Options extends Endpoint {

	public $action = 'rz_dev_export_options';

    public function action() {

		$options = [];
		foreach( wp_load_alloptions() as $key => $value ) {
			if( Rz()->is_prefixed( $key ) ) {

				// skip integration details
				if(
					$key == 'rz_google_api_key' or
					$key == 'rz_enable_facebook_auth' or
					$key == 'rz_facebook_app_id' or
					$key == 'rz_facebook_app_secret' or
					$key == 'rz_enable_google_auth' or
					$key == 'rz_google_client_id' or
					$key == 'rz_google_client_secret'
				) {
					continue;
				}

				$options[ $key ] = $value;
			}
		}

		wp_send_json([
			'success' => true,
			'output' => wp_json_encode( $options )
		]);

	}

}
