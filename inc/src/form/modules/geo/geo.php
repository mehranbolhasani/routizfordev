<?php

namespace Routiz\Inc\Src\Form\Modules\Geo;

use \Routiz\Inc\Src\Form\Modules\Module;

class Geo extends Module {

    public function initial() {
        $this->class[] = 'rz-geo';
    }

    public function finish() {

        $this->props->geo_n = $this->get_storage_value('geo_n');
        $this->props->geo_e = $this->get_storage_value('geo_e');
        $this->props->geo_s = $this->get_storage_value('geo_s');
        $this->props->geo_w = $this->get_storage_value('geo_w');

    }

}
