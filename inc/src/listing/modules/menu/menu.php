<?php

namespace Routiz\Inc\Src\Listing\Modules\Menu;

use \Routiz\Inc\Src\Listing\Modules\Module;

class Menu extends Module {

    public function controller() {

        global $rz_listing;

        // d( Rz()->json_decode( $rz_listing->get('rz_menu') ) );

        return array_merge( (array) $this->props, [
            'items' => Rz()->json_decode( $rz_listing->get('rz_menu') )
            // 'id' => $this->props->id,
            // 'name' => $this->props->name,
            // 'content' => Rz()->get_meta( $this->props->id ),
        ]);

    }

}
