<?php

namespace Routiz\Inc\Src\Submission\Modules\Publish;

use \Routiz\Inc\Src\Submission\Submission;
use \Routiz\Inc\Src\Submission\Modules\Module;
use \Routiz\Inc\Src\Request\Custom_Request;
use \Routiz\Inc\Src\Request\Raw_Request;
use \Routiz\Inc\Src\Form\Component as Form;
use \Routiz\Inc\Src\Validation;
use \Routiz\Inc\Src\Woocommerce\Packages\Plan;
use \Routiz\Inc\Src\Listing\Listing;

class Publish extends Module {

    public function controller() {
        return [
            'strings' => (object) [
                'about_publish' => esc_html__('You are about to publish', 'routiz'),
                'confirm' => esc_html__('Are you sure you you want to publish this listing?', 'routiz'),
            ]
        ];
    }

    public function validation() {

        $request = new Custom_Request('input');
        $raw_request = new Raw_Request('input');
        $form = new Form( Form::Storage_Field );
        $submission = new Submission( $request->get('type') );

        // check the listing type
        if( ! $submission->listing_type->id ) {
            return;
        }

        $terms = [];

        foreach( $submission->tabs as $tab ) {
            if( isset( $tab['content'] ) and is_array( $tab['content'] ) ) {
                foreach( $tab['content'] as $item ) {
                    if( $item->fields->is_submit_form and isset( $item->fields->required ) and $item->fields->required == true ) {

                        $field = $form->create(
                            Rz()->prefix_item( $item )
                        );

                        $terms[ $field->props->id ] = 'required';

                    }
                }
            }
        }

        $validation = new Validation();
		$response = $validation->validate( $request->params, $terms );

        if( $response->success ) {

            $listing_id = wp_insert_post([
                'post_title' => wp_kses_post( $raw_request->get('post_title') ),
                'post_content' => wp_kses_post( $raw_request->get('post_content') ),
                'post_type' => 'rz_listing',
                'post_status' => $submission->listing_type->get('rz_requires_admin_approval') ? 'pending' : 'publish',
                'post_author' => get_current_user_id(),
            ]);

            if( ! is_wp_error( $listing_id ) ) {

                $listing = new Listing( $listing_id );

                // set listing type
                add_post_meta( $listing->id, 'rz_listing_type', $submission->listing_type->id );
                $listing->type = $submission->listing_type;

                /*
                 * save meta data
                 *
                 */
                foreach( $submission->tabs as $tab ) {
                    if( isset( $tab['content'] ) and is_array( $tab['content'] ) ) {
                        foreach( $tab['content'] as $item ) {
                            if( $item->fields->is_submit_form ) {

                                $item->fields->key = Rz()->prefix( $item->fields->key );

                                // set item value from request
                                // $item->fields->value = $request->get( $item->fields->key );
                                if( is_string( $raw_request->get( $item->fields->key ) ) ) {
                                    $item->fields->value = wp_kses_post( $raw_request->get( $item->fields->key ) );
                                }else{
                                    $item->fields->value = $raw_request->get( $item->fields->key );
                                }

                                $field = $form->create(
                                    Rz()->prefix_item( $item )
                                );

                                $id = $field->props->id;

                                if( $id == 'post_title' or $id == 'post_content' ) {
                                    continue;
                                }

                                if( $request->has( $id ) ) {

                                    $value = $field->props->value;
                                    $value = $field->before_save( $listing->id, $value );

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
                }

                // trigger post update to handle important actions,
                // like setting featured image using the first gallery image
                do_action( 'save_post', $listing->id, get_post( $listing->id ), true );

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
        			'rz_guest_price',
                    'rz_child_price',
                    'rz_child_sep_price',
        			'rz_reservation_length_min',
        			'rz_reservation_length_max',
        			'rz_reservation_start',
        			'rz_reservation_end',
                    'rz_hour_book_multi_disable',
        			'rz_time_availability'
                ];

                foreach( $static_fields as $static_field ) {
                    if( $request->has( $static_field ) ) {
                        update_post_meta( $listing->id, $static_field, $request->get( $static_field ) );
                    }
                }

                // required admin approval
                if( $listing->type->get('rz_requires_admin_approval') ) {

                    $response->listing = [
                        'id' => $listing->id,
                        'button_url' => esc_url( Rz()->get_submission_page_url() ),
                        'button_text' => esc_html__( 'Add new', 'routiz' )
                    ];

                }
                // no admin approval
                else{

                    $response->listing = [
                        'id' => $listing->id,
                        'button_url' => get_permalink( $listing->id ),
                        'button_text' => esc_html__( 'View submission', 'routiz' )
                    ];

                }

                /*
                 * notify
                 *
                 */
                routiz()->notify->distribute( 'new-listing', [
					'user_id' => get_current_user_id(),
					'meta' => [
                        'listing_id' => $listing->id,
                    ],
				]);

                /*
                 * plan
                 *
                 */
                if( ! $request->is_empty('rz_plan') ) {

                    $plan = new Plan( $request->get('rz_plan') );

                    if( $plan->id ) {

                        $availability = $plan->availability();

                        // plan not owned
                        if( $availability === 0 ) {

                            // premium package
                            if( $plan->is_purchasable() ) {

                                // add to cart
                                $listing->awaits_payment();
                                $cart_response = $plan->add_to_cart( $listing->id );

                                $response->listing = $cart_response;
                                return $response;

                            }
                            // free package
                            else{

                                $user_plan_id = $plan->create( null, get_current_user_id() );
                                $listing->pack_away( $user_plan_id );

                            }
                        }
                        // use available plan
                        else{

                            $user_plan_id = $plan->get_first_available();
                            $listing->pack_away( $user_plan_id );

                        }
                    }
                }

                /*
                 * claim
                 *
                 */
                $action = $listing->type->get_action('claim');
                if( $action->exists() ) {
                    update_post_meta( $listing->id, 'rz_is_claimed', true );
                }

            }
        }

        return $response;

    }

}
