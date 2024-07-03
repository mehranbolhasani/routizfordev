<?php

namespace Routiz\Inc\Src\Notification\Notifications;

class New_Claim extends \Routiz\Inc\Src\Notification\Base {

    public $user_can_manage = false;

    /*
     * general
     *
     */
    public function get_id() {
        return 'new-claim';
    }

    public function get_name() {
        return esc_html__('Business claim has been generated', 'routiz');
    }

    /*
     * email
     *
     */
    public function get_email_subject() {
        return esc_html__( 'Business claim has been generated', 'routiz' );
    }

    public function get_email_template() {
        return esc_html__( "Hello {user_display_name},\r\n\rA business claim has been generated.", 'routiz' );
    }

    /*
     * email admin
     *
     */
    public function get_email_admin_subject() {
        return esc_html__( 'Business claim has been generated', 'routiz' );
    }

    public function get_email_admin_template() {
        return esc_html__( "Hello,\r\n\rA business claim has been generated.", 'routiz' );
    }

    /*
     * site
     *
     */
    public function get_site_icon() {
        return 'material-icon-storefront';
    }

    public function get_site_message() {
        return esc_html__( 'New business claim has been generated', 'routiz' );
    }

    public function get_site_url() {
        return null;
    }

}
