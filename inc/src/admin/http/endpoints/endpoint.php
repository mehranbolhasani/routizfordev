<?php

namespace Routiz\Inc\Src\Admin\Http\Endpoints;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Endpoint {

	public $action;

    public function __construct() {

		add_action( sprintf( 'wp_ajax_%s', $this->action ), [ $this, 'action' ] );
		add_action( sprintf( 'wp_ajax_nopriv_%s', $this->action ), [ $this, 'action' ] );

	}

    public function action() {}

}
