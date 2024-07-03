<?php

namespace Routiz\Inc\Src\Explore\Filter\Modules\Heading;

use \Routiz\Inc\Src\Explore\Filter\Modules\Module;
use \Routiz\Inc\Src\Explore\Filter\Comparison;

class Heading extends Module {

    public function controller() {

        return (array) $this->props;

        // return array_merge( (array) $this->props, [
        //     'component' => $this->component,
        // ]);

    }

    public function query() {
        return [];
    }

}
