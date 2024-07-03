<?php

namespace Routiz\Inc\Src\Notification;

abstract class Base {

    // the user id to receive the message
    public $user_id = 0;

    // the receiver user data
    public $userdata = null;

    // meta data, typically holding ids
    public $meta = [];

    // meta defaults
    public $meta_defaults = [
        'from_user_id'  => null,
        'listing_id'    => null,
        'entry_id'      => null,
        'claim_id'      => null,
        'comment_id'    => null,
    ];

    // if the user can enable / disable the notification through the user notification area
    public $user_can_manage = false;

    // parameters send to generate the notification
    public $params = [];

    function __construct( $args = [], $params = [] ) {

        if( is_array( $args ) ) {
            $args = (object) $args;
        }

        // check if user
        // if( ! isset( $args->user_id ) ) {
        //     return;
        // }

        if( isset( $args->user_id ) ) {
            if( $this->user_id = (int) $args->user_id ) {
                $this->userdata = get_userdata( $this->user_id );
            }
        }

        // meta
        if( isset( $args->meta ) ) {
            $this->meta = is_serialized( $args->meta ) ? maybe_unserialize( $args->meta ) : $args->meta;
            if( ! is_array( $this->meta ) ) {
                $this->meta = [];
            }
        }

        $this->meta = array_merge( $this->meta_defaults, $this->meta );

        if( $params ) {
            $this->params = $params;
        }

    }

    protected function get_user_email() {
        return $this->userdata->user_email;
    }

    protected function get_user_name() {
        return $this->userdata->display_name;
    }

    protected function get_user_first_name() {
        return $this->userdata->first_name;
    }

    protected function get_user_last_name() {
        return $this->userdata->last_name;
    }

    abstract public function get_id();

    abstract public function get_name();

    public function is_email_enabled() {
        return get_option( sprintf( 'rz_is_notification_email_%s', $this->get_id() ) );
    }

    public function is_email_admin_enabled() {
        return get_option( sprintf( 'rz_is_notification_email_admin_%s', $this->get_id() ) );
    }

    public function is_site_enabled() {
        return get_option( sprintf( 'rz_is_notification_site_%s', $this->get_id() ) );
    }

    public function is_webhook_enabled() {
        return get_option( sprintf( 'rz_is_notification_webhook_%s', $this->get_id() ) );
    }

    public function is_user_enabled() {
        return ! get_user_meta( $this->user_id, sprintf( 'rz_is_user_notification_%s', $this->get_id() ), true );
    }

    /*
     * send
     *
     */
    public function send() {

        $this->before_send();

        $this->send_email();
        $this->send_email_admin();
        $this->send_site();
        $this->send_webhook();

        $this->after_send();

    }

    /*
     * send email
     *
     */
    abstract public function get_email_subject();

    abstract public function get_email_admin_subject();

    abstract public function get_email_template();

    abstract public function get_email_admin_template();

    public function get_email_template_from_option() {

        return stripslashes(get_option( sprintf('rz_notification_email_template_%s', $this->get_id() ) ));

    }
    public function get_email_admin_template_from_option() {

        return stripslashes(get_option( sprintf('rz_notification_email_template_admin_%s', $this->get_id() ) ));

    }
    public function before_send() {
        // ..
    }

    public function after_send() {
        // ..
    }

    public function send_email() {

        // check if enabled by site
        if( ! $this->is_email_enabled() ) {
            return;
        }

        // check if enabled by the user
        if( ! $this->is_user_enabled() ) {
            return;
        }

        if( empty( $message = $this->get_email_template_from_option() ) ) {
            $message = $this->get_email_template();
        }

        // email
        wp_mail(
            $this->get_user_email(),
            $this->replace_dynamic_values( $this->get_email_subject() ),
            $this->replace_dynamic_values( $message ),
            [
                'MIME-Version: 1.0',
                'Content-Type: text/html; charset=UTF-8'
            ]
        );

    }

    public function send_email_admin() {

        // check if enabled by site
        if( ! $this->is_email_admin_enabled() ) {
            return;
        }

        if( empty( $message = $this->get_email_admin_template_from_option() ) ) {
            $message = $this->get_email_admin_template();
        }

        // email
        wp_mail(
            get_option('admin_email'),
            $this->replace_dynamic_values( $this->get_email_admin_subject() ),
            $this->replace_dynamic_values( $message ),
            [
                'MIME-Version: 1.0',
                'Content-Type: text/html; charset=UTF-8'
            ]
        );

    }

