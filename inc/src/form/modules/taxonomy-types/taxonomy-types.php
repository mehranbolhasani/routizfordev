<?php

namespace Routiz\Inc\Src\Form\Modules\Taxonomy_Types;

use \Routiz\Inc\Src\Form\Modules\Module;

class Taxonomy_Types extends Module {

    public function after_build() {

        // built-in taxonomies
        $this->props->options = [
            'rz_listing_category' => 'Categories',
            'rz_listing_region' => 'Regions',
            'rz_listing_tag' => 'Tags',
        ];

        // custom taxonomies
        $custom_taxonomies = Rz()->get_custom_taxonomies();

        if( is_array( $custom_taxonomies ) ) {
            foreach( $custom_taxonomies as $custom_taxonomy ) {
                $this->props->options += [ $custom_taxonomy->slug => $custom_taxonomy->name ];
            }
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
