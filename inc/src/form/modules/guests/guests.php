<?php

namespace Routiz\Inc\Src\Form\Modules\Guests;

use \Routiz\Inc\Src\Form\Modules\Module;
use \Routiz\Inc\Src\Request\Request;

class Guests extends Module {

    public function before_construct() {
        $this->defaults += [
            'num_guests' => 999,
            'info_guests' => null,
            'show_info_guests' => true,
            'guests' => [
                'adults' => 0,
                'children' => 0,
                'infants' => 0,
            ]
        ];
    }

    public function controller() {

        $request = Request::instance();

        if( $this->props->storage == 'field' or $this->props->storage == 'request' ) {
            if( ! $request->is_empty('guest_adults') ) {
                $this->props->guests['adults'] = (int) $request->get('guest_adults');
            }
            if( ! $request->is_empty('guest_children') ) {
                $this->props->guests['children'] = (int) $request->get('guest_children');
            }
            if( ! $request->is_empty('guest_infants') ) {
                $this->props->guests['infants'] = (int) $request->get('guest_infants');
            }
        }

        $guests_num = 0;
        if( $request->has('guests') ) {
            $guests_num = (int) $request->get('guests');
        }

        return array_merge( (array) $this->props, [
            'component' => $this->component,
            'guests' => (object) $this->props->guests,
            'guests_num' => $guests_num,
            'strings' => (object) [
                'one_guest' => esc_html__('1 guest', 'routiz'),
                'n_guest' => esc_html__('%s guests', 'routiz'),
                'select_guests' => esc_html__('Select guests', 'routiz'),
                'adults' => esc_html__('Adults', 'routiz'),
                'children' => esc_html__('Children', 'routiz'),
                'infants' => esc_html__('Infants', 'routiz'),
                'close' => esc_html__('Close', 'routiz'),
                'guest_max' => esc_html__('%s guest maximum. Infants don’t count toward the number of guests.', 'routiz'),
                'guests_max' => esc_html__('%s guests maximum. Infants don’t count toward the number of guests.', 'routiz'),
            ]
        ]);

    }

}
