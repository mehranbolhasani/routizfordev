<?php

namespace Routiz\Inc\Src\Entry\Modules\Purchase;

use \Routiz\Inc\Src\Entry\Modules\Module;
use \Routiz\Inc\Src\Listing\Listing;
use \Routiz\Inc\Src\User;
use \Routiz\Inc\Src\Request\Request;

class Purchase extends Module {

    public function admin() {

        $listing = new Listing( Rz()->get_meta('rz_listing') );
        $image = $listing->get_first_from_gallery();
        $entry_id = get_the_ID();
        $post_status = get_post_status();
        $user = new User( Rz()->get_meta('rz_request_user_id') );
        $user_owner_id = get_post_field( 'post_author', $entry_id );
        $userdata = get_userdata( $user->id );

        return array_merge( (array) $this->props, [
            'form' => $this->component->form,
            'entry_id' => $entry_id,
            'listing' => $listing,
            'image' => $listing->get_first_from_gallery(),
            'status' => $post_status,
            'user_owner_id' => $user_owner_id,
            'userdata' => $userdata,
            'checkin_date' => date_i18n( get_option( 'date_format' ), Rz()->get_meta('rz_checkin_date') ),
            'checkout_date' => date_i18n( get_option( 'date_format' ), Rz()->get_meta('rz_checkout_date') ),
            'pricing' => Rz()->json_decode( Rz()->get_meta('rz_pricing') ),
            'addons' => Rz()->json_decode( Rz()->get_meta('rz_addons', $listing->id) ),
            'services' => Rz()->json_decode( Rz()->get_meta('rz_extra_pricing', $listing->id) ),
            'strings' => (object) [
                'purchase' => esc_html__('Purchase', 'routiz'),
                'purchased_by' => esc_html__('Purchased by', 'routiz'),
                'pricing_details' => esc_html__('Pricing details', 'routiz'),
                'base_price' => esc_html__('Base price', 'routiz'),
                'child_price' => esc_html__('Child Dis. price', 'routiz'),
                'child_sep_price' => esc_html__('Child price', 'routiz'),
                'security_deposit' => esc_html__('Security deposit', 'routiz'),
                'service_fee' => esc_html__('Service fee', 'routiz'),
                'extra_service_total' => esc_html__('Extra services total', 'routiz'),
                'payment' => esc_html__('Payment', 'routiz'),
                'total' => esc_html__('Total', 'routiz'),
                'processing' => esc_html__('Processing', 'routiz'),
            ]
        ]);

    }

    public function controller() {

        $request = Request::instance();

        if( $request->is_empty('id') ) {
            return [];
        }

        $entry_id = (int) $request->get('id');
        $listing = new Listing( Rz()->get_meta('rz_listing') );
        $image = $listing->get_first_from_gallery();
        $user = new User( Rz()->get_meta('rz_request_user_id') );
        $user_owner_id = get_post_field( 'post_author', $entry_id );
        $userdata = get_userdata( $user->id );
        $post_status = get_post_status( $entry_id );
        $expiration_time = 24; // hours
        if( floatval( $expiration_time_option = get_option('rz_days_booking_pending_payment') ) ) {
            $expiration_time = $expiration_time_option;
        }

        $params = [
            'type' => $request->get('type'),
            'form' => $this->component->form,
            'entry_id' => $entry_id,
            'listing' => $listing,
            'image' => $listing->get_first_from_gallery(),
            'status' => $post_status,
            'user_owner_id' => $user_owner_id,
            'userdata' => $userdata,
            'checkin_date' => date_i18n( get_option( 'date_format' ), Rz()->get_meta('rz_checkin_date') ),
            'checkout_date' => date_i18n( get_option( 'date_format' ), Rz()->get_meta('rz_checkout_date') ),
            'guests' => (int) Rz()->get_meta('rz_guests'),
            'children' => (int) Rz()->get_meta('rz_children'),
            'pricing' => Rz()->json_decode( Rz()->get_meta('rz_pricing') ),
            'addons' => Rz()->json_decode( Rz()->get_meta('rz_addons', $listing->id) ),
            'services' => Rz()->json_decode( Rz()->get_meta('rz_extra_pricing', $listing->id) ),
            'cancellation_date' => date( get_option('date_format') . ' ' . get_option('time_format'), time() + $expiration_time ),
            'strings' => (object) [
                'purchase' => esc_html__('Purchase', 'routiz'),
                'purchased_by' => esc_html__('Purchased by', 'routiz'),
                'pricing_details' => esc_html__('Pricing details', 'routiz'),
                'base_price' => esc_html__('Base price', 'routiz'),
                'security_deposit' => esc_html__('Security deposit', 'routiz'),
                'service_fee' => esc_html__('Service fee', 'routiz'),
                'extra_service_total' => esc_html__('Extra services total', 'routiz'),
                'payment' => esc_html__('Payment', 'routiz'),
                'total' => esc_html__('Total', 'routiz'),
                'processing' => esc_html__('Processing', 'routiz'),
            ]
        ];

        return array_merge( (array) $this->props, $params );

    }

}
