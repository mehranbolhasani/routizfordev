<?php

namespace Routiz\Inc\Src\Form;

use \Routiz\Inc\Extensions\Component\Component as Main_Component;

class Component extends Main_Component {

    const Storage_Option = 'option';
    const Storage_Meta = 'meta';
    const Storage_Term = 'term';
    const Storage_Comment = 'comment';
    const Storage_Request = 'request';
    const Storage_Field = 'field';

    const Post_Fields = [
        'post_title',
        'post_content',
        'post_excerpt',
        'post_author',
        'post_date',
        'post_date_gmt',
        'post_status',
    ];

    public $storage = Component::Storage_Field;
    public $prefix = false;

    function __construct( $storage = false ) {
        if( $storage ) {
            $this->storage = $storage;
        }
    }

    public function set( $storage ) {
        $this->storage = $storage;
    }

    public function extract_options( $props ) {

        $options = [];
        $raw = $props->options;

        /*
         * repeater item
         *
         */
        if( isset( $props->repeater_id ) ) {
            $options = [];
            $repeater_items = Rz()->json_decode( get_option( Rz()->prefix( $props->repeater_id ) ) );

            if( is_array( $repeater_items ) ) {
                foreach( $repeater_items as $item ) {
                    $options[ $item->fields->id ] = $item->fields->name;
                }
            }
        }
        /*
         * string
         *
         */
        elseif( is_string( $raw ) ) {

            $raw = str_replace( '<br>', "%%", $raw );

            $lines = explode( '%%', $raw );

            foreach( $lines as $line ) {

                $option = explode( ':', trim( preg_replace( '/([ \t]+)?:([ \t]+)?/', ':', $line ) ) );

                if( empty( $option[0] ) ) { continue; }

                if( ! isset( $option[1] ) ) { $option[1] = $option[0]; }

                // with image
                if ( preg_match( '/image-id-([0-9]+)/', $option[0], $matches ) ) {
                    $image_attributes = wp_get_attachment_image_src( $matches[1], 'thumbnail' );
                    $options[ $option[0] ] = [
                        'label' => $option[1],
                        'image' => $image_attributes[0],
                    ];
                }
                // text only
                else{
                    $options[ $option[0] ] = $option[1];
                }

            }

            return $options;

        }
        /*
         * wp query
         *
         */
        elseif( isset( $raw['query'] ) ) {

            $posts = get_posts( $raw['query'] );

            if ( $posts ) {
                foreach( $posts as $post ) {
                    $options[ $post->ID ] = $post->post_title;
                }
            }

        }

        /*
         * array
         *
         */
        else{
            return $raw;
        }

        return $options;

    }

}

?>
