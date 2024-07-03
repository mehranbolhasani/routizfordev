<?php

namespace Routiz\Inc\Src\Woocommerce\Packages;

use \Routiz\Inc\Src\Listing\Listing;

abstract class Package {

    public $id = null;
    public $product = null;
    public $slug = null;

    function __construct( $product_id ) {

        if( $this->product = wc_get_product( $product_id ) ) {
            $this->id = (int) $this->product->get_id();
        }

    }

    public function is_purchasable() {
        return $this->product->get_price() > 0;
    }

    public function get_total_packages() {

        $the_query = new \WP_Query([
            'post_type' => $this->slug,
            'post_status' => 'publish',
            'post_author' => get_current_user_id(),
            'meta_query' => [
                'relation' => 'AND',
                [
                    'key' => 'rz_product_id',
                    'value' => $this->id,
                    'compare' => '='
                ]
            ]
        ]);

        return (int) $the_query->found_posts;

    }

    /*
     * create package
     *
     */
    abstract public function create( $order_id = null, $user_id = null );
    abstract public function add_to_cart( $listing_id );

}
