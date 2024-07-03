<?php

namespace Routiz\Inc\Src\Explore\Filter\Modules\Autocomplete;

use \Routiz\Inc\Src\Explore\Filter\Modules\Module;
use \Routiz\Inc\Src\Explore\Filter\Comparison;

class Autocomplete extends Module {

    public function before_get() {

        if( isset( $this->props->id ) ) {
            $this->props->taxonomy = Rz()->prefix( $this->props->id );
        }

        $this->props->empty = true;
        $this->props->format = 'slug>>>name';
        $this->props->term_name = null;

        if( $this->props->value ) {
            $term_obj = get_term_by( 'slug', $this->props->value, $this->props->id );
            if( ! is_wp_error( $term_obj ) and $term_obj ) {
                $this->props->term_name = $term_obj->name;
            }
        }

    }

    public function query() {

        $comparison = new Comparison( $this->props );
        return $comparison->tax_ids()->in()->get();

    }

    public function get_label() {

        $this->before_get();

        $field = $this->component->form->create( (array) $this->props );

        if( is_array( $field->props->options ) ) {
            if( is_array( $this->props->value ) ) {
                $label = [];
                foreach( $this->props->value as $value ) {
                    if( array_key_exists( $value, $field->props->options ) ) {
                        $label[] = $field->props->options[ $value ];
                    }
                }
                return $label;
            }else{
                return $field->props->options[ $this->props->value ];
            }
        }

    }

    public function get() {

        $this->before_get();

        $field = $this->component->form->create( array_merge( (array) $this->props, [
            'type' => 'autocomplete',
        ]));

        if( ! $field instanceof \Routiz\Inc\Src\Error ) {
            return sprintf( $this->wrapper(), $this->props->type, $field->get() );
        }

    }

}
