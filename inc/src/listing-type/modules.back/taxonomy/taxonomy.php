<?php

namespace Routiz\Inc\Src\Listing_Type\Modules\Taxonomy;

use \Routiz\Inc\Src\Listing_Type\Modules\Module;
use \Routiz\Inc\Src\Listing_Type\Listing_Type;

class Taxonomy extends Module {

    public function controller() {

        $listing_type = get_queried_object();
        $terms = get_terms( $this->props->id, [
            'hide_empty' => false
        ]);

        foreach( $terms as $term ) {
            $term_image = json_decode( get_term_meta( $term->term_id, 'rz_image', true ) );
            if( isset( $term_image->id ) ) {
                $image_attrs = wp_get_attachment_image_src( $term_image->id, 'rz_listing' );
                if( isset( $image_attrs[0] ) ) {
                    $term->image = $image_attrs[0];
                }
            }
        }

        return array_merge([
            'items' => $terms,
            'listing_type' => new Listing_Type( $listing_type->ID )
        ], (array) $this->props );

    }

}
