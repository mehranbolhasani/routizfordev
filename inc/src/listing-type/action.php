<?php

namespace Routiz\Inc\Src\Listing_Type;

class Action {

    public $id;
    public $key = null;
    public $action = null;
    public $types = [];

    function __construct( Listing_Type $listing_type = null, $key = '' ) {

        if( ! $listing_type->id ) {
            return null;
        }

        $this->id = $listing_type->id;
        $this->key = $key;

        $this->set_action_type();

    }

    public function set_action_type() {

		foreach( Rz()->jsoning( 'rz_action_types', $this->id ) as $action ) {
			if( $this->key and $action->template->id == $this->key ) {
                if( ! $this->action ) {
                    $this->action = $action;
                }
			}
            $this->types[] = $action->template->id;
		}

    }

    public function exists() {
        return $this->has( $this->key );
    }

    public function has( $key ) {
        return in_array( $key, $this->types );
    }

    public function get_template( $id, $default = '' ) {
        if( $this->action ) {
            if( isset( $this->action->template->{$id} ) ) {
                return $this->action->template->{$id};
            }
        }
    }

    public function get_field( $id, $default = '' ) {
        if( $this->action ) {
            if( isset( $this->action->fields->{$id} ) ) {
                return $this->action->fields->{$id};
            }else{
                return $default;
            }
        }
    }

    public function has_reservation_section() {
        return  $this->has('booking') or
                $this->has('booking_hourly') or
                $this->has('booking_appointments');
    }

    static function get_action_fields( Listing_Type $listing_type ) {

        $static_fields = [
            'allow_pricing',
            'allow_not_required_price',
            'allow_seasons',
            'allow_long_term',
            'allow_security_deposit',
            'allow_extra_pricing',
            'allow_addons',
            'allow_instant',
            'allow_guests',
            'allow_guest_pricing',
            'allow_child_pricing',
            'allow_child_based_pricing',
            'allow_min_max',
            'payment_processing',
            'days_pending',
        ];

        $arr = [];
        foreach( $static_fields as $static_field ) {
            $arr[ $static_field ] = $listing_type->get( Rz()->prefix( $static_field ) );
        }

        return (object) $arr;

    }

}
