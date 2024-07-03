<?php

namespace Routiz\Inc\Src\Listing\Action\Modules\Download;

use \Routiz\Inc\Src\User;
use \Routiz\Inc\Src\Listing\Action\Modules\Module;

class Download extends Module {

    public function controller() {

        global $rz_listing;

        $owner = new User( $rz_listing->post->post_author );
        $owner_userdata = get_userdata( $owner->id );
        $description = Rz()->get_meta( 'rz_action_type_download_description', $rz_listing->type->id );

        return array_merge( (array) $this->props, [
            'component' => $this->component,
            'listing_id' => get_the_ID(),
            'current_user_id' => get_current_user_id(),
            'owner' => $owner,
            'owner_userdata' => $owner_userdata,
            'description' => $description,
            'strings' => (object) [
                'download' => esc_html__('Download', 'routiz'),
                'buy_now' => esc_html__('Buy now', 'routiz'),
            ]
        ]);

    }

}
