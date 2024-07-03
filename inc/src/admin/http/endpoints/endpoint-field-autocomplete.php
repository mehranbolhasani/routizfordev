<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Listing_Type\Listing_Type;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint_Field_Autocomplete extends Endpoint {

	public $action = 'rz_autocomplete';

    public function action() {

		global $wpdb;

		$request = Request::instance();

		if( ! $request->has('taxonomy') ) {
			return;
		}

		$total = $limit = apply_filters( 'routiz/search/quick/limit', 8 );

		$content = '';

		$terms = get_terms([
			'taxonomy' => Rz()->prefix( $request->get('taxonomy') ),
			'hide_empty' => false,
			'fields' => 'all',
			'name__like' => $request->get('search'),
			'number' => $limit,
		]);

		if( ! is_wp_error( $terms ) ) {

			global $rz_row;

			$limit -= count( $terms );

			if( ! empty( $terms ) ) {

				$icon = null;
				if( ! $request->is_empty('icon') ) {
					$icon = $request->get('icon');
				}

				foreach( $terms as $term ) {

					$rz_row = $term;
					if( $term_icon = get_term_meta( $term->term_id, 'rz_icon', true ) ) {
						$icon = $term_icon;
					}

					$rz_row->icon = $icon;

					ob_start();
					include RZ_PATH . 'inc/src/form/modules/autocomplete/row.php';
					$content .= ob_get_clean();

				}
			}
		}

        $return = [
            'success' => true,
            'content' => $content,
            'found' => $total - $limit,
        ];

        wp_send_json( $return );

	}

}
