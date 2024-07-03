<?php

namespace Routiz\Inc\Src\Form\Modules\Range;

use \Routiz\Inc\Src\Form\Modules\Module;

class Range extends Module {

    public function before_construct() {
        $this->defaults += [
            'suffix' => '',
            'separator' => '-',
            // 'input_type' => 'number',
        ];
        $this->defaults['placeholder'] = [ '', '' ];
    }

    public function after_build() {

        $this->attrs['data-input-type'] = $this->props->input_type;

        $this->props->single = false;

        $number_attrs = [];

        if( isset( $this->props->min ) and ! empty( $this->props->min ) ) { $number_attrs['min'] = $this->props->min; }
        if( isset( $this->props->max ) and ! empty( $this->props->max ) ) { $number_attrs['max'] = $this->props->max; }
        if( isset( $this->props->step ) and ! empty( $this->props->step ) ) { $number_attrs['step'] = $this->props->step; }

        $this->props->number_attrs = $number_attrs;

    }

    public function finish() {
        if ($this->props->value !== null) {
            $this->props->value = is_array($this->props->value) ? $this->props->value : json_decode($this->props->value);
        }
    }


}
