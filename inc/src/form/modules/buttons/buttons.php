<?php

namespace Routiz\Inc\Src\Form\Modules\Buttons;

use \Routiz\Inc\Src\Form\Modules\Module;

class Buttons extends Module {

    public $fieldset = true;

    public function before_construct() {
        $this->defaults += [
            'options' => [],
            'style' => 'v1',
        ];
    }

    public function after_build() {
        $this->props->options = $this->component->extract_options( $this->props );
    }

}
