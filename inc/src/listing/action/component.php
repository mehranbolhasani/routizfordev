<?php

namespace Routiz\Inc\Src\Listing\Action;

use Routiz\Inc\Src\Traits\Singleton;
use Routiz\Inc\Extensions\Component\Component as Main_Component;
use Routiz\Inc\Src\Form\Component as Form;

class Component extends Main_Component {

    use Singleton;

    public $form; // Explicitly declared property

    function __construct() {
        $this->form = new Form(Form::Storage_Field);
    }

}

