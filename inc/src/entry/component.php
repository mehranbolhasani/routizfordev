<?php

namespace Routiz\Inc\Src\Entry;

use \Routiz\Inc\Src\Traits\Singleton;
use \Routiz\Inc\Extensions\Component\Component as Main_Component;
// use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Form\Component as Form;

class Component extends Main_Component {

    public $form;

    use Singleton;

    function __construct() {

        // $this->request = Request::instance();
        $this->form = new Form( Form::Storage_Meta );

    }

}
