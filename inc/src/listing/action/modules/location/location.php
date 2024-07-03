<?php

namespace Routiz\Inc\Src\Listing\Action\Modules\Location;

use \Routiz\Inc\Src\User;
use \Routiz\Inc\Src\Listing\Action\Modules\Module;

class Location extends Module {

    public function controller() {

        global $rz_listing;

        // dd( $this->props->fields );

        return array_merge( (array) $this->props, [
            'listing' => $rz_listing,
            'lat' => $rz_listing->get('rz_location__lat'),
            'lng' => $rz_listing->get('rz_location__lng'),
            'icon' => $rz_listing->type->get( 'rz_icon' ),
            'strings' => (object) [
                'not_specified' => esc_html__('Location not specified', 'routiz'),
            ]
        ]);

    }

}
