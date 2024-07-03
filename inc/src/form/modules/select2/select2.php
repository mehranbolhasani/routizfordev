<?php

namespace Routiz\Inc\Src\Form\Modules\Select2;

use \Routiz\Inc\Src\Form\Modules\Module;

class Select2 extends Module {

    public function before_construct() {
        $this->defaults += [
            'options' => [],
            'allow_empty' => true
        ];
    }

    public function after_build() {
        $this->props->options = $this->component->extract_options( $this->props );
    }

    public function controller() {

        return array_merge( (array) $this->props, [
            'self' => $this,
            'strings' => (object) [
                'select' => esc_html__( 'Select', 'routiz' )
            ],
        ]);

    }

    public function selected( $key ) {

        if( ! $this->props->value ) {
            return;
        }

        if( $this->props->single ) {
            return $this->props->value == $key;
        }

        return in_array( $key, $this->props->value );

    }

}
