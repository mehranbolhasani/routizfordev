<?php

namespace Routiz\Inc\Src\Request;

class Request {

    use \Routiz\Inc\Src\Traits\Singleton;

    // Define properties explicitly to avoid deprecation warnings
    public $params;
    public $method;

    function __construct() {

        $this->params = Rz()->sanitize( $_REQUEST );
        $this->method = $_SERVER['REQUEST_METHOD'];

    }

    public function has( $id ) {
        return isset( $this->params[ $id ] );
    }

    public function is_empty( $id ) {

        if( ! $this->has( $id ) ) {
            return true;
        }

        if( is_array( $this->params[ $id ] ) ) {
            return strlen( implode( $this->params[ $id ] ) ) == 0;
        }else{
            return empty( $this->params[ $id ] );
        }

    }

    public function get( $id ) {
        return $this->has( $id ) ? $this->params[ $id ] : false;
    }

    public function parse( $value ) {
        if ( is_string( $value ) and strpos( $value, ',' ) !== false ) {
            return explode( ',', $value );
        }
        return $value;
    }

}
