<?php

namespace Routiz\Inc\Src\Page\Modules;

use \Routiz\Inc\Extensions\Component\Module as Main_Module;
use \Routiz\Inc\Src\Page\Init;

abstract class Module extends Main_Module {

    public function get_engine() {
        return Init::instance()->engine();
    }

}
