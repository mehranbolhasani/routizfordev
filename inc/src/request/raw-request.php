<?php

namespace Routiz\Inc\Src\Request;

class Raw_Request extends Request {

    public $params;

    function __construct( $params ) {

        if( is_string( $params ) ) {
            $params = isset( $_REQUEST[ $params ] ) ? $_REQUEST[ $params ] : [];
        }

        $this->params = Rz()->to_array( $params );

    }

}
