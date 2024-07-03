<?php

namespace Routiz\Inc\Src\Explore\Filter\Modules\Number;

use \Routiz\Inc\Src\Explore\Filter\Modules\Module;
use \Routiz\Inc\Src\Explore\Filter\Comparison;

class Number extends Module {

    public function query() {

        $comparison = new Comparison( $this->props );
        return $comparison->compare( $this->props->comparison )->get();

    }

    public function get_label() {
        return $this->props->value;
    }

    public function get() {

        $this->before_get();

        $field = $this->component->form->create( (array) $this->props );

        if( ! $field instanceof \Routiz\Inc\Src\Error ) {
            return sprintf( $this->wrapper(), $this->props->type, $field->get() );
        }

    }

}
