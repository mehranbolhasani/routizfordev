<?php

namespace Routiz\Inc\Src\Form\Modules\Options;

use \Routiz\Inc\Src\Form\Modules\Module;

class Options extends Module {

    public function before_construct() {
        $this->defaults += [
            'options' => [],
            'options_description' => sprintf( esc_html__( 'Put each option in a new line. Specify both a value and label, example: %s', 'routiz' ), '<br><br>home : Home<br>some-restaurant : Some Restaurant' ),
        ];
    }

    public function finish() {
        $this->props->value_raw = str_replace( '<br>', "\r\n", $this->props->value );
        $this->props->options = $this->props->value;
        $this->props->options = $this->component->extract_options( $this->props );
    }

    public function controller() {

        return array_merge( (array) $this->props, [
            'strings' => (object) [
                'add_options' => esc_html__('Add Options', 'routiz'),
                'save_options' => esc_html__('Save Options', 'routiz'),
            ]
        ]);

    }

}
