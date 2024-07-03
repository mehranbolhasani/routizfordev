<?php

namespace Routiz\Inc\Src\Form\Modules\Select;

use \Routiz\Inc\Src\Form\Modules\Module;

class Select extends Module {

    public function before_construct() {
        $this->defaults += [
            'options' => [],
            'allow_empty' => true,
            'label' => esc_html__('Select', 'routiz')
        ];
    }

    public function after_build() {
        $this->props->options = $this->component->extract_options( $this->props );
    }

}
