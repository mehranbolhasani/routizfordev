<?php

namespace Routiz\Inc\Src\Form\Modules\Taxonomy;

use \Routiz\Inc\Src\Form\Modules\Module;

class Taxonomy extends Module {
    public $allowed_formats;

    public function before_construct() {

        $this->allowed_formats = [
            'term_id',
            'name',
            'slug',
        ];

        $this->defaults += [
            'options' => [],
            'display' => 'select2',
            'format' => 'term_id>>>name',
            'allow_empty' => true,
        ];

    }

    public function after_build() {

        if( empty( $this->props->id ) ) {
            $this->props->id = $this->props->taxonomy;
        }elseif( empty( $this->props->taxonomy ) ) {
            $this->props->taxonomy = $this->props->id;
        }

    }

    public function finish() {

        // listing type
        if( $this->props->taxonomy == 'rz_listing_type' ) {
            $tax_query = [];
            $listing_types = get_posts([
                'post_type' => 'rz_listing_type',
                'post_status' => 'publish',
            ]);
            foreach( $listing_types as $listing_type ) {
                $tax_query[] = (object) [
                    'term_id' => $listing_type->ID,
                    'name' => $listing_type->post_title,
                    'slug' => get_post_meta( $listing_type->ID, 'rz_slug', true ),
                ];
            }
        }
        // taxonomy
        else{

            // exclude terms by getting their listing types
            // you can specify if terms are visible for any listing types
            // if their listing type is empty, the term will be visible for all listing types
            global $rz_filter;

            $tax_meta_query = [
                'relation' => 'OR'
            ];

            if( isset( $rz_filter->type ) and ! empty( $rz_filter->type ) ) {
                $tax_meta_query[] = [
                    'key' => 'rz_term_listing_types',
                    'value' => $rz_filter->type,
                    'compare' => '=',
                ];
                $tax_meta_query[] = [
                    'key' => 'rz_term_listing_types',
                    'compare' => 'NOT EXISTS'
                ];
            }

            $tax_query = get_terms( $this->props->taxonomy, [
                'hide_empty' => false,
                'meta_query' => $tax_meta_query
            ]);

            // dd( $this->props->taxonomy );
            // dd( $tax_query );

        }

        if( ! is_wp_error( $tax_query ) ) {

            $options = [];

            $format = explode( '>>>', $this->props->format );

            foreach( $tax_query as $tq ) {
                if( in_array( $format[0], $this->allowed_formats ) and in_array( $format[1], $this->allowed_formats ) ) {
                    $options[ $tq->{$format[0]} ] = $tq->{$format[1]};
                }
            }

            $this->props->options = $options;

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
