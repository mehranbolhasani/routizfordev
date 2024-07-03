<?php

namespace Routiz\Inc\Src\Form\Modules\Key;

use \Routiz\Inc\Src\Form\Modules\Module;

class Key extends Module {

    public $pre_defined = [];

    public function before_construct() {
        $this->defaults += [
            'defined' => true,
        ];
        $this->pre_defined = apply_filters('routiz/key/pre-defined', [
            'post_title' => esc_html__('Title', 'routiz'),
            'post_content' => esc_html__('Content', 'routiz'),
            'rz_gallery' => esc_html__('Gallery', 'routiz'),
            'rz_location' => esc_html__('Location', 'routiz'),
            'rz_price' => esc_html__('Price', 'routiz'),
            'rz_guests' => esc_html__('Guests', 'routiz'),
            'rz_faq' => esc_html__('Faq', 'routiz'),
        ]);
    }

    public function finish() {

        $this->props->is_defined = array_key_exists( $this->props->value, $this->pre_defined );

        if( $this->props->is_defined ) {
            $this->class[] = 'rz-is-defined';
        }

    }

    public function controller() {

        return array_merge( (array) $this->props, [
            'pre_defined' => $this->pre_defined,
            'strings' => (object) [
                'defined' => esc_html__('Defined', 'routiz'),
                'select' => esc_html__('Select', 'routiz'),
                'custom' => esc_html__('Custom', 'routiz'),
            ]
        ]);

    }

}
