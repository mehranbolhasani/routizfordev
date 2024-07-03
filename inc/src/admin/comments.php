<?php

namespace Routiz\Inc\Src\Admin;

use \Routiz\Inc\Src\Listing\Listing;

class Comments {

    use \Routiz\Inc\Src\Traits\Singleton;

    function __construct() {

        add_action( 'edit_comment', [ $this, 'update_comment' ], 2 );
        add_action( 'comment_post', [ $this, 'update_comment' ], 2 );
        add_action( 'comment_unapproved_to_approved', [ $this, 'update_comment' ] );
        add_action( 'comment_approved_to_unapproved', [ $this, 'update_comment' ] );
        add_action( 'comment_spam_to_approved', [ $this, 'update_comment' ] );
        add_action( 'comment_approved_to_spam', [ $this, 'update_comment' ] );
        add_action( 'comment_approved_to_trash', [ $this, 'update_comment' ] );
        add_action( 'comment_trash_to_approved', [ $this, 'update_comment' ] );
        add_action( 'routiz/listing/insert_comment', [ $this, 'update_comment' ] );

    }

    public function update_comment( $comment ) {

        if( ! $comment instanceof \WP_Comment ) {
            $comment = get_comment( $comment );
        }

        if( $comment->comment_type !== 'rz-review' ) {
            return;
        }

        if( $comment->comment_parent > 0 ) {
            return;
        }

        $listing = new Listing( $comment->comment_post_ID );
        $listing->reviews->flush();

    }
}
