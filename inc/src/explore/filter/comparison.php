<?php

namespace Routiz\Inc\Src\Explore\Filter;

class Comparison {

    public $props;
    public $defaults = [
        'id' => '',
        'value' => '',
    ];

    public $body;

    function __construct( $props ) {

        $this->props = (object) array_merge( $this->defaults, is_object( $props ) ? (array) $props : $props );
        $this->body = [];

        return $this;

    }

    public function __call( $function, $args ) {
        return $this->compare( $function );
    }

    public function get_tax_id( $value ) {

        if( ! is_numeric( $value ) ) {

            $term = get_term_by( 'slug', $value, $this->props->id, OBJECT );
            if( isset( $term->term_id ) ) {
                return [ $term->term_id ];
            }
        }

        return [];

    }

    /*
     * comparison type
     *
     * @type {string}
     * Possible values are NUMERIC, BINARY, CHAR, DATE, DATETIME, DECIMAL, SIGNED, TIME, UNSIGNED
     * Default value is CHAR
     *
     */
    /*public function type( $type ) {
        $this->body->type = $type;
        return $this;
    }*/

    public function get_tax_children() {

        $children = [];
        if( is_array( $this->props->value ) ) {
            foreach( $this->props->value as $value ) {
                $children = array_merge( $children, get_term_children( $value, $this->props->id ) );
            }
            $this->props->value = array_merge( $this->props->value, $children );
        }
    }

    public function tax_ids( $with_child = true ) {

        if( is_array( $this->props->value ) ) {
            $values = [];
            foreach( $this->props->value as $value ) {
                if( ! empty( $value ) ) {
                    $values = array_merge( $values, $this->get_tax_id( $value ) );
                }
            }
            $this->props->value = $values;
        }else{
            $this->props->value = $this->get_tax_id( $this->props->value );
        }

        if( $with_child ) {
            $this->get_tax_children();
        }

        return $this;

    }

    public function compare( $compare_type ) {

        $entry = (object) [
            'key' => $this->props->id,
            'value' => $this->props->value
        ];

        switch( $compare_type ) {
            case 'in':
                $entry->compare = 'IN'; break;
            case 'not_in':
                $entry->compare = 'NOT IN'; break;
            case 'like':
                $entry->compare = 'LIKE'; break;
            case 'less':
                $entry->type = 'NUMERIC';
                $entry->compare = '<'; break;
            case 'less_or_equal':
                $entry->type = 'NUMERIC';
                $entry->compare = '<='; break;
            case 'greater':
                $entry->type = 'NUMERIC';
                $entry->compare = '>'; break;
            case 'greater_or_equal':
                $entry->type = 'NUMERIC';
                $entry->compare = '>='; break;
            case 'between':
                $entry->type = 'NUMERIC';
                $entry->compare = 'BETWEEN'; break;
            case 'between_dates':
                $entry->compare = 'BETWEEN'; break;
            default:
                $entry->compare = '=';
        }

        $this->body[] = (array) $entry;
        return $this;

    }

    public function get() {

        $meta_comparisons = count( $this->body );

        // multiple meta queries
        if( $meta_comparisons > 1 ) {

            $this->body = array_merge([
                'relation' => 'AND'
            ], $this->body );

            return $this->body;

        }
        // single meta query
        elseif( $meta_comparisons == 1 ) {

            return $this->body[0];

        }

        return [];

    }

}
