<?php

namespace Routiz\Inc\Src\Listing\Modules\Author;

use \Routiz\Inc\Src\Listing\Modules\Module;
use \Routiz\Inc\Src\User;

class Author extends Module {

    public function controller() {

        global $rz_listing;
        $user_id = $rz_listing->post->post_author;

        $userdata = get_userdata( $user_id );
        $user = new User( $user_id );

        return array_merge( (array) $this->props, [
            'listing' => $rz_listing,
            'display_name' => ! empty( $this->props->format ) ? sprintf( $this->props->format, $userdata->display_name ) : $userdata->display_name,
            'user' => $user,
            'userdata' => $userdata,
            'user_description' => get_the_author_meta( 'description', $user_id ),
            'join_date' => date( get_option('date_format'), strtotime( $userdata->user_registered ) ),
            'total_reviews' => $user->get_total_reviews(),
            'strings' => (object) [
                'verified' => esc_html__('Verified', 'routiz'),
                'review' => esc_html__('%s review', 'routiz'),
                'reviews' => esc_html__('%s reviews', 'routiz'),
                'contact' => esc_html__('Contact %s', 'routiz'),
            ]
        ]);

    }

}
