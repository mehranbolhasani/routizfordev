<?php

namespace Routiz\Inc\Src\Submission\Modules\Select_Plan;

use \Routiz\Inc\Src\Submission\Modules\Module;
use \Routiz\Inc\Src\Request\Custom_Request;
use \Routiz\Inc\Src\Validation;
use \Routiz\Inc\Src\Woocommerce\Packages\Plan;

class Select_Plan extends Module {

    public function controller() {

        global $rz_submission;

        return [
            'products' => $rz_submission->listing_type->get_wc_packages(),
            'strings' => (object) [
                'select_plan' => esc_html__('Select a plan', 'routiz'),
                'most_popular' => esc_html__('Most popular', 'routiz'),
                'unlimited' => esc_html__('Unlimited submissions available', 'routiz'),
                'available_submission' => esc_html__('%s submission available', 'routiz'),
                'available_submissions' => esc_html__('%s submissions available', 'routiz'),
                'owned' => esc_html__('Plan owned', 'routiz'),
                'no_packages' => esc_html__('There are no packages available for this listing type', 'routiz'),
            ]
        ];

    }

    public function validation() {

        $request = new Custom_Request('input');

        $validation = new Validation();
		$response = $validation->validate( $request->params, [
            'rz_plan' => 'required'
        ]);

        if( $response->success ) {

            $plan = new Plan( $request->get('rz_plan') );

            if( $plan->is_limit_reached() ) {
                wp_send_json([
                    'success' => false,
                    'errors' => [
                        'rz_plan' => esc_html__( 'The plan limit has been reached', 'routiz' )
                    ]
                ]);
            }

        }else{
            $response->errors['rz_plan'] = esc_html__( 'Please select a plan', 'routiz' );
        }

        return $response;

    }

}
