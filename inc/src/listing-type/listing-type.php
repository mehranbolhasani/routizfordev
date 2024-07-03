<?php

namespace Routiz\Inc\Src\Listing_Type;

use \Routiz\Inc\Src\Listing_Type\Action;
use \Routiz\Inc\Src\Listing_Type\Pricing;

class Listing_Type {

    public $id = null;
    public $pricing = null;
    public $post;

    function __construct( $id = null ) {

        // if( get_post_type( $id ) !== 'rz_listing_type' ) {
        //     return;
        // }

        if( $id ) {
            $this->get_post( $id );
        }

        $this->pricing = new Pricing( $this );

    }

    public function get_post( $id ) {

        // by slug
        if( is_numeric( $id ) ) {

            $this->id = $id;

        }else{

            global $wpdb;

            $result = $wpdb->get_row( $wpdb->prepare("
                SELECT *
                FROM {$wpdb->posts} p
                LEFT JOIN {$wpdb->postmeta} m
                    ON ( p.ID = m.post_id )
                WHERE p.post_type = 'rz_listing_type'
                AND p.post_status = 'publish'
                AND m.meta_key = 'rz_slug'
                AND m.meta_value = '%s'
            ", sanitize_text_field( $id ) ), OBJECT );

            if( $result ) {
                $this->id = $result->post_id;
            }

        }

        $this->post = get_post( $this->id );

    }

    public function get( $key, $single = true ) {
        return Rz()->get_meta( $key, $this->id, $single );
    }

    // TODO: all above merge to one function with parameter for product type
    public function packages_enabled() {
        return $this->get('rz_enable_plans');
    }

    public function has_plans() {
        return ( $this->get('rz_enable_plans') and ! empty( $this->get('rz_plans', false) ) );
    }

    public function get_plans() {
        if( $this->has_plans() ) {
            return $this->get('rz_plans', false);
        }
        return [];
    }

    public function get_wc_packages( $type = [ 'listing_plan', 'listing_subscription_plan' ] ) {

        if( ! class_exists('WC_Product_Query') ) {
            return [];
        }

        $package_ids = $this->get_plans();

        $query = new \WC_Product_Query([
            'post_type' => 'product',
            'type' => $type,
            'status' => 'publish',
            'orderby' => 'menu_order',
            'include' => $package_ids ? $package_ids : [ 0 ],
        ]);

        return $query->get_products();

    }

    public function has_promotions() {
        return ( $this->get('rz_enable_promotions') and ! empty( $this->get( 'rz_promotions', false ) ) );
    }

    public function get_promotions() {
        if( $this->has_promotions() ) {
            return $this->get('rz_promotions', false);
        }
        return [];
    }

    public function get_wc_promotions() {

        if( ! class_exists('WC_Product_Query') ) {
            return [];
        }

        $package_ids = $this->get_promotions();

        $query = new \WC_Product_Query([
            'post_type' => 'product',
            'status' => 'publish',
            'orderby' => 'menu_order',
            'include' => $package_ids ? $package_ids : [ 0 ],
        ]);

        return $query->get_products();

    }

    public function get_claims() {

        $action_claim = $this->get_action_type('claim');

        if( $action_claim ) {
            if( $action_claim->fields->action_product ) {
                return [ $action_claim->fields->action_product ];
            }
        }

        return [ 0 ];

    }

    public function get_wc_claim() {

        if( ! class_exists('WC_Product_Query') ) {
            return [];
        }

        $package_ids = $this->get_claims();

        $query = new \WC_Product_Query([
            'post_type' => 'product',
            'status' => 'publish',
            'orderby' => 'menu_order',
            'include' => $package_ids,
        ]);

        return $query->get_products();

    }

    // TODO: replace with Action->get_action_type()
    public function get_action_type( $key ) {

		foreach( Rz()->jsoning( 'rz_action_types', $this->id ) as $action ) {
			if( $action->template->id == $key ) {
				return $action;
			}
		}

        return [];

    }

    public function get_action( $key = '' ) {
        return new Action( $this, $key );
    }

}
