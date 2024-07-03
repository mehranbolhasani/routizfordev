<?php

namespace Routiz\Inc\Src\Listing_Type\Modules;

use Routiz\Inc\Extensions\Component\Module as Main_Module;
use Routiz\Inc\Src\Listing_Type\Init;

abstract class Module extends Main_Module {

    public function get_engine() {
        return Init::instance()->engine();
    }

}
