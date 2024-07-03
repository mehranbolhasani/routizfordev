<?php

namespace Routiz\Inc\Src\Form\Modules\Menu;

use \Routiz\Inc\Src\Form\Modules\Module;

class Menu extends Module {

    public function controller() {

        return [
            'props' => (array) $this->props,
            'component' => $this->component,
            'strings' => (object) [
                'add_section' => esc_html__('Add Section', 'routiz'),
                'section' => esc_html__('Section', 'routiz'),
                'name' => esc_html__('Name', 'routiz'),
                'item' => esc_html__('Item', 'routiz'),
                'items' => esc_html__('Items', 'routiz'),
                'description' => esc_html__('Description', 'routiz'),
                'price' => esc_html__('Price', 'routiz'),
                'special' => esc_html__('How is this item special? ( Optional )', 'routiz'),
                'short_info' => esc_html__('Add some short information if you want to highlight the menu item', 'routiz'),
            ]
        ];

    }

}
