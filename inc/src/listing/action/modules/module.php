<?php

namespace Routiz\Inc\Src\Listing\Action\Modules;

use Routiz\Inc\Extensions\Component\Module as Main_Module;
use Routiz\Inc\Src\Listing\Action\Init;

abstract class Module extends Main_Module {

    public function get_engine() {
        return Init::instance()->engine();
    }

    public function wrapper() {
        return '<div class="rz-mod-action rz-mod-action-%1$s" data-type="%1$s">%2$s</div>';
    }

}
