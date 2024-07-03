<?php

namespace Routiz\Inc\Src\Notification\Notifications;

class Entry_Approved extends \Routiz\Inc\Src\Notification\Base {

    public $user_can_manage = true;

    /*
     * general
     *
     */
    public function get_id() {
        return 'entry-approved';
    }

    public function get_name() {
        return esc_html__('Your request has been approved', 'routiz');
    }

    /*
     * email
     *
     */
    public function get_email_subject() {
        return esc_html__( 'Your request has been approved', 'routiz' );
    }

    public function get_email_template() {
        return esc_html__( "Hello {user_display_name},\r\n\r\nYour request has been approved.", 'routiz' );
    }

    /*
     * email admin
     *
     */
    public function get_email_admin_subject() {
        return esc_html__( 'Your request has been approved', 'routiz' );
    }

    public function get_email_admin_template() {
        return esc_html__( "Hello,\r\n\r\nYour request has been approved.", 'routiz' );
    }

    /*
     * site
     *
     */
    public function get_site_icon() {
        return 'material-icon-thumb_up_alt';
    }

    public function get_site_message() {
        return esc_html__( 'Your request has been approved', 'routiz' );
    }

    public function get_site_url() {
        if( function_exists('wc_get_account_endpoint_url') ) {
            return add_query_arg( 'type', 'outgoing', wc_get_account_endpoint_url( 'entries' ) );
        }
    }

}
