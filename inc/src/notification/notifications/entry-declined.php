<?php

namespace Routiz\Inc\Src\Notification\Notifications;

class Entry_Declined extends \Routiz\Inc\Src\Notification\Base {

    public $user_can_manage = true;

    /*
     * general
     *
     */
    public function get_id() {
        return 'entry-declined';
    }

    public function get_name() {
        return esc_html__('Your request has been declined', 'routiz');
    }

    /*
     * email
     *
     */
    public function get_email_subject() {
        return esc_html__( 'Your request has been declined', 'routiz' );
    }

    public function get_email_template() {
        return esc_html__( "Hello {user_display_name},\r\n\r\nYour request has been declined", 'routiz' );
    }

    /*
     * email admin
     *
     */
    public function get_email_admin_subject() {
        return esc_html__( 'A request has been declined', 'routiz' );
    }

    public function get_email_admin_template() {
        return esc_html__( "Hello,\r\n\r\nA request has been declined", 'routiz' );
    }

    /*
     * site
     *
     */
    public function get_site_icon() {
        return 'material-icon-thumb_down_alt';
    }

    public function get_site_message() {
        return esc_html__( 'Your request has been declined', 'routiz' );
    }

    public function get_site_url() {
        if( function_exists('wc_get_account_endpoint_url') ) {
            return add_query_arg( 'type', 'outgoing', wc_get_account_endpoint_url( 'entries' ) );
        }
    }

}
