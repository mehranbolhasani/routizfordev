<?php

namespace Routiz\Inc\Src\Admin;

use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Form\Component as Form;

class Term_Metabox {

    use \Routiz\Inc\Src\Traits\Singleton;

    function __construct() {

        add_action( 'admin_init', [ $this, 'init' ] );

    }

    public function init() {

        global $pagenow;

        $taxonomies = [
            'rz_listing_category',
            'rz_listing_region',
            'rz_listing_tag',
        ];

        // add custom taxonomies
        $custom_taxonomies = Rz()->get_custom_taxonomies();
        foreach( $custom_taxonomies as $custom_taxonomy ) {
            $taxonomies[] = Rz()->prefix( $custom_taxonomy->slug );
        }

        // init panel
        if( in_array( $pagenow, [ 'edit-tags.php', 'term.php' ] ) ) {
            $request = Request::instance();
            if( $request->has('taxonomy') and in_array( $request->get('taxonomy'), $taxonomies ) ) {
                Panel::instance();
            }
        }

        foreach( $taxonomies as $taxonomy ) {

            // meta
            add_action( sprintf( '%s_add_form_fields', $taxonomy ), [ $this, 'taxonomy_add_custom_field' ], 10, 1 );
            add_action( sprintf( '%s_edit_form_fields', $taxonomy ), [ $this, 'taxonomy_add_custom_field' ], 10, 1 );

            // save
            add_action( sprintf( 'edited_%s', $taxonomy ), [ $this, 'save_taxonomy_custom_meta_field' ], 10, 1 );
            add_action( sprintf( 'create_%s', $taxonomy ), [ $this, 'save_taxonomy_custom_meta_field' ], 10, 1 );

        }

    }

    function taxonomy_add_custom_field( $taxonomy ) {

        Rz()->the_template('admin/term-metabox/terms');

    }

    function save_taxonomy_custom_meta_field( $term_id ) {

        $request = Request::instance();
        $form = new Form( Form::Storage_Request );

        $fields = [];
        $fields[] = $form->create([
            'type' => 'icon',
            'id' => 'rz_icon',
        ]);
        $fields[] = $form->create([
            'type' => 'icon',
            'id' => 'rz_image',
        ]);

        foreach( $fields as $field ) {
            if( $request->has( $field->props->id ) ) {
                $this->save(
                    $term_id,
                    $field->props->id,
                    $field->props->value
                );
            }
        }

    }

    public function save( $term_id, $term_meta_name, $term_meta_value ) {

        if( is_array( $term_meta_value ) ) {
            delete_term_meta(
                $term_id,
                $term_meta_name
            );
            foreach( $term_meta_value as $tm_value ) {
                add_term_meta(
                    $term_id,
                    $term_meta_name,
                    $tm_value
                );
            }
        }else{
            delete_term_meta(
                $term_id,
                $term_meta_name
            );
            update_term_meta(
                $term_id,
                $term_meta_name,
                $term_meta_value
            );
        }

    }
}
