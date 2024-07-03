<?php

namespace Routiz\Templates\Admin\Metabox;

class Filters {

    static public function get_fields( $id ) {
        return [
            'type' => 'repeater',
            'id' => $id,
            'name' => esc_html__('Filters', 'routiz'),
            'button' => [
                'label' => esc_html__('Add Filter', 'routiz')
            ],
            'templates' => [

                /*
                 * text
                 *
                 */
                'text' => [
                    'name' => esc_html__( 'Text', 'routiz' ),
                    'heading' => 'name',
                    'fields' => [
                        'name' => [
                            'type' => 'text',
                            'name' => esc_html__( 'Name', 'routiz' ),
                            'col' => 6
                        ],
                        'description' => [
                            'type' => 'text',
                            'name' => esc_html__( 'Description', 'routiz' ),
                            'col' => 6
                        ],
                        'id' => [
                            'type' => 'key',
                            'name' => esc_html__( 'Field Key', 'routiz' ),
                            'description' => esc_html__( 'The filder id should match the field id you created in Listing Type > Listing > Fields', 'routiz' ),
                            'col' => 6
                        ],
                        'placeholder' => [
                            'type' => 'text',
                            'name' => esc_html__( 'Placeholder', 'routiz' ),
                        ],
                        'col' => [
                            'type' => 'select',
                            'name' => esc_html__( 'Column Size', 'routiz' ),
                            'value' => 12,
                            'options' => [
                                12 => esc_html__('100%', 'routiz'),
                                6 => esc_html__('50%', 'routiz'),
                                4 => esc_html__('33%', 'routiz'),
                                3 => esc_html__('25%', 'routiz'),
                                2 => esc_html__('16%', 'routiz'),
                                'auto' => esc_html__('Auto', 'routiz'),
                            ],
                            'allow_empty' => false,
                        ],
                        /*'filter_icon' => [
                            'type' => 'icon',
                            'name' => esc_html__('Filter Icon', 'routiz'),
                        ],*/
                    ]
                ],

                /*
                 * checkbox
                 *
                 */
                'checkbox' => [
                    'name' => esc_html__( 'Checkbox', 'routiz' ),
                    'heading' => 'name',
                    'fields' => [
                        'name' => [
                            'type' => 'text',
                            'name' => esc_html__( 'Name', 'routiz' ),
                            'col' => 6
                        ],
                        'description' => [
                            'type' => 'text',
                            'name' => esc_html__( 'Description', 'routiz' ),
                            'col' => 6
                        ],
                        'id' => [
                            'type' => 'key',
                            'name' => esc_html__( 'Field Key', 'routiz' ),
                            'description' => esc_html__( 'The filder id should match the field id you created in Listing Type > Listing > Fields', 'routiz' ),
                            'col' => 6
                        ],
                        'col' => [
                            'type' => 'select',
                            'name' => esc_html__( 'Column Size', 'routiz' ),
                            'value' => 12,
                            'options' => [
                                12 => esc_html__('100%', 'routiz'),
                                6 => esc_html__('50%', 'routiz'),
                                4 => esc_html__('33%', 'routiz'),
                                3 => esc_html__('25%', 'routiz'),
                                2 => esc_html__('16%', 'routiz'),
                                'auto' => esc_html__('Auto', 'routiz'),
                            ],
                            'allow_empty' => false,
                        ],
                    ]
                ],

                /*
                 * taxonomy
                 *
                 */
                'taxonomy' => [
                    'name' => esc_html__( 'Taxonomy', 'routiz' ),
                    'heading' => 'name',
                    'fields' => [
                        'name' => [
                            'type' => 'text',
                            'name' => esc_html__( 'Name', 'routiz' ),
                            'col' => 6
                        ],
                        'description' => [
                            'type' => 'text',
                            'name' => esc_html__( 'Description', 'routiz' ),
                            'col' => 6
                        ],
                        'id' => [
                            'type' => 'taxonomy_types',
                            'name' => esc_html__('Taxonomy', 'routiz'),
                            'col' => 6,
                            'allow_empty' => false
                        ],
                        'display' => [
                            'type' => 'select',
                            'name' => esc_html__('Field Type', 'routiz'),
                            'value' => 'select',
                            'options' => [
                                'select' => esc_html__('Select', 'routiz'),
                                'select2' => esc_html__('Select2', 'routiz'),
                                'radio' => esc_html__('Radio Buttons', 'routiz'),
                                'checklist' => esc_html__('Checklist', 'routiz'),
                                'buttons' => esc_html__('Buttons', 'routiz'),
                            ],
                            'allow_empty' => false
                        ],
                        'col' => [
                            'type' => 'select',
                            'name' => esc_html__( 'Column Size', 'routiz' ),
                            'value' => 12,
                            'options' => [
                                12 => esc_html__('100%', 'routiz'),
                                6 => esc_html__('50%', 'routiz'),
                                4 => esc_html__('33%', 'routiz'),
                                3 => esc_html__('25%', 'routiz'),
                                2 => esc_html__('16%', 'routiz'),
                                'auto' => esc_html__('Auto', 'routiz'),
                            ],
                            'allow_empty' => false,
                        ],
                        /*'filter_icon' => [
                            'type' => 'icon',
                            'name' => esc_html__('Filter Icon', 'routiz'),
                        ],*/
                    ]
                ],

                /*
                 * taxonomy heading
                 *
                 *
                'taxonomy-heading' => [
                    'name' => esc_html__( 'Taxonomy Heading', 'routiz' ),
                    'heading' => 'name',
                    'fields' => [
                        'name' => [
                            'type' => 'text',
                            'name' => esc_html__( 'Name', 'routiz' ),
                            'value' => esc_html__( 'Name', 'routiz' ),
                            'col' => 6
                        ],
                        'id' => [
                            'type' => 'taxonomy-types',
                            'name' => esc_html__('Taxonomy', 'routiz'),
                            'value' => 'post_title',
                            'col' => 6
                        ],
                        'format' => [
                            'type' => 'text',
                            'name' => esc_html__( 'Format', 'routiz' ),
                            'value' => esc_html__( '%s', 'routiz' ),
                        ],
                    ]
                ],*/

                /*
                 * number
                 *
                 */
                'number' => [
                    'name' => esc_html__( 'Number', 'routiz' ),
                    'heading' => 'name',
                    'fields' => [
                        'name' => [
                            'type' => 'text',
                            'name' => esc_html__( 'Name', 'routiz' ),
                            'col' => 6
                        ],
                        'description' => [
                            'type' => 'text',
                            'name' => esc_html__( 'Description', 'routiz' ),
                            'col' => 6
                        ],
                        'id' => [
                            'type' => 'key',
                            'name' => esc_html__( 'Field Key', 'routiz' ),
                            'col' => 6
                        ],
                        'input_type' => [
                            'type' => 'select',
                            'name' => esc_html__( 'Field Type', 'routiz' ),
                            'options' => [
                                'number' => esc_html__('Number', 'routiz'),
                                'range' => esc_html__('Slider', 'routiz'),
                                'stepper' => esc_html__('Stepper', 'routiz'),
                            ],
                            'allow_empty' => false,
                            'col' => 6
                        ],
                        'min' => [
                            'type' => 'number',
                            'name' => esc_html__( 'Minimum', 'routiz' ),
                            'col' => 6
                        ],
                        'max' => [
                            'type' => 'number',
                            'name' => esc_html__( 'Maximun', 'routiz' ),
                            'col' => 6
                        ],
                        'step' => [
                            'type' => 'number',
                            'name' => esc_html__( 'Step Size', 'routiz' ),
                            'col' => 6
                        ],
                        'comparison' => [
                            'type' => 'select',
                            'name' => esc_html__( 'Comparison', 'routiz' ),
                            'value' => 'equal',
                            'options' => [
                                'equal' => esc_html__('Equal', 'routiz'),
                                'less' => esc_html__('Less', 'routiz'),
                                'less_or_equal' => esc_html__('Less or equal', 'routiz'),
                                'greater' => esc_html__('Greater', 'routiz'),
                                'greater_or_equal' => esc_html__('Greater or equal', 'routiz'),
                            ],
                            'allow_empty' => false,
                            'col' => 6
                        ],
                        'col' => [
                            'type' => 'select',
                            'name' => esc_html__( 'Column Size', 'routiz' ),
                            'value' => 12,
                            'options' => [
                                12 => esc_html__('100%', 'routiz'),
                                6 => esc_html__('50%', 'routiz'),
                                4 => esc_html__('33%', 'routiz'),
                                3 => esc_html__('25%', 'routiz'),
                                2 => esc_html__('16%', 'routiz'),
                                'auto' => esc_html__('Auto', 'routiz'),
                            ],
                            'allow_empty' => false,
                        ],
                        /*'filter_icon' => [
                            'type' => 'icon',
                            'name' => esc_html__('Filter Icon', 'routiz'),
                        ],*/
                    ]
                ],

                /*
                 * range
                 *
                 */
                'range' => [
                    'name' => esc_html__( 'Range', 'routiz' ),
                    'heading' => 'name',
                    'fields' => [
                        'name' => [
                            'type' => 'text',
                            'name' => esc_html__( 'Name', 'routiz' ),
                            'col' => 6
                        ],
                        'description' => [
                            'type' => 'text',
                            'name' => esc_html__( 'Description', 'routiz' ),
                            'col' => 6
                        ],
                        'id' => [
                            'type' => 'key',
                            'name' => esc_html__( 'Field Key', 'routiz' ),
                            'col' => 6
                        ],
                        'input_type' => [
                            'type' => 'select',
                            'name' => esc_html__( 'Field Type', 'routiz' ),
                            'value' => 'select',
                            'options' => [
                                'number' => esc_html__( 'Number', 'routiz' ),
                                // 'slider' => esc_html__( 'Slider', 'routiz' ),
                            ],
                            'allow_empty' => false,
                            'col' => 6
                        ],
                        'min' => [
                            'type' => 'number',
                            'name' => esc_html__( 'Minimum', 'routiz' ),
                            'col' => 6
                        ],
                        'max' => [
                            'type' => 'number',
                            'name' => esc_html__( 'Maximun', 'routiz' ),
                            'col' => 6
                        ],
                        'step' => [
                            'type' => 'number',
                            'name' => esc_html__( 'Step Size', 'routiz' ),
                            'col' => 6
                        ],
                        'suffix' => [
                            'type' => 'text',
                            'name' => esc_html__( 'Suffix', 'routiz' ),
                            'col' => 6
                        ],
                        'placeholder_from' => [
                            'type' => 'text',
                            'name' => esc_html__( 'Placeholder From', 'routiz' ),
                            'col' => 6
                        ],
                        'placeholder_to' => [
                            'type' => 'text',
                            'name' => esc_html__( 'Placeholder To', 'routiz' ),
                            'col' => 6
                        ],
                        'col' => [
                            'type' => 'select',
                            'name' => esc_html__( 'Column Size', 'routiz' ),
                            'value' => 12,
                            'options' => [
                                12 => esc_html__('100%', 'routiz'),
                                6 => esc_html__('50%', 'routiz'),
                                4 => esc_html__('33%', 'routiz'),
                                3 => esc_html__('25%', 'routiz'),
                                2 => esc_html__('16%', 'routiz'),
                                'auto' => esc_html__('Auto', 'routiz'),
                            ],
                            'allow_empty' => false,
                        ],
                        /*'filter_icon' => [
                            'type' => 'icon',
                            'name' => esc_html__('Filter Icon', 'routiz'),
                        ],*/
                    ]
                ],

                /*
                 * autocomplete
                 *
                 */
                'autocomplete' => [
                    'name' => esc_html__( 'Autocomplete', 'routiz' ),
                    'heading' => 'name',
                    'fields' => [
                        'name' => [
                            'type' => 'text',
                            'name' => esc_html__( 'Name', 'routiz' ),
                            'col' => 6
                        ],
                        'id' => [
                            'type' => 'taxonomy_types',
                            'col' => 6,
                            'allow_empty' => false
                        ],
                        'placeholder' => [
                            'type' => 'text',
                            'name' => esc_html__( 'Placeholder', 'routiz' ),
                        ],
                        'icon' => [
                            'type' => 'icon',
                            'name' => esc_html__('Icon', 'routiz'),
                            'value' => 'fas fa-map-marker-alt',
                        ],
                        'col' => [
                            'type' => 'select',
                            'name' => esc_html__( 'Column Size', 'routiz' ),
                            'value' => 12,
                            'options' => [
                                12 => esc_html__('100%', 'routiz'),
                                6 => esc_html__('50%', 'routiz'),
                                4 => esc_html__('33%', 'routiz'),
                                3 => esc_html__('25%', 'routiz'),
                                2 => esc_html__('16%', 'routiz'),
                                'auto' => esc_html__('Auto', 'routiz'),
                            ],
                            'allow_empty' => false,
                        ],
                        /*'filter_icon' => [
                            'type' => 'icon',
                            'name' => esc_html__('Filter Icon', 'routiz'),
                        ],*/
                    ]
                ],

                /*
                 * availability calendar
                 *
                 */
                'booking_calendar' => [
                    'name' => esc_html__( 'Availability Calendar', 'routiz' ),
                    'heading' => 'name',
                    'fields' => [
                        'name' => [
                            'type' => 'text',
                            'name' => esc_html__( 'Name', 'routiz' ),
                        ],
                        'id' => [
                            'type' => 'hidden',
                            'value' => 'rz_booking_dates',
                        ],
                        'single_selection' => [
                            'type' => 'checkbox',
                            'name' => esc_html__( 'Single Date Selection?', 'routiz' ),
                        ],
                        'col' => [
                            'type' => 'select',
                            'name' => esc_html__( 'Column Size', 'routiz' ),
                            'value' => 12,
                            'options' => [
                                12 => esc_html__('100%', 'routiz'),
                                6 => esc_html__('50%', 'routiz'),
                                4 => esc_html__('33%', 'routiz'),
                                3 => esc_html__('25%', 'routiz'),
                                2 => esc_html__('16%', 'routiz'),
                                'auto' => esc_html__('Auto', 'routiz'),
                            ],
                            'allow_empty' => false,
                        ],
                    ]
                ],

                /*
                 * guests
                 *
                 */
                'guests' => [
                    'name' => esc_html__( 'Guests', 'routiz' ),
                    'heading' => 'name',
                    'fields' => [
                        'name' => [
                            'type' => 'text',
                            'name' => esc_html__( 'Name', 'routiz' ),
                        ],
                        'id' => [
                            'type' => 'hidden',
                            'value' => 'rz_guests',
                        ],
                        'col' => [
                            'type' => 'select',
                            'name' => esc_html__( 'Column Size', 'routiz' ),
                            'value' => 12,
                            'options' => [
                                12 => esc_html__('100%', 'routiz'),
                                6 => esc_html__('50%', 'routiz'),
                                4 => esc_html__('33%', 'routiz'),
                                3 => esc_html__('25%', 'routiz'),
                                2 => esc_html__('16%', 'routiz'),
                                'auto' => esc_html__('Auto', 'routiz'),
                            ],
                            'allow_empty' => false,
                        ],
                        /*'filter_icon' => [
                            'type' => 'icon',
                            'name' => esc_html__('Filter Icon', 'routiz'),
                        ],*/
                    ]
                ],

                /*
                 * tab
                 *
                 */
                'tab' => [
                    'name' => esc_html__( 'Tab', 'routiz' ),
                    'heading' => 'name',
                    'fields' => [
                        'asdasd' => [
                            'type' => 'hidden',
                        ],
                        'id' => [
                            'type' => 'auto-key',
                        ],
                        'name' => [
                            'type' => 'text',
                            'name' => esc_html__( 'Name', 'routiz' ),
                            'value' => esc_html__( 'Tab Name', 'routiz' ),
                            'col' => 6
                        ],
                        'label' => [
                            'type' => 'text',
                            'name' => esc_html__( 'Tab Label', 'routiz' ),
                            'col' => 6
                        ],
                        'col' => [
                            'type' => 'select',
                            'name' => esc_html__( 'Column Size', 'routiz' ),
                            'value' => 12,
                            'options' => [
                                12 => esc_html__('100%', 'routiz'),
                                6 => esc_html__('50%', 'routiz'),
                                4 => esc_html__('33%', 'routiz'),
                                3 => esc_html__('25%', 'routiz'),
                                2 => esc_html__('16%', 'routiz'),
                                'auto' => esc_html__('Auto', 'routiz'),
                            ],
                            'allow_empty' => false,
                        ],
                        /*'filter_icon' => [
                            'type' => 'icon',
                            'name' => esc_html__('Filter Icon', 'routiz'),
                        ],*/
                    ]
                ],

                /*
                 * tab break
                 *
                 */
                'tab_break' => [
                    'name' => esc_html__( 'Tab Break', 'routiz' ),
                    'heading' => 'name',
                    'fields' => [

                    ]
                ],

                /*
                 * heading
                 *
                 */
                'heading' => [
                    'name' => esc_html__( 'Heading', 'routiz' ),
                    'heading' => 'name',
                    'fields' => [
                        'name' => [
                            'type' => 'text',
                            'name' => esc_html__( 'Name', 'routiz' ),
                        ],
                        'id' => [
                            'type' => 'auto-key',
                        ],
                        'description' => [
                            'type' => 'text',
                            'name' => esc_html__( 'Description', 'routiz' ),
                        ],
                    ]
                ],

                /*
                 * separator
                 *
                 */
                'separator' => [
                    'name' => esc_html__( 'Separator', 'routiz' ),
                    'heading' => 'name',
                    'fields' => [

                    ]
                ],

                /*
                 * listing types
                 *
                 */
                'listing_types' => [
                    'name' => esc_html__( 'Listing Types', 'routiz' ),
                    'heading' => 'name',
                    'fields' => [
                        'name' => [
                            'type' => 'text',
                            'name' => esc_html__( 'Name', 'routiz' ),
                            'col' => 6
                        ],
                        'id' => [
                            'type' => 'hidden',
                            'value' => 'rz_type',
                        ],
                        'choice' => [
                            'type' => 'select',
                            'name' => esc_html__('Field Type', 'routiz'),
                            'value' => 'select',
                            'options' => [
                                'select' => esc_html__('Select', 'routiz'),
                                'select2' => esc_html__('Select2', 'routiz'),
                                'radio' => esc_html__('Radio Buttons', 'routiz'),
                                'buttons' => esc_html__('Buttons', 'routiz'),
                            ],
                            'allow_empty' => false,
                            'col' => 6
                        ],
                        'col' => [
                            'type' => 'select',
                            'name' => esc_html__( 'Column Size', 'routiz' ),
                            'value' => 12,
                            'options' => [
                                12 => esc_html__('100%', 'routiz'),
                                6 => esc_html__('50%', 'routiz'),
                                4 => esc_html__('33%', 'routiz'),
                                3 => esc_html__('25%', 'routiz'),
                                2 => esc_html__('16%', 'routiz'),
                                'auto' => esc_html__('Auto', 'routiz'),
                            ],
                            'allow_empty' => false,
                        ],
                        /*'filter_icon' => [
                            'type' => 'icon',
                            'name' => esc_html__('Filter Icon', 'routiz'),
                        ],*/
                    ]
                ],

                /*
                 * geolocation
                 *
                 */
                'geo' => [
                    'name' => esc_html__( 'Geolocation', 'routiz' ),
                    'heading' => 'name',
                    'fields' => [
                        'name' => [
                            'type' => 'text',
                            'name' => esc_html__( 'Name', 'routiz' ),
                            'col' => 6
                        ],
                        'description' => [
                            'type' => 'text',
                            'name' => esc_html__( 'Description', 'routiz' ),
                            'col' => 6
                        ],
                        'id' => [
                            'type' => 'hidden',
                            'value' => 'geo',
                        ],
                        'placeholder' => [
                            'type' => 'text',
                            'name' => esc_html__( 'Placeholder', 'routiz' ),
                        ],
                        'col' => [
                            'type' => 'select',
                            'name' => esc_html__( 'Column Size', 'routiz' ),
                            'value' => 12,
                            'options' => [
                                12 => esc_html__('100%', 'routiz'),
                                6 => esc_html__('50%', 'routiz'),
                                4 => esc_html__('33%', 'routiz'),
                                3 => esc_html__('25%', 'routiz'),
                                2 => esc_html__('16%', 'routiz'),
                                'auto' => esc_html__('Auto', 'routiz'),
                            ],
                            'allow_empty' => false,
                        ],
                    ]
                ],

            ],
        ];
    }
}
