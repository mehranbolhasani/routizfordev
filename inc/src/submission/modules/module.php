<?php

namespace Routiz\Inc\Src\Submission\Modules;

use Routiz\Inc\Extensions\Component\Module as Main_Module;
use Routiz\Inc\Src\Submission\Init;

abstract class Module extends Main_Module {

    public function get_engine() {
        return Init::instance()->engine();
    }

    public function validation() {
        return [
            'success' => true,
        ];
    }

    public function wrapper() {
        $group = isset( $this->props->group ) ? ' data-group="' . $this->props->group . '"' : '';
        return '<section class="rz-submission-step" data-id="%1$s"' . $group . '>%2$s</section>';
    }

}
