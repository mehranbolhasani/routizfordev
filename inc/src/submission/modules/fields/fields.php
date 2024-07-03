<?php

namespace Routiz\Inc\Src\Submission\Modules\Fields;

use \Routiz\Inc\Src\Submission\Submission;
use \Routiz\Inc\Src\Submission\Modules\Module;
use \Routiz\Inc\Src\Request\Custom_Request;
use \Routiz\Inc\Src\Form\Component as Form;
use \Routiz\Inc\Src\Validation;

class Fields extends Module {

    public function controller() {

        return [
            'form' => new Form( Form::Storage_Request ),
            'title' => $this->props->title,
            'fields' => $this->props->fields,
            'strings' => (object) [
                'not_found' => esc_html__('No listing fields were found', 'routiz'),
            ]
        ];

    }

    public function validation() {

        $request = new Custom_Request('input');
        $form = new Form( Form::Storage_Request );
        $submission = new Submission( $request->get('type') );

        $terms = [];

        foreach( $submission->tabs[ $this->props->group ]['content'] as $item ) {
            if( $item->fields->is_submit_form and isset( $item->fields->required ) and $item->fields->required == true ) {

                $field = $form->create(
                    Rz()->prefix_item( $item )
                );

                if( empty( $field->props->id ) ) {
                    continue;
                }

                $terms[ $field->props->id ] = 'required';

            }
        }

        $validation = new Validation();
		$response = $validation->validate( $request->params, $terms );

        return $response;

    }

}
