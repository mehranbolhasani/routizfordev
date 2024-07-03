<?php

namespace Routiz\Inc\Src\Form\Modules;

use Routiz\Inc\Extensions\Component\Module as Main_Module;
use Routiz\Inc\Src\Form\Init;

abstract class Module extends Main_Module {

    public $type;
    public $id;
    public $props;
    public $class = [];
    public $attrs = [];
    public $style = '';
    public $defaults = [
        'storage' => 'field',
        'type' => '',
        'id' => '',
        'name' => '',
        'description' => '',
        'value' => '',
        'placeholder' => '',
        'single' => true,
        'heading' => false,
        'v_model' => '',
        'col' => 12,
        'disabled' => false,
        'required' => false,
        'is_field' => true,
        'is_wrapped' => true,
        'is_col' => true,
        'readonly' => false,
        'class' => [],
        'html' => []
    ];

    public $html = [
        'class' => '',
        'id' => '',
    ];

    public function init() {

        $this->initial(); // initial action
        $this->identity();
        $this->singular();
        $this->dependency();
        $this->after_build(); // after action
        $this->value();
        $this->finish(); // finish action

    }

    public function get_engine() {
        return Init::instance()->engine();
    }

    public function initial() {}
    public function after_build() {}
    public function finish() {}

    public function identity() {

        $this->type = $this->props->type;
        $this->id = $this->id();
        $this->props->id = $this->prefix();
        $this->props->html = array_merge( $this->html, (array) $this->props->html );

    }

    public function id() {
        return $this->is_prefixed() ? substr( $this->props->id, 3 ) : $this->props->id;
    }

    public function prefix() {

        if( empty( $this->props->id ) ) {
            return;
        }

        if( ! $this->is_prefixed() ) {
            if( ! $this->is_post_field() ) {
                if(
                    $this->component->prefix == true or
                    ! (
                        $this->component->storage == $this->component::Storage_Request or
                        $this->component->storage == $this->component::Storage_Field
                    )
                ) {
                    return sprintf( 'rz_%s', $this->props->id );
                }
            }
        }

        return $this->props->id;

    }

    public function is_prefixed() {
        return Rz()->is_prefixed( $this->props->id );
    }

    public function is_post_field() {
        return Rz()->is_post_field( $this->id );
    }

    public function singular() {
        if( is_array( $this->props->value ) ) {
            $this->props->single = false;
        }
    }

    public function dependency() {
        if( isset( $this->props->dependency ) ) {
            $this->attrs['data-dependency'] = htmlspecialchars( json_encode( $this->props->dependency ), ENT_QUOTES, 'UTF-8' );
        }
    }

    public function value() {

        $value = $this->get_storage_value();

        // $this->props->value = is_string( $value ) ? stripslashes( $value ) : $value;

        if( is_string( $value ) ) {
            $this->props->value = is_string( $value ) ? stripslashes( str_replace( '\r\n', '__NEW_LINE__', $value ) ) : $value;
            $this->props->value = str_replace( '__NEW_LINE__', '\r\n', $this->props->value );
        }else{
            $this->props->value = $value;
        }

    }

    public function get_storage_value( $custom_id = null ) {

        if( ! $this->id ) {
            return $this->props->value;
        }

        $id = $custom_id ? $custom_id : $this->props->id;
        $default = $this->props->value;

        switch( $this->component->storage ) {

            /*
             * option
             *
             */
            case $this->component::Storage_Option:

                $value = get_option( $id );
                return $value === false ? $default : $value;

            /*
             * meta
             *
             */
            case $this->component::Storage_Meta:

                if( $this->is_post_field() and $this->component->storage == 'meta' ) {
                    return get_post_field( $this->id, get_the_ID() );
                }

                // dd( htmlspecialchars( get_post_meta( get_the_ID(), $id, $this->props->single ) ) );

                return ! metadata_exists( 'post', get_the_ID(), $id ) ? $default : get_post_meta( get_the_ID(), $id, $this->props->single );

            /*
             * term
             *
             */
            case $this->component::Storage_Term:

                return isset( $_GET['tag_ID'] ) ? get_term_meta( $_GET['tag_ID'], $id, $this->props->single ) : null;

            /*
             * comment
             *
             */
            case $this->component::Storage_Comment:

                return ! metadata_exists( 'comment', get_comment_ID(), $id ) ? $default : get_comment_meta( get_comment_ID(), $id, $this->props->single );

            /*
             * request
             *
             */
            case $this->component::Storage_Request:

                // exact match
                if( isset( $_REQUEST[ $id ] ) ) {
                    return $_REQUEST[ $id ];
                }
                // match with no prefix
                elseif( Rz()->is_prefixed( $id ) and isset( $_REQUEST[ Rz()->unprefix( $id ) ] ) ) {
                    return $_REQUEST[ Rz()->unprefix( $id ) ];
                }
                // no match
                else{
                    return null;
                }

                break;

            /*
             * field
             *
             */
            default:
                return $default;

        }
    }

