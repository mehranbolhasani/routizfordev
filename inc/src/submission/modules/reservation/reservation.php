<?php

namespace Routiz\Inc\Src\Submission\Modules\Reservation;

use \Routiz\Inc\Src\Submission\Submission;
use \Routiz\Inc\Src\Submission\Modules\Module;
use \Routiz\Inc\Src\Request\Custom_Request;
use \Routiz\Inc\Src\Form\Component as Form;
use \Routiz\Inc\Src\Validation;
use \Routiz\Inc\Src\Listing_Type\Action;

class Reservation extends Module {

    public function controller() {

        global $rz_submission;

        $actions = $rz_submission->listing_type->get_action();
        $action_fields = Action::get_action_fields( $rz_submission->listing_type );

        $time = [];
        $today = strtotime( 'today', time() );
        $date = new \DateTime('01:00:00');
        $date_end = new \DateTime('24:00:00');
        $date_format = get_option('time_format');

        while( $date <= $date_end ) {
            $time[ $date->getTimestamp() - $today ] = $date->format( $date_format );
            $date->add( new \DateInterval('PT60M') );
        }

        return [
            'form' => new Form( Form::Storage_Request ),
            'actions' => $actions,
            'time' => $time,
            'title' => esc_html__('Reservation', 'routiz'),
            'action_fields' => $action_fields,
            'strings' => (object) [
                'allow_instant' => esc_html__('Allow instant booking', 'routiz'),
                'max_guests' => esc_html__('Maximum number of guests allowed', 'routiz'),
                'max_guests_desc' => esc_html__('Limit the number of guests', 'routiz'),
                'guest_price' => esc_html__('Price per each additional guests', 'routiz'),
                'guest_price_desc' => esc_html__('Add additional price for each guest > 1', 'routiz'),
                'child_price' => esc_html__('Discount per each childs', 'routiz'),
                'child_price_desc' => esc_html__('Discount price for each childs', 'routiz'),
                'child_sep_price' => esc_html__('Price per each childs', 'routiz'),
                'child_price_sep' => esc_html__('Separate price for each childs', 'routiz'),
                'reservation_length_min' => $actions->has('booking') ? esc_html__('Minimum number of days per reservation', 'routiz') : esc_html__('Minimum number of hours per reservation', 'routiz'),
                'reservation_length_max' => $actions->has('booking') ? esc_html__('Maximum number of days per reservation', 'routiz') : esc_html__('Maximum number of hours per reservation', 'routiz'),
                'length_empty' => esc_html__('Leave empty to skip', 'routiz'),
                'booking_start' => esc_html__('Booking Start Time', 'routiz'),
                'booking_end' => esc_html__('Booking End Time', 'routiz'),
                'hourly_multi_disable' => esc_html__('Disable Multiple Booking at Same Time Slot', 'routiz'),
            ]
        ];

    }

    public function validation() {

        $request = new Custom_Request('input');
        $submission = new Submission( $request->get('type') );
        $validation = new Validation();
        $action_types = $submission->listing_type->get_action();

        wp_send_json([
            'success' => true
        ]);

    }

}
