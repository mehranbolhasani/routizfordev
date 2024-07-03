<?php

namespace Routiz\Inc\Src\Entry\Modules\Application;

use \Routiz\Inc\Src\Entry\Modules\Module;
use \Routiz\Inc\Src\Listing\Listing;

class Application extends Module {

    public function admin() {

        $listing = new Listing( Rz()->get_meta('rz_listing') );

        return array_merge( (array) $this->props, [
            'form' => $this->component->form,
            'action_application' => $listing->type->get_action_type( 'application' ),
            'strings' => (object) [
                'action_type_not_found' => esc_html__('Action type was not found.', 'routiz'),
            ]
        ]);

    }

    public function controller() {

        $listing = new Listing( Rz()->get_meta('rz_listing') );

        return array_merge( (array) $this->props, [
            'form' => $this->component->form,
            'action_application' => $listing->type->get_action_type( 'application' ),
            'strings' => (object) [
                'action_type_not_found' => esc_html__('Action type was not found.', 'routiz'),
            ]
        ]);

    }

}
