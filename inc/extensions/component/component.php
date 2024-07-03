<?php

namespace Routiz\Inc\Extensions\Component;

use \Routiz\Inc\Extensions\Blade\Blade;
use \Routiz\Inc\Src\Error;

class Component {

    public $create;

    public function create( $props ) {

        // props from template item
        if( is_object( $props ) ) {
            $props = $this->extract( $props );
        }

        $ref = new \ReflectionClass( $this );
        $ref_name = $ref->getNamespaceName();
        $namespaced = sprintf( '%1$s\modules\%2$s\%2$s', $ref_name, str_replace( '-', '_', $props['type'] ) );

        // built-in modules
        if( class_exists( $namespaced ) ) {
            return new $namespaced( $props, $this );
        }

        $namespaced_external = sprintf( '%1$s\Collector', $ref_name );
        if( class_exists( $namespaced_external ) ) {
            $collector = $namespaced_external::instance();

            // external modules
            if( array_key_exists( $props['type'], $collector->modules ) ) {
                return new $collector->modules[ $props['type'] ]['name']( $props, $this );
            }
        }

        return new Error("Module \"{$props['type']}\" not found.");

    }

    public function render( $props ) {

        $module = $this->create( $props );

        if( ! Rz()->is_error( $module ) ) {
            echo $module->get();
        }else{
            $module->display_error();
        }

    }

    /*
     * TODO: move items to class
     *
     */
    public function extract( $item ) {

        $props = array_merge( (array) $item->fields, [
            'type' => isset( $item->fields->choice ) ? $item->fields->choice : $item->template->id,
        ]);

        if( isset( $item->fields->multiple ) and $item->fields->multiple == true and ! isset( $item->fields->value ) ) {
            $props['value'] = [];
        }

        if( isset( $item->fields->key ) ) {
            $props['id'] = $item->fields->key;
            unset( $props['key'] );
        }

        return $props;

    }

    public function extract_ids( $items ) {

        $ids = [];

        foreach( $items as $item ) {
            if( isset( $item->fields->id ) ) {
                $ids[] = $item->fields->id;
            }
        }

        return $ids;

    }

}
