<?php

namespace Routiz\Inc\Src\Form\Modules\Map;

use \Routiz\Inc\Src\Form\Modules\Module;

class Map extends Module {

    public $map_defaults = [
        'Barcelona, Spain',
        '41.38506389999999',
        '2.1734034999999494',
    ];

    public function after_build() {
        $this->props->single = false;
    }

    public function finish() {

        // set default
        if( isset( $this->props->default ) and is_array( $this->props->default ) and ! $this->props->value ) {
            $this->props->value = [
                $this->props->default[0],
                $this->props->default[1],
                $this->props->default[2]
            ];
        }

        // set default barcelona
        if( empty( $this->props->value ) ) {
            $this->props->value = $this->map_defaults;
        }
    }

    public function before_save( $post_id, $value ) {

        if(
            isset( $value[0] ) and
            isset( $value[1] ) and
            isset( $value[2] )
        ) {

            update_post_meta(
                $post_id,
                sprintf( '%s__address', $this->props->id ) ,
                Rz()->sanitize( $value[0] )
            );

            update_post_meta(
                $post_id,
                sprintf( '%s__lat', $this->props->id ) ,
                Rz()->sanitize( $value[1] )
            );

            update_post_meta(
                $post_id,
                sprintf( '%s__lng', $this->props->id ),
                Rz()->sanitize( $value[2] )
            );

        }

        if( isset( $value[3] ) ) {
            update_post_meta(
                $post_id,
                sprintf( '%s__geo_country', $this->props->id ) ,
                Rz()->sanitize( $value[3] )
            );
        }

        if( isset( $value[4] ) ) {
            update_post_meta(
                $post_id,
                sprintf( '%s__geo_city', $this->props->id ) ,
                Rz()->sanitize( $value[4] )
            );
        }

        if( isset( $value[5] ) ) {
            update_post_meta(
                $post_id,
                sprintf( '%s__geo_city_alt', $this->props->id ) ,
                Rz()->sanitize( $value[5] )
            );
        }

        return $value;

    }

    public function controller() {

        return array_merge( (array) $this->props, [
            'strings' => (object) [
                'address' => esc_html__('Address', 'routiz'),
                'e_g_barcelona' => esc_html__('e.g. Barcelona', 'routiz'),
                'latitude' => esc_html__('Latitude', 'routiz'),
                'longitude' => esc_html__('Longitude', 'routiz'),
            ]
        ]);

    }

}
