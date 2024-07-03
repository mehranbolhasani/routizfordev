<?php

namespace Routiz\Inc\Src\Form\Modules\Use_Field;

use \Routiz\Inc\Src\Form\Modules\Module;

class Use_Field extends Module {

    // Initialize groups property
    protected $groups = [];

    public function before_construct() {

        $this->defaults += [
            'options' => [],
            'group' => 'text',
            'allow_empty' => true,
            'exclude' => [],
            'include' => [],
        ];

        // Add values to the groups property
        $this->groups = [
            'any' => [
                // all type of fields
            ],
            'text' => [
                'text',
                'textarea',
                'editor',
                'faq',
                'number',
            ],
            'checkbox' => [
                'checkbox',
                'toggle',
            ],
            'number' => [
                'number',
            ],
            'taxonomy' => [
                'taxonomy',
            ],
            'download' => [
                'upload',
            ],
            'media' => [
                'upload',
            ],
        ];

    }

    public function after_build() {

        // get all terms
        if( get_post_type() !== 'rz_listing_type' ) {

            $taxonomies = [
                'rz_listing_category' => esc_html__('Category', 'routiz'),
                'rz_listing_region' => esc_html__('Region', 'routiz'),
                'rz_listing_tag' => esc_html__('Tags', 'routiz'),
            ];

            $custom_taxonomies = Rz()->get_custom_taxonomies();
            if( is_array( $custom_taxonomies ) ) {
                foreach( $custom_taxonomies as $custom_taxonomy ) {
                    $taxonomies[ $custom_taxonomy->slug ] = $custom_taxonomy->name;
                }
            }

            $this->props->options = $taxonomies;

        }
        // collect listing type terms from fields
        else{

            foreach( Rz()->jsoning( 'rz_fields' ) as $k => $item ) {

                if( $this->props->group !== 'any' ) {
                    if( ! in_array( $item->template->id, $this->groups[ $this->props->group ] ) ) {
                        continue;
                    }
                }

                if( isset( $item->fields->key ) ) {
                    $this->props->options += [ Rz()->prefix( $item->fields->key ) => $item->fields->name ];
                }

            }

        }

        // exclude
        if( $this->props->exclude ) {
            foreach( $this->props->exclude as $exclude ) {
                unset( $this->props->options[ $exclude ] );
            }
        }

        // include
        if( $this->props->include ) {
            $this->props->options = array_merge( $this->props->options, $this->props->include );
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
