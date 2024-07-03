<?php

namespace Routiz\Inc\Src\Listing;

class Reviews {

    public $listing_id;

    public $average;
    public $count;
    public $type_id;

    function __construct( $listing_id ) {

        $this->listing_id = $listing_id;
        $this->type_id = Rz()->get_meta('rz_listing_type', $this->listing_id);

        $this->set_data();

    }

    public function set_data() {

        global $wpdb;

        if( ( $this->average = get_transient( sprintf( 'rz_reviews_average_%s', $this->listing_id ) ) ) === false ) {
            $this->average = $this->get_reviews_average();
            set_transient( sprintf( 'rz_reviews_average_%s', $this->listing_id ), $this->average );
        }

        if( ( $this->count = get_transient( sprintf( 'rz_reviews_count_%s', $this->listing_id ) ) ) === false ) {
            $this->count = $this->get_reviews_count();
            set_transient( sprintf( 'rz_reviews_count_%s', $this->listing_id ), $this->count );
        }

    }

    public function get_reviews_average() {

        if( ! Rz()->get_meta('rz_enable_review_ratings', $this->type_id ) ) {
            return null;
        }

        $review_ratings = Rz()->jsoning( 'rz_review_ratings', $this->type_id );

        if( empty( $review_ratings ) ) {
            return null;
        }

        $ratings = [];
        foreach( $review_ratings as $review_rating ) {
            $ratings[ $review_rating->fields->key ] = [
                'count' => 0,
                'sum' => 0,
            ];
        }

        $comment_query = new \WP_Comment_Query;
        $comments = $comment_query->query([
            'post_id' => $this->listing_id,
            'status' => 'approve',
            'parent' => 0,
            'hierarchical' => false,
            'number' => 0,
        ]);

        if( $comments ) {
            foreach( $comments as $comment ) {

                $comment_ratings = get_comment_meta( $comment->comment_ID, 'rz_ratings', true );

                if( is_array( $comment_ratings ) ) {
                    foreach( $comment_ratings as $comment_rating_key => $comment_rating_value ) {

                        $comment_rating_value = (int) $comment_rating_value;
                        if( $comment_rating_value >= 1 and $comment_rating_value <= 5 ) {

                            if( array_key_exists( $comment_rating_key, $ratings ) ) {

                                $ratings[ $comment_rating_key ]['count'] += 1;
                                $ratings[ $comment_rating_key ]['sum'] += $comment_rating_value;

                            }
                        }
                    }
                }
            }
        }

        $total_count = $total_sum = 0;

        foreach( $ratings as $rating_key => $rating_value ) {

            $rating_id = sprintf( 'rz_review_rating_average_%s', $rating_key );

            if( $rating_value['count'] > 0 ) {

                // total
                $total_count += $rating_value['count'];
                $total_sum += $rating_value['sum'];

                $average = number_format( $rating_value['sum'] / $rating_value['count'], 2 );
                update_post_meta( $this->listing_id, $rating_id, $average );

            }else{

                delete_post_meta( $this->listing_id, $rating_id );

            }

        }

        // update listing total average review rating
        if( $total_count > 0 ) {
            $total_average = number_format( $total_sum / $total_count, 2 );
            return round( $total_average, 2 );
        }

        return null;

    }

    public function get_reviews_count() {

        global $wpdb;

        return (int) $wpdb->get_var( $wpdb->prepare("
        	SELECT COUNT( comment_ID )
        	FROM {$wpdb->comments}
        	WHERE comment_approved = 1
            AND comment_post_ID = %d
        	AND comment_parent = 0
        ", $this->listing_id ) );

    }

    public function flush() {
        delete_transient( sprintf( 'rz_reviews_average_%s', $this->listing_id ) );
        delete_transient( sprintf( 'rz_reviews_count_%s', $this->listing_id ) );
        $this->set_data();
    }

}
