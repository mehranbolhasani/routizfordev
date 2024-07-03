<?php

namespace Routiz\Inc\Src\Listing\Action\Modules\Button;

use \Routiz\Inc\Src\User;
use \Routiz\Inc\Src\Listing\Action\Modules\Module;
use \Routiz\Inc\Src\Listing\Listing;

class Button extends Module {

    public function get() {

        if( isset( $this->props->hide_if_field_empty ) and $this->props->hide_if_field_empty == true ) {
            if( empty( Rz()->get_meta( $this->props->button_field, get_the_ID(), true ) ) ) {
                return;
            }
        }

        return sprintf( $this->wrapper(), $this->props->type, $this->template() );

    }

    public function controller() {

        $url = '#';
        $modal = $target = '';

        if( $this->props->botton_target_blank ) {
            if( ! $this->props->request_login or ( $this->props->request_login and is_user_logged_in() ) ) {
                $target = 'target="_blank"';
            }
        }

        if( ! $this->props->request_login or ( $this->props->request_login and is_user_logged_in() ) ) {
            if( $this->props->button_field ) {
                $url = esc_attr( Rz()->get_meta( $this->props->button_field, get_the_ID(), true ) );
            }
        }

        // if email
        if( filter_var( $url, FILTER_VALIDATE_EMAIL ) ) {
            $url = sprintf('mailto:%s', $url);
        }

        if( $this->props->request_login and ! is_user_logged_in() ) {
            $modal = 'data-modal="signin"';
        }

        global $rz_listing;

        return array_merge( (array) $this->props, [
            'component' => $this->component,
            'listing' => new Listing( get_the_ID() ),
            'listing_id' => get_the_ID(),
            'target' => $target,
            'url' => $url,
            'modal' => $modal,
            'strings' => (object) [
                'label' => esc_html__( 'Button', 'routiz' )
            ],
        ]);

    }

}
