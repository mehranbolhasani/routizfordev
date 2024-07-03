<?php

namespace Routiz\Inc\Src\Woocommerce\Products;

use \Routiz\Inc\Src\Traits\Singleton;
use \Routiz\Inc\Src\Listing\Listing;
use \Routiz\Inc\Src\Woocommerce\Packages\Plan;

class Subscription {

    use Singleton;

    function __construct() {

        add_action( 'woocommerce_process_product_meta_listing_subscription_plan', array( $this, 'save_listing_subscription_plan_data' ) );

        // subscription synchronisation.
		// activate sync ( process meta ) for listing package
        if( class_exists( 'WC_Subscriptions_Admin' ) && method_exists( 'WC_Subscriptions_Admin', 'save_subscription_meta' ) ) {
            add_action( 'woocommerce_process_product_meta_listing_subscription_plan', 'WC_Subscriptions_Synchroniser::save_subscription_meta', 10 );
		}

        // add product to valid subscription type
        add_filter( 'woocommerce_is_subscription', [ $this, 'woocommerce_is_subscription' ], 10, 2 );
        add_filter( 'woocommerce_subscription_product_types', [ $this, 'subscription_product_types' ] );

        // subscription starts
        add_action( 'woocommerce_subscription_status_active', [ $this, 'subscription_activated' ] );

        // subscription renewal
        add_action( 'woocommerce_subscription_renewal_payment_complete', [ $this, 'subscription_renewed' ] );

        // subscription end
		add_action( 'woocommerce_subscription_status_on-hold', [ $this, 'subscription_ended' ] );
		add_action( 'woocommerce_subscription_status_expired', [ $this, 'subscription_ended' ] );
		add_action( 'woocommerce_subscription_status_cancelled', [ $this, 'subscription_ended' ] );

    }

    public function save_listing_subscription_plan_data( $post_id ) {

		$meta_save = [
			'_rz_plan_duration',
			'_rz_plan_limit',
			'_rz_plan_priority',
			'_rz_plan_disable_repeat_purchase',
		];

        $data = (object) Rz()->sanitize( $_POST );
		foreach( $meta_save as $meta_key ) {
			update_post_meta( $post_id, $meta_key, isset( $data->{$meta_key} ) ? $data->{$meta_key} : '' );
		}

	}

    public function woocommerce_is_subscription( $is_subscription, $product_id ) {
		$product = wc_get_product( $product_id );
		if ( $product && $product->is_type( ['listing_subscription_plan'] ) ) {
			$is_subscription = true;
		}
		return $is_subscription;
	}

    public function subscription_product_types( $name ) {
        $types[] = 'listing_subscription_plan';
		return $types;
    }

    public function subscription_activated( $subscription ) {

        // prevent duplication
        if( get_post_meta( $subscription->get_id(), 'routiz_subscription_plan_processed', true ) ) {
			return;
		}

        foreach( $subscription->get_items() as $item ) {

            $product = wc_get_product( $item['product_id'] );

            if(
                $product->is_type( array( 'listing_subscription_plan' ) ) and
                $subscription->get_user_id() and
                ! isset( $item['switched_subscription_item_id'] )
            ) {

                // give plan to user
                $plan = new Plan( $product->get_id() );
                $user_plan_id = $plan->create( $subscription->get_id(), $subscription->get_user_id() );

                // handle listings
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

        update_post_meta( $subscription->get_id(), 'routiz_subscription_plan_processed', true );

    }

    public function subscription_renewed( $subscription ) {

        foreach( $subscription->get_items() as $item ) {

            $product = wc_get_product( $item['product_id'] );

            if(
                $product->is_type( array( 'listing_subscription_plan' ) ) and
                $subscription->get_user_id()
            ) {

                // give plan to user or update its data
                $plan = new Plan( $product->get_id() );
                $plan_id = $plan->create( $subscription->get_id(), $subscription->get_user_id(), $item['product_id'] );

                // renew plan which refresh every term
                if( $plan_id ) {

                    update_post_meta( $plan_id, 'rz_order_id', $subscription->get_id() );

                    wp_update_post([
                        'ID' => $plan_id,
                        'post_status' => 'publish',
                        'meta_input' => [
                            'rz_count' => 0,
                        ]
                    ]);

                }
            }
		}
    }

    public function subscription_ended( $subscription ) {

        foreach( $subscription->get_items() as $item ) {

            $product = wc_get_product( $item['product_id'] );

            if(
                $product->is_type( array( 'listing_subscription_plan' ) ) and
                $subscription->get_user_id()
            ) {

                // delete the user's plan
                $plan = new Plan( $product->get_id() );
                $plan_id = $plan->delete( $subscription->get_id(), $subscription->get_user_id() );

                // handle listings
				for( $i = 0; $i < $item['qty']; $i++ ) {

                    /*
                     * handle each listing inside plan
                     *
                     */
                    $listing_ids = json_decode( $item->get_meta('_listing_id') );

                    if( is_array( $listing_ids ) ) {
                        foreach( $listing_ids as $id ) {

                            $listing = new Listing( $id );

                            // cancel listing
                            wp_update_post([
                                'ID' => $listing->id,
                                'post_status' => 'cancelled',
                            ]);

                        }
                    }
				}
            }
        }

        delete_post_meta( $subscription->get_id(), 'routiz_subscription_plan_processed' );

    }

}
