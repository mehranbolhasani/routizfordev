<?php

namespace Routiz\Inc\Src\Listing\Action\Modules\Contact;

use \Routiz\Inc\Src\User;
use \Routiz\Inc\Src\Listing\Action\Modules\Module;

class Contact extends Module {

    public function controller() {

        return array_merge( (array) $this->props, [
            'component' => $this->component,
            'listing_id' => get_the_ID(),
            'strings' => (object) [
                'send_message' => esc_html__('Send message', 'routiz'),
            ]
        ]);

    }

}
