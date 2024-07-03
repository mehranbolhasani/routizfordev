<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

if( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Upload_Icon_Set extends Endpoint {

	public $action = 'rz_upload_icon_set';

    public function action() {

		add_filter( 'map_meta_cap', [ $this,'unrestricted_upload_filter'], 0, 2 );

		$data = (object) Rz()->sanitize( $_POST );

		if( ! $data->post_id ) {
			return;
		}

		// remove previous set
		$this->delete_set( $data->post_id );

		if( current_user_can( 'upload_files' ) ) {

			// handle media upload
			$id = media_handle_upload(
				'rz_file_upload',
				0,
				[
					'test_form' => true,
					'action' => 'routiz-icon-set-upload-action',
				]
			);
		}

		// create the directory
		if( ! file_exists( RZ_ICONS_PATH ) ) {
			wp_mkdir_p( RZ_ICONS_PATH );
		}

		$package_path = get_attached_file( $id );
		$status = false;

		if( $package_path && file_exists( $package_path ) ) {

			$icon_dir_name = $this->get_unique_dir_name( pathinfo( $package_path, PATHINFO_FILENAME ), RZ_ICONS_PATH );
			$icon_set_path = RZ_ICONS_PATH . $icon_dir_name;

			// create icon set directory
			wp_mkdir_p( $icon_set_path );

			// extract the zip file
			if( class_exists( 'ZipArchive' ) ) {
				$zip = new \ZipArchive();
				if( true === $zip->open( $package_path ) ) {
					$zip->extractTo( $icon_set_path );
					$zip->close();
					$status = true;
				}
			}else{
				$status = unzip_file( $package_path, $icon_set_path );
			}
		}

		if( $status == true ) {

			$icon_set['icon_dir_name'] = $icon_dir_name;
			$parsed_package = $this->parse_icons( $icon_dir_name );

			foreach( $parsed_package as $key => $value ) {
				$icon_set[ $key ] = $parsed_package[ $key ];
			}

			$icon_set['icon_set_id'] = md5( $data->post_id . $id );

			update_post_meta( $data->post_id, 'rz_custom_icon_set', $icon_set );
			update_post_meta( $id, 'ruutiz_icon_set_id', $icon_set['icon_set_id'] );

		}

		wp_send_json([
			'success' => true
		]);

	}

	protected function get_unique_dir_name( $name, $parent_path ) {

		$parent_path = trailingslashit( $parent_path );
		$path = $parent_path . $name;

		$counter = 0;
		$tmp_name = $name;

		while( file_exists( $path ) ) {
			$counter++;
			$name = $tmp_name . '-' . $counter;
			$path = $parent_path . $name;
		}

		return $name;

	}

	protected function parse_icons( $icon_dir_name ) {

		// the configuration
		$icons_config = $this->get_package_config( $icon_dir_name );

		if( ! $icons_config ) {
			return [];
		}

		$parsed_package = [];
		$parsed_package['icons'] = [];

		foreach( $icons_config['icons'] as $icon ) {
			$parsed_package['icons'][] = $icon['properties']['name'];
		}

		$parsed_package['prefix'] = $icons_config['preferences']['fontPref']['prefix'];
		$parsed_package['icon_count'] = count( $parsed_package['icons'] );

		return $parsed_package;
	}

	protected function get_package_config( $icon_dir_name ) {

		global $wp_filesystem;

		if( empty( $wp_filesystem ) ) {
			require_once wp_normalize_path( ABSPATH . '/wp-admin/includes/file.php' );
			WP_Filesystem();
		}

		if ( ! isset( $this->package_config[ $icon_dir_name ] ) ) {
			$json_file = $wp_filesystem->get_contents( RZ_ICONS_PATH . '/' . $icon_dir_name . '/selection.json' );
			$this->package_config[ $icon_dir_name ] = json_decode( $json_file, true );
		}

		return $this->package_config[ $icon_dir_name ];
	}

	public function unrestricted_upload_filter( $caps, $cap ) {

		if( $cap == 'unfiltered_upload' ) {
			$caps = array();
			$caps[] = $cap;
		}

		return $caps;

	}

	public function delete_set( $post_id ) {

		global $wp_filesystem;

		if( empty( $wp_filesystem ) ) {
			require_once wp_normalize_path( ABSPATH . '/wp-admin/includes/file.php' );
			WP_Filesystem();
		}

		if( get_post_type( $post_id ) !== 'rz_icon_set' ) {
			return;
		}

		$icon_set = Rz()->get_meta( 'rz_custom_icon_set', $post_id );

		if( isset( $icon_set['icon_dir_name'] ) ) {
			$icon_set_path = RZ_ICONS_PATH . $icon_set['icon_dir_name'];

			// delete set directory
			$wp_filesystem->rmdir( $icon_set_path, true );
		}
	}

}
