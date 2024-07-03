<?php

namespace Routiz\Inc\Src\Listing\Action\Modules\Booking_Appointments;

use \Routiz\Inc\Src\Listing\Action\Modules\Module;
use \Routiz\Inc\Src\Listing\Appointments;

class Booking_Appointments extends Module {

    public function controller() {

        global $rz_listing;

        $num_guests = (int) $rz_listing->get('rz_guests');
        $addon_label = $rz_listing->type->get('rz_addon_label');
        $appointments = new Appointments( $rz_listing );
        $action = $rz_listing->type->get_action('booking_appointments');

        return [
            'component' => $this->component,
            'action' => $action,
            'title' => isset( $this->props->title ) ? $this->props->title : [],
            'description' => isset( $this->props->description ) ? $this->props->description : [],
            'fields' => isset( $this->props->fields ) ? $this->props->fields : [],
            'upcoming' => $appointments->get(),
            'allow_guests' => $rz_listing->type->get('rz_allow_guests'),
            'num_guests' => $num_guests ? $num_guests : 9999,
            'allow_addons' => $rz_listing->type->get('rz_allow_addons'),
            'addon_label' => $addon_label ? $addon_label : esc_html__( 'Select services', 'routiz' ),
            'listing' => $rz_listing,
            'strings' => (object) [
                'select' => esc_html__('Select', 'routiz'),
                'select_date' => esc_html__('Select date', 'routiz'),
                'select_start_date' => esc_html__('Select start date', 'routiz'),
                'see_more_dates' => esc_html__('See more dates', 'routiz'),
                'no_appointments' => esc_html__('No appointments were found', 'routiz'),
            ]
        ];

    }

}
