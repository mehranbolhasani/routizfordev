<?php

namespace Routiz\Inc\Src;

class User {

    use \Routiz\Inc\Src\Traits\Singleton;

    public $id = 0;
    public $favorites = [];

    function __construct( $user_id = null ) {

        $this->id = is_null( $user_id ) ? get_current_user_id() : $user_id;

        if( $this->id ) {
            $this->favorites = $this->get_favorites();
        }

    }

    public function get_avatar( $size = 'thumbnail' ) {

        // user account picture
        $user_avatar = Rz()->json_decode( get_user_meta( $this->id, 'user_avatar', true ) );
        if( $user_avatar and isset( $user_avatar[0] ) and isset( $user_avatar[0]->id ) ) {
            $attach_src = wp_get_attachment_image_src( $user_avatar[0]->id, $size );
            return isset( $attach_src[0] ) ? $attach_src[0] : null;
        }

        // user social media source
        $user_media_picture = get_user_meta( $this->id, 'rz_media_picture', true );
        if( $user_media_picture ) {
            $attach_src = wp_get_attachment_image_src( $user_media_picture, $size );
            return isset( $attach_src[0] ) ? $attach_src[0] : null;
        }

        return null;

    }

    public function avatar( $size = 'thumbnail' ) {
        $avatar = $this->get_avatar( $size );
        if( $avatar ) {
            echo '<div class="rz-avatar"><img src="' . esc_url( $avatar ) . '" alt=""></div>';
        }else{
            echo '<div class="rz-avatar-placeholder"><i class="material-icon-person"></i></div>';
        }
    }

    public function the_avatar( $size = 'thumbnail' ) {
        echo $this->avatar( $size );
    }

    public function get_cover( $size = 'rz_gallery_large' ) {

        // user account picture
        $user_cover = Rz()->json_decode( get_user_meta( $this->id, 'user_cover', true ) );
        if( $user_cover and isset( $user_cover[0] ) and isset( $user_cover[0]->id ) ) {
            $attach_src = wp_get_attachment_image_src( $user_cover[0]->id, $size );
            return $attach_src[0];
        }

        return null;

    }

    public function get_dummy_avatar() {
        return Rz()->get_template('routiz/globals/avatar');
    }

    public function get_total_reviews() {

        global $wpdb;

        $listing_ids = $wpdb->get_results("
            SELECT ID FROM {$wpdb->posts}
            WHERE post_author = {$this->id}
            AND post_status = 'publish'
            AND post_type = 'rz_listing'
        ");

        if( ! $listing_ids ) {
            return 0;
        }

        $ids = [];
        foreach( $listing_ids as $id ) {
            $ids[] = (int) $id->ID;
        }

        $ids = implode( ',', $ids );

        $count = $wpdb->get_var("
            SELECT COUNT( comment_id ) FROM {$wpdb->comments}
            WHERE comment_post_ID IN ( {$ids} )
            AND comment_approved = 1
            AND comment_type = 'rz-review'
        ");

        return $count;
    }

    public function get_user_ip() {

        if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;

    }

    public function get_user_agent() {

        return $_SERVER['HTTP_USER_AGENT'];

    }

    public function add_to_favorite( $listing_id ) {

        $user_favorites = $this->favorites;

        // remove
        if( in_array( $listing_id, $user_favorites ) ) {
            unset( $user_favorites[ array_search( $listing_id, $user_favorites ) ] );
        }
        // add
        else{
            $user_favorites[ time() ] = $listing_id;
        }

        update_user_meta( $this->id, 'rz_favorites', $user_favorites );

    }

    public function get_favorites() {

        $user_favorites = get_user_meta( $this->id, 'rz_favorites', true );
        if( ! is_array( $user_favorites ) ) {
            $user_favorites = [];
        }

        return $user_favorites;

    }

    public function is_favorite( $listing_id ) {
        return in_array( $listing_id, $this->favorites );
    }

    public function get_userdata() {
        return get_userdata( $this->id );
    }

    public function get_num_listings() {
        return count_user_posts( $this->id, 'rz_listing' );
    }

    static public function create( $user_data ) {

        if( ! username_exists( $user_data['user_login'] ) ) {

            if( email_exists( $user_data['user_email'] ) ) {
                $user_data['user_email'] = '';
            }

            $user_id = wp_insert_user( $user_data );

            if( ! is_wp_error( $user_id ) ) {

                routiz()->notify->distribute('welcome', [
                    'user_id' => $user_id
                ]);

                return $user_id;

            }

        }

    }

    static public function creates( $user_data ) {
        if( ! username_exists( $user_data['user_login'] ) ) {
            if( email_exists( $user_data['user_email'] ) ) {
                $user_data['user_email'] = '';
            }
            $user_id = wp_insert_user( $user_data );
            if( ! is_wp_error( $user_id ) ) {
                
                return $user_id;
            }

        }

    }

}
