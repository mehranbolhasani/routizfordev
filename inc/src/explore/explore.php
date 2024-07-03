<?php


namespace Routiz\Inc\Src\Explore;

use \Routiz\Inc\Src\Traits\Singleton;
use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Listing_Type\Listing_Type;
use \Routiz\Inc\Src\User;

class Explore {

    use Singleton;

    // Define properties explicitly to avoid deprecation warnings
    public $request;
    public $type;
    public $mods;
    public $query = null;
    public $user;
    public $is_explore = false;
    public $total_types = 0;
    public $map_provider;
    public $component;

    function __construct() {

        Search\Init::instance();

        $this->map_provider = $this->get_map_provider();
        $this->request = Request::instance();
        $this->component = Filter\Component::instance();
        $this->user = new User();
        $this->is_explore = Rz()->is_explore();

        $this->build();

    }

    public function __call( $function, $args ) {

        if( $function == 'query' ) {

            if( $this->query === null ) {
                $this->query = Query::instance();
                $this->query->add_query( $this->get_query() );
                $this->query
                    ->add_meta_query( 'filters', $this->get_meta_query() )
                    ->fetch();

            }

            return $this->query;

        }
    }

    public function build() {

        global $wpdb;

        if(Rz()->is_explore()){

            $type_ids = $wpdb->get_results("
            SELECT ID FROM $wpdb->posts
            WHERE post_status = 'publish'
            AND post_type = 'rz_listing_type'
        ");
        }
        else
        {
            // get total listing types
        $type_ids = $wpdb->get_results("
            SELECT ID FROM $wpdb->posts p
            RIGHT JOIN $wpdb->postmeta pm ON (
                pm.post_id = p.ID
                AND pm.meta_key = 'rz_disable_user_submission'
                OR pm.meta_value = ''
            )
            WHERE p.post_status = 'publish'
            AND p.post_type = 'rz_listing_type'
        ");
        }

        

        $total_types = count( $type_ids );

        // no types
        if( $total_types == 0 ) {
            // ..
        }
        // default type
        elseif( $total_types == 1 ) {

            $this->type = new Listing_Type( $type_ids[0]->ID );

            // set main listing type
            $this->main_type = $type_ids[0]->ID;

        }
        // multiple types
        else{

            // type in request
            if( ! $this->request->is_empty('type') ) {
                $query = new \WP_Query([
                    'post_status' => 'publish',
                    'post_type' => 'rz_listing_type',
                    'meta_query' => [
                        [
                            'key' => 'rz_slug',
                            'value' => $this->request->get('type'),
                            'compare' => '=',
                        ]
                    ]
                ]);
                if( $query->found_posts ) {
                    $this->type = new Listing_Type( $this->request->get('type') );
                }
            }
            // multiple types
            else{
                // ..
            }

        }

        $this->total_types = $total_types;

    }

    public function get_display_type() {

        if( ! $this->type ) {
            $global_explore_type = get_option('rz_global_explore_type');
            return $global_explore_type ? $global_explore_type : 'map';
        }

        $display_type = esc_html( $this->type->get('rz_display_explore_type') );

        if( empty( $display_type ) ) {
            $display_type = 'map';
        }

        return $display_type;

    }

    public function get_meta_query() {

        $meta_query = [];

        // type
        if( $this->type ) {
            $meta_query[] = [
                'key' => 'rz_listing_type',
                'value' => $this->type->id,
            ];
        }
        // global
        else{
            $global_listing_types = new \WP_Query([
                'post_status' => 'publish',
                'post_type' => 'rz_listing_type',
                'posts_per_page' => -1,
                'fields' => 'ids',
                'meta_query' => [
                    'relation' => 'OR',
                    [
                        'key' => 'rz_hide_global',
                        'value' => '1',
                        'compare' => '!=',
                    ],
                    [
                        'key' => 'rz_hide_global',
                        'value' => '1',
                        'compare' => 'NOT EXISTS',
                    ]
                ]
            ]);

            if( $global_listing_types ) {
                $meta_query[] = [
                    'key' => 'rz_listing_type',
                    'value' => $global_listing_types->posts,
                ];
            }
        }

        $filters = [];

        /*
         * inline listing filters
         *
         */
        $search_form = $this->type ? Rz()->get_meta( 'rz_search_form', $this->type->id ) : get_option('rz_global_search_form');
        $search_form_more = $this->type ? Rz()->get_meta( 'rz_search_form_more', $this->type->id ) : get_option('rz_global_search_form_more');
        $primary_search_form = get_option( 'rz_primary_search_form' );

        if( $search_form ) {

            // search form
            $search_filters = Rz()->json_decode( Rz()->get_meta( 'rz_search_fields', $search_form ) );
            if( empty( $search_filters ) or ! is_array( $search_filters ) ) {
                $search_filters = [];
            }

            // search form more
            $search_filters_more = Rz()->json_decode( Rz()->get_meta( 'rz_search_fields', $search_form_more ) );
            if( empty( $search_filters_more ) or ! is_array( $search_filters_more ) ) {
                $search_filters_more = [];
            }

            // primary search
            $search_filters_primary = Rz()->json_decode( Rz()->get_meta( 'rz_search_fields', $primary_search_form ) );
            if( empty( $search_filters_primary ) or ! is_array( $search_filters_primary ) ) {
                $search_filters_primary = [];
            }

            $search_filters_all = array_merge( $search_filters, $search_filters_more, $search_filters_primary );

            foreach( $search_filters_all as $filter ) {

                if( ! isset( $filter->fields->id ) ) {
                    continue;
                }

                // rz_type will be handled as rz_listing_type
                if( $filter->fields->id == 'rz_type' ) {
                    continue;
                }

                if( in_array( $filter->fields->id, [
                    'post_title',
                    'post_content',
                ])) {
                    continue;
                }

                $filter->fields->id = Rz()->prefix( $filter->fields->id );

                if( in_array( $filter->fields->id, $filters ) ) {
                    continue;
                }

                $filters[] = $filter->fields->id;

                $filter = $this->component->create( array_merge( (array) $filter->fields, [
                    'type' => $filter->template->id
                ]));

                if( ! Rz()->is_error( $filter ) ) {

                    if( $filter->is_requested() ) {
                        $query = $filter->query();
                        if( ! empty( $query ) ) {
                            $meta_query[] = $query;
                        }
                    }
                }
            }
        }

        return $meta_query;

    }

    public function get_query() {

        $meta_query = [];
        $filters = [];

        /*
         * inline listing filters
         *
         */
        $search_form = $this->type ? Rz()->get_meta( 'rz_search_form', $this->type->id ) : get_option('rz_global_search_form');

        if( $search_form ) {

            $search_filters = Rz()->json_decode( Rz()->get_meta( 'rz_search_fields', $search_form ) );
            if( empty( $search_filters ) or ! is_array( $search_filters ) ) {
                $search_filters = [];
            }

            $search_filters_more = Rz()->json_decode( Rz()->get_meta( 'rz_search_fields_more', $search_form ) );
            if( empty( $search_filters_more ) or ! is_array( $search_filters_more ) ) {
                $search_filters_more = [];
            }

            $search_filters_all = array_merge( $search_filters, $search_filters_more );

            foreach( $search_filters_all as $filter ) {

                if( ! isset( $filter->fields->id ) ) {
                    continue;
                }

                // rz_type will be handled as rz_listing_type
                if( $filter->fields->id == 'rz_type' ) {
                    continue;
                }

                if( in_array( $filter->fields->id, [
                    'post_title',
                    'post_content',
                ])) {
                    continue;
                }

                $filter->fields->id = Rz()->prefix( $filter->fields->id );

                if( in_array( $filter->fields->id, $filters ) ) {
                    continue;
                }

                $filters[] = $filter->fields->id;

                $filter = $this->component->create( array_merge( (array) $filter->fields, [
                    'type' => $filter->template->id
                ]));

                if( ! Rz()->is_error( $filter ) ) {

                    if( $filter->is_requested() ) {
                        $query = $filter->main_query();
                        if( ! empty( $query ) ) {
                            $meta_query = array_merge( $meta_query, $query );
                        }
                    }
                }
            }
        }

        return $meta_query;

    }

    public function get_map_provider() {
        $map_provider = get_option('rz_map_provider');
        if( empty( $map_provider ) ) {
            $map_provider = 'google';
        }
        return $map_provider;
    }

}
