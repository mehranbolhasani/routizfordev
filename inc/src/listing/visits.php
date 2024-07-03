<?php

namespace Routiz\Inc\Src\Listing;

class Visits {

    public $listing_id;

    function __construct( $listing_id ) {

        $this->listing_id = $listing_id;

    }

    public function has() {

        global $wpdb;

        $result = $wpdb->get_row(
            $wpdb->prepare("
                    SELECT *
                    FROM {$wpdb->prefix}routiz_visits
                    WHERE listing_id = %d
                    AND identity = %s
                    AND ip = %s
                ",
                $this->listing_id,
                $this->get_identity(),
                $this->get_ip()
            )
        );

        return $result;

    }

    public function add() {

        global $wpdb;

        $wpdb->insert( $wpdb->prefix . 'routiz_visits', [
            'listing_id' => $this->listing_id,
            'identity' => $this->get_identity(),
            'ip' => $this->get_ip(),
        ]);

    }

    public function get_ip() {

        if( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }elseif( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }elseif( ! empty( $_SERVER['REMOTE_ADDR'] ) ) {
            return $_SERVER['REMOTE_ADDR'];
        }else{
            return;
        }

    }

    public function get_identity() {
        return md5( $_SERVER['HTTP_USER_AGENT'] );
    }

}
