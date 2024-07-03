<?php

namespace Routiz\Inc\Src\Listing_Type\Modules\Listings;

use \Routiz\Inc\Src\Listing_Type\Modules\Module;
use \Routiz\Inc\Src\Listing_Type\Listing_Type;

class Listings extends Module {

    public function controller() {

        $listing_type = new Listing_Type( get_queried_object()->ID );

        $meta_query = [
            'relation' => 'AND',
            [
                'key' => 'rz_listing_type',
                'value' => $listing_type->id,
                'compare' => '=',
            ]
        ];

        $url_params = [
            'type' => $listing_type->get('rz_slug')
        ];

        if( $this->props->terms ) {

            $terms = $this->props->terms;

            $meta_query[] = [
                'key' => $terms->taxonomy,
                'value' => $terms->term,
                'compare' => 'IN'
            ];
            $url_params[ $terms->taxonomy ] = Rz()->term_id_to_slug( $terms->term, $terms->taxonomy );
        }

        $args = [
            'post_type' => 'rz_listing',
            'posts_per_page' => $this->props->posts_per_page,
            'meta_query' => $meta_query,
            //'tax_query' => $tax_query,
        ];

        switch( $this->props->sorting ) {
            case 'top_rated':
                $args['meta_key'] = 'rz_review_rating_average';
                $args['orderby'] = [
                    'meta_value_num' => 'DESC',
                    'date' => 'DESC',
                ];
                $args['order'] = 'DESC';
                $url_params['sorting'] = 'top_rated';
                break;
        }

        $args['meta_query'] = $meta_query;

        $listings = new \WP_Query( $args );

        return array_merge([
            'listing_type' => $listing_type,
            'listings' => $listings,
            'more_posts' => $listings->found_posts - $this->props->posts_per_page,
            'more_url' => Rz()->get_explore_page_url( $url_params ),
            'strings' => (object) [
                'view_more' => esc_html__( 'View More', 'routiz' )
                'no_results' => esc_html__( 'No results were found', 'routiz' )
            ]
        ], (array) $this->props );

    }

}
