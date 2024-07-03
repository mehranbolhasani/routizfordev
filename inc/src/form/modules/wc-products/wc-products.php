<?php

namespace Routiz\Inc\Src\Form\Modules\Wc_Products;

use \Routiz\Inc\Src\Form\Modules\Module;

class Wc_Products extends Module {

    public function before_construct() {
        $this->defaults += [
            'product_type' => 'simple',
            'options' => [],
            'choice' => 'select',
            'return_ids' => true,
            'allow_empty' => false,
            'error_message' => esc_html__( 'There are no available products', 'routiz' )
        ];
    }

    public function after_build() {

        $options = [];

        if( ! class_exists('WC_Product_Query') ) {
            $this->props->error_message = esc_html__( 'WooCommerce is required in order to use this feature.', 'routiz' );
            return;
        }

        $q = new \WC_Product_Query([
            'post_type' => 'product',
            'type' => $this->props->product_type,
            'status' => [ 'publish', 'private' ],
            'limit' => -1,
        ]);

        $products = $q->get_products();

        foreach( $products as $product ) {
            $options[ $product->get_id() ] = $product->get_name();
        }

        $this->props->options = $options;

    }

    public function wrapper() {
        return $this->template();
    }

    public function controller() {

        return array_merge( (array) $this->props, [
            'component' => $this->component,
            'props' => (array) $this->props,
        ]);

    }

}
