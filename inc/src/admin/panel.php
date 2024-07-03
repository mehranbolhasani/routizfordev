<?php

namespace Routiz\Inc\Src\Admin;

use \Routiz\Inc\Src\Form\Component as Form;

class Panel {

    use \Routiz\Inc\Src\Traits\Singleton;

    // Define the property explicitly to avoid deprecation warning
    public $form;

    function __construct() {

        $this->form = new Form();

        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        add_action( 'admin_footer', [ $this, 'templates' ] );

    }

    public function enqueue_scripts() {

        wp_enqueue_script('rz-panel');
        wp_enqueue_style('rz-panel');

    }

    public function templates() {

        Rz()->the_template('routiz/admin/panel/modal/field-id');

        echo '<span class="rz-overlay"></span>';

    }

}
