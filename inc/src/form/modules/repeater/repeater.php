<?php

namespace Routiz\Inc\Src\Form\Modules\Repeater;

use \Routiz\Inc\Src\Form\Modules\Module;

class Repeater extends Module {

    public function before_construct() {
        $this->defaults += [
            'can_hide' => false,
        ];
    }

    public function controller() {

        $items = is_array( $this->props->value ) ? $this->props->value : Rz()->json_decode( $this->props->value );

        return array_merge( (array) $this->props, [
            'items' => $items,
            'strings' => (object) [
                'add_new' => isset( $this->props->button ) ? $this->props->button['label'] : esc_html__( 'Add New', 'routiz' ),
                'no_modules' => esc_html__( 'No modules were found', 'routiz' ),
            ],
            'component' => $this->component,
            'repeater' => $this,
            'templates_num' => count( $this->props->templates ),
        ]);

    }

}
