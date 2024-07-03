<?php

namespace Routiz\Inc\Src\Listing\Action\Modules\Claim;

use \Routiz\Inc\Src\Listing\Action\Modules\Module;

class Claim extends Module {

    public function controller() {

        return array_merge( (array) $this->props, [
            'component' => $this->component,
            'listing_id' => get_the_ID(),
            'is_claimed' => Brk()->get_meta('rz_is_claimed'),
            'strings' => (object) [
                'button_label' => esc_html__( 'Claim this business', 'routiz' ),
                'claimed' => esc_html__( 'Claimed', 'routiz' ),
                'claimed_description' => sprintf( esc_html__( '%s confirmed a business or employee ownership', 'routiz' ), get_bloginfo('name') ),
            ],
        ]);

    }

}
