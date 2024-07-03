<?php

namespace Routiz\Inc\Src\Notification\Notifications;

class Payout_Request extends \Routiz\Inc\Src\Notification\Base {

    public $user_can_manage = true;

    /*
     * general
     *
     */
    public function get_id() {
        return 'payout-request';
    }

    public function get_name() {
        return esc_html__('Your payout request has been received', 'routiz');
    }

    /*
     * email
     *
     */
    public function get_email_subject() {
        return esc_html__( 'Your payout request has been received', 'routiz' );
    }

    public function get_email_template() {
        return esc_html__( "Hello {user_display_name},\r\n\r\nYour payout request has been received", 'routiz' );
    }

    /*
     * email admin
     *
     */
    public function get_email_admin_subject() {
        return esc_html__( 'A new payout request has been received', 'routiz' );
    }

    public function get_email_admin_template() {
        return esc_html__( "Hello,\r\n\r\nA new payout request has been received", 'routiz' );
    }

    /*
     * site
     *
     */
    public function get_site_icon() {
        return 'material-icon-account_balance_wallet';
    }

    public function get_site_message() {
        return esc_html__( 'Your payout request has been received', 'routiz' );
    }

    public function get_site_url() {
        if( function_exists('wc_get_account_endpoint_url') ) {
            return wc_get_account_endpoint_url( 'payouts' );
        }
    }

}
