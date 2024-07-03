<?php

namespace Routiz\Inc\Src\Form\Modules\Submit;

use \Routiz\Inc\Src\Form\Modules\Module;

class Submit extends Module {

    public function controller() {

        $id = $class = '';

        if( isset( $this->props->button ) ) {

            $this->props->button = (object) $this->props->button;

            if( isset( $this->props->button->id ) ) {
                $id = sprintf( ' id="%s"', $this->props->button->id );
            }

            if( isset( $this->props->button->class ) ) {
                $class = is_array( $this->props->button->class ) ? implode( ' ', $this->props->button->class ) : $this->props->button->class;
            }

        }

        return array_merge( (array) $this->props, [
            'button' => (object) [
                'id' => $id,
                'class' => $class,
            ]
        ]);

    }

}