    // replace dynamic values
    public function replace_dynamic_values( $text ) {

        /*
         * receiver
         *
         */
        if( $this->user_id ) {
            if( $user = get_userdata( (int) $this->user_id ) ) {
                $text = str_replace('{user_id}', (int) $this->user_id, $text);
                $text = str_replace('{user_first_name}', $user->first_name, $text);
                $text = str_replace('{user_last_name}', $user->last_name, $text);
                $text = str_replace('{user_display_name}', $user->display_name, $text);
                $text = str_replace('{user_email}', $user->user_email, $text);
                $text = str_replace('{user_billing_phone}', get_user_meta( $this->user_id, 'billing_phone', true ), $text);
            }
        }

        /*
         * sender
         *
         */
        if( isset( $this->meta['from_user_id'] ) ) {
            if( $from_user = get_userdata( (int) $this->meta['from_user_id'] ) ) {
                $text = str_replace('{from_user_id}', (int) $this->meta['from_user_id'], $text);
                $text = str_replace('{from_user_first_name}', $from_user->first_name, $text);
                $text = str_replace('{from_user_last_name}', $from_user->last_name, $text);
                $text = str_replace('{from_user_display_name}', $from_user->display_name, $text);
                $text = str_replace('{from_user_email}', $from_user->user_email, $text);
                $text = str_replace('{from_user_billing_phone}', get_user_meta( $this->meta['from_user_id'], 'billing_phone', true ), $text);
            }
        }

        /*
         * listing
         *
         */
        if( isset( $this->meta['listing_id'] ) ) {
            if( $listing_id = (int) $this->meta['listing_id'] ) {
                $text = str_replace('{listing_id}', $listing_id, $text);
                $text = str_replace('{listing_name}', get_the_title( $listing_id ), $text);
                $text = str_replace('{listing_url}', get_permalink( $listing_id ), $text);
            }
        }

        /*
         * entry
         *
         */
        if( isset( $this->meta['entry_id'] ) ) {
            if( $entry_id = (int) $this->meta['entry_id'] ) {
                $text = str_replace('{entry_id}', $entry_id, $text);
            }
        }

        /*
         * claim
         *
         */
        if( isset( $this->meta['claim_id'] ) ) {
            if( $claim_id = (int) $this->meta['claim_id'] ) {
                $text = str_replace('{claim_id}', $claim_id, $text);
            }
        }

        /*
         * comment
         *
         */
        if( isset( $this->meta['comment_id'] ) ) {
            if( $comment_id = (int) $this->meta['comment_id'] ) {
                // ..
            }
        }

        /*
         * custom meta
         *
         */

        preg_match_all('/{(.*):(.*)}/', $text, $m, PREG_PATTERN_ORDER );

        if( ! empty( $m[0] ) ) {
            foreach( $m[1] as $k => $field ) {

                if( in_array( $field, ['listing'] ) ) {

                    $value = $m[2][$k];

                    $post_id = null;

                    if( $field == 'listing' and isset( $this->meta['listing_id'] ) ) {
                        $post_id = $this->meta['listing_id'];
                    }

                    if( ! $post_id ) {
                        continue;
                    }

                    $text = preg_replace( sprintf( '/{%s:%s}/i', $field, $value ), Rz()->get_meta( Rz()->prefix( $value ), $post_id ), $text );

                }
            }
        }

        return $text;

    }

    /*
     * send site
     *
     */
    abstract public function get_site_message();

    // get last repeated site notifications in 24 hours
    /*public function check_last() {

        global $wpdb;

        $results = $wpdb->get_row( $wpdb->prepare("
            SELECT *
            FROM {$wpdb->prefix}routiz_notifications
            WHERE user_id = %d
            AND code = %s
            AND active = 1
            AND meta = %s
            AND created_at >= date( now() ) - 1
        ", $this->user_id, $this->get_id(), $this->meta ), OBJECT );

        return !! $results;

    }*/

    public function get_site_icon() {
        return 'fas fa-magic';
    }

    public function get_site_url() {
        return null;
    }

    public function get_site() {

        return [
            'code'  => $this->get_id(),
            'text'  => $this->replace_dynamic_values( $this->get_site_message() ),
            'icon'  => $this->get_site_icon(),
            'url'   => $this->get_site_url(),
        ];

    }

