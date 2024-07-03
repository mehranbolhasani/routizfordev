<?php

namespace Routiz\Inc\Src\Form\Modules\Radio;

use \Routiz\Inc\Src\Form\Modules\Module;

class Radio extends Module {

    public $fieldset = true;

    public function before_construct() {
        $this->defaults += [
            'options' => [],
        ];
    }

    public function after_build() {
        $this->props->options = $this->component->extract_options( $this->props );
    }

}
