<?php

namespace Routiz\Inc\Src\Form\Modules\Autocomplete;

use \Routiz\Inc\Src\Form\Modules\Module;

class Autocomplete extends Module {

    public function finish() {

        global $rz_search_form;

        $this->attrs['data-search-form'] = $rz_search_form;
        $this->attrs['data-taxonomy'] = $this->props->id;
        $this->attrs['data-icon'] = isset( $this->props->icon ) ? $this->props->icon : null;

    }

}
