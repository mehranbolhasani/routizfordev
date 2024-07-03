<?php

namespace Routiz\Inc\Src\Form\Modules\Term;

use \Routiz\Inc\Src\Form\Modules\Module;

class Term extends Module {

    public function before_construct() {

        $this->defaults += [
            'taxonomy' => '',
            'multiple' => true,
        ];

    }

    public function after_build() {

        $this->attrs['data-taxonomy'] = $this->props->taxonomy;

    }

    public function controller() {

        $terms = [];
        $terms_values = is_string( $this->props->value ) ? Rz()->json_decode( $this->props->value ) : $this->props->value;

        if( isset( $terms_values->taxonomy ) ) {

            $the_terms = get_terms( Rz()->prefix( $terms_values->taxonomy ), [
				'hide_empty' => false,
			]);

			if( ! is_wp_error( $the_terms ) ) {
				foreach( $the_terms as $the_term ) {
                    $terms[ $the_term->term_id ] = $the_term->name;
                }
			}

        }

        return [
            'props' => (array) $this->props,
            'component' => $this->component,
            'terms' => $terms,
            'term_values' => $terms_values
        ];

    }

}
