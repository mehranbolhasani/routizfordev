<?php

namespace Routiz\Inc\Src\Listing\Action\Modules\Purchase;

use \Routiz\Inc\Src\Listing\Action\Modules\Module;

class Purchase extends Module {

    public function wrapper() {
        return '<div class="rz-mod-action rz-mod-action-%1$s rz-ajaxing" data-type="%1$s">%2$s</div>';
    }

    public function controller() {

        global $rz_listing;

        $addon_label = $rz_listing->type->get('rz_addon_label');

        return array_merge( (array) $this->props, [
            'listing' => $rz_listing,
            'price' => $rz_listing->get_price(),
            'component' => $this->component,
            'allow_addons' => $rz_listing->type->get('rz_allow_addons'),
            'addon_label' => $addon_label ? $addon_label : esc_html__( 'Select services', 'routiz' ),
            'strings' => (object) [
                'label' => esc_html__( 'Purchase', 'routiz' )
            ]
        ]);

    }

}
