<?php

namespace Routiz\Inc\Src;

class Shortcodes {

    use \Routiz\Inc\Src\Traits\Singleton;

    function __construct() {

        add_action( 'init', [ $this, 'register_shortcodes' ] );

    }

    public function register_shortcodes() {

        // quick search
        add_shortcode( 'rz-search-quick', [ $this, 'shortcode_search_quick' ] );

        // simple search form
        add_shortcode( 'rz-search-form', [ $this, 'shortcode_search_form' ] );

        // notifications
        add_shortcode( 'rz-notifications', [ $this, 'shortcode_notifications' ] );

        // favorites
        add_shortcode( 'rz-favorites', [ $this, 'shortcode_favorites' ] );

        // user action
        add_shortcode( 'rz-user-action', [ $this, 'shortcode_user_action' ] );

        // user action
        add_shortcode( 'rz-wallet-balance', [ $this, 'shortcode_wallet_balance' ] );

        // social icons
        add_shortcode( 'rz-social-icons', [ $this, 'shortcode_social_icons' ] );

    }

    public function shortcode_search_quick() {
        return Rz()->get_template('routiz/search/quick/quick');
    }

    public function shortcode_search_form( $atts ) {

        global $rz_atts;
        $rz_atts = $atts;

        return Rz()->get_template('routiz/search/form');

    }

    public function shortcode_notifications() {
        return Rz()->get_template('routiz/globals/notifications');
    }

    public function shortcode_favorites() {
        return Rz()->get_template('routiz/globals/favorites/favorites');
    }

    public function shortcode_user_action() {
        return Rz()->get_template('routiz/globals/user-action');
    }

    public function shortcode_wallet_balance() {
        return Rz()->get_template('routiz/globals/wallet-balance');
    }

    public function shortcode_social_icons() {
        return Rz()->get_template('routiz/globals/social-icons');
    }

}
