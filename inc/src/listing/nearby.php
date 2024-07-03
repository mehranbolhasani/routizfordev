<?php

namespace Routiz\Inc\Src\Listing;

class Nearby {

    public $id;
    public $listing_type_id;
    public $lat;
    public $lng;
    public $results_keyed;
    public $post_ids;
    public $distance = 5; // Default value
    public $limit = 3; // Default value
    public $measure;

    function __construct( $id ) {

        $this->id = $id;
        $this->listing_type_id = (int) Rz()->get_meta( 'rz_listing_type', $id );
        $this->lat = floatval( Rz()->get_meta( 'rz_location__lat', $id ) );
        $this->lng = floatval( Rz()->get_meta( 'rz_location__lng', $id ) );
        $this->distance = (int) Rz()->get_meta( 'rz_nearby_distance', Rz()->get_meta('rz_listing_type', $id ) );

        $this->measure = get_option('rz_measure_system') == 'metric' ? 1.609344 : 1;

    }

    public function query() {

        $post_ids = $this->extract_ids( $this->get() );

        if( ! $post_ids ) {
            $post_ids = [0];
        }

        $query = new \WP_Query([
            'post_type' => 'rz_listing',
            'post_status' => 'publish',
            'post__in' => $post_ids,
            'orderby' => 'post__in'
        ]);

        return $query;

    }

    public function extract_ids( $results ) {
        return wp_list_pluck( $results, 'post_id' );
    }

    public function get() {

        global $wpdb;

        $results = $wpdb->get_results("
            SELECT * FROM (
                SELECT *,
                    (
                        (
                            (
                                acos(
                                    sin(( {$this->lat} * pi() / 180))
                                    *
                                    sin(( lat * pi() / 180)) + cos(( {$this->lat} * pi() /180 ))
                                    *
                                    cos(( lat * pi() / 180)) * cos((( {$this->lng} - lng) * pi()/180)))
                            ) * 180/pi()
                        ) * 60 * 1.1515 * {$this->measure}
                    )
                as distance FROM (
                        SELECT * FROM (
                            SELECT LAT.meta_id, LAT.post_id, LAT.meta_value AS lat, LNG.meta_value AS lng FROM {$wpdb->postmeta} LNG
                            INNER JOIN {$wpdb->postmeta} LAT ON LAT.post_id = LNG.post_id
                            INNER JOIN {$wpdb->postmeta} TYPEE ON LAT.post_id = TYPEE.post_id
                            INNER JOIN {$wpdb->posts} POSTS ON LAT.post_id = POSTS.ID
                            WHERE LAT.meta_key = 'rz_location__lat'
                            AND LNG.meta_key = 'rz_location__lng'
                            AND LNG.post_id != {$this->id}
                            AND POSTS.post_status = 'publish'
                            AND TYPEE.meta_value = {$this->listing_type_id}
                        ) {$wpdb->postmeta}
                    ) as meta_locations
                ) meta_locations
            WHERE distance <= {$this->distance}
            ORDER BY distance ASC
            LIMIT {$this->limit}
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

    public function get_distance_between_locations( $lat1, $lng1, $lat2, $lng2, $unit = 'km' ) {

        $theta = $lng1 - $lng2;
        $distance = sin( deg2rad( $lat1 ) ) * sin( deg2rad( $lat2 ) ) + cos( deg2rad( $lat1 ) ) * cos( deg2rad( $lat2 ) ) * cos( deg2rad( $theta ) );

        $distance = acos( $distance );
        $distance = rad2deg( $distance );
        $distance = $distance * 60 * 1.1515;

        switch( $unit ) {
            case 'mi': break;
            case 'km' : $distance = $distance * 1.609344;
        }

        return round( $distance, 2 );

    }

}
