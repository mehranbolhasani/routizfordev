<?php

namespace Routiz\Inc\Src\Submission\Modules\Success;

use \Routiz\Inc\Src\Submission\Modules\Module;

class Success extends Module {

    public function controller() {

        global $rz_submission;

        return [
            'requires_admin_approval' => $rz_submission->listing_type->get('rz_requires_admin_approval'),
            'strings' => (object) [
                'success' => esc_html__('Your submission was successful!', 'routiz'),
                'awaits_approval' => esc_html__('Your submission has been sent and awaits approval', 'routiz'),
                'published' => esc_html__('Your submission has been sent and published', 'routiz'),
            ]
        ];

    }

    public function validation() {

        return wp_send_json([
            'success' => true
        ]);

    }

}
