<?php

namespace Routiz\Inc\Src\Notification\Notifications;

class New_Purchase extends \Routiz\Inc\Src\Notification\Base {

    public $user_can_manage = true;

    /*
     * general
     *
     */
    public function get_id() {
        return 'new-purchase';
    }

    public function get_name() {
        return esc_html__('New purchase has been received', 'routiz');
    }

    /*
     * email
     *
     */
    public function get_email_subject() {
        return esc_html__( 'New purchase has been received', 'routiz' );
    }

    public function get_email_template() {
        return esc_html__( "Hello {user_display_name},\r\n\rA new purchase has been received.", 'routiz' );
    }

    /*
     * email admin
     *
     */
    public function get_email_admin_subject() {
        return esc_html__( 'New purchase has been received', 'routiz' );
    }

    public function get_email_admin_template() {
        return esc_html__( "Hello,\r\n\rA new purchase has been received.", 'routiz' );
    }

    /*
     * site
     *
     */
    public function get_site_icon() {
        return 'material-icon-attach_money';
    }

    public function get_site_message() {
        return esc_html__( 'New purchase has been received', 'routiz' );
    }

    public function get_site_url() {
        if( function_exists('wc_get_account_endpoint_url') ) {
            return wc_get_account_endpoint_url( 'entries' );
        }
    }

}
