<?php

global $rz_form;

$start = new \DateTime('00:01:00');
$date_format = get_option('time_format');
$time = [
    0 => $start->format( $date_format ),
];
$today = strtotime( 'today', time() );
$date = new \DateTime('01:00:00');
$date_end = new \DateTime('24:00:00');

while( $date <= $date_end ) {
    $time[ $date->getTimestamp() - $today ] = $date->format( $date_format );
    $date->add( new \DateInterval('PT60M') );
}

$rz_form->render([
    'type' => 'repeater',
    'id' => 'rz_time_availability',
    'name' => esc_html__('Add Availability', 'routiz'),
    'button' => [
        'label' => esc_html__('Add Period', 'routiz')
    ],
    'templates' => [

        /*
         * period
         *
         */
        'period' => [
            'name' => esc_html__('Period', 'routiz'),
            'heading' => 'name',
            'fields' => [

                'name' => [
                    'type' => 'text',
                    'name' => esc_html__('Name', 'routiz'),
                    'value' => esc_html__('Custom Period', 'routiz'),
                    'col' => 12,
                ],

                'key' => [
                    'type' => 'auto-key',
                ],

                'start_time' => [
                    'type' => 'select',
                    'name' => esc_html__('Start Time', 'routiz'),
                    'options' => $time,
                    'value' => 28800,
                    'allow_empty' => false,
                    'col' => 6
                ],

                'end_time' => [
                    'type' => 'select',
                    'name' => esc_html__('End Time', 'routiz'),
                    'options' => $time,
                    'value' => 64800,
                    'allow_empty' => false,
                    'col' => 6
                ],

                'duration' => [
                    'type' => 'select',
                    'name' => esc_html__('Appointment Duration', 'routiz'),
                    'options' => [
                        '900' => '15m',
                        '1800' => '30m',
                        '3600' => '60m',
                        'custom' => esc_html__('Custom', 'routiz'),
                    ],
                    'value' => '60m',
                    'allow_empty' => false,
                    'style' => 'v2',
                ],

                'custom_duration_length' => [
                    'type' => 'text',
                    'name' => esc_html__('Custom Appointment Duration Lenght', 'routiz'),
                    'dependency' => [
                        'id' => 'duration',
                        'compare' => '=',
                        'value' => 'custom',
                    ],
                    'col' => 6
                ],

                'custom_duration_entity' => [
                    'type' => 'select',
                    'name' => esc_html__('Custom Appointment Duration Entity', 'routiz'),
                    'options' => [
                        '60' => esc_html__('Minutes', 'routiz'),
                        '3600' => esc_html__('Hours', 'routiz'),
                        '86400' => esc_html__('Day', 'routiz'),
                    ],
                    'value' => 'm',
                    'allow_empty' => false,
                    'dependency' => [
                        'id' => 'duration',
                        'compare' => '=',
                        'value' => 'custom',
                    ],
                    'col' => 6
                ],

                'interval' => [
                    'type' => 'select',
                    'name' => esc_html__('Time Between Appointment', 'routiz'),
                    'options' => [
                        'none' => esc_html__('None', 'routiz'),
                        '300' => '5m',
                        '600' => '10m',
                        'custom' => esc_html__('Custom', 'routiz'),
                    ],
                    'value' => 'none',
                    'allow_empty' => false,
                    'style' => 'v2',
                ],

                'custom_interval_length' => [
                    'type' => 'text',
                    'name' => esc_html__('Custom Time Between Appointment Lenght', 'routiz'),
                    'dependency' => [
                        'id' => 'interval',
                        'compare' => '=',
                        'value' => 'custom',
                    ],
                    'col' => 6
                ],

                'custom_interval_entity' => [
                    'type' => 'select',
                    'name' => esc_html__('Custom Time Between Appointment Entity', 'routiz'),
                    'options' => [
                        '60' => esc_html__('Minutes', 'routiz'),
                        '3600' => esc_html__('Hours', 'routiz'),
                        '86400' => esc_html__('Days', 'routiz'),
                    ],
                    'value' => 'm',
                    'allow_empty' => false,
                    'dependency' => [
                        'id' => 'interval',
                        'compare' => '=',
                        'value' => 'custom',
                    ],
                    'col' => 6
                ],

                'recurring' => [
                    'type' => 'checkbox',
                    'name' => esc_html__('Recurring Period', 'routiz' ),
                ],

                'start' => [
                    'type' => 'text',
                    'name' => esc_html__('Start Date', 'routiz' ),
                    'placeholder' => 'YYYY-MM-DD',
                    'col' => 6,
                    'dependency' => [
                        'id' => 'recurring',
                        'compare' => '=',
                        'value' => false,
                    ],
                ],

                'end' => [
                    'type' => 'text',
                    'name' => esc_html__('End Date', 'routiz' ),
                    'placeholder' => 'YYYY-MM-DD',
                    'col' => 6,
                    'dependency' => [
                        'id' => 'recurring',
                        'compare' => '=',
                        'value' => false,
                    ],
                ],

                'recurring_availability' => [
                    'type' => 'checklist',
                    'name' => esc_html__('Repeat Availability', 'routiz' ),
                    'options' => [
                        '1' => esc_html__('Monday', 'routiz'),
                        '2' => esc_html__('Tuesday', 'routiz'),
                        '3' => esc_html__('Wednesday', 'routiz'),
                        '4' => esc_html__('Thursday', 'routiz'),
                        '5' => esc_html__('Friday', 'routiz'),
                        '6' => esc_html__('Saturday', 'routiz'),
                        '7' => esc_html__('Sunday', 'routiz'),
                    ],
                    'dependency' => [
                        'id' => 'recurring',
                        'compare' => '=',
                        'value' => true,
                    ],
                ],

                'price' => [
                    'type' => 'number',
                    'name' => esc_html__('Custom Price', 'routiz'),
                    'description' => esc_html__('Leave empty if you want to use the base price', 'routiz' ),
                    'min' => 0,
                    'step' => 0.01,
                    'col' => 6
                ],

                'price_weekend' => [
                    'type' => 'number',
                    'name' => esc_html__('Custom Weekend Price', 'routiz'),
                    'description' => esc_html__('Leave empty if you want to use the base weekend price', 'routiz' ),
                    'min' => 0,
                    'step' => 0.01,
                    'col' => 6
                ],

                'limit' => [
                    'type' => 'number',
                    'name' => esc_html__('Limit Guests', 'routiz'),
                    'description' => esc_html__('Number only. Leave empty for unlimited.', 'routiz' ),
                    'min' => 0,
                    'step' => 1,
                ],

            ]
        ],

    ]
]);

?>

<script type="text/javascript">
    jQuery(document).ready(function() {
        var input = jQuery('input[name="custom_duration_length"]');

        // Create a new span element with an ID of custom_duration_length-error and a class of error
        var errorSpan = jQuery('<span>', {
            id: 'custom_duration_length-error',
            class: 'error'
        });

        // Append the span element after the input element
        input.after(errorSpan);

        jQuery('select[name="custom_duration_entity"]').change(function() {
            if (jQuery(this).val() === "86400") {
                jQuery("#custom_duration_length-error").text('Booking a slot is limited to 1 day only and cannot be extended to multiple days.').css({'color':'red'});
            } else {
                jQuery("#custom_duration_length-error").text(''); // Remove the event listener when the select box value is not "123"
            }
        });
    });
</script>
