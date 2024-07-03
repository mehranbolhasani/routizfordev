<?php

namespace Routiz\Inc\Src\Woocommerce;

use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Listing\Listing;
use \Routiz\Inc\Src\Traits\Singleton;
use \Routiz\Inc\Src\Woocommerce\Packages\Plan;
use \Routiz\Inc\Src\Woocommerce\Packages\Promotion;
use \Routiz\Inc\Src\Wallet;

class Order {

    use Singleton;

    function __construct() {

        add_action( 'woocommerce_order_status_completed', [ $this, 'plan_order_completed' ] );
        add_action( 'woocommerce_order_status_completed', [ $this, 'promotion_order_completed' ] );
        add_action( 'woocommerce_order_status_completed', [ $this, 'booking_order_completed' ] );
        add_action( 'woocommerce_order_status_completed', [ $this, 'claim_order_completed' ] );
        add_action( 'woocommerce_order_status_completed', [ $this, 'purchase_order_completed' ] );

        add_filter( 'woocommerce_order_item_display_meta_key', [ $this, 'display_meta_key' ], 20, 3 );
        add_filter( 'woocommerce_order_item_display_meta_value', [ $this, 'display_meta_value' ], 20, 3 );

        add_action( 'woocommerce_order_item_meta_end', [ $this, 'action_woocommerce_order_item_meta_end' ], 10, 3 );

    }

    public function action_woocommerce_order_item_meta_end( $item_id, $item, $order ) {
        $listing_ids_value = $item->get_meta('_listing_id');
        if( ! empty( $listing_ids_value ) ) {
            $listing_ids = json_decode( $listing_ids_value );
            if( is_array( $listing_ids ) ) {
                foreach( $listing_ids as $id ) {
                    $listing = new \Routiz\Inc\Src\Listing\Listing( $id );
                    if( $listing->id ) {
                        echo sprintf('<p><a href="%s" target="_blank">%s</a></p>', get_permalink( $listing->post->ID ), esc_html( $listing->post->post_title ) );
                    }
                }
            }
        }
    }

    public function plan_order_completed( $order_id ) {

		$order = wc_get_order( $order_id );
        $user_id = $order->get_customer_id();

        if( ! $user_id ) {
            return;
        }

        // prevent duplication
        if( get_post_meta( $order_id, 'routiz_payment_plan_processed', true ) ) {
			return;
		}

		foreach( $order->get_items() as $item ) {

			$product = wc_get_product( $item['product_id'] );

            /*
             * plan
             *
             */
            if( $product->is_type( 'listing_plan' ) ) {

                $plan = new Plan( $product->get_id() );

                /*
                 * inser user plan post
                 *
                 */
                $order = wc_get_order( $order_id );
                $user_plan_id = $plan->create( $order_id, $order->get_user_id() );

    			for( $i = 0; $i < $item['qty']; $i++ ) {

                    /*
                     * handle each listing inside plan
                     *
                     */
                     $listing_ids = json_decode( $item->get_meta('_listing_id') );

                     if( is_array( $listing_ids ) ) {
                         foreach( $listing_ids as $id ) {

                             $listing = new Listing( $id );

                             if( $listing->id and $listing->get_status() == 'pending_payment' ) {
                                 $listing->pack_away( $user_plan_id );
                             }
                         }
                     }
    			}
            }
		}

		update_post_meta( $order_id, 'routiz_payment_plan_processed', true );

	}

    public function promotion_order_completed( $order_id ) {

		$order = wc_get_order( $order_id );
        $user_id = $order->get_customer_id();

        // prevent duplication
        if( get_post_meta( $order_id, 'routiz_payment_promotion_processed', true ) ) {
			return;
		}

		foreach( $order->get_items() as $item ) {

			$product = wc_get_product( $item['product_id'] );

			if( $product->is_type( 'listing_promotion' ) and $user_id ) {
				for ( $i = 0; $i < $item['qty']; $i ++ ) {

                    $listing_ids = json_decode( $item->get_meta('_listing_id') );
                    $package_id = $item->get_product()->get_id();
                    $promotion = new Promotion( $package_id );

                    if( is_array( $listing_ids ) ) {
                        foreach( $listing_ids as $id ) {

                            $listing = new Listing( $id );

                            if( $listing->id ) {

                                $user_promotion_id = $promotion->create( $order_id );
                                $listing->promote( $user_promotion_id );

                            }
                        }
                    }
				}
			}
		}

		update_post_meta( $order_id, 'routiz_payment_promotion_processed', true );

	}

