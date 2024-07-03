<?php

namespace Routiz\Inc\Src\Explore\Filter\Modules\Tab;

use \Routiz\Inc\Src\Explore\Filter\Modules\Module;
use \Routiz\Inc\Src\Explore\Filter\Comparison;

class Tab extends Module {

    public function controller() {

        return array_merge( (array) $this->props, [
            'component' => $this->component,
            'strings' => (object) [
                'close' => esc_html__( 'Close', 'routiz' )
            ]
        ]);

    }

    public function query() {

        return [];

    }

}
