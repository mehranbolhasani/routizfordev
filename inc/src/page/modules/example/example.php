<?php

namespace Routiz\Inc\Src\Page\Modules\Example;

use \Routiz\Inc\Src\Page\Modules\Module;

class Example extends Module {

    public function controller() {

        return array_merge( (array) $this->props, [
            'some' => 'value'
        ]);

    }

}
