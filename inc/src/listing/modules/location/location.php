<?php

namespace Routiz\Inc\Src\Listing\Modules\Location;

use \Routiz\Inc\Src\Listing\Modules\Module;

class Location extends Module {

    public function controller() {

        $listing_type_id = Rz()->get_meta( 'rz_listing_type' );

        return array_merge(
            [
                'show_address' => false,
            ],
            (array) $this->props,
            [
                'lat' => esc_attr( Rz()->get_meta('rz_location__lat') ),
                'lng' => esc_attr( Rz()->get_meta('rz_location__lng') ),
                'address' => esc_attr( Rz()->get_meta('rz_location__address') ),
                'icon' => esc_attr( Rz()->get_meta( 'rz_icon', $listing_type_id ) ),
                'strings' => (object) [
                    'not_specified' => esc_html__('Location not specified', 'routiz'),
                ]
            ]
        );

    }

}