    public function booking_order_completed( $order_id ) {

		$order = wc_get_order( $order_id );
        $user_id = $order->get_customer_id();

        // prevent duplication
        if( get_post_meta( $order_id, 'routiz_payment_booking_processed', true ) ) {
			// return;
		}

		foreach( $order->get_items() as $item ) {

			$product = wc_get_product( $item['product_id'] );

			if( $product->is_type('listing_booking') and $user_id ) {
				for( $i = 0; $i < $item['qty']; $i++ ) {

                    $listing_ids = json_decode( $item->get_meta('_listing_id') );

                    if( isset( $listing_ids[0] ) ) {
                        $listing = new Listing( $listing_ids[0] );
                        if( $listing->id ) {

                            // use existing entry
                            if( ! $entry_id = $item->get_meta('_entry_id') ) {

                                $appt_period_id = $item->get_meta('_appt_period_id');

                                /*
                                 * create entry
                                 *
                                 */

                                // booking
                                if( empty( $appt_period_id ) ) {

                                    $action_booking = $listing->type->get_action('booking');
                                    $action_booking_hourly = $listing->type->get_action('booking_hourly');

                                    $entry_id = wp_insert_post([
                                        'post_title' => esc_html__( 'Booking', 'routiz' ),
                                        'post_status' => 'publish',
                                        'post_type' => 'rz_entry',
                                        'post_author' => $listing->post->post_author,
                                        'meta_input' => [
                                            'rz_entry_type' => $action_booking->action ? 'booking' : 'booking_hourly',
                                            'rz_listing' => $listing->id,
                                            'rz_checkin_date' => $item->get_meta('_checkin'),
                                            'rz_checkout_date' => $item->get_meta('_checkout'),
                                            'rz_request_user_id' => (int) $item->get_meta('_request_user_id'),
                                            'rz_pricing' => json_encode( $item->get_meta('_pricing') ),
                                            'rz_guests' => $item->get_meta('_guests'),
                                            'rz_children' => $item->get_meta('_children'),
                                            'rz_order_id' => $order_id,
                                        ]
                                    ]);

                                }

                                // appointment
                                else{

                                    $entry_id = wp_insert_post([
                        				'post_title' => esc_html__( 'Booking', 'routiz' ),
                        				'post_status' => 'publish',
                        				'post_type' => 'rz_entry',
                        				'post_author' => $listing->post->post_author,
                                        'meta_input' => [
                                            'rz_entry_type' => 'booking_appointments',
                                            'rz_listing' => $listing->id,
                                            'rz_checkin_date' => $item->get_meta('_checkin'),
                                            'rz_request_user_id' => (int) $item->get_meta('_request_user_id'),
                                            'rz_pricing' => json_encode( $item->get_meta('_pricing') ),
                                            'rz_guests' => $item->get_meta('_guests'),
                                            'rz_children' => $item->get_meta('_children'),
                                            'rz_appt_id' => sprintf( '%s-%s', $appt_period_id, $item->get_meta('_checkin') ),
                                            'rz_order_id' => $order_id,
                                        ]
                        			]);

                                }

                                $item->update_meta_data( '_entry_id', (int) $entry_id );


                            }else{

                                wp_update_post([
        							'ID' => $entry_id,
        							'post_status' => 'publish'
        						]);

                                update_post_meta( $entry_id, 'rz_order_id', $order_id );

                            }

                            if( ! is_wp_error( $entry_id ) ) {

                                $pricing = $item->get_meta('_pricing'); // processing
                                $price = $listing->get_price();

                                // add to owner wallet
                                $wallet = new Wallet( $listing->post->post_author );

                                // $earnings = number_format( $pricing->total - $pricing->security_deposit - $pricing->service_fee, 2, '.', '' );
                                $earnings = number_format( $pricing->processing - $pricing->service_fee, 2, '.', '' );

                                // charge host fee
                                if( $listing->type->get('rz_host_fee_type') == 'percentage' ) {
                                    if( $host_fee = floatval( $listing->type->get('rz_host_fee_amount_percentage') ) ) {
                                        $earnings = number_format( ( ( 100 - $host_fee ) * 0.01 ) * $earnings, 2 , '.', '' );
                                    }
                                }

                                $wallet->add_funds( $earnings, $order_id, 'earnings' );

                                /*
                                 * send notification
                                 *
                                 */
                                routiz()->notify->distribute( 'new-booking', [
                                    'user_id' => $listing->post->post_author,
                                    'meta' => [
                                        'entry_id' => $entry_id,
                                        'listing_id' => $listing->id,
                                        'from_user_id' => (int) $item->get_meta('_request_user_id'),
                                    ],
                                ], [
                                    'listing' => $listing,
                                    'request_user_id' => (int) $item->get_meta('_request_user_id'),
                                    'guests' => $item->get_meta('_guests'),
                                    'children' => $item->get_meta('_children'),
                                    'checkin' => $item->get_meta('_checkin'),
                                    'checkout' => $item->get_meta('_checkout'),
                                ]);

                            }
                        }
                    }
				}
			}
		}

		update_post_meta( $order_id, 'routiz_payment_booking_processed', true );

	}

