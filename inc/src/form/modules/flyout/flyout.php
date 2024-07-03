<?php

namespace Routiz\Inc\Src\Form\Modules\Flyout;

use \Routiz\Inc\Src\Form\Modules\Module;

class Flyout extends Module {

    public function before_construct() {
        $this->defaults += [
            'label' => esc_html__('Select', 'routiz'),
            'strings' => (object) [
                'close' => esc_html__('Close', 'routiz')
            ]
        ];

    }

}