    public function before_save( $post_id, $value ) {
        return $value;
    }

    /*public function save( $post_id = null ) {

        $value = $field->props->value;
        $value = $field->before_save( $post_id, $value );

        switch( $this->component->storage ) {

            /*
             * option
             *
             *
            case $this->component::Storage_Option:

                // array
                if( is_array( $value ) ) {
                    foreach( $value as $val ) {
                        add_post_meta( $post_id, $this->props->id, $val );
                    }
                }
                // single
                else{
                    update_post_meta( $post_id, $this->props->id, $value );
                }

                $field->after_save( $post_id, $value );

                break;

            /*
             * meta
             *
             *
            case $this->component::Storage_Meta:

                update_option( $this->props->id, $value );

                break;

            /*
             * term
             *
             *
            case $this->component::Storage_Term:

                update_term_meta( $post_id, $this->props->id, $value );

                break;

            /*
             * comment
             *
             *
            case $this->component::Storage_Comment:

                update_comment_meta( $post_id, $this->props->id, $value );

                break;


        }

    }*/

    public function after_save( $post_id, $value ) {
        return $value;
    }

    public function wrapper() {

        if( ! $this->props->is_wrapped ) {
            return $this->template();
        }

        ob_start(); ?>
            <div class="%1$s" %2$s>%3$s</div><?php
        return ob_get_clean();

    }

    public function implode( $attr ) {
        return is_array( $attr ) ? implode( ' ', $attr ) : $attr;
    }

    public function html() {
        $this->props->html = array_map( [ $this, 'implode' ], $this->props->html );
    }

    public function get() {

        $this->html();
        $this->before_get();

        $id = '';

        // attributes
        $attrs = $this->attrs;
        $attrs['data-type'] = $this->type;
        $attrs['data-storage'] = $this->component->storage;
        $attrs['data-disabled'] = $this->props->disabled ? 'yes' : 'no';
        if( $this->props->name ) {
            $attrs['data-heading'] = $this->props->name;
        }

        if( $this->props->id ) {
            $attrs['data-id'] = $this->id;
        }

        // classes
        $class = array_merge( $this->class, $this->props->class );
        $class[] = 'rz-form-group';

        if( $this->props->is_field )  {
            $class[] = 'rz-field';
        }

        // if( $this->props->col > 0 and $this->props->col < 12 ) {
        if( $this->props->is_col ) {
            $class[] = "rz-col-{$this->props->col}";
            if( $this->props->col < 12 ) {
                $class[] = "rz-col-sm-12";
            }
        }
        // }

        // TODO: fix html props,
        // add defaults for html props, globally
        if( isset( $this->props->html ) ) {

            $this->props->html = (object) $this->props->html;

            /*// prop id
            if( isset( $this->props->html->id ) ) {
                $id = sprintf( 'id="%s"', $this->props->html->id );
            }*/

            // prop classes
            // if( isset( $this->props->html->class ) ) {
            //     if( is_array( $this->props->html->class ) ) {
            //         $class = array_merge( $class, $this->props->html->class );
            //     }else{
            //         $class[] = $this->props->html->class;
            //     }
            // }

        }

        return sprintf(
            $this->wrapper(),
            implode( ' ', $class ),
            Rz()->attrs( $attrs ),
            $this->template()
        );

    }

    public function set_id( $id ) {

        $this->__construct( array_merge( (array) $this->props, [
            'id' => $id
        ]), $this->component );

    }

}
