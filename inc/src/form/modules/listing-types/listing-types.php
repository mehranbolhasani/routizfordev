<?php

namespace Routiz\Inc\Src\Form\Modules\Listing_Types;

use \Routiz\Inc\Src\Form\Modules\Module;

class Listing_Types extends Module {

    public function before_construct() {
        $this->defaults += [
            'options' => [],
            'choice' => 'select',
            'return_ids' => false,
            'allow_empty' => true,
        ];
    }

    public function after_build() {

        $listing_types = get_posts([
            'post_type' => 'rz_listing_type',
            'post_status' => 'publish',
            'posts_per_page' => -1,
        ]);

        foreach( $listing_types as $listing_type ) {

            $id = $this->props->return_ids ? $listing_type->ID : get_post_meta( $listing_type->ID, 'rz_slug', true );

            $name = get_post_meta( $listing_type->ID, 'rz_name_plural', true );
            if( empty( $name ) ) {
                $name = esc_html__('( No name was specified for the listing type )', 'routiz');
            }

            $this->props->options += [ $id => $name ];

        }

    }

    public function get() {

        return $this->template();

    }

    public function controller() {

        return [
            'props' => (array) $this->props,
            'component' => $this->component,
        ];

    }

}
