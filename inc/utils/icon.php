<?php

namespace Routiz\Inc\Utils;

class Icon {

    use \Routiz\Inc\Src\Traits\Singleton;

    // Define properties explicitly to avoid deprecation warnings
    public $custom_sets;

    function __construct() {
        add_action( 'init', [ $this, 'set_custom_sets' ] );
    }

    public function set_custom_sets() {
        $this->custom_sets = $this->get_custom_sets();
    }

    public function get( $name = null, $set = 'font-awesome' ) {

        if( ! $name ) {
            return '';
        }

        switch( $set ) {
            case 'font-awesome':
                return sprintf('<i class="%s"></i>', $name);
            case 'material-icons':
                return sprintf('<i class="material-icons">%s</i>', $name);
            default:
                if( isset( $this->custom_sets[ $set ] ) ) {
                    $prefix = $this->custom_sets[ $set ]['prefix'];
                    return sprintf('<i class="%1$s %1$s%2$s"></i>', $prefix, $name);
                }

        }

    }

    public function get_custom_sets() {

        if( ( $custom_icons = get_transient('rz_custom_sets') ) === false ) {

            $custom_icons = [];

            $posts = get_posts([
                'post_type' => 'rz_icon_set',
                'post_status' => [ 'publish' ],
                'posts_per_page' => -1,
            ]);

            foreach( $posts as $post ) {
                $meta = Rz()->get_meta( 'rz_custom_icon_set', $post->ID );

                if( $meta !== '' ) {
                    $custom_icons[ $post->post_name ] = $meta;
                    $custom_icons[ $post->post_name ]['name'] = get_the_title( $post->ID );
                    $custom_icons[ $post->post_name ]['css_url'] = $this->get_set_url( $post->ID );
                }
            }

            set_transient( 'rz_custom_sets', $custom_icons, DAY_IN_SECONDS );

        }

        return $custom_icons;

    }

    public function get_set_url( $post_id = 0 ) {

        if ( ! $post_id ) {
            $post_id = get_the_ID();
        }

        $icon_set = Rz()->get_meta( 'rz_custom_icon_set', $post_id );

        return ! empty( $icon_set['icon_dir_name'] ) ? RZ_UPLOAD_URI . 'utillz-icons/' . $icon_set['icon_dir_name'] . '/style.css' : '';
    }

}
