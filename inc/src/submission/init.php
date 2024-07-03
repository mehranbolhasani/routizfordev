<?php

namespace Routiz\Inc\Src\Submission;

use \Routiz\Inc\Extensions\Component\Init as Main_Init;
use \Routiz\Inc\Src\Traits\Singleton;

class Init extends Main_Init {

    use Singleton;

    function __construct() {

        global $rz_submission;

        parent::__construct();

        if( ! wp_doing_ajax() ) {
            delete_transient( 'rz_steps' );
        }

        $rz_submission = new Submission();

    }

    public function enqueue_scripts() {

        // wp_enqueue_editor();
        wp_enqueue_script( 'rz-submission' );
        wp_enqueue_style( 'rz-submission' );

    }

}
