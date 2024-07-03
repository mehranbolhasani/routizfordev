<?php

namespace Routiz\Inc\Src\Explore\Filter\Modules;

use \Routiz\Inc\Extensions\Component\Module as Main_Module;
use \Routiz\Inc\Src\Explore\Filter\Comparison;
use \Routiz\Inc\Src\Explore\Filter\Init;

abstract class Module extends Main_Module {

    public $id;

    public function get_engine() {
        return Init::instance()->engine();
    }

    public function wrapper() {
        $col = '';
        if( isset( $this->props->col ) ) {
            $col = " data-col='{$this->props->col}'";
        }
        return '<div class="rz-mod" data-type="%1$s"' . $col . '>%2$s</div>';
    }

    public function after_construct() {
        if( ! isset( $this->props->id ) ) {
            return;
        }
        $this->id = Rz()->unprefix( $this->props->id );
        $this->props->value = $this->get_value();
    }

    public function is_requested() {
        if( ! isset( $this->props->id ) ) {
            return;
        }
        return ! $this->component->request->is_empty( $this->id );
    }

    public function get_value() {
        return $this->component->request->has( Rz()->unprefix( $this->id ) ) ? $this->component->request->parse( $this->component->request->get( $this->id ) ) : '';
    }

    public function get_label() {}

    public function main_query() {
        return [];
    }

    public function query() {

        $comparison = new Comparison( $this->props );
        return $comparison->equal()->get();

    }

}
