<?php

namespace Routiz\Inc\Src\Explore\Filter\Modules\Range;

use \Routiz\Inc\Src\Explore\Filter\Modules\Module;
use \Routiz\Inc\Src\Explore\Filter\Comparison;

class Range extends Module {

    public function get_label() {
        return $this->props->value;
    }

    public function before_get() {

        $this->props->placeholder = [
            $this->props->placeholder_from,
            $this->props->placeholder_to
        ];

    }

    public function get() {

        $this->before_get();

        $field = $this->component->form->create( (array) $this->props );

        if( ! $field instanceof \Routiz\Inc\Src\Error ) {
            return sprintf( $this->wrapper(), $this->props->type, $field->get() );
        }

    }

    public function query() {

        $this->props->value = [
            ( isset( $this->props->value[0] ) and $this->props->value[0] !== '' ) ? $this->props->value[0] : 0,
            ( isset( $this->props->value[1] ) and $this->props->value[1] !== '' ) ? $this->props->value[1] : 999999999,
        ];

        $comparison = new Comparison( $this->props );
        return $comparison->between()->get();

    }

}
