<?php

namespace Routiz\Inc\Src\Explore;

use \Routiz\Inc\Extensions\Component\Init as Main_Init;
use \Routiz\Inc\Src\Traits\Singleton;

class Init extends Main_Init {

    use Singleton;

    public function enqueue_scripts() {

        wp_enqueue_script( 'rz-explore' );
        wp_enqueue_style( 'rz-explore' );

    }

}
