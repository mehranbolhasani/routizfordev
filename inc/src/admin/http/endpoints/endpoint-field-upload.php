<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

use \Routiz\Inc\Src\Request\Request;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Field_Upload extends Endpoint {

	public $action = 'rz_upload';

	public $image_mime_types = [
		'image/jpeg',
		'image/png'
	];

    public function action() {

		add_action( 'rz_upload_action', [ $this, sanitize_text_field( $_POST['rz_action'] ) ] );
		die( do_action( 'rz_upload_action' ) );

	}

	public function upload() {

		$request = Request::instance();

		if( $request->is_empty('upload_type') or ! in_array( $request->get('upload_type'), [ 'image', 'file' ] ) ) {
			wp_send_json([
				'success' => false,
				'error' => esc_html__( 'Something went wrong', 'routiz' )
			]);
		}

		$data = isset( $_FILES ) ? $_FILES : [];

		$response['success'] = false;

		$wordpress_upload_dir = wp_upload_dir();
		// $wordpress_upload_dir['path'] is the full server path to wp-content/uploads/2017/05, for multisite works good as well
		// $wordpress_upload_dir['url'] the absolute URL to the same folder, actually we do not need it, just to show the link to file
		$i = 1; // number of tries when the file with the same name is already exists

		$upload = $data['rz_file_upload'];

		$filetype = wp_check_filetype_and_ext( $upload['tmp_name'], $upload['name'] );
		$filename = sanitize_file_name($upload['name']);

		$new_file_path = $wordpress_upload_dir['path'] . '/' . $filename;
		$new_file_mime = wp_check_filetype_and_ext( $new_file_path, $filename );

		$upload_type = $request->get('upload_type');
		if( $upload_type == 'image' ) {
			if( ! in_array( $new_file_mime['type'], $this->image_mime_types ) ) {
				wp_send_json([
					'success' => false,
					'error' => esc_html__( 'File type is not allowed', 'routiz' )
				]);
			}
		}

		if( empty( $upload ) ) {
			wp_send_json([
				'success' => false,
				'error' => esc_html__( 'File is not selected', 'routiz' )
			]);
		}

		if( $upload['error'] ) {
			wp_send_json([
				'success' => false,
				'error' => $upload['error']
			]);
		}

		if( $upload['size'] > wp_max_upload_size() ) {
			wp_send_json([
				'success' => false,
				'error' => esc_html__( 'It is too large than expected', 'routiz' )
			]);
		}

		if( ! in_array( $new_file_mime['type'], get_allowed_mime_types() ) ) {
			wp_send_json([
				'success' => false,
				'error' => esc_html__( 'File type is not allowed', 'routiz' )
			]);
		}

		while( file_exists( $new_file_path ) ) {
			$i++;
			$new_file_path = $wordpress_upload_dir['path'] . '/' . $i . '_' . $filename;
		}

		// looks like everything is OK
		if( move_uploaded_file( $upload['tmp_name'], $new_file_path ) ) {

			$upload_id = wp_insert_attachment([
				'guid'           => $new_file_path,
				'post_mime_type' => $new_file_mime['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', $filename ),
				'post_content'   => '',
				'post_status'    => 'inherit'
			], $new_file_path );

			// generate and save the attachment meta data
			wp_update_attachment_metadata( $upload_id, wp_generate_attachment_metadata( $upload_id, $new_file_path ) );

			$image_attributes = wp_get_attachment_image_src( $upload_id, 'rz_thumbnail' );

			$response = [
				'success' => true,
				'id' => $upload_id,
				'thumb' => ( $upload_type == 'image' and isset( $image_attributes[0] ) ) ? $image_attributes[0] : null,
				'url' => wp_get_attachment_url( $upload_id ),
				'name' => basename( get_attached_file( $upload_id ) ),
			];

		}

		wp_send_json( $response );

	}

	public function delete() {

		// TODO ..

	}

}
