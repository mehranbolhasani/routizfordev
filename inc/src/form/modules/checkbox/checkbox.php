<?php

namespace Routiz\Inc\Src\Form\Modules\Checkbox;

use \Routiz\Inc\Src\Form\Modules\Module;

class Checkbox extends Module {

    public function initial() {
        $this->html = [
            'text' => esc_html__( 'Yes', 'routiz' )
        ];
    }

}
