<?php

namespace Routiz\Inc\Src\Form\Modules\Repeater_Item;

use \Routiz\Inc\Src\Form\Modules\Module;

class Repeater_Item extends Module {

    public function controller() {

        $is_empty = true;
        if( is_array( $this->props->schema['fields'] ) ) {
            foreach( $this->props->schema['fields'] as $id => $props ) {
                if( $props['type'] !== 'hidden' ) {
                    $is_empty = false;
                    break;
                }
            }
        }

        $item_hidden_field = [];
        if( $this->props->parent->props->can_hide ) {
            $item_hidden_field = [
                '_item_hidden' => [
                    'type' => 'hidden'
                ]
            ];
        }

        $this->props->schema['fields'] = array_merge( $item_hidden_field, $this->props->schema['fields'] );

        return array_merge([
            'is_item_empty' => $is_empty,
            'can_hide' => $this->props->parent->props->can_hide,
            'is_item_hidden' => isset( $this->props->fields->_item_hidden ) ? $this->props->fields->_item_hidden : false,
        ], (array) $this->props );

    }

    public function get() {
        return $this->template();
    }

}
