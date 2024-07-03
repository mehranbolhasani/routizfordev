<?php

namespace Routiz\Inc\Src\Form\Modules\Toggle;

use \Routiz\Inc\Src\Form\Modules\Module;

class Toggle extends Module {

    public function initial() {
        $this->html = [
            'text' => 'Yes'
        ];
    }

}
