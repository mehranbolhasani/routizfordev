<?php

namespace Routiz\Inc\Extensions\Component;

abstract class Module {

    public $props;
    public $defaults = [];
    public $component;

    function __construct( $props, $component ) {

        $this->before_construct();

        $this->props = (object) array_merge( $this->defaults, $props );
        $this->component = $component;
        $this->init();
        $this->after_construct();

    }

    public function before_construct() {}
    public function init() {}
    public function after_construct() {}

    public function controller() {
        return (array) $this->props;
    }

    public function wrapper() {
        return '<div class="rz-mod" data-type="%1$s">%2$s</div>';
    }

    public function template() {

        return $this->get_engine()->run(
            sprintf( '%s.index', str_replace( '_', '-', $this->props->type ) ), // $this->props->template->id
            $this->controller()
        );

    }

    public function before_get() {}

    public function get() {
        $this->before_get();
        return sprintf( $this->wrapper(), $this->props->type, $this->template() );
    }

}
