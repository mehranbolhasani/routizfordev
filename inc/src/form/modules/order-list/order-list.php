<?php

namespace Routiz\Inc\Src\Form\Modules\Order_List;

use \Routiz\Inc\Src\Form\Modules\Module;

class Order_List extends Module {

    public function before_construct() {
        $this->defaults += [
            'options' => [],
            'repeater_id' => '',
            'repeater_empty_notify' => esc_html__( 'No options were found.', 'routiz' ),
            'single' => false,
            'allow_empty' => true
        ];
    }

    public function after_build() {
        $this->props->single = false;
        $this->props->options = $this->component->extract_options( $this->props );
    }

    public function controller() {

        $split = [
            'available' => [],
            'active' => []
        ];

        if( is_array( $this->props->options ) ) {
            // available
            foreach( $this->props->options as $option_id => $option_name ) {
                if( empty( $option_id ) ) {
                    continue;
                }
                if( ! is_array( $this->props->value ) or ! in_array( $option_id, $this->props->value ) ) {
                    $split['available'][ $option_id ] = $option_name;
                }
            }
            // active
            if( is_array( $this->props->value ) ) {
                foreach( $this->props->value as $value ) {
                    if( empty( $value ) ) {
                        continue;
                    }
                    if( isset( $this->props->options[ $value ] ) ) {
                        $split['active'][ $value ] = $this->props->options[ $value ];
                    }
                }
            }
        }

        return array_merge( (array) $this->props, [
            'split' => $split,
            'strings' => (object) [
                'available' => esc_html__( 'Available', 'routiz' ),
                'active' => esc_html__( 'Active', 'routiz' ),
            ],
        ]);

    }

}
