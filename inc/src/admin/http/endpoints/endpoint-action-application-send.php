<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

use \Routiz\Inc\Src\Request\Custom_Request;
use \Routiz\Inc\Src\Request\Raw_Request;
use \Routiz\Inc\Src\Validation;
use \Routiz\Inc\Src\Listing\Listing;
use \Routiz\Inc\Src\Form\Component as Form;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Action_Application_Send extends Endpoint {

	public $action = 'rz_action_application_send';

    public function action() {

		$request = new Custom_Request('input');
		$raw_request = new Raw_Request('input');
		$listing = new Listing( (int) $_POST['listing_id'] );
		$form = new Form( Form::Storage_Field );
		$terms = [];
		$items = $listing->type->get_action_type( 'application' )->fields->form;

		foreach( $items as $item ) {

			// add prefix
			Rz()->prefix_item( $item );

			if( $item->fields->show_if_guest and is_user_logged_in() ) {
				continue;
			}

			if( $item->fields->required == true ) {

				$field = $form->create( $item );
				$terms[ $field->props->id ] = 'required';

			}
		}

		$validation = new Validation();
		$response = $validation->validate( $request->params, $terms );

		if( $response->success ) {

			$entry_id = wp_insert_post([
                'post_title' => esc_html__( 'Application', 'routiz' ),
                'post_type' => 'rz_entry',
                'post_status' => 'publish',
                'post_author' => $listing->post->post_author,
				'meta_input' => [
					'rz_entry_type' => 'application',
					'rz_listing' => $listing->id,
					'rz_request_user_id' => get_current_user_id(),
				]
            ]);

            if( ! is_wp_error( $entry_id ) ) {

				/*
				 * send notification
				 *
				 */
				routiz()->notify->distribute( 'new-application', [
					'user_id' => $listing->post->post_author,
					'meta' => [
						'entry_id' => $entry_id,
						'listing_id' => $listing->id,
						'from_user_id' => get_current_user_id(),
					],
				]);

				foreach( $items as $item ) {

					if( $item->fields->show_if_guest and is_user_logged_in() ) {
						continue;
					}

					if( is_string( $raw_request->get( $item->fields->key ) ) ) {
						$item->fields->value = wp_kses_post( $raw_request->get( $item->fields->key ) );
					}else{
						$item->fields->value = $raw_request->get( $item->fields->key );
					}

					$field = $form->create(
						Rz()->prefix_item( $item )
					);

					$id = $field->props->id;

					if( $raw_request->has( $id ) ) {

						$value = $field->props->value;
						$value = $field->before_save( $entry_id, $value );

						// array
						if( is_array( $value ) ) {
							foreach( $value as $val ) {
								add_post_meta( $entry_id, $id, $val );
							}
						}
						// single
						else{
							add_post_meta( $entry_id, $id, $value );
						}

						$field->after_save( $entry_id, $value );

					}
				}
			}
		}

		wp_send_json( $response );

	}

}