    public function claim_order_completed( $order_id ) {

        $order = wc_get_order( $order_id );
        $user_id = $order->get_customer_id();

        // prevent duplication
        if( get_post_meta( $order_id, 'routiz_payment_claim_processed', true ) ) {
			return;
		}

		foreach( $order->get_items() as $item ) {
            $product = wc_get_product( $item['product_id'] );

            if( $product->is_type( 'listing_claim' ) and $user_id ) {

                $listing_ids = json_decode( $item->get_meta('_listing_id') );

                if( isset( $listing_ids[0] ) ) {
                    $listing = new Listing( $listing_ids[0] );
                    if( $listing->id ) {

                        $claim_comment = $item->get_meta('_claim_comment');

                        // insert claim post
        				$claim_id = wp_insert_post([
        	                'post_title' => esc_html__( 'Claim', 'routiz' ),
        	                'post_type' => 'rz_claim',
        	                'post_status' => 'pending',
        	                'post_author' => (int) $item->get_meta('_request_user_id'),
        					'meta_input' => [
        						'rz_listing' => $listing->id,
        						'rz_claim_comment' => $item->get_meta('_claim_comment'),
        						'rz_order_id' => $order_id,
        						'rz_product_id' => $product->get_id(),
        					]
        	            ]);

                        /*
                         * send notification
                         *
                         */
                        if( $claim_id ) {
            				routiz()->notify->distribute( 'new-claim', [
            					'user_id' => $listing->post->post_author,
            					'meta' => [
                                    'claim_id' => $claim_id,
                                    'listing_id' => $listing->id,
                                ],
            				]);
                        }

                    }
                }
            }
        }

        update_post_meta( $order_id, 'routiz_payment_claim_processed', true );

    }

    public function purchase_order_completed( $order_id ) {

        $order = wc_get_order( $order_id );
        $user_id = $order->get_customer_id();

        // prevent duplication
        if( get_post_meta( $order_id, 'routiz_payment_purchase_processed', true ) ) {
			return;
		}

		foreach( $order->get_items() as $item ) {
            $product = wc_get_product( $item['product_id'] );

            if( $product->is_type( 'listing_purchase' ) and $user_id ) {

                $listing_ids = json_decode( $item->get_meta('_listing_id') );

                if( isset( $listing_ids[0] ) ) {
                    $listing = new Listing( $listing_ids[0] );
                    if( $listing->id ) {

                        /*
                         * create entry
                         *
                         */
                        $entry_id = wp_insert_post([
                            'post_title' => esc_html__( 'Purchase', 'routiz' ),
                            'post_status' => 'publish',
                            'post_type' => 'rz_entry',
                            'post_author' => $listing->post->post_author,
                            'meta_input' => [
                                'rz_entry_type' => 'purchase',
                                'rz_listing' => $listing->id,
                                'rz_request_user_id' => (int) $item->get_meta('_request_user_id'),
                                'rz_pricing' => json_encode( $item->get_meta('_pricing') ),
                                'rz_order_id' => $order_id,
                            ]
                        ]);

                        // entry was created successfully
                        if( ! is_wp_error( $entry_id ) ) {

                            /*
                             * add funds
                             *
                             */
                            $pricing = $item->get_meta('_pricing'); // processing
                            $price = $listing->get_price();

                            // add to owner wallet
                            $wallet = new Wallet( $listing->post->post_author );

                            $earnings = number_format( $pricing->total - $pricing->service_fee, 2, '.', '' );

                            // charge host fee
                            if( $listing->type->get('ulz_host_fee_type') == 'percentage' ) {
                                if( $host_fee = floatval( $listing->type->get('rz_host_fee_amount_percentage') ) ) {
                                    $earnings = number_format( ( ( 100 - $host_fee ) * 0.01 ) * $earnings, 2 , '.', '' );
                                }
                            }

                            $wallet->add_funds( $earnings, $order_id, 'earnings' );

                            /*
                             * send notification
                             *
                             */
            				routiz()->notify->distribute( 'new-purchase', [
            					'user_id' => $listing->post->post_author,
            					'meta' => [
                                    'entry_id' => $entry_id,
                                    'listing_id' => $listing->id,
                                    'from_user_id' => (int) $item->get_meta('_request_user_id'),
                                ],
            				]);

                        }

                    }
                }
            }
        }

        update_post_meta( $order_id, 'routiz_payment_purchase_processed', true );

    }

    public function display_meta_key( $key, $meta, $item ) {

        if( $meta->key == '_listing_id' ) {
            return __( 'Listings Attached', 'routiz' );
        }

        return $key;

    }

    public function display_meta_value( $value, $meta, $item ) {

        if( $meta->key == '_listing_id' ) {
            $array = json_decode( $meta->value );
            if( is_array( $array ) ) {
                $arr = [];
                foreach( $array as $id ) {
                    $listing = new Listing( $id );
                    if( $listing->id ) {
                        $title = get_the_title( $listing->id );
                        $arr[] = '#' . $listing->id . ' - ' . ( $title ? $title : esc_html__( '( no title )', 'routiz' ) );
                    }
                }
                return implode( ', ', $arr );
            }
        }

        return $value;

    }

}
