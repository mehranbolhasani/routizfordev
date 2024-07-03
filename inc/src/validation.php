<?php

namespace Routiz\Inc\Src;

class Validation {

    protected $result = [];

    function __construct() {}

    public function validate( $data, $rules ) {

        $validation_errors = [];
        $success = true;
        $this->result = [];

        if( is_array( $data ) ) { $data = (object) $data; }

        foreach( $rules as $field_name => $field_rule ) {

            $field_rule = array_filter( explode( '|', $field_rule ) );

            foreach( $field_rule as $field_rule_string ) {

                $matches = [];
                preg_match( '/^([a-zA-Z0-9-_]+)[:]?([a-zA-Z0-9,-_]+)?$/', $field_rule_string, $matches );

                $rule_key = $matches[1];
                $rule_params = isset( $matches[2] ) ? explode( ',', $matches[2] ) : false;

                if( is_callable( [ $this, $rule_key ] ) ) {

                    $value = isset( $data->{$field_name} ) ? $data->{$field_name} : '';

                    $validation_passed = call_user_func(
                        [ $this, $rule_key ],
                        $value, $rule_params, $data
                    );

                    if( ! $validation_passed->pass ) {

                        $validation_errors[ $field_name ] = $validation_passed->error_message;
                        $this->result[ $field_name ] = $rule_key;

                        $success = false;
                        break;

                    }else{

                        // passed the validation

                    }

                }else{

                    $validation_errors[ $field_name ] = '__validation_not_found: (' . $rule_key . ')';
                    $success = false;
                    break;

                }
            }
        }

        // return the results
        return (object) [
            'success' => $success,
            'errors' => $validation_errors,
        ];

    }

    public function get_result() {
        return $this->result;
    }

    /*
     * required
     *
     */
    public function required( $value, $params, $data ) {

        if( is_array( $value ) ) {
            $pass = false;
            foreach( $value as $val ) {
                if( ! empty( $val ) ) {
                    $pass = true;
                }
            }
        }else{
            $pass = ! empty( $value );
        }

        return (object) [
            'pass' => $pass,
            'error_message' => esc_html__( 'Required field', 'routiz' )
        ];
    }

    /*
     * min
     *
     */
    public function min( $value, $params ) {
        return (object) [
            'pass' => strlen( $value ) >= (int) $params[0],
            'error_message' => esc_html__( 'Too short', 'routiz' )
        ];
    }

    /*
     * max
     *
     */
    public function max( $value, $params ) {
        return (object) [
            'pass' => strlen( $value ) <= (int) $params[0],
            'error_message' => esc_html__( 'Too long', 'routiz' )
        ];
    }

    /*
     * email
     *
     */
    public function email( $value ) {
        return (object) [
            'pass' => filter_var( $value, FILTER_VALIDATE_EMAIL ),
            'error_message' => esc_html__( 'Not a valid email', 'routiz' )
        ];
    }

    /*
     * email_exists
     *
     */
    public function email_exists( $value ) {
        return (object) [
            'pass' => email_exists( $value ),
            'error_message' => esc_html__( 'Wrong username or password', 'routiz' )
        ];
    }

    /*
     * username_exists
     *
     */
    public function username_exists( $value ) {
        return (object) [
            'pass' => username_exists( $value ),
            'error_message' => esc_html__( 'Wrong username or password', 'routiz' )
        ];
    }

    /*
     * email_not_exists
     *
     */
    public function email_not_exists( $value ) {
        return (object) [
            'pass' => ! email_exists( $value ),
            'error_message' => esc_html__( 'This email is already registered', 'routiz' )
        ];
    }

    /*
     * username_not_exists
     *
     */
    public function username_not_exists( $value ) {
        return (object) [
            'pass' => ! username_exists( $value ),
            'error_message' => esc_html__( 'This username is already registered', 'routiz' )
        ];
    }

    /*
     * numeric
     *
     */
    public function numeric( $value ) {
        return (object) [
            'pass' => is_numeric( $value ),
            'error_message' => esc_html__( 'Should be a number', 'routiz' )
        ];
    }

    /*
     * whole number
     *
     */
    public function number( $value ) {
        return (object) [
            'pass' => is_int( $value ),
            'error_message' => esc_html__( 'Should be a whole number', 'routiz' )
        ];
    }

    /*
     * match
     *
     */
    public function match( $value, $params, $data ) {
        return (object) [
            'pass' => isset( $data->{$params[0]} ) and ( $value == $data->{$params[0]} ),
            'error_message' => esc_html__( 'Doesn\t match', 'routiz' )
        ];
    }

    /*
     * recaptcha
     *
     */
    public function recaptcha( $value, $params, $data ) {

        $passed = false;

        if( ! empty( $value ) ) {

            $secret = 'YOUR_RECAPTCHA_SECRET_KEY';

            $request = wp_remote_get( "https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$value}" );
            $response = wp_remote_retrieve_body( $request );

            if ( ! is_wp_error( $response ) ) {
                $output = json_decode( $response );
                if( $output->success ) {
                     $passed = true;
                }
            }

        }

        return (object) [
            'pass' => $passed,
            'error_message' => esc_html__( 'Are you a robot?', 'routiz' )
        ];

    }

}
