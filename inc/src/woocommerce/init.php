<?php

namespace Routiz\Inc\Src\Woocommerce;

use \Routiz\Inc\Utils\Woocommerce;
use \Routiz\Inc\Src\Traits\Singleton;

class Init {

    use Singleton;

    function __construct() {

        Products\Init::instance();
        Products\Subscription::instance();
        Post_Types::instance();
        Cart::instance();
        Checkout::instance();
        Order::instance();

        $this->register_helper();

        add_action( 'after_setup_theme', [ $this, 'register_account_pages' ] );
        add_action( 'wp_enqueue_script', [ $this, 'enqueue_script' ] );

    }

    static function enqueue_script() {

        wp_enqueue_style( 'rz-account' );
        wp_enqueue_script( 'rz-account' );

    }

    public function register_helper() {
        routiz()->register( 'wc', Woocommerce::instance() );
    }

    public function register_account_pages() {

        $this->switch_user_role();

        $user_role = get_user_meta( get_current_user_id(), 'rz_role', true );

        if( $user_role == 'business' ) {
            routiz()->wc()->add_account_page([
    			'endpoint' => 'listings',
    			'title' => null,
    			'template' => 'account/listings/listings',
    			'show_in_menu' => true,
    			'order' => 4,
    		]);
        }

        routiz()->wc()->add_account_page([
			'endpoint' => 'messages',
			'title' => null,
			'template' => 'account/messages/messages',
			'show_in_menu' => true,
			'order' => 5,
		]);

        routiz()->wc()->add_account_page([
			'endpoint' => 'entries',
			'title' => null,
			'template' => 'account/entries/entries',
			'show_in_menu' => true,
			'order' => 6,
		]);

        if( get_option('rz_enable_payouts') and $user_role == 'business' ) {
            routiz()->wc()->add_account_page([
    			'endpoint' => 'payouts',
    			'title' => null,
    			'template' => 'account/payouts/payouts',
    			'show_in_menu' => true,
    			'order' => 7,
    		]);
        }

        routiz()->wc()->add_account_page([
			'endpoint' => 'notification-settings',
			'title' => null,
			'template' => 'account/notifications/notifications',
			'show_in_menu' => true,
			'order' => 8,
		]);

    }

    public function switch_user_role() {
        if( isset( $_GET['rz_switch_user_role'] ) ) {

            if( get_option('rz_enable_standard_role') ) {
                $user_id = get_current_user_id();
                $user_current_role = get_user_meta( $user_id, 'rz_role', true );
                update_user_meta( $user_id, 'rz_role', ( empty( $user_current_role ) or $user_current_role == 'customer' ) ? 'business' : 'customer' );
            }

            wp_redirect( wc_get_account_endpoint_url( 'dashboard' ) );
            exit;

        }
    }

}
