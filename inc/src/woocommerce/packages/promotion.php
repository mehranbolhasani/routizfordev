<?php

namespace Routiz\Inc\Src\Woocommerce\Packages;

use \Routiz\Inc\Src\Listing\Listing;

class Promotion extends Package {

    public $slug = 'rz_promotion';

    public function get_duration() {
        return (int) $this->product->get_meta('_rz_promotion_duration');
    }

    public function get_priority() {
        return (int) $this->product->get_meta('_rz_promotion_priority');
    }

    public function create( $order_id = null, $user_id = null ) {

        $duration = $this->get_duration();
		if( $duration == 0 ) {
			$duration = 30;
		}

		$priority = $this->get_priority();
		if( $priority == 0 ) {
			$priority = 2;
		}

        $post_id = wp_insert_post([
			'post_title' => '',
			'post_status' => 'publish',
			'post_type' => $this->slug,
			'post_author' => get_current_user_id(),
            'meta_input'  => [
				'rz_product_id' => $this->id,
				'rz_duration' => $duration,
				'rz_priority' => $priority,
            ]
		]);

        if( $order_id ) {
            add_post_meta( $post_id, 'rz_order_id', $order_id );
        }

        wp_update_post([
            'ID' => $post_id,
            'post_title' => sprintf( '#%s', $post_id ),
        ]);

        /*
         * save expiration date
         *
         */
        $expiry_date = strtotime( "+{$duration} days" );
        update_post_meta( $post_id, 'rz_expires', $expiry_date );

        return $post_id;

    }

    public function add_to_cart( $listing_id ) {

        $listing = new Listing( $listing_id );

        if( ! $listing->id ) {
            return;
        }

        WC()->cart->add_to_cart( $this->id, 1, '', '', [
            'listing_id' => [ $listing->id ],
        ]);

        wc_add_to_cart_message( $this->id );

    }

}
