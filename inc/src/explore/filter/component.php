<?php


namespace Routiz\Inc\Src\Explore\Filter;

use \Routiz\Inc\Src\Traits\Singleton;
use \Routiz\Inc\Extensions\Component\Component as Main_Component;
use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Form\Component as Form;

class Component extends Main_Component {

    use Singleton;

    // Define properties explicitly to avoid deprecation warnings
    public $request;
    public $form;
    public $registry = [
        'global' => [],
        'types' => []
    ];

    function __construct() {

        $this->request = Request::instance();
        $this->form = new Form( Form::Storage_Request );

    }

    public function group_tabs( $filters ) {

        foreach( $filters as $filter ) {
            $this->render( array_merge( (array) $filter->fields, [
                'type' => $filter->template->id,
            ]));
        }

    }

    public function tabs( $items ) {

        $current_tab_key = 0;
        $stacks = [];

        $is_tab = false;
        $id = null;

        foreach( $items as $item ) {

            // break tab
            if( $item->template->id == 'tab_break' ) {
                $is_tab = false;
                continue;
            }

            // create tab
            if( $item->template->id == 'tab' ) {

                $is_tab = true;
                $id = $item->fields->id;

                $stacks[ $id ] = (object) array_merge( (array) $item->fields, [
                    'type' => 'tab',
                    // 'id' => $item->fields->id,
                    'name' => $item->fields->{ $item->template->heading },
                    // 'label' => $item->fields->label,
                    'is_tab' => true,
                    // 'col' => isset( $item->fields->col ) ? $item->fields->col : 12,
                    'content' => []
                ]);

                continue;

            }

            // tab
            if( $is_tab ) {
                if( isset( $item->fields->id ) ) {
                    $stacks[ $id ]->content[ $item->fields->id ] = $item;
                }else{
                    $stacks[ $id ]->content[ rand(1111,9999) ] = $item;
                }
            }
            // filter
            else{
                $stacks[] = $item;
            }

        }

        foreach( $stacks as $stack ) {

            // tab
            if( isset( $stack->is_tab ) ) {
                $this->render( (array) $stack );
            }
            // filter
            else{
                $this->render( array_merge( (array) $stack->fields, [
                    'type' => $stack->template->id,
                ]));
            }
        }
    }

    public function count_active_filters( $search_form_id ) {

        global $rz_explore;

        $active = 0;

        if( ! $search_form_id ) {
            return 0;
        }

        $search_filters = Rz()->json_decode( Rz()->get_meta( 'rz_search_fields', $search_form_id ) );

        if( ! empty( $search_filters ) and is_array( $search_filters ) ) {
            foreach( $search_filters as $filter ) {
                if( isset( $filter->fields->id ) ) {

                    if( ! $rz_explore->request->is_empty( Rz()->unprefix( $filter->fields->id ) ) ) {
                        $active += 1;
                    }
                }
            }
        }

        return $active;

    }

    public function render_labels( $labels ) {

        $text = '';

        if( ! empty( $labels ) ) {
            $text .= '<ul>';
            foreach( $labels as $label ) {
                $text .= '<li>';
                $text .= "<span>{$label['name']}:</span>";
                if( is_array( $label['value'] ) ) {
                    $text .= '<strong>' . implode( ', ', $label['value'] ) . '</strong>';
                }else{
                    $text .= '<strong>' . $label['value'] . '</strong>';
                }
                $text .= '</li>';
            }
            $text .= '</ul>';
        }

        return $text;

    }

}
