<?php

namespace Routiz\Inc\Src\Entry\Modules;

use \Routiz\Inc\Extensions\Component\Module as Main_Module;
use \Routiz\Inc\Src\Entry\Init;

abstract class Module extends Main_Module {

    public function template() {

        // admin
        if( is_admin() and ! wp_doing_ajax() ) {

            return $this->get_engine()->run(
                sprintf( '%s.%s', str_replace( '_', '-', $this->props->type ), 'admin' ),
                $this->admin()
            );

        }
        // front
        else{

            return $this->get_engine()->run(
                sprintf( '%s.%s', str_replace( '_', '-', $this->props->type ), 'index' ),
                $this->controller()
            );

        }

    }

    public function wrapper() {
        return '%2$s';
    }

    public function get_engine() {
        return Init::instance()->engine();
    }

}
