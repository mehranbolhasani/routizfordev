<?php

namespace Routiz\Inc\Src\Notification\Notifications;

class New_Message extends \Routiz\Inc\Src\Notification\Base {

    public $user_can_manage = true;

    /*
     * general
     *
     */
    public function get_id() {
        return 'new-message';
    }

    public function get_name() {
        return esc_html__('New message received', 'routiz');
    }

    /*
     * email
     *
     */
    public function get_email_subject() {
        return esc_html__( 'You have received a new message', 'routiz' );
    }

    public function get_email_template() {
        return esc_html__( "Hello {user_display_name},\r\n\r\nYou have received a new message from {from_user_display_name}.", 'routiz' );
    }

    /*
     * email admin
     *
     */
    public function get_email_admin_subject() {
        return esc_html__( 'A new message was sent', 'routiz' );
    }

    public function get_email_admin_template() {
        return esc_html__( "Hello,\r\n\r\nA new message was sent from {from_user_display_name} to {user_display_name}.", 'routiz' );
    }

    /*
     * site
     *
     */
    public function get_site_icon() {
        return 'material-icon-chat';
    }

    public function get_site_message() {

        $sender_name = null;
        if( $this->meta['from_user_id'] ) {
            if( $userdata = get_userdata( $this->meta['from_user_id'] ) ) {
                $sender_name = $userdata->display_name;
            }
        }

        return $sender_name ? sprintf( esc_html__( 'You have received a new message from %s', 'routiz' ), $sender_name ) : esc_html__( 'You have received a new message', 'routiz' );
    }

    public function get_site_url() {
        if( function_exists('wc_get_account_endpoint_url') ) {
            return wc_get_account_endpoint_url( 'messages' );
        }
    }

}
