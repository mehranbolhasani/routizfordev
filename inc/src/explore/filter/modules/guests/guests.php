<?php

namespace Routiz\Inc\Src\Explore\Filter\Modules\Guests;

use \Routiz\Inc\Src\Explore\Filter\Modules\Module;
use \Routiz\Inc\Src\Explore\Filter\Comparison;
use \Routiz\Inc\Src\Form\Component as Form;

class Guests extends Module {

    public function query() {

        $comparison = new Comparison( $this->props );
        return $comparison->greater_or_equal()->get();

    }

    public function get_label() {
        return $this->props->value;
    }

    public function get() {

        $this->before_get();

        $field = $this->component->form->create( array_merge( (array) $this->props, [
            'show_info_guests' => true,
            'info_guests' => esc_html__( 'Select the number of guests, infants don’t count toward the number of guests.', 'routiz' ),
        ]));

        if( ! $field instanceof \Routiz\Inc\Src\Error ) {
            return sprintf( $this->wrapper(), $this->props->type, $field->get() );
        }

    }

}
