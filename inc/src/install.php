<?php

namespace Routiz\Inc\Src;

use \Routiz\Inc\Src\Traits\Singleton;

class Install {

    use Singleton;

    public $version = '1.0';

    function __construct() {

        register_activation_hook( RZ_PLUGIN, [ $this, 'activation' ] );
        register_deactivation_hook( RZ_PLUGIN, [ $this, 'deactivation' ] );

    }

    public function activation( $network_wide ) {

        /*
         * multisite
         *
         */
        if( is_multisite() && $network_wide ) {

            $sites = get_sites([
                'fields' => 'ids'
            ]);

            foreach( $sites as $blog_id ) {
                switch_to_blog( $blog_id );
                $this->install();
                restore_current_blog();
            }

        }
        /*
         * single site
         *
         */
        else{

            $this->install();

        }
    }

    public function deactivation( $network_wide ) {

        /*
         * multisite
         *
         */
        if( is_multisite() && $network_wide ) {

            $sites = get_sites([
                'fields' => 'ids'
            ]);

            foreach( $sites as $blog_id ) {
                switch_to_blog( $blog_id );
                $this->uninstall();
                restore_current_blog();
            }

        }
        /*
         * single site
         *
         */
        else{

            $this->uninstall();

        }
    }

    public function install() {

        $this->create_table_notifications();
        $this->create_table_messages();
        $this->create_table_user_packages();
        $this->create_table_wallet();
        $this->create_table_wallet_transactions();
        $this->create_table_wallet_payouts();
        $this->create_table_visits();
        $this->create_table_views();
        $this->update_db_version();
        $this->schedule_events();

    }

    public function uninstall() {

        $this->unschedule_events();

    }

    public function schedule_events() {

        if ( ! wp_next_scheduled( 'routiz_check_for_expired_listings' ) ) {
			wp_schedule_event( time(), 'hourly', 'routiz_check_for_expired_listings' );
		}

        if ( ! wp_next_scheduled( 'routiz_check_for_expired_promotions' ) ) {
			wp_schedule_event( time(), 'hourly', 'routiz_check_for_expired_promotions' );
		}

        if ( ! wp_next_scheduled( 'routiz_reset_visits' ) ) {
			wp_schedule_event( time(), 'hourly', 'routiz_reset_visits' );
		}

        if ( ! wp_next_scheduled( 'routiz_check_for_pending_payment_bookings' ) ) {
			wp_schedule_event( time(), 'hourly', 'routiz_check_for_pending_payment_bookings' );
		}

        if ( ! wp_next_scheduled( 'routiz_scheduled_ical_sync' ) ) {
			wp_schedule_event( time(), 'hourly', 'routiz_scheduled_ical_sync' );
		}

        /*if ( ! wp_next_scheduled( 'routiz_check_for_expired_bookings' ) ) {
			wp_schedule_event( time(), 'hourly', 'routiz_check_for_expired_bookings' );
		}*/

	}

	public function unschedule_events() {
		wp_clear_scheduled_hook( 'routiz_check_for_expired_listings' );
		wp_clear_scheduled_hook( 'routiz_check_for_expired_promotions' );
		wp_clear_scheduled_hook( 'routiz_reset_visits' );
		wp_clear_scheduled_hook( 'routiz_check_for_pending_payment_bookings' );
		wp_clear_scheduled_hook( 'routiz_scheduled_ical_sync' );
		// wp_clear_scheduled_hook( 'routiz_check_for_expired_bookings' );
	}

    public function create_table_notifications() {

        global $wpdb;

        $table_name = $wpdb->prefix . 'routiz_notifications';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "
            CREATE TABLE $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                user_id mediumint(9) NOT NULL,
                code tinytext NOT NULL,
                meta longtext NULL,
                active boolean DEFAULT 1 NOT NULL,
                created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
                PRIMARY KEY (id)
            ) $charset_collate;
        ";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    }

    public function create_table_messages() {

        global $wpdb;

        $table_name = $wpdb->prefix . 'routiz_messages';
        $charset_collate = $wpdb->get_charset_collate();

        // TODO: rename system

        $sql = "
            CREATE TABLE $table_name (
                id int(9) NOT NULL AUTO_INCREMENT,
                conversation_id int(9) NOT NULL,
                sender_id int(9) NOT NULL,
                text longtext NULL,
                system int(9) NULL,
                active boolean DEFAULT 1 NOT NULL,
                created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
                PRIMARY KEY (id)
            ) $charset_collate;
        ";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    }

    public function create_table_user_packages() {

        global $wpdb;

        $table_name = $wpdb->prefix . 'routiz_user_packages';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "
            CREATE TABLE $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                user_id mediumint(9) NOT NULL,
                order_id mediumint(9) NOT NULL,
                package_id mediumint(9) NOT NULL,
                package_duration mediumint(9) NOT NULL,
                package_limit mediumint(9) NOT NULL,
                listings_attached mediumint(9) DEFAULT 0 NOT NULL,
                created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
                PRIMARY KEY (id)
            ) $charset_collate;
        ";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    }

    public function create_table_wallet() {

        global $wpdb;

        $table_name = $wpdb->prefix . 'routiz_wallet';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "
            CREATE TABLE $table_name (
                user_id mediumint(9) NOT NULL,
                balance text NOT NULL,
                spent text NOT NULL,
                status varchar(255) DEFAULT 'active' NOT NULL,
                PRIMARY KEY (user_id)
            ) $charset_collate;
        ";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    }

    public function create_table_wallet_transactions() {

        global $wpdb;

        $table_name = $wpdb->prefix . 'routiz_wallet_transactions';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "
            CREATE TABLE $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                user_id mediumint(9) NOT NULL,
                order_id mediumint(9) NULL,
                type varchar(255) NOT NULL,
                source varchar(255) NULL,
                amount TEXT NOT NULL,
                created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
                PRIMARY KEY (id)
            ) $charset_collate;
        ";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    }

    public function create_table_wallet_payouts() {

        global $wpdb;

        $table_name = $wpdb->prefix . 'routiz_wallet_payouts';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "
            CREATE TABLE $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                user_id mediumint(9) NOT NULL,
                amount text NOT NULL,
                payment_method text NOT NULL,
                address text NOT NULL,
                status varchar(255) NOT NULL,
                created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
                PRIMARY KEY (id)
            ) $charset_collate;
        ";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    }

    public function create_table_visits() {

        global $wpdb;

        $table_name = $wpdb->prefix . 'routiz_visits';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "
            CREATE TABLE $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                listing_id mediumint(9) NOT NULL,
                identity varchar(64) NOT NULL,
                ip varchar(32) NULL,
                PRIMARY KEY (id)
            ) $charset_collate;
        ";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    }

    public function create_table_views() {

        global $wpdb;

        $table_name = $wpdb->prefix . 'routiz_views';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "
            CREATE TABLE $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                listing_id mediumint(9) NOT NULL,
                count mediumint(9) NOT NULL,
                datetime datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
                PRIMARY KEY (id)
            ) $charset_collate;
        ";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    }

    public function update_db_version() {
        add_option( 'routiz_db_version', $this->version );
    }

}