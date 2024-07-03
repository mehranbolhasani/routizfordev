<?php

defined('ABSPATH') || exit;

use \Routiz\Inc\Src\User;
use \Routiz\Inc\Src\Listing\Conversation;

global $rz_conversation;

if( ! isset( $rz_conversation->listing ) or ! isset( $rz_conversation->listing->id ) ) {
    return;
}

if( ! is_user_logged_in() ) {

    /*
     * not logged in
     *
     */
    Rz()->the_template( 'routiz/globals/conversation/modal/guest' );

}else{

    if( ! $rz_conversation->is_own() ) {

        /*
         * listing
         *
         */
        Rz()->the_template( 'routiz/globals/conversation/modal/listing' );

        /*
         * messages
         *
         */
        Rz()->the_template( 'routiz/globals/conversation/modal/messages' );

    }else{

        /*
         * owned listing
         *
         */
        Rz()->the_template( 'routiz/globals/conversation/modal/owner' );

    }
}

/*
 * preloader
 *
 */
Rz()->the_template( 'routiz/globals/preloader' );
