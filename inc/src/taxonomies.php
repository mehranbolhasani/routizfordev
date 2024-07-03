<?php

namespace Routiz\Inc\Src;

class Taxonomies {

    use \Routiz\Inc\Src\Traits\Singleton;

    function __construct() {

        // build-in taxonomies
        add_action( 'init', [ $this, 'register_taxonomies' ] );
        // custom taxonomies
        add_action( 'init', [ $this, 'register_custom_taxonomies' ] );

    }

    function register_taxonomies() {

        // built-in taxonomy: category
        $labels = [
            'name'                => _x( 'Categories', 'Categories Singular Name', 'routiz' ),
            'singular_name'       => _x( 'Item', 'Categories Singular Name', 'routiz' ),
            'search_items'        => __( 'Search Categories', 'routiz' ),
            'all_items'           => __( 'All Categories', 'routiz' ),
            'parent_item'         => __( 'Parent Category', 'routiz' ),
            'parent_item_colon'   => __( 'Parent Category:', 'routiz' ),
            'edit_item'           => __( 'Edit Category', 'routiz' ),
            'update_item'         => __( 'Update Category', 'routiz' ),
            'add_new_item'        => __( 'Add New Category', 'routiz' ),
            'new_item_name'       => __( 'New Category Name', 'routiz' ),
            'menu_name'           => __( 'Categories', 'routiz' ),
        ];
        $args = [
            'hierarchical'        => true,
            'labels'              => $labels,
            'public'              => false,
            'show_ui'             => true,
            'show_in_quick_edit'  => false,
            'meta_box_cb'         => false,
            'show_admin_column'   => false,
            'query_var'           => true,
            'rewrite'             => [
                'slug' => 'category'
            ],
        ];
        register_taxonomy( 'rz_listing_category', [ 'rz_listing' ], $args );

        // built-in taxonomy: region
        $labels = [
            'name'                => _x( 'Regions', 'Regions Singular Name', 'routiz' ),
            'singular_name'       => _x( 'Item', 'Regions Singular Name', 'routiz' ),
            'search_items'        => __( 'Search Regions', 'routiz' ),
            'all_items'           => __( 'All Regions', 'routiz' ),
            'parent_item'         => __( 'Parent Region', 'routiz' ),
            'parent_item_colon'   => __( 'Parent Region:', 'routiz' ),
            'edit_item'           => __( 'Edit Region', 'routiz' ),
            'update_item'         => __( 'Update Region', 'routiz' ),
            'add_new_item'        => __( 'Add New Region', 'routiz' ),
            'new_item_name'       => __( 'New Region Name', 'routiz' ),
            'menu_name'           => __( 'Regions', 'routiz' ),
        ];
        $args = [
            'hierarchical'        => true,
            'labels'              => $labels,
            'public'              => false,
            'show_ui'             => true,
            'show_in_quick_edit'  => false,
            'meta_box_cb'         => false,
            'show_admin_column'   => false,
            'query_var'           => true,
            'rewrite'             => [
                'slug' => 'region'
            ],
        ];
        register_taxonomy( 'rz_listing_region', [ 'rz_listing' ], $args );

        // built-in taxonomy: tag
        $labels = [
            'name'                => _x( 'Tags', 'Taxonomy Singular Name', 'routiz' ),
            'singular_name'       => _x( 'Item', 'Taxonomy Singular Name', 'routiz' ),
            'search_items'        => __( 'Search Tags', 'routiz' ),
            'all_items'           => __( 'All Tags', 'routiz' ),
            'parent_item'         => __( 'Parent Tag', 'routiz' ),
            'parent_item_colon'   => __( 'Parent Tag:', 'routiz' ),
            'edit_item'           => __( 'Edit Tag', 'routiz' ),
            'update_item'         => __( 'Update Tag', 'routiz' ),
            'add_new_item'        => __( 'Add New Tag', 'routiz' ),
            'new_item_name'       => __( 'New Tag Name', 'routiz' ),
            'menu_name'           => __( 'Tags', 'routiz' ),
        ];
        $args = [
            'hierarchical'        => false,
            'labels'              => $labels,
            'public'              => false,
            'show_ui'             => true,
            'show_in_quick_edit'  => false,
            'meta_box_cb'         => false,
            'show_admin_column'   => false,
            'query_var'           => true,
            'rewrite'             => [
                'slug' => 'tag'
            ],
        ];
        register_taxonomy( 'rz_listing_tag', [ 'rz_listing' ], $args );

    }

    function register_custom_taxonomies() {

        $custom_taxonomies = Rz()->get_custom_taxonomies();
        if( is_array( $custom_taxonomies ) ) {
            foreach( $custom_taxonomies as $custom_taxonomy ) {

                $labels = [
                    'name'                => $custom_taxonomy->name,
            		'singular_name'       => _x( 'Item', 'Taxonomy Singular Name', 'routiz' ),
            		'search_items'        => __( 'Search Items', 'routiz' ),
            		'all_items'           => __( 'All Items', 'routiz' ),
            		'parent_item'         => __( 'Parent Item', 'routiz' ),
            		'parent_item_colon'   => __( 'Parent Item:', 'routiz' ),
            		'edit_item'           => __( 'Edit Item', 'routiz' ),
            		'update_item'         => __( 'Update Item', 'routiz' ),
            		'add_new_item'        => __( 'Add New Item', 'routiz' ),
            		'new_item_name'       => __( 'New Item Name', 'routiz' ),
            		'menu_name'           => $custom_taxonomy->name,
            	];
            	$args = [
            		'hierarchical'        => true,
            		'labels'              => $labels,
                    'public'              => false,
            		'show_ui'             => true,
            		'show_in_quick_edit'  => false,
            		'meta_box_cb'         => false,
            		'show_admin_column'   => false,
            		'query_var'           => true,
            		'rewrite'             => [
                        'slug' => $custom_taxonomy->slug
                    ],
            	];

                if( $custom_taxonomy->slug ) {
                    register_taxonomy( Rz()->prefix( $custom_taxonomy->slug ), [ 'rz_listing' ], $args );
                }
            }
        }
    }
}
