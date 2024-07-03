<?php

namespace Routiz\Inc\Src\Explore;

use \Routiz\Inc\Src\Traits\Singleton;
use \Routiz\Inc\Src\Request\Request;

class Query {

    use Singleton;

    public $posts_per_page;
    public $request;
    public $posts;
    public $page;
    public $query = [];
    public $meta_query = [
        'relation' => 'OR'
    ];

    function __construct() {

        global $rz_explore;

        $this->request = Request::instance();
        $this->page = $this->request->has('onpage') ? $this->request->get('onpage') : 1;

        if( $rz_explore->type ) {
            $this->posts_per_page = (int) $rz_explore->type->get('rz_listings_per_page');
        }else{
            $global_listings_per_page = (int) get_option('rz_global_listings_per_page');
            $this->posts_per_page = $global_listings_per_page ? $global_listings_per_page : (int) get_option( 'posts_per_page' );
        }

    }

    /*
     * listing sorting
     *
     */
    public function sorting() {

        $type = get_option('rz_promoted_listings_display');

        if( empty( $type ) ) {
            $type = 'inline';
        }

        // by priority
        if( $type == 'inline' ) {

            return [
                'meta_key' => 'rz_priority',
                'orderby' => [
                    'meta_value_num' => 'DESC',
                    'date' => 'DESC',
                ],
                'order' => 'DESC'
            ];
        }

        return [];

    }

    public function paging() {

        if( $this->request->has('onpage') ) {
            return [
                'offset' => ( $this->page - 1 ) * $this->posts_per_page
            ];
        }

    }

    public function post_title() {

        if( $this->request->has('post_title') ) {
            add_filter( 'posts_where', [ $this, 'where_title' ], 10, 2 );
            return [
                'rz_search_post_title' => $this->request->get('post_title')
            ];
        }

    }

    public function post_content() {

        if( $this->request->has('post_content') ) {
            add_filter( 'posts_where', [ $this, 'where_content' ], 10, 2 );
            return [
                'rz_search_post_content' => $this->request->get('post_content')
            ];
        }

    }

    public function search_term() {

        if( $this->request->has('search_term') ) {
            add_filter( 'posts_where', [ $this, 'where_term' ], 10, 2 );
            return [
                'rz_search_post_term' => $this->request->get('search_term')
            ];
        }
    }

    public function booking_availability() {

        if( $this->request->has('booking_dates') ) {
            $dates = [];
            $booking_dates = $this->request->get('booking_dates');
            if( is_array( $booking_dates ) && isset( $booking_dates[0] ) && isset( $booking_dates[1] ) ) {
                $start = (int) $booking_dates[0];
                $end = (int) $booking_dates[1];

                if( $start <= 0 ) {
                    return;
                }

                if( $end <= 0 ) {
                    $dates = [ $start ];
                }else{
                    if( $start < $end ) {
                        $check_in = new \DateTime( '@' . $start );
                        $check_in_unix = $check_in->getTimestamp();

                        $check_out = new \DateTime( '@' . $end );
                        $check_out_unix = $check_out->getTimestamp();

                        $check_in_unix = $check_in->getTimestamp();

                        $break = 0;
                        while( $check_in_unix < $check_out_unix ) {
                            // safe break
                            if( $break >= 999 ) {
                                break;
                            }
                            $dates[] = $check_in_unix;
                            $check_in->modify('tomorrow');
                            $check_in_unix = $check_in->getTimestamp();
                            $break += 1;
                        }
                    }
                }
            }

            $wp_query = new \WP_Query([
                'post_type' => 'rz_listing',
                'posts_per_page' => '-1',
                'post_status' => 'publish',
            ]);

            $exclude_post_ids = [];
            if( $wp_query->have_posts() ) {
                while( $wp_query->have_posts() ) { $wp_query->the_post();
                    $availability_dates = get_post_meta( get_the_ID(), 'rz_availability_dates', false );
                    $booking_unavailable = get_post_meta( get_the_ID(), 'rz_booking_unavailable', true );
                    $continue = true;
                    foreach( $availability_dates as $unavailable_date ) {
                        if( in_array( $unavailable_date, $dates ) ) {
                            $exclude_post_ids[] = get_the_ID();
                            $continue = false;
                            break;
                        }
                    }
                    if( $continue && $booking_unavailable ) {
                        foreach( $booking_unavailable as $unavailable_date ) {
                            if( in_array( $unavailable_date, $dates ) ) {
                                $exclude_post_ids[] = get_the_ID();
                                break;
                            }
                        }
                    }
                }
                wp_reset_postdata();
            }

            return [
                'post__not_in' => $exclude_post_ids
            ];
        }

    }

    public function get() {

        $this->add_query( $this->sorting() )
        ->add_query( $this->paging() )
        ->add_query( $this->post_title() )
        ->add_query( $this->post_content() )
        ->add_query( $this->search_term() )
        ->add_query( $this->booking_availability() )
        ->add_query([
            'post_status' => 'publish',
            'post_type' => 'rz_listing',
            'posts_per_page' => $this->posts_per_page,
            'meta_query' => $this->meta_query,
        ]);

        // dd( $this->query );exit;

    }

    public function add_query( $query ) {

        if( ! $query ) {
            return $this;
        }

        $this->query = array_merge( $this->query, $query );

        return $this;

    }

    public function add_meta_query( $id = null, $meta_query = [], $relation = 'AND' ) {

        if( ! $meta_query ) {
            return $this;
        }

        // main meta query
        if( is_null( $id ) ) {

            $this->meta_query = array_merge( $this->meta_query, $meta_query );
            return $this;

        }

        // sub meta query
        if( ! array_key_exists( $id, $this->meta_query ) ) {
            $this->meta_query[ $id ] = [
                'relation' => $relation
            ];
        }

        $this->meta_query[ $id ] = array_merge( $this->meta_query[ $id ], $meta_query );

        return $this;

    }

    public function fetch() {

        $this->get();

        $this->posts = new \WP_Query( $this->query );
        $this->clear_filters();

    }

    public function clear_filters() {

        remove_filter( 'posts_where', [ $this, 'where_title' ], 10 );
        remove_filter( 'posts_where', [ $this, 'where_content' ], 10 );
        remove_filter( 'posts_where', [ $this, 'where_term' ], 10 );

    }

    /*
     * search post title
     *
     */
    function where_title( $where, $wp_query ) {

        global $wpdb;

        if( $search_term = $wp_query->get('rz_search_post_title') ) {
            $where .= " AND {$wpdb->posts}.post_title LIKE '%" . stripslashes( $wpdb->esc_like( $search_term ) ) . "%'";
        }

        return $where;

    }

    /*
     * search post content
     *
     */
    function where_content( $where, $wp_query ) {

        global $wpdb;

        if( $search_term = $wp_query->get('rz_search_post_content') ) {
            $where .= " AND {$wpdb->posts}.post_content LIKE '%" . stripslashes( $wpdb->esc_like( $search_term ) ) . "%'";
        }

        return $where;

    }

    /*
     * search post term ( title + content )
     *
     */
    function where_term( $where, $wp_query ) {

        global $wpdb;

        if( $search_term = $wp_query->get( 'rz_search_post_term' ) ) {
            $where .= " AND ( {$wpdb->posts}.post_content LIKE '%" . $wpdb->esc_like( $search_term ) . "%' OR {$wpdb->posts}.post_title LIKE '%" . $wpdb->esc_like( $search_term ) . "%')";
        }

        return $where;

    }

}
