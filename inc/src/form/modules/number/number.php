<?php

namespace Routiz\Inc\Src\Form\Modules\Number;

use \Routiz\Inc\Src\Form\Modules\Module;

class Number extends Module {

    public function before_construct() {
        $this->defaults += [
            'input_type' => 'number', // number, range, stepper
            'format' => '<strong>%s</strong>',
            'style' => 'v1',
        ];
    }

    public function after_construct() {
        if( empty( $this->props->input_type ) ) {
            $this->props->input_type = $this->defaults['input_type'];
        }
        if( $this->props->input_type == 'range' or $this->props->input_type == 'stepper' ) {
            if( empty( $this->props->value ) ) {
                $this->props->value = ( isset( $this->props->min ) and ! empty( $this->props->min ) ) ? $this->props->min : 0;
            }
        }
    }

    public function after_build() {

        $this->attrs['data-input-type'] = $this->props->input_type;

        $number_attrs = [];

        if( isset( $this->props->min ) and is_numeric( $this->props->min ) ) { $number_attrs['min'] = floatval( $this->props->min ); }
        if( isset( $this->props->max ) and is_numeric( $this->props->max ) ) { $number_attrs['max'] = floatval( $this->props->max ); }
        if( isset( $this->props->step ) and is_numeric( $this->props->step ) ) { $number_attrs['step'] = floatval( $this->props->step ); }

        $number_attrs['data-format'] = $this->props->format;

        $this->props->number_attrs = $number_attrs;

    }

}
