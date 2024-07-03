<?php

namespace Routiz\Inc\Src\Listing_Type;

class Pricing {

    public $id;

    function __construct( Listing_Type &$listing_type = null ) {

        if( ! $listing_type->id ) {
            return null;
        }

        $this->id = $listing_type->id;

    }

    public function get( $key, $single = true ) {
        return Rz()->get_meta( $key, $this->id, $single );
    }

}
