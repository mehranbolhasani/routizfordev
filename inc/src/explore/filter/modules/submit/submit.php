<?php

namespace Routiz\Inc\Src\Explore\Filter\Modules\Submit;

use \Routiz\Inc\Src\Explore\Filter\Modules\Module;
use \Routiz\Inc\Src\Explore\Filter\Comparison;

class Submit extends Module {

    public function query() {}

    public function get() {

        $this->before_get();

        $field = $this->component->form->create( (array) $this->props );

        if( ! $field instanceof \Routiz\Inc\Src\Error ) {
            return sprintf( $this->wrapper(), $this->props->type, $field->get() );
        }

    }

}