    // send
    public function send_site() {

        // check if enabled by site
        if( ! $this->is_site_enabled() ) {
            return;
        }

        // check if enabled by the user
        if( ! $this->is_user_enabled() ) {
            return;
        }

        global $wpdb;

        // check if repeated message
        // if( $this->check_last() ) {
        //     return;
        // }

        if( is_array( $this->meta ) ) {
            $meta = [];
            foreach( $this->meta as $meta_key => $meta_value ) {
                if( ! empty( $meta_value ) ) {
                    $meta[ $meta_key ] = $meta_value;
                }
            }
        }else{
            $meta = $this->meta;
        }

        // insert into db
        return $wpdb->insert( $wpdb->prefix . 'routiz_notifications', [
            'user_id' => $this->user_id,
            'code' => $this->get_id(),
            'active' => 1,
            'meta' => is_array( $meta ) ? maybe_serialize( $meta ) : $meta
        ]);

    }

    /*
     * send webhook
     *
     */
    public function get_webhook_url() {
        return get_option( sprintf( 'rz_notification_webhook_url_%s', $this->get_id() ) );
    }

    public function get_webhook_custom_fields() {
        return get_option( sprintf( 'rz_notification_webhook_custom_fields_%s', $this->get_id() ) );
    }

    public function get_data() {

        $data = [];

        // receiver
        if( $this->user_id ) {
            if( $user = get_userdata( (int) $this->user_id ) ) {
                $data['user_id'] = (int) $this->user_id;
                $data['user_first_name'] = $this->get_user_first_name();
                $data['user_last_name'] = $this->get_user_last_name();
                $data['user_display_name'] = $this->get_user_name();
                $data['user_email'] = $this->get_user_email();
                $data['user_billing_email'] = get_user_meta( $this->user_id, 'billing_email', true );
                $data['user_billing_phone'] = get_user_meta( $this->user_id, 'billing_phone', true );
                $data['user_billing_country'] = get_user_meta( $this->user_id, 'billing_country', true );
                $data['user_billing_city'] = get_user_meta( $this->user_id, 'billing_city', true );
                $data['user_billing_postcode'] = get_user_meta( $this->user_id, 'billing_postcode', true );
            }
        }

        // sender
        if( isset( $this->meta['from_user_id'] ) ) {
            if( $from_user = get_userdata( (int) $this->meta['from_user_id'] ) ) {
                $data['from_user_id'] = (int) $this->meta['from_user_id'];
                $data['from_user_first_name'] = $from_user->first_name;
                $data['from_user_last_name'] = $from_user->last_name;
                $data['from_user_display_name'] = $from_user->display_name;
                $data['from_user_email'] = $from_user->user_email;
                $data['from_user_billing_phone'] = get_user_meta( $this->meta['from_user_id'], 'billing_phone', true );
            }
        }

        // listing
        if( isset( $this->meta['listing_id'] ) ) {
            if( $listing_id = (int) $this->meta['listing_id'] ) {
                $data['listing_id'] = $listing_id;
                $data['listing_name'] = get_the_title( $listing_id );
                $data['listing_url'] = get_permalink( $listing_id );
            }
        }

        // custom fields
        $webhook_custom_fields = $this->get_webhook_custom_fields();
        if( isset( $this->meta['listing_id'] ) and ! empty( $webhook_custom_fields ) ) {
            if( $listing_id = (int) $this->meta['listing_id'] ) {

                foreach( array_map( 'trim', explode( ',', $webhook_custom_fields ) ) as $wh_custom_field ) {
                    $wh_custom_field = array_map( 'trim', explode( ':', $wh_custom_field ) );

                    if( isset( $wh_custom_field[0] ) and isset( $wh_custom_field[1] ) ) {
                        $wh_param_name = $wh_custom_field[0];
                        $wh_meta_key = $wh_custom_field[1];
                        $data[ sanitize_title( $wh_param_name ) ] = Rz()->get_meta( Rz()->prefix( $wh_meta_key ), $listing_id );
                    }
                }
            }
        }

        return $data;

    }

    public function send_webhook() {

        // check if enabled by site
        if( ! $this->is_webhook_enabled() ) {
            return;
        }

        // check if enabled by the user
        if( ! $this->is_user_enabled() ) {
            return;
        }

        $webhook_url = $this->get_webhook_url();

        // check url
        if( empty( $webhook_url ) or filter_var( $webhook_url, FILTER_VALIDATE_URL ) === false ) {
            return;
        }

        /*
         * generate data
         *
         */
        $data = $this->get_data();

        $ch = curl_init( $webhook_url );
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
