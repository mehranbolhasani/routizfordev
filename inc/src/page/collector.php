<?php

namespace Routiz\Inc\Src\Page;

use \Routiz\Inc\Src\Traits\Singleton;

class Collector {

    use Singleton;

    public $modules = [];

    public function add( $module_id, $module_params ) {
        $this->modules[ $module_id ] = $module_params;
    }

}
