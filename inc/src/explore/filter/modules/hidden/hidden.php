<?php

namespace Routiz\Inc\Src\Explore\Filter\Modules\Hidden;

use \Routiz\Inc\Src\Explore\Filter\Modules\Module;
use \Routiz\Inc\Src\Explore\Filter\Comparison;

class Hidden extends Module {

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
