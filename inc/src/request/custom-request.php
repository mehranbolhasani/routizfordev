<?php

namespace Routiz\Inc\Src\Request;

class Custom_Request extends Request {

    public $params;

    function __construct( $params ) {

        if( is_string( $params ) ) {
            $params = isset( $_REQUEST[ $params ] ) ? $_REQUEST[ $params ] : [];
        }

        $this->params = Rz()->sanitize( $params );

    }

}
