<?php

namespace Routiz\Inc\Src\Submission\Modules\Pricing;

use \Routiz\Inc\Src\Submission\Submission;
use \Routiz\Inc\Src\Submission\Modules\Module;
use \Routiz\Inc\Src\Request\Custom_Request;
use \Routiz\Inc\Src\Form\Component as Form;
use \Routiz\Inc\Src\Validation;
use \Routiz\Inc\Src\Listing_Type\Action;

class Pricing extends Module {

    public function controller() {

        global $rz_submission;

        $action_types = $rz_submission->listing_type->get_action();
        $action_fields = Action::get_action_fields( $rz_submission->listing_type );

        return [
            'form' => new Form( Form::Storage_Request ),
            'title' => esc_html__('Pricing', 'routiz'),
            'action_fields' => $action_fields,
            'allow_not_required_price' => $action_fields,
            'strings' => (object) [
                'price_base' => esc_html__('Base Price', 'routiz'),
                'price_weekend' => esc_html__('Weekend Price', 'routiz'),
                'seasonal_pricing' => esc_html__('Seasonal Pricing', 'routiz'),
                'add_seasonal_pricing' => esc_html__('Add Seasonal Pricing', 'routiz'),
                'add_period' => esc_html__('Add Period', 'routiz'),
                'seasson' => esc_html__('Seasson', 'routiz'),
                'period' => esc_html__('Period', 'routiz' ),
                'start_date' => esc_html__('Start Date', 'routiz' ),
                'end_date' => esc_html__('End Date', 'routiz' ),
                'january' => esc_html__('January', 'routiz'),
                'february' => esc_html__('February', 'routiz'),
                'march' => esc_html__('March', 'routiz'),
                'april' => esc_html__('April', 'routiz'),
                'may' => esc_html__('May', 'routiz'),
                'june' => esc_html__('June', 'routiz'),
                'july' => esc_html__('July', 'routiz'),
                'august' => esc_html__('August', 'routiz'),
                'september' => esc_html__('September', 'routiz'),
                'october' => esc_html__('October', 'routiz'),
                'november' => esc_html__('November', 'routiz'),
                'december' => esc_html__('December', 'routiz'),
                'long_term_discount' => esc_html__('Long term discount', 'routiz'),
                'long_term_week' => esc_html__('Apply 7+ days discount in percentage', 'routiz'),
                'long_term_month' => esc_html__('Apply 30+ days discount in percentage', 'routiz'),
                'extra_service_pricing' => esc_html__('Extra service pricing', 'routiz'),
                'add_extra_pricing' => esc_html__('Add extra service pricing', 'routiz'),
                'add_service' => esc_html__('Add service', 'routiz'),
                'service' => esc_html__('Service', 'routiz'),
                'service_name' => esc_html__('Service name', 'routiz'),
                'service_name_placeholder' => esc_html__('Enter service name', 'routiz'),
                'service_type' => esc_html__('Service type', 'routiz'),
                'service_price' => esc_html__('Service price', 'routiz'),
                'single_fee' => esc_html__('Single fee', 'routiz'),
                'per_day' => esc_html__('Per day', 'routiz'),
                'security_deposit' => esc_html__('Security Deposit', 'routiz'),
                'add_addons' => esc_html__('Add addons', 'routiz'),
                'add_addon' => esc_html__('Add addon', 'routiz'),
                'addons' => esc_html__('Addons', 'routiz'),
                'addon' => esc_html__('Addon', 'routiz'),
                'addon_name' => esc_html__('Addon name', 'routiz'),
                'addon_id' => esc_html__('Addon id', 'routiz'),
                'enter_addon_name' => esc_html__('Enter addon name', 'routiz'),
                'addon_price' => esc_html__('Addon price', 'routiz'),
            ]
        ];

    }

    public function validation() {

        $request = new Custom_Request('input');
        $submission = new Submission( $request->get('type') );
        $validation = new Validation();

        $action_types = $submission->listing_type->get_action();
        $action_fields = Action::get_action_fields( $submission->listing_type );

        // pricing
        if( $action_fields->allow_pricing ) {

            $terms = [];

            if( boolval( ! $action_fields->allow_not_required_price ) ) {
                $terms['rz_price'] = 'required';
            }

            $response = $validation->validate( $request->params, $terms );

            wp_send_json( $response );

        }

    }

}
