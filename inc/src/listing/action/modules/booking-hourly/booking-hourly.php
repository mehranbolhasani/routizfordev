<?php

namespace Routiz\Inc\Src\Listing\Action\Modules\Booking_Hourly;

use \Routiz\Inc\Src\Listing\Action\Modules\Module;

class Booking_Hourly extends Module {

    public function controller() {

        global $rz_listing;

        $action = $rz_listing->type->get_action('booking_hourly');
        $num_guests = (int) $rz_listing->get('rz_guests');
        $addon_label = $rz_listing->type->get('rz_addon_label');
        $entity_text = $action->get_field('entity_text');

        $time_stack = (int) $action->get_field('action_type_stack');
        if( $time_stack < 15 or $time_stack > 60 ) {
            $time_stack = 60;
        }

        // set start time
        if( $start = $rz_listing->get('rz_reservation_start') ) {
            $start_date = new \DateTime('00:00:00');
            $start_date->add( new \DateInterval('PT' . $start . 'S') );
        }

        // set end time
        if( $end = $rz_listing->get('rz_reservation_end') ) {
            $end_date = new \DateTime('00:00:00');
            $end_date->add( new \DateInterval('PT' . $end . 'S') );
        }

        $time = [];
        $today = strtotime( 'today', time() );
        $date = $start ? $start_date : new \DateTime('01:00:00');
        $date_end = $end ? $end_date : new \DateTime('24:00:00');
        $date_format = get_option('time_format');

        while( $date <= $date_end ) {
            $time[ $date->getTimestamp() - $today ] = $date->format( $date_format );
            $date->add( new \DateInterval( sprintf( 'PT%sM', $time_stack ) ) );
        }

        return array_merge( (array) $this->props, [
            'listing' => $rz_listing,
            'action' => $action,
            'time' => $time,
            'price' => $rz_listing->get_price(),
            'title' => $action->get_field('title'),
            'summary' => $action->get_field('summary'),
            'entity_text' => $entity_text ? $entity_text : esc_html__('per hour', 'routiz'),
            'component' => $this->component,
            'allow_pricing' => $rz_listing->type->get('rz_allow_pricing'),
            'allow_guests' => $rz_listing->type->get('rz_allow_guests'),
            'allow_addons' => $rz_listing->type->get('rz_allow_addons'),
            'addon_label' => $addon_label ? $addon_label : esc_html__( 'Select services', 'routiz' ),
            'num_guests' => $num_guests ? $num_guests : 9999,
            'currency' => class_exists('woocommerce') ? get_woocommerce_currency_symbol() : '',
            'strings' => (object) [
                'action_button_text' => $rz_listing->is_instant() ? esc_html__( 'Instant Book', 'routiz' ) : esc_html__( 'Send reservation request', 'routiz' ),
                'select_date' => esc_html__('Select date', 'routiz'),
                'select_start_date' => esc_html__('Select start date', 'routiz'),
                'start_time' => esc_html__('Start time', 'routiz'),
                'end_time' => esc_html__('End time', 'routiz'),
            ]
        ]);

    }

}
