<?php

namespace Routiz\Inc\Src\Listing;

use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Listing\Listing;

class Conversation {

    public $id = null;
    public $listing;

    public $sender_id;
    public $receiver_id;

    function __construct( $id, $user_id = null ) {

        switch( get_post_type( $id ) ) {

            case 'rz_conversation':

                $this->id = $id;
                $this->listing = new Listing( $this->get('rz_listing_id') );
                break;

            case 'rz_listing':

                $this->id = $this->get_conversation_by_listing_id( $id );
                $this->listing = new Listing( $id );

                break;

            default: return;

        }

        $this->set_participants( $user_id );

    }

    public function get( $key, $single = true ) {
        return Rz()->get_meta( $key, $this->id, $single );
    }

    public function get_conversation_by_listing_id( $listing_id ) {

        global $wpdb;

        $author_id = (int) get_post_field( 'post_author', $listing_id );
        $current_user_id = get_current_user_id();

        if( $current_user_id == $author_id ) {
            return null;
        }

        $posts = get_posts([
            'post_status' => 'publish',
            'post_type' => 'rz_conversation',
            'meta_query' => [
                'relation' => 'AND',
                [
                    'key' => 'rz_listing_id',
                    'value' => $listing_id,
                    'compare' => '=',
                ],
                [
                    'key' => 'rz_user_id',
                    'value' => $author_id,
                    'compare' => '=',
                ],
                [
                    'key' => 'rz_user_id',
                    'value' => get_current_user_id(),
                    'compare' => '=',
                ]
            ]
        ]);

        // existing conversation
        if( isset( $posts[0] ) ) {
            return $posts[0]->ID;
        }

        return null;

    }

    public function set_participants( $user_id = null ) {

        $sender_id = ! is_null( $user_id ) ? $user_id : get_current_user_id();

        // no conversation
        if( ! $this->has_conversation() ) {

            $receiver_id = (int) $this->listing->post->post_author;

        }
        // has conversation
        else{

            $receiver_id = 0;
            $db_user_ids = Rz()->get_meta( 'rz_user_id', $this->id, false );

            // the current user is not part of this conversation
            if( ! is_array( $db_user_ids ) or ! in_array( $sender_id, $db_user_ids ) ) {
                return;
            }

            foreach( $db_user_ids as $db_user_id ) {
                if( (int) $db_user_id !== $sender_id ) {
                    $receiver_id = (int) $db_user_id;
                    break;
                }
            }

        }

        // listing owner can\'t message himself
        if( $sender_id == $receiver_id ) {
            return;
        }

        $this->sender_id = $sender_id;
        $this->receiver_id = $receiver_id;

    }

    public function has_conversation() {
        return ! is_null( $this->id );
    }

    public function create_conversation() {

        if( ! $this->listing ) {
            return;
        }

        $conversation_id = wp_insert_post([
            'post_title' => $this->listing->post->post_title,
            'post_status' => 'publish',
            'post_type' => 'rz_conversation',
            'post_author' => $this->listing->post->post_author,
            'meta_input'  => [
                'rz_listing_id' => $this->listing->id,
            ]
        ]);

        if( ! is_wp_error( $conversation_id ) ) {

            add_post_meta( $conversation_id, 'rz_user_id', $this->sender_id );
            add_post_meta( $conversation_id, 'rz_user_id', $this->receiver_id );

            $this->id = $conversation_id;

        }

        return $conversation_id;

    }

    public function send( $text ) {

        global $wpdb;

        if( ! $this->has_conversation() ) {
            $this->create_conversation();
        }

        $wpdb->insert( $wpdb->prefix . 'routiz_messages', [
            'conversation_id' => $this->id,
            'sender_id' => $this->sender_id,
            'text' => $text,
            'active' => 1,
        ]);

        update_post_meta( $this->id, 'rz_messages_count', (int) get_post_meta( $this->id, 'rz_messages_count', true ) + 1 );
        // update_post_meta( $this->id, 'rz_last_messages_date', time() );

        // update last modify date
        wp_update_post([
            'ID' => $this->id,
        ]);

        return $this->id;

    }

    public function get_messages( $sort = 'asc' ) {

        global $wpdb;

        $messages = $wpdb->get_results(
            $wpdb->prepare("
                    SELECT *
                    FROM {$wpdb->prefix}routiz_messages
                    WHERE conversation_id = %d
                    ORDER BY created_at " . ( $sort == 'asc' ? 'ASC' : 'DESC' ) . "
                    LIMIT 50
                ",
                $this->id
            )
        );

        return $messages;

    }

    public function count_messages() {

        global $wpdb;

        $counted = $wpdb->get_var(
            $wpdb->prepare("
                    SELECT COUNT(*)
                    FROM {$wpdb->prefix}routiz_messages
                    WHERE conversation_id = %d
                ",
                $this->id
            )
        );

        return (int) $counted;

    }

    public function mark_as_read() {

        global $wpdb;

        if( ! $this->has_conversation() ) {
            return;
        }

        if( is_admin() and ! wp_doing_ajax() ) {
            return;
        }

        if( ! $this->id ) {
            return;
        }

        if( apply_filters( 'routiz/conversation/mark_as_read', true ) ) {
            $results = $wpdb->query(
                $wpdb->prepare("
                        UPDATE {$wpdb->prefix}routiz_messages
                        SET active = 0
                        WHERE sender_id = %d
                        AND conversation_id = %d
                    ",
                    $this->receiver_id,
                    $this->id
                )
            );
        }

    }

    public function is_own() {
        return $this->sender_id == $this->receiver_id;
    }

    static function get_last_message( $conversation_id ) {

        global $wpdb;

        $last_message = $wpdb->get_row(
            $wpdb->prepare("
                    SELECT *
                    FROM {$wpdb->prefix}routiz_messages
                    WHERE conversation_id = %d
                    ORDER BY created_at DESC
                ",
                $conversation_id
            )
        );

        return $last_message;

    }

    static function get_conversations() {

        $request = Request::instance();
        $page = $request->has('onpage') ? $request->get('onpage') : 1;
        $posts_per_page = 10;

        $query = new \WP_Query([
            'post_type' => 'rz_conversation',
            'post_status' => [ 'publish' ],
            'meta_query' => [
                [
                    'key' => 'rz_user_id',
                    'value' => get_current_user_id(),
                    'compare' => '=',
                ],
                [
                    'key' => 'rz_messages_count',
                    'value' => 0,
                    'compare' => '>',
                    'type' => 'numeric'
                ],
            ],
            'orderby' => 'modified',
            'order' => 'DESC',
            'posts_per_page' => $posts_per_page,
            'offset' => ( $page - 1 ) * $posts_per_page,

        ]);

        return $query;

    }

    static function get_or_create_by_user_id( $listing_id, $sender_id, $reciever_id ) {

        if( $sender_id == $reciever_id ) {
            return;
        }

        $posts = get_posts([
            'post_status' => 'publish',
            'post_type' => 'rz_conversation',
            'meta_query' => [
                'relation' => 'AND',
                [
                    'key' => 'rz_listing_id',
                    'value' => $listing_id,
                    'compare' => '=',
                ],
                [
                    'key' => 'rz_user_id',
                    'value' => $sender_id,
                    'compare' => '=',
                ],
                [
                    'key' => 'rz_user_id',
                    'value' => $reciever_id,
                    'compare' => '=',
                ]
            ]
        ]);

        if( isset( $posts[0] ) ) {
            return $posts[0]->ID;
        }

        $conversation = new Conversation( $listing_id, $sender_id );



        if( ! $conversation->has_conversation() ) {
            return $conversation->create_conversation();
        }

    }

}
