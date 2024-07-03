<?php

namespace Routiz\Inc\Src\Explore\Search;

use \Routiz\Inc\Src\Traits\Singleton;
use \Routiz\Inc\Extensions\Component\Component as Main_Component;
use \Routiz\Inc\Src\Form\Component as Form;
use \Routiz\Inc\Src\Explore\Explore;

class Component extends Main_Component {

    use Singleton;

    function __construct() {

        $this->form = new Form( Form::Storage_Request );
        $this->explore = Explore::instance();

    }

}
