<?php

namespace Routiz\Inc\Src\Explore\Filter\Modules\Geo;

use \Routiz\Inc\Src\Explore\Filter\Modules\Module;
use \Routiz\Inc\Src\Explore\Filter\Comparison;

class Geo extends Module {

    public $measure;

    public function main_query() {

        if(
            ! isset( $_GET['geo_n'] ) or
            ! isset( $_GET['geo_e'] ) or
            ! isset( $_GET['geo_s'] ) or
            ! isset( $_GET['geo_w'] ) )
        {
            return [];
        }

        $this->geo_n = floatval( $_GET['geo_n'] );
        $this->geo_e = floatval( $_GET['geo_e'] );
        $this->geo_s = floatval( $_GET['geo_s'] );
        $this->geo_w = floatval( $_GET['geo_w'] );

        $listing_ids = $this->get_nearby_listing_ids();

        if( ! $listing_ids ) {
            $listing_ids = [0];
        }

        return [
            'post__in' => $listing_ids
        ];

    }

    public function get_nearby_listing_ids() {
        return $this->extract_ids( $this->get_by_lat_lng() );
    }

    public function extract_ids( $results ) {
        return wp_list_pluck( $results, 'post_id' );
    }

    public function get_by_lat_lng() {

        global $wpdb;

        $select_markers = "
        (
            SELECT LAT.meta_id, LAT.post_id, LAT.meta_value AS lat, LNG.meta_value AS lng
            FROM {$wpdb->postmeta} LNG
            INNER JOIN {$wpdb->postmeta} LAT ON LAT.post_id = LNG.post_id
            INNER JOIN {$wpdb->posts} POSTS ON LAT.post_id = POSTS.ID
            WHERE LAT.meta_key = 'rz_location__lat'
            AND LNG.meta_key = 'rz_location__lng'
            AND POSTS.post_status = 'publish'
        )
        ";

        $results = $wpdb->get_results("
            SELECT * FROM {$select_markers} as pin
            WHERE
            pin.lat > {$this->geo_s} AND
            pin.lat < {$this->geo_n} AND
            pin.lng > {$this->geo_w} AND
            pin.lng < {$this->geo_e}

            UNION

            SELECT * FROM {$select_markers} as pin
            WHERE
            pin.lat > {$this->geo_s} AND
            pin.lat < {$this->geo_n} AND
            pin.lng > {$this->geo_w} AND
            pin.lng < {$this->geo_e}
        ");

        $results_keyed = $post_ids = [];
        foreach( $results as $row ) {
            $results_keyed[ $row->post_id ] = $row;
            $post_ids[] = $row->post_id;
        }

        $this->results_keyed = $results_keyed;
        $this->post_ids = $post_ids;

        return $results;

    }

    public function query() {
        return [];
    }

    public function get() {

        $this->before_get();

        $field = $this->component->form->create( (array) $this->props );

        if( ! $field instanceof \Routiz\Inc\Src\Error ) {
            return sprintf( $this->wrapper(), $this->props->type, $field->get() );
        }

    }

}
