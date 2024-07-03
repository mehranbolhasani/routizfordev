<?php

namespace Routiz\Inc\Src\Notification\Notifications;

class Welcome extends \Routiz\Inc\Src\Notification\Base {

    /*
     * general
     *
     */
    public function get_id() {
        return 'welcome';
    }

    public function get_name() {
        return esc_html__('Welcome aboard!', 'routiz');
    }

    /*
     * email
     *
     */
    public function get_email_subject() {
        return esc_html__( 'Welcome aboard', 'routiz' );
    }

    public function get_email_template() {
        return esc_html__( "Hello {user_display_name},\r\n\r\nThank you for joining us!", 'routiz' );
    }

    /*
     * email admin
     *
     */
    public function get_email_admin_subject() {
        return esc_html__( 'New user', 'routiz' );
    }

    public function get_email_admin_template() {
        return esc_html__( "Hello,\r\n\r\nNew user has joined the family!", 'routiz' );
    }

    /*
     * site
     *
     */
    public function get_site_icon() {
        return 'material-icon-favorite';
    }

    public function get_site_message() {
        return esc_html__( 'Welcome aboard {user_display_name}!', 'routiz' );
    }

    public function get_site_url() {
        return null;
    }

}
