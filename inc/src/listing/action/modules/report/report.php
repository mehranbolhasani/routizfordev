<?php

namespace Routiz\Inc\Src\Listing\Action\Modules\Report;

use \Routiz\Inc\Src\Listing\Action\Modules\Module;

class Report extends Module {

    public function controller() {

        return array_merge( (array) $this->props, [
            'strings' => (object) [
                'report' => esc_html__('Report this listing', 'routiz'),
            ]
        ]);

    }

}
