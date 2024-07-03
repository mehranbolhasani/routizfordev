<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

use \Routiz\Inc\Src\Form\Component as Form;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Field_Repeater extends Endpoint {

	public $action = 'rz_repeater';

    public function action() {

		$data = (object) Rz()->sanitize( $_POST );
        $schema = Rz()->json_decode( stripslashes( $data->schema ), true );

		if( isset( $data->post_id ) ) {

			$post_id = (int) $data->post_id;

			global $post;
			$post = get_post( $post_id, OBJECT );
			setup_postdata( $post ); // set post globally

		}

        $form = new Form( $data->storage );

        ob_start();

		$name = $schema['name'];
		if( isset( $schema['heading'] ) ) {
			$heading_id = $schema['heading'];
			$heading_text = isset( $schema['fields'][ $heading_id ]['value'] ) ? $schema['fields'][ $heading_id ]['value'] : '';
		}

        $form->render([
			'type' => 'repeater-item',
			'schema' => $schema,
			'parent' => $form->create([
				'type' => 'repeater'
			]),
			'template' => (object) [
				'id' => $data->template,
				'name' => $name,
				'heading' => isset( $heading_id ) ? $heading_id : null,
				'heading_text' => isset( $heading_text ) ? $heading_text : null,
			]
        ]);

		if( isset( $data->post_id ) ) {
			wp_reset_postdata();
		}

        wp_send_json([
            'success' => true,
            'html' => ob_get_clean()
        ]);

	}

}
