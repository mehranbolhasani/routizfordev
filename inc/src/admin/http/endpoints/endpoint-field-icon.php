<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Field_Icon extends Endpoint {

	public $action = 'rz_icon';

    public function action() {

		$request = \Routiz\Inc\Src\Request\Request::instance();

		if( $request->has('set') ) {

			$icons = [];

			switch( $request->get('set') ) {
				case 'font-awesome':
					$set = \Routiz\Inc\Src\Form\Modules\Icon\Sets\Font_Awesome::instance();
					break;
				case 'material-icons':
					$set = \Routiz\Inc\Src\Form\Modules\Icon\Sets\Material_Icons::instance();
					break;
				case 'amenities':
					$set = \Routiz\Inc\Src\Form\Modules\Icon\Sets\Amenities::instance();
					break;
				// custom set
				default:

					$custom_sets = routiz()->icon->get_custom_sets();

					if( isset( $custom_sets[ $request->get('set') ] ) ) {

						foreach( $custom_sets[ $request->get('set') ]['icons'] as $icon ) {
							$active = ( $icon == $request->get('active') ) ? 'rz-active' : '';
							$prefix = $custom_sets[ $request->get('set') ]['prefix'];
							$icons[] = "<li class='{$active}' title='{$prefix} {$prefix}{$icon}'><a href='#'><i class='{$prefix} {$prefix}{$icon}'></i></a></li>";
						}

						wp_send_json([
							'success' => true,
							'icons' => implode( '', $icons )
						]);

					}

			}

			foreach( $set->get() as $icon ) {
				$active = ( $icon == $request->get('active') ) ? 'rz-active' : '';
				$icons[] = "<li class='{$active}' title='{$icon}'><a href='#'><i class='{$icon}'></i></a></li>";
			}

			wp_send_json([
				'success' => true,
				'icons' => implode( '', $icons )
			]);

		}

	}

}
