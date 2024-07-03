<?php

namespace Routiz\Inc\Src\Listing\Action\Modules\Application;

use \Routiz\Inc\Src\Listing\Action\Modules\Module;

class Application extends Module {

    public function controller() {

        return array_merge( (array) $this->props, [
            'component' => $this->component,
            'listing_id' => get_the_ID(),
            'strings' => (object) [
                'button_label' => esc_html__( 'Send Application', 'routiz' )
            ],
        ]);

    }

}
