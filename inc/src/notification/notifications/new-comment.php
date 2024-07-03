<?php

namespace Routiz\Inc\Src\Notification\Notifications;

class New_Comment extends \Routiz\Inc\Src\Notification\Base {

    public $user_can_manage = true;

    /*
     * general
     *
     */
    public function get_id() {
        return 'new-comment';
    }

    public function get_name() {
        return esc_html__('New comment has been received', 'routiz');
    }

    /*
     * email
     *
     */
    public function get_email_subject() {
        return esc_html__( 'New comment has been received', 'routiz' );
    }

    public function get_email_template() {
        return esc_html__( "Hello {user_display_name},\r\n\r\nYou have received a new comment.", 'routiz' );
    }

    /*
     * email admin
     *
     */
    public function get_email_admin_subject() {
        return esc_html__( 'New comment', 'routiz' );
    }

    public function get_email_admin_template() {
        return esc_html__( "Hello,\r\n\r\nA new comment has been sent", 'routiz' );
    }

    /*
     * site
     *
     */
    public function get_site_icon() {
        return 'material-icon-stargrade';
    }

    public function get_site_message() {
        return esc_html__( 'New comment has been received', 'routiz' );
    }

    public function get_site_url() {
        if( isset( $this->meta['listing_id'] ) ) {
            return get_permalink( $this->meta['listing_id'] );
        }
        return null;
    }

}
