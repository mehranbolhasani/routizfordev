<?php

namespace Routiz\Inc\Src\Submission;

use \Routiz\Inc\Src\Traits\Singleton;
use \Routiz\Inc\Extensions\Component\Component as Main_Component;

class Component extends Main_Component {

    use Singleton;

    public function tabs( $items ) {

        $current_tab_key = 0;
        $tabs = [
            $current_tab_key => [
                'title' => apply_filters( 'routiz/submission/title_general', esc_html__( 'General', 'routiz' ) ),
                'content' => []
            ]
        ];

        foreach( $items as $item ) {
            if( $item->template->id == 'tab' ) {
                $current_tab_key++;
                $tabs[ $current_tab_key ]['title'] = $item->fields->{ $item->template->heading };
                continue;
            }
            $tabs[ $current_tab_key ]['content'][] = $item;
        }

        return $tabs;

    }

}
