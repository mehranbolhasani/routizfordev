<?php

namespace Routiz\Inc\Src\Form\Modules\Preview_Listing;

use \Routiz\Inc\Src\Form\Modules\Module;

class Preview_Listing extends Module {

    public function controller() {

        return array_merge( (array) $this->props, [
            'component' => $this->component,
            'props' => [
                'type' => Rz()->get_meta('rz_display_listing_type', null, true, 'grid'),
                'cover' => Rz()->get_meta('rz_display_listing_cover', null, true, 'image'),
                'favorite' => Rz()->get_meta('rz_display_listing_favorite', null, true, true),
                'review' => Rz()->get_meta('rz_display_listing_review', null, true, true),
                'title' => Rz()->get_meta('rz_display_listing_title'),
                'tagline' => Rz()->get_meta('rz_display_listing_tagline'),
                // 'top_labels' => Rz()->get_meta('rz_display_listing_top'),
                'bottom_labels' => Rz()->get_meta('rz_display_listing_bottom'),
                'content' => Rz()->get_meta('rz_display_listing_content'),
            ],
        ]);

    }

}
