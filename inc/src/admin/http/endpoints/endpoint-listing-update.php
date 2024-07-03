<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Validation;
use \Routiz\Inc\Src\Listing\Listing;
use \Routiz\Inc\Src\Form\Component as Form;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Listing_Update extends Endpoint {

	public $action = 'rz_listing_update';

    public function action() {

		do_action('routiz/account/listing/before_update');

		global $post;

		$request = Request::instance();
		$form = new Form( Form::Storage_Request );

		// security
		if( ! wp_verify_nonce( $request->get('security'), 'routiz_account_listing_update' ) ) {
			return;
		}

		$post = get_post( (int) $request->get('listing_id'), OBJECT );
		setup_postdata( $post );

		// check owner
		if( (int) $post->post_author !== get_current_user_id() ) {
			return;
		}

		$listing = new Listing( (int) $request->get('listing_id') );

		if( ! $listing->id ) {
			return;
		}

		$items = Rz()->jsoning( 'rz_fields', $listing->type->id );

		/*
		 * validation
		 *
		 */
		$terms = [];

		if( is_array( $items ) ) {
			foreach( $items as $item ) {

                if(
					isset( $item->fields->is_submit_form )
					and $item->fields->is_submit_form == true
					and isset( $item->fields->required )
					and $item->fields->required == true
				) {

                    $field = $form->create(
                        Rz()->prefix_item( $item )
                    );

                    $terms[ $field->props->id ] = 'required';

                }
            }
        }

		$validation = new \Routiz\Inc\Src\Validation();
		$response = $validation->validate( $request->params, $terms );

		if( ! $response->success ) {
			wp_send_json( $response );
		}

		/*
		 * update fields
		 *
		 */
		if( $response->success ) {
			if( is_array( $items ) ) {
				foreach( $items as $item ) {

					// prefix custom field ids
					if( isset( $item->fields->key ) ) {
						$item->fields->key = Rz()->prefix( $item->fields->key );
					}

					$field = $form->create( $item );
					$id = $field->props->id;

					if( $request->has( $id ) ) {

						// post_title exception
						if( $item->fields->key == 'post_title' ) {
							wp_update_post([
								'ID' => $listing->id,
								'post_title' => $field->props->value
							]);
							continue;
						}

						// post_content exception
						if( $item->fields->key == 'post_content' ) {
							wp_update_post([
								'ID' => $listing->id,
								'post_content' => $field->props->value
							]);
							continue;
						}

						$value = $field->props->value;
						$value = $field->before_save( $listing->id, $value );

						// TODO: improve this
						// menu exception
						if( $item->template->id == 'menu' and is_array( $value ) ) {
							$value = json_encode( $value );
						}

						delete_post_meta( $listing->id, $id );

						// array
						if( is_array( $value ) ) {
							foreach( $value as $val ) {
								add_post_meta( $listing->id, $id, $val );
							}
						}
						// single
						else{
							update_post_meta( $listing->id, $id, $value );
						}

						$field->after_save( $listing->id, $value );

					}
				}
			}
		}

		/*
		 * static fields
		 *
		 */
		$static_fields = [
			'rz_instant',
			'rz_price',
			'rz_price_weekend',
			'rz_security_deposit',
			'rz_long_term_week',
			'rz_long_term_month',
			'rz_price_seasonal',
			'rz_extra_pricing',
			'rz_addons',
			'rz_guests',
			'rz_children',
			'rz_reservation_length_min',
			'rz_reservation_length_max',
			'rz_reservation_start',
			'rz_reservation_end',
			'rz_hour_book_multi_disable',
		];

		foreach( $static_fields as $static_field ) {
			if( $request->has( $static_field ) ) {
				update_post_meta( $listing->id, $static_field, $request->get( $static_field ) );
			}
		}

		/*
		 * update post status
		 *
		 */
		if( $listing->type->get('rz_requires_admin_approval_after_update') ) {
			wp_update_post([
				'ID' => $listing->id,
				'post_status' => 'pending'
			]);
		}

		wp_send_json([
			'success' => true
		]);

	}

}
