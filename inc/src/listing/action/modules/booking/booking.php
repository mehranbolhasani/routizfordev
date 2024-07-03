<?php

namespace Routiz\Inc\Src\Listing\Action\Modules\Booking;

use \Routiz\Inc\Src\Listing\Action\Modules\Module;

class Booking extends Module {

    public function controller() {

        global $rz_listing;

        $num_guests = (int) $rz_listing->get('rz_guests');
        $addon_label = $rz_listing->type->get('rz_addon_label');
        $action = $rz_listing->type->get_action('booking');
        $entity_text = $action->get_field('entity_text');

        return array_merge( (array) $this->props, [
            'listing' => $rz_listing,
            'price' => $rz_listing->get_price(),
            'title' => $action->get_field('title'),
            'summary' => $action->get_field('summary'),
            'selection_type' => $action->get_field('selection_type'),
            'entity_text' => $entity_text ? $entity_text : esc_html__('per night', 'routiz'),
            'component' => $this->component,
            'allow_guests' => $rz_listing->type->get('rz_allow_guests'),
            'allow_addons' => $rz_listing->type->get('rz_allow_addons'),
            'addon_label' => $addon_label ? $addon_label : esc_html__( 'Select services', 'routiz' ),
            'num_guests' => $num_guests ? $num_guests : 9999,
            'currency' => class_exists('woocommerce') ? get_woocommerce_currency_symbol() : '',
            'strings' => (object) [
                'action_button_text' => $rz_listing->is_instant() ? esc_html__( 'Instant Book', 'routiz' ) : esc_html__( 'Send reservation request', 'routiz' ),
            ]
        ]);

    }

}
