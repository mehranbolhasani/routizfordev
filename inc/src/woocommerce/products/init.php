<?php

namespace Routiz\Inc\Src\Woocommerce\Products;

use \Routiz\Inc\Src\Traits\Singleton;

class Init {

    use Singleton;

    function __construct() {

        add_action( 'init', [ $this, 'register_packages' ] );

        // wc register products types
        add_filter( 'product_type_selector', [ $this, 'add_type' ] );

        // wc product meta template
        add_action( 'woocommerce_product_options_general_product_data', array( $this, 'product_data' ) );

        // wc save product data
        add_action( 'woocommerce_process_product_meta_listing_plan', array( $this, 'save_listing_plan_data' ) );
        add_action( 'woocommerce_process_product_meta_listing_promotion', array( $this, 'save_listing_promotion_data' ) );

        // calculate booking totals
        add_action('woocommerce_before_calculate_totals', [ $this, 'set_booking_product_price' ] );

    }

    public function register_packages() {

        if( class_exists('WC_Product') ) {
            require RZ_PATH . 'inc/src/woocommerce/products/wc-product-listing-plan.php';
            require RZ_PATH . 'inc/src/woocommerce/products/wc-product-listing-promotion.php';
            require RZ_PATH . 'inc/src/woocommerce/products/wc-product-listing-booking.php';
            require RZ_PATH . 'inc/src/woocommerce/products/wc-product-listing-claim.php';
            require RZ_PATH . 'inc/src/woocommerce/products/wc-product-listing-purchase.php';
        }

        if( class_exists('WC_Product_Subscription') ) {
            require RZ_PATH . 'inc/src/woocommerce/products/wc-product-listing-subscription-plan.php';
        }

    }

    public function add_type( $types ) {

        $types['listing_plan'] = esc_html__( 'Listing Plan', 'routiz' );
        $types['listing_promotion'] = esc_html__( 'Listing Promotion', 'routiz' );
        $types['listing_booking'] = esc_html__( 'Listing Booking', 'routiz' );
        $types['listing_claim'] = esc_html__( 'Listing Claim', 'routiz' );
        $types['listing_purchase'] = esc_html__( 'Listing Purchase', 'routiz' );

        if( class_exists('WC_Product_Subscription') ) {
            $types['listing_subscription_plan'] = esc_html__( 'Listing Subscription Plan', 'routiz' );
        }

        return $types;

    }

    public function product_data() {

        Rz()->the_template('admin/woocommerce/product-listing-plan');
        Rz()->the_template('admin/woocommerce/product-listing-promotion');
        Rz()->the_template('admin/woocommerce/product-listing-booking');
        Rz()->the_template('admin/woocommerce/product-listing-claim');
        Rz()->the_template('admin/woocommerce/product-listing-purchase');

        // if( class_exists('WC_Product_Subscription') ) {
        //     Rz()->the_template('admin/woocommerce/product-listing-subscription-plan');
        // }

	}

    public function save_listing_plan_data( $post_id ) {

        global $wpdb;

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

    public function save_listing_promotion_data( $post_id ) {

        global $wpdb;

		$meta_save = [
			'_rz_promotion_duration',
			'_rz_promotion_priority',
		];

        $data = (object) Rz()->sanitize( $_POST );

		foreach( $meta_save as $meta_key ) {
			update_post_meta( $post_id, $meta_key, isset( $data->{$meta_key} ) ? $data->{$meta_key} : '' );
		}

    }

    public function set_booking_product_price( $cart ) {

        if ( empty( $cart->cart_contents ) ) {
            return;
        }

        foreach( $cart->cart_contents as $key => $value ) {
            $type = $value['data']->get_type();
            // bookings
            if( $type == 'listing_booking' ) {
                if( isset( $value['booking_price'] ) ) {
                    $value['data']->set_price( floatval( $value['booking_price'] ) );
                }
            }
            // purchase
            elseif( $type == 'listing_purchase' ) {
                if( isset( $value['purchase_price'] ) ) {
                    $value['data']->set_price( floatval( $value['purchase_price'] ) );
                }
            }
        }

    }

}
