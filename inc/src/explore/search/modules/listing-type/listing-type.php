<?php

namespace Routiz\Inc\Src\Explore\Search\Modules\Listing_Type;

use \Routiz\Inc\Src\Explore\Search\Modules\Module;

class Listing_Type extends Module {

    public function controller() {

        return array_merge( (array) $this->props, [
            'form' => $this->component->form
        ]);

    }

}
