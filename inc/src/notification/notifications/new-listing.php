<?php

namespace Routiz\Inc\Src\Notification\Notifications;

class New_Listing extends \Routiz\Inc\Src\Notification\Base {

    public $user_can_manage = false;

    /*
     * general
     *
     */
    public function get_id() {
        return 'new-listing';
    }

    public function get_name() {
        return esc_html__('New listing has been created', 'routiz');
    }

    /*
     * email
     *
     */
    public function get_email_subject() {
        return esc_html__( 'New listing has been created', 'routiz' );
    }

    public function get_email_template() {
        return esc_html__( "Hello {user_display_name},\r\n\r\nNew listing has been created.", 'routiz' );
    }

    /*
     * email admin
     *
     */
    public function get_email_admin_subject() {
        return esc_html__( 'New listing', 'routiz' );
    }

    public function get_email_admin_template() {
        return esc_html__( "Hello,\r\n\r\nNew listing has been created", 'routiz' );
    }

    /*
     * site
     *
     */
    public function get_site_icon() {
        return 'material-icon-add';
    }

    public function get_site_message() {
        return esc_html__( 'New listing has been created', 'routiz' );
    }

    public function get_site_url() {
        if( function_exists('wc_get_account_endpoint_url') ) {
            return wc_get_account_endpoint_url( 'listings' );
        }
    }

}
