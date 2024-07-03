<?php

namespace Routiz\Inc\Src\Form\Modules\Checklist;

use \Routiz\Inc\Src\Form\Modules\Module;

class Checklist extends Module {

    public function before_construct() {
        $this->defaults += [
            'options' => [],
        ];
    }

    public function initial() {
        if( ! $this->props->value ) {
            $this->props->value = [];
        }
    }

    public function after_build() {

        $this->props->single = false;
        $this->props->options = $this->component->extract_options( $this->props );

    }

}
