<?php

namespace Routiz\Inc\Src\Form\Modules\Preview_Marker;

use \Routiz\Inc\Src\Form\Modules\Module;

class Preview_Marker extends Module {

    public function controller() {

        return array_merge( (array) $this->props, [
            'component' => $this->component,
            'props' => [
                'type' => Rz()->get_meta('rz_marker_type', null, true, 'icon'),
                'icon' => Rz()->get_meta('rz_icon'),
                'image' => Rz()->get_meta('rz_marker_image'),
                'image_width' => Rz()->get_meta('rz_marker_image_width', null, true, 80),
                'field' => Rz()->get_meta('rz_marker_field'),
                'field_format' => Rz()->get_meta('rz_marker_field_format', null, true, '%s'),
            ],
        ]);

    }

}
