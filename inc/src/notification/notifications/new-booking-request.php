<?php

namespace Routiz\Inc\Src\Notification\Notifications;

class New_Booking_Request extends \Routiz\Inc\Src\Notification\Base {

    public $user_can_manage = true;

    /*
     * general
     *
     */
    public function get_id() {
        return 'new-booking-request';
    }

    public function get_name() {
        return esc_html__('New reservation request has been received', 'routiz');
    }

    /*
     * email
     *
     */
    public function get_email_subject() {
        return esc_html__( 'New reservation request has been received', 'routiz' );
    }

    public function get_email_template() {
        return esc_html__( "Hello {user_display_name},\r\n\r\nYou have received a new reservation request.", 'routiz' );
    }

    /*
     * email admin
     *
     */
    public function get_email_admin_subject() {
        return esc_html__( 'New reservation request', 'routiz' );
    }

    public function get_email_admin_template() {
        return esc_html__( "Hello,\r\n\r\nA new reservation request has been sent.", 'routiz' );
    }

    /*
     * site
     *
     */
    public function get_site_icon() {
        return 'material-icon-date_range';
    }

    public function get_site_message() {
        return esc_html__( 'New reservation request has been received', 'routiz' );
    }

    public function get_site_url() {
        if( function_exists('wc_get_account_endpoint_url') ) {
            return wc_get_account_endpoint_url( 'entries' );
        }
    }

}
