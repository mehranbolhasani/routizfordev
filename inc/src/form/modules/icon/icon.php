<?php

namespace Routiz\Inc\Src\Form\Modules\Icon;

use \Routiz\Inc\Src\Form\Component as Form;
use \Routiz\Inc\Src\Form\Modules\Module;

class Icon extends Module {

    public function controller() {

        $form = new Form( Form::Storage_Meta );

        $sets_options = [
            'font-awesome' => esc_html__( 'Font Awesome', 'routiz' ),
            'material-icons' => esc_html__( 'Material Icons', 'routiz' ),
            'amenities' => esc_html__( 'Amenities', 'routiz' ),
        ];

        foreach( routiz()->icon->get_custom_sets() as $set_key => $set ) {
            $sets_options[ $set_key ] = $set['name'];
        }

        return array_merge( (array) $this->props, [
            'form' => $form,
            'set' => Rz()->get_meta( $this->id . '__set' ),
            'sets_options' => $sets_options,
            'strings' => (object) [
                'select' => esc_html__( 'Click to Select Icon', 'routiz' ),
                'search' => esc_html__( 'Search icon ...', 'routiz' ),
                'remove' => esc_html__( 'Remove icon', 'routiz' ),
            ]
        ]);

    }

    public function before_save( $post_id, $value ) {

        update_post_meta( $post_id, $this->props->id . '__set', sanitize_text_field( $_POST[ $this->props->id . '__set' ] ) );

        return $value;

    }

}
