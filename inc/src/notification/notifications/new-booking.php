<?php

namespace Routiz\Inc\Src\Notification\Notifications;

class New_Booking extends \Routiz\Inc\Src\Notification\Base {

    public $user_can_manage = true;

    /*
     * general
     *
     */
    public function get_id() {
        return 'new-booking';
    }

    public function get_name() {
        return esc_html__('New reservation has been received', 'routiz');
    }

    /*
     * email
     *
     */
    public function get_email_subject() {
        return esc_html__( 'New reservation has been received', 'routiz' );
    }

    public function get_email_template() {
        return esc_html__( "Hello {user_display_name},\r\n\r\nYou have received a new reservation.", 'routiz' );
    }

    /*
     * email admin
     *
     */
    public function get_email_admin_subject() {
        return esc_html__( 'New reservation', 'routiz' );
    }

    public function get_email_admin_template() {
        return esc_html__( "Hello,\r\n\r\nA new reservation has been sent", 'routiz' );
    }

    /*
     * site
     *
     */
    public function get_site_icon() {
        return 'material-icon-date_range';
    }

    public function get_site_message() {
        return esc_html__( 'New reservation has been received', 'routiz' );
    }

    public function get_site_url() {
        if( function_exists('wc_get_account_endpoint_url') ) {
            return wc_get_account_endpoint_url( 'entries' );
        }
    }

    public function after_send() {

        if( ! $this->params['listing']->id ) {
            return;
        }

        if( ! $this->params['request_user_id'] ) {
            return;
        }

        $params = array_merge([
            'guests' => 0,
            'checkin' => '',
            'checkout' => '',
        ], $this->params );

        if( $this->params['listing']->type->get('rz_enable_reservation_webhook') ) {
            if( $reservation_webhook_url = $this->params['listing']->type->get('rz_reservation_webhook') ) {

                // check for valid webhook url
                if( filter_var( $reservation_webhook_url, FILTER_VALIDATE_URL ) === false ) {
                    return;
                }

                $request_user_id = $this->params['request_user_id'];
                $userdata = get_userdata( $request_user_id );

                // date
    			$date_format = get_option('date_format');
    			$time_format = get_option('time_format');

                $res_data = [
                    'guests' => $params['guests'],
                    'unix_checkin' => $params['checkin'],
                    'date_checkin' => date( $date_format, $params['checkin'] ),
                    'time_checkin' => date( $time_format, $params['checkin'] ),
                    'datetime_checkin' => date( sprintf( '%s %s', $date_format, $time_format ), $params['checkin'] ),
                ];

                if( isset( $params['checkout'] ) and ! empty( $params['checkout'] ) ) {
                    $res_data = array_merge( $res_data, [
                        'unix_checkout' => $params['checkout'],
                        'date_checkout' => date( $date_format, $params['checkout'] ),
                        'time_checkout' => date( $time_format, $params['checkout'] ),
                        'datetime_checkout' => date( sprintf( '%s %s', $date_format, $time_format ), $params['checkout'] ),
                    ]);
                }

                $data = array_merge( $res_data, $this->get_data() );

                // reservation custom fields
                $reservation_webhook_custom_fields = $this->params['listing']->type->get('rz_reservation_webhook_custom_fields');
                if( isset( $this->meta['listing_id'] ) and ! empty( $reservation_webhook_custom_fields ) ) {
                    if( $listing_id = (int) $this->meta['listing_id'] ) {

                        foreach( array_map( 'trim', explode( ',', $reservation_webhook_custom_fields ) ) as $wh_custom_field ) {
                            $wh_custom_field = array_map( 'trim', explode( ':', $wh_custom_field ) );

                            if( isset( $wh_custom_field[0] ) and isset( $wh_custom_field[1] ) ) {
                                $wh_param_name = $wh_custom_field[0];
                                $wh_meta_key = $wh_custom_field[1];
                                $data[ sanitize_title( $wh_param_name ) ] = Rz()->get_meta( Rz()->prefix( $wh_meta_key ), $listing_id );
                            }
                        }
                    }
                }

                $ch = curl_init( $reservation_webhook_url );
                curl_setopt( $ch, CURLOPT_HTTPHEADER, [
                    'Content-type: application/json'
                ]);
                curl_setopt( $ch, CURLOPT_POST, 1);
                curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ));
                curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt( $ch, CURLOPT_HEADER, 0);
                curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

                $response = curl_exec( $ch );

                curl_close( $ch );

            }
        }
    }
}
