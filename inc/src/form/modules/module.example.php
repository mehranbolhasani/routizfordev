<?php

/*
 * example
 * available modules
 *
 */
exit;

/*
 * BUTTON
 * no value
 *
 */
$form->render([
    'type' => 'button',
    'name' => esc_html__('__NAME__', 'routiz'),
    'html' => [
        'id' => '__some_id__'
        'class' => '__some_class__'
    ]
]);

/*
 * BUTTONS
 * single
 *
 */
$form->render([
    'type' => 'buttons',
    'id' => '__ID__',
    'name' => esc_html__('__NAME__', 'routiz'),
    'value' => 'button_1',
    'options' => [
        'button_1' => 'Button 1',
        'button_2' => 'Button 2',
        'button_3' => 'Button 3',
    ],
]);

/*
 * BUTTONS
 * single / multiple
 *
 */
$form->render([
    'type' => 'calendar',
    'id' => '__ID__',
    'format' => 'd-m-Y',
    'months' => 12,
    'range' => true,
    'large' => true
]);

/*
 * CHECKBOX
 * single
 *
 */
$form->render([
    'type' => 'checkbox',
    'id' => '__ID__',
    'name' => esc_html__('__NAME__', 'routiz'),
]);

/*
 * CHECKLIST
 * multiple
 *
 */
$form->render([
    'type' => 'checklist',
    'id' => '__ID__',
    'name' => esc_html__('__NAME__', 'routiz'),
    'value' => [
        'check_1',
        'check_2',
    ],
    'options' => [
        'check_1' => 'Check 1',
        'check_2' => 'Check 2',
        'check_3' => 'Check 3',
    ],
]);

/*
 * TODO: COLORPICKER
 * single
 *
 */

/*
 * EDITOR
 * single
 *
 */
$form->render([
    'type' => 'editor',
    'id' => '__ID__',
    'name' => esc_html__('__NAME__', 'routiz'),
]);

/*
 * FAQ'S
 * single
 *
 */
$form->render([
    'type' => 'faq',
    'id' => '__ID__',
    'name' => esc_html__('__NAME__', 'routiz'),
]);

/*
 * HEADING
 * no value
 *
 */
$form->render([
    'type' => 'heading',
    'id' => '__ID__',
    'name' => esc_html__('__NAME__', 'routiz'),
    'description' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'routiz'),
]);

/*
 * HIDDEN
 * single
 *
 */
$form->render([
    'type' => 'hidden',
    'id' => '__ID__',
    'value' => '__VALUE__',
]);

/*
 * ICON
 * single
 *
 */
$form->render([
    'type' => 'icon',
    'id' => '__ID__',
    'name' => esc_html__('__NAME__', 'routiz'),
    'value' => 'fas fa-map-marker-alt',
]);

/*
 * KEY
 * single
 *
 */
$form->render([
    'type' => 'key',
    'id' => '__ID__',
    'name' => esc_html__('__NAME__', 'routiz'),
    'defined' => true,
]);

/*
 * MAP
 * single ( sub values for lat and lng )
 *
 */
$form->render([
    'type' => 'map',
    'id' => '__ID__',
    'name' => esc_html__('__NAME__', 'routiz'),
    'value' => [
        'Barcelona, Spain', // address
        '40.741895', // lat
        '-73.989308', // lng
    ],
]);

/*
 * NUMBER
 * single
 *
 */
$form->render([
    'type' => 'number',
    'id' => '__ID__',
    'name' => esc_html__('__NAME__', 'routiz'),
    'input_type' => 'range', // number, range, stepper
    'format' => '%s',
    'min' => 10,
    'max' => 50,
    'step' => 5,
]);

/*
 * OPTIONS
 * single
 *
 */
$form->render([
    'type' => 'options',
    'id' => '__ID__',
    'name' => esc_html__( '__NAME__', 'routiz' ),
]);

/*
 * ORDER LIST
 * multiple
 *
 */
$form->render([
    'type' => 'order_list',
    'id' => '__ID__',
    'name' => esc_html__( '__NAME__', 'routiz' ),
    'repeater_id' => '__REPEATER_ID__',
    'repeater_empty_notify' => esc_html__('__NOTIFY__', 'routiz'),
]);

/*
 * RADIO
 * single
 *
 */
$form->render([
    'type' => 'radio',
    'id' => '__ID__',
    'name' => esc_html__('__NAME__', 'routiz'),
    'value' => 'radio_1',
    'options' => [
        'radio_1' => 'Radio 1',
        'radio_2' => 'Radio 2',
        'radio_3' => 'Radio 3',
    ],
]);

/*
 * RADIO IMAGE
 * single
 *
 */
$form->render([
    'type' => 'radio_image',
    'id' => '__ID__',
    'name' => esc_html__('__NAME__', 'routiz'),
    'value' => 'image_1',
    'options' => [
        'image_1' => [
            'label' => esc_html__( 'Image 1', 'routiz' ),
            'image' => RZ_URI . 'assets/images/admin/layout-full.png',
        ],
        'image_2' => [
            'label' => esc_html__( 'Image 1', 'routiz' ),
            'image' => RZ_URI . 'assets/images/admin/layout-sidebar.png',
        ],
    ],
]);

/*
 * RANGE
 * single ( json )
 *
 */
$form->render([
    'type' => 'range',
    'id' => '__ID__',
    'name' => esc_html__('__NAME__', 'routiz'),
    'min' => 100,
    'max' => 500,
    'step' => 100,
    'suffix' => 'USD',
    'separator' => '-',
    'placeholder' => [ 100, 1000 ],
    'value' => [ 300, 1200 ],
]);

