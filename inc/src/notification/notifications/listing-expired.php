<?php

namespace Routiz\Inc\Src\Notification\Notifications;

class Listing_Expired extends \Routiz\Inc\Src\Notification\Base {

    public $user_can_manage = true;

    /*
     * general
     *
     */
    public function get_id() {
        return 'listing-expired';
    }

    public function get_name() {
        return esc_html__('Listing expired', 'routiz');
    }

    /*
     * email
     *
     */
    public function get_email_subject() {
        return esc_html__( 'Listing expired', 'routiz' );
    }

    public function get_email_template() {
        return esc_html__( "Hello {user_display_name},\r\n\r\nYour listing has expired", 'routiz' );
    }

    /*
     * email admin
     *
     */
    public function get_email_admin_subject() {
        return esc_html__( 'Listing expired', 'routiz' );
    }

    public function get_email_admin_template() {
        return esc_html__( "Hello,\r\n\r\nA listing has expired", 'routiz' );
    }

    /*
     * site
     *
     */
    public function get_site_icon() {
        return 'material-icon-timer_off';
    }

    public function get_site_message() {

        $listing_name = null;
        if( isset( $this->meta['listing_id'] ) ) {
            $listing_name = get_the_title( $this->meta['listing_id'] );
        }

        return $listing_name ? sprintf( esc_html__( 'Your listing %s has expired', 'routiz' ), $listing_name ) : esc_html__( 'Your listing has expired', 'routiz' );

    }

    public function get_site_url() {
        if( function_exists('wc_get_account_endpoint_url') ) {
            return wc_get_account_endpoint_url( 'listings' );
        }
    }

}
