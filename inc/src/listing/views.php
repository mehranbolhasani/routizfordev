<?php

namespace Routiz\Inc\Src\Listing;

class Views {

    public $listing_id;
    public $visits;
    function __construct( $listing_id ) {

        $this->listing_id = $listing_id;
        $this->visits = new Visits( $listing_id );

    }

    public function add() {

        if( $this->visits->has() ) {
            return;
        }

        $this->visits->add();
        $this->insert();

    }

    public function get() {

        global $wpdb;

        $result = $wpdb->get_var(
            $wpdb->prepare("
                    SELECT id
                    FROM {$wpdb->prefix}routiz_views
                    WHERE listing_id = %d
                    AND datetime > %s
                    AND datetime < %s
                ",
                $this->listing_id,
                date('Y-m-d H:00:00'),
                date('Y-m-d H:59:59')
            )
        );

        return $result ? $result : null;

    }

    public function create_view() {

        global $wpdb;

        $wpdb->insert( $wpdb->prefix . 'routiz_views', [
            'listing_id' => $this->listing_id,
            'count' => 0,
            'datetime' => date('Y-m-d H:m:s')
        ]);

        return $wpdb->insert_id;

    }

    public function insert() {

        global $wpdb;

        $view_id = $this->get();

        if( ! $view_id ) {
            $view_id = $this->create_view();
        }

        $wpdb->query(
            $wpdb->prepare("
                    UPDATE {$wpdb->prefix}routiz_views
                    SET count = count + 1
                    WHERE id = %s
                ",
                $view_id
            )
        );

    }

    public function get_views_by_day( $days_back = 30 ) {

        global $wpdb;

        return $wpdb->get_results(
            $wpdb->prepare("
                    SELECT count, datetime
                    FROM {$wpdb->prefix}routiz_views
                    WHERE listing_id = %d
                    AND datetime >= %s
                ",
                $this->listing_id,
                date( 'Y-m-d', strtotime( sprintf( '-%s days', (int) $days_back ) ) )
            )
        );

    }

    public function get_total_views() {

        global $wpdb;

        return (int) $wpdb->get_var(
            $wpdb->prepare("
                    SELECT SUM( count )
                    FROM {$wpdb->prefix}routiz_views
                    WHERE listing_id = %d
                ",
                $this->listing_id
            )
        );

    }

    public function get_today_views() {

        global $wpdb;

        $today = date('Y-m-d');
        $tomorrow = date( 'Y-m-d', strtotime( '+1 days' ) );

        return (int) $wpdb->get_var(
            $wpdb->prepare("
                    SELECT count
                    FROM {$wpdb->prefix}routiz_views
                    WHERE listing_id = %d
                    AND datetime >= %s
                    AND datetime < %s
                ",
                $this->listing_id,
                $today,
                $tomorrow
            )
        );

    }

    static function get_all_today_views( $user_id = null, $date = null ) {

        global $wpdb;

        if( is_null( $user_id ) ) {
            $user_id = get_current_user_id();
        }

        if( is_null( $date ) ) {
            $date = date('Y-m-d');
        }

        $listing_ids = new \WP_Query([
            'post_type' => 'rz_listing',
            'posts_per_page' => 0,
            'fields' => 'ids',
            'author' => $user_id
        ]);

        $ids = implode( ',', $listing_ids->posts );

        if( $ids ) {
            return (int) $wpdb->get_var(
                $wpdb->prepare("
                        SELECT sum( count ) as total
                        FROM {$wpdb->prefix}routiz_views
                        WHERE listing_id IN ( {$ids} )
                        AND datetime >= %s
                    ",
                    $date
                )
            );
        }

        return 0;

    }

    static function get_all_views_by_day( $user_id = null, $days_back = 15 ) {

        global $wpdb;

        if( is_null( $user_id ) ) {
            $user_id = get_current_user_id();
        }

        $listing_ids = new \WP_Query([
            'post_type' => 'rz_listing',
            'posts_per_page' => 0,
            'fields' => 'ids',
            'author' => $user_id
        ]);

        $ids = implode( ',', $listing_ids->posts );

        if( $listing_ids->found_posts ) {
            $results = $wpdb->get_results(
                $wpdb->prepare("
                        SELECT sum( count ) as total, CAST(datetime AS DATE) as date
                        FROM {$wpdb->prefix}routiz_views
                        WHERE listing_id IN ( {$ids} )
                        AND datetime >= %s
                        GROUP BY date( datetime )
                    ",
                    date( 'Y-m-d', strtotime( sprintf( '-%s days', (int) $days_back ) ) )
                )
            );
        }else{
            $results = [];
        }

        $results = array_reverse( $results );
        $results_keys = $days = [];

        foreach( $results as $result ) {
            $results_keys[ $result->date ] = $result->total;
        }

        for( $i = 0; $i < $days_back; $i++ ) {
            $date = date( 'Y-m-d', strtotime( sprintf( '-%s days', $i ) ) );
            $days[] = (object) [
                'date' => $date,
                'total' => array_key_exists( $date, $results_keys ) ? $results_keys[ $date ] : 0,
            ];
        }

        return array_reverse( $days );

    }

}
