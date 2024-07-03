<?php

namespace Routiz\Inc\Src;

class Error {

    public $errors = [];

    function __construct( $error ) {
        $this->errors[] = $error;
    }

    public function display_error() {
        echo sprintf( '<div class="rz-alert-error"><ul><li>%s</li></ul></div>', implode( '</li><li>', $this->errors ) );
    }

}
