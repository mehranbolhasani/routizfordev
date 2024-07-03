<?php

namespace Routiz\Inc\Src\Form\Modules\Title;

use \Routiz\Inc\Src\Form\Modules\Module;

class Title extends Module {

    public function before_construct() {
        $this->defaults += [
            'tag' => 'h2',
        ];
    }

}
