<?php

namespace Routiz\Inc\Src\Listing\Modules\Taxonomy;

use \Routiz\Inc\Src\Listing\Modules\Module;

class Taxonomy extends Module {

    public function controller() {

        $terms = [];
        $include = Rz()->get_meta( $this->props->id, get_the_ID(), false );

        if( is_array( $include ) and array_sum( $include ) > 0 ) {
            $terms = get_terms( $this->props->id, [
                'hide_empty' => false,
                'include' => $include
            ]);
        }

        if( ! empty( $terms ) and ! is_wp_error( $terms ) ) {
            foreach( $terms as $term ) {
                $term->icon = get_term_meta( $term->term_id, 'rz_icon', true );
            }
        }else{
            $terms = [];
        }

        return array_merge( (array) $this->props, [
            'id' => $this->props->id,
            'terms' => $terms
        ]);

    }

}