/*
 * REPEATER
 * single ( json )
 *
 */
$form->render([
    'type' => 'repeater',
    'id' => '__ID__',
    'name' => esc_html__('__NAME__', 'routiz'),
    'button' => [
        'label' => esc_html__('__BUTTON_NAME__', 'routiz')
    ], // optional
    'templates' => [
        // repeater template 1
        '__ID_TEMPLATE_1__' => [
            'name' => esc_html__('Text Field', 'routiz'),
            'heading' => '__ID_1__',
            'fields' => [
                '__ID_1__' => [
                    'type' => 'text',
                    'name' => esc_html__('__NAME_1__', 'routiz'),
                ],
            ]
        ],
        // repeater template 2
        '__ID_TEMPLATE_2__' => [
            'name' => esc_html__('Text Field', 'routiz'),
            'heading' => '__ID_2__',
            'fields' => [
                '__ID_2__' => [
                    'type' => 'text',
                    'name' => esc_html__('__NAME_2__', 'routiz'),
                ],
            ]
        ],
    ]
]);

/*
 * SELECT
 * single, multiple
 *
 */
$form->render([
    'type' => 'select',
    'id' => '__ID__',
    'name' => esc_html__('__NAME__', 'routiz'),
    'value' => 'select_1', // or multiple: [ 'select_1', 'select_2' ]
    // array
    'options' => [
        'select_1' => 'Select 1',
        'select_2' => 'Select 2',
        'select_3' => 'Select 3',
    ],
    // query
    'options' => [
        'query' => [
            'post_type' => 'page',
            'posts_per_page' => -1,
        ]
    ],
]);

/*
 * SELECT2
 * single, multiple
 *
 */
$form->render([
    'type' => 'select2',
    'id' => '__ID__',
    'name' => esc_html__('__NAME__', 'routiz'),
    'value' => 'select_1', // or multiple: [ 'select_1', 'select_2' ]
    'options' => [
        'select_1' => 'Select 1',
        'select_2' => 'Select 2',
        'select_3' => 'Select 3',
    ],
]);

/*
 * SUBMIT
 * no value
 *
 */
$form->render([
    'type' => 'submit',
    'name' => esc_html__('__NAME__', 'routiz'),
]);

/*
 * TAB
 * no value
 *
 */
$form->render([
    'type' => 'tab',
    'id' => '__ID__',
    'name' => esc_html__('__NAME__', 'routiz'),
]);

/*
 * TAXONOMY
 * single, multiple
 *
 */
$form->render([
    'type' => 'taxonomy',
    'id' => '__ID__',
    'name' => esc_html__('__NAME__', 'routiz'),
    'taxonomy' => '__TAXONOMY__', // ex. rz_listing_tag
    'display' => 'select', // select, select2, checklist, radio, buttons
    'value' => '', // or multiple: []
]);

/*
 * TAXONOMY TYPES
 * single
 *
 */
$form->render([
    'type' => 'taxonomy_types',
    'id' => 'asdasdasd',
    'name' => esc_html__('__NAME__', 'routiz'),
]);

/*
 * TERM
 * single, multiple
 *
 */
$form->render([
    'type' => 'term',
    'id' => '__ID__',
    'multiple' => false,
]);

/*
 * TEXT
 * single
 *
 */
$form->render([
    'type' => 'text',
    'id' => '__ID__',
    'name' => esc_html__('__NAME__', 'routiz'),
]);

/*
 * TEXTAREA
 * single
 *
 */
$form->render([
    'type' => 'text',
    'id' => '__ID__',
    'name' => esc_html__('__NAME__', 'routiz'),
]);

/*
 * TITLE
 * no value
 *
 */
$form->render([
    'type' => 'title',
    'name' => esc_html__('__NAME__', 'routiz'),
    'description' => esc_html__('__NAME__', 'routiz'),
    'tag' => 'h3',
]);

/*
 * TOGGLE
 * single
 *
 */
$form->render([
    'type' => 'toggle',
    'id' => '__ID__',
    'name' => esc_html__('__NAME__', 'routiz'),
    'value' => true,
]);

/*
 * UPLOAD
 * single
 *
 */
$form->render([
    'type' => 'upload',
    'id' => '__ID__',
    'name' => esc_html__('__NAME__', 'routiz'),
    'button' => [
        'label' => esc_html__('__BUTTON_LABEL__', 'routiz')
    ],
    'multiple_upload' => false,
]);

/*
 * USE_FIELD
 * single
 *
 */
$form->render([
    'type' => 'use_field',
    'id' => '__ID__',
    'name' => esc_html__('__NAME__', 'routiz'),
]);

/*
 * @ DEPENDENCY
 *
 */
$form->render([
    'type' => 'text',
    'id' => '__ID__',
    'name' => esc_html__('__NAME__', 'routiz'),

    // single dependency
    'dependency' => [
        'id' => '__DEPENDS_ID__',
        'value' => 'image',
        'compare' => 'IN', // =, !=, <, >, <=, >=, IN, NOT IN
        'style' => 'rz-opacity-30', // css class
    ],

    // multiple dependency
    'dependency' => [
        'relation' => 'OR', // AND, OR
        [
            'id' => '__DEPENDS_ID__',
            'value' => '__VALUE__',
            'compare' => '=',
        ],
        [
            'id' => '__DEPENDS_ID__',
            'value' => '__VALUE__',
            'compare' => '=',
        ],
        'style' => 'rz-opacity-30', // dependency style: display, opacity
    ],

]);
