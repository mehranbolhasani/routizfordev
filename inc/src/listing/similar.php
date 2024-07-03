<?php

namespace Routiz\Inc\Src\Listing;

use \Routiz\Inc\Src\Form\Component as Form;

class Similar {

    public $id;
    public $listing_type_id;
    public $taxonomy;
    public $terms;

    function __construct( $id ) {

        $this->id = $id;
        $this->listing_type_id = (int) Rz()->get_meta( 'rz_listing_type', $id );
        $this->taxonomy = Rz()->prefix( Rz()->get_meta( 'rz_similar_taxonomy', $this->listing_type_id ) );
        $this->terms = Rz()->get_meta( $this->taxonomy, $id, false );

    }

    public function query() {

        global $rz_nearby_post_ids;

        $args = [
            'post_type' => 'rz_listing',
            'post_status' => 'publish',
            'posts_per_page' => 3,
            'meta_query' => [
                'relation' => 'AND',
                [
                    'key' => 'rz_listing_type',
                    'value' => $this->listing_type_id,
                    'compare' => '=',
                ],
                [
                    'key' => $this->taxonomy,
                    'value' => $this->terms,
                    'compare' => 'IN',
                ]
            ]
        ];

        $exclude = [ $this->id ];

        if( $rz_nearby_post_ids and is_array( $rz_nearby_post_ids ) ) {
            $exclude = array_merge( $exclude, $rz_nearby_post_ids );
        }

        $args['post__not_in'] = $exclude;

        $query = new \WP_Query( $args );

        return $query;

    }

}
