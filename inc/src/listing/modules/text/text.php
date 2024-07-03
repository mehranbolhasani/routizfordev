<?php

namespace Routiz\Inc\Src\Listing\Modules\Text;

use \Routiz\Inc\Src\Listing\Modules\Module;

class Text extends Module {

    public function controller() {

        return array_merge( (array) $this->props, [
            'name' => $this->props->name,
        ]);

    }

}
