<?php

defined('ABSPATH') || exit;

$panel = \Routiz\Inc\Src\Admin\Panel::instance();
$panel->form->set( $panel->form::Storage_Meta );

?>

<div class="rz-outer">
    <div class="rz-panel rz-ml-auto rz-mr-auto">

        <div class="rz-content">
            <section class="rz-sections">
                <aside class="rz-section">

                    <div class="rz-form">
                        <div class="rz-grid">

                            <?php

                                $listing_type_field = $panel->form->create([
                                    'type' => 'listing_types',
                                    'id' => 'rz_listing_type',
                                    'name' => esc_html__( 'Listing Type', 'routiz' ),
                                    'return_ids' => true,
                                ]);

                                $listing_type = new \Routiz\Inc\Src\Listing_Type\Listing_Type( $listing_type_field->props->value );
                                $actions = $listing_type->get_action();

                                echo $listing_type_field->get();

                                if( $listing_type_field->props->value ) {

                                    $panel->form->render([
                                        'type' => 'tab',
                                        'name' => esc_html__( 'General', 'routiz' )
                                    ]);

                                    $items = Rz()->jsoning( 'rz_fields', Rz()->get_meta('rz_listing_type') );

                                    foreach( $items as $item ) {
                                        if( isset( $item->fields->is_admin_page ) and $item->fields->is_admin_page ) {

                                            $field = $panel->form->create( $item );

                                            if( ! Rz()->is_error( $field ) ) {

                                                if( $field->id == 'post_title' ) {
                                                    $field->props->disabled = true;
                                                }

                                                echo $field->get();

                                            }else{

                                                $field->display_error();

                                            }
                                        }
                                    }

                                    /*
                                     * pricing
                                     *
                                     */
                                    $action_fields = \Routiz\Inc\Src\Listing_Type\Action::get_action_fields( $listing_type );

                                    if( $action_fields->allow_pricing ) {

                                        $panel->form->render([
                                            'type' => 'tab',
                                            'name' => esc_html__( 'Pricing', 'routiz' )
                                        ]);

                                        if( $action_fields->allow_seasons ) {

                                            $panel->form->render([
                                                'type' => 'number',
                                                'id' => 'rz_price',
                                                'name' => esc_html__('Base Price', 'routiz'),
                                                'min' => 0,
                                                'step' => 0.01,
                                                'required' => boolval( ! $action_fields->allow_not_required_price ),
                                                'col' => 6
                                            ]);

                                            $panel->form->render([
                                                'type' => 'number',
                                                'id' => 'rz_price_weekend',
                                                'name' => esc_html__('Weekend Price', 'routiz'),
                                                'min' => 0,
                                                'step' => 0.01,
                                                'col' => 6
                                            ]);

                                        }else{

                                            $panel->form->render([
                                                'type' => 'number',
                                                'id' => 'rz_price',
                                                'name' => esc_html__('Base Price', 'routiz'),
                                                'min' => 0,
                                                'step' => 0.01,
                                                'required' => boolval( ! $action_fields->allow_not_required_price ),
                                            ]);

                                        }

                                        if( $action_fields->allow_security_deposit ) {

                                            $panel->form->render([
                                                'type' => 'number',
                                                'id' => 'rz_security_deposit',
                                                'name' => esc_html__('Security Deposit', 'routiz'),
                                                'min' => 0,
                                                'step' => 0.01,
                                            ]);

                                        }

                                        if( $action_fields->allow_long_term ) {
                                            if(
                                                $actions->has('booking') or
                                                $actions->has('booking_hourly')
                                            ) {

                                                $panel->form->render([
                                                    'type' => 'number',
                                                    'id' => 'rz_long_term_week',
                                                    'name' => esc_html__('Apply 7+ days discount in percentage', 'routiz'),
                                                    'min' => 0,
                                                    'step' => 0.01,
                                                    'col' => 6,
                                                ]);

                                                $panel->form->render([
                                                    'type' => 'number',
                                                    'id' => 'rz_long_term_month',
                                                    'name' => esc_html__('Apply 30+ days discount in percentage', 'routiz'),
                                                    'min' => 0,
                                                    'step' => 0.01,
                                                    'col' => 6,
                                                ]);

                                            }
                                        }

                                        if( $action_fields->allow_seasons ) {

                                            $panel->form->render([
                                                'type' => 'repeater',
                                                'id' => 'rz_price_seasonal',
                                                'name' => esc_html__('Add Seasonal Pricing', 'routiz'),
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
                                                        'heading' => 'start',
                                                        'fields' => [

                                                            'start' => [
                                                                'type' => 'text',
                                                                'name' => esc_html__('Start Date', 'routiz' ),
                                                                'placeholder' => 'DD/MM',
                                                                'col' => 6,
                                                            ],
                                                            'end' => [
                                                                'type' => 'text',
                                                                'name' => esc_html__('End Date', 'routiz' ),
                                                                'placeholder' => 'DD/MM',
                                                                'col' => 6,
                                                            ],
                                                            'price' => [
                                                                'type' => 'number',
                                                                'name' => esc_html__('Base Price', 'routiz'),
                                                                'min' => 0,
                                                                'step' => 0.01,
                                                                'col' => 6,
                                                            ],
                                                            'price_weekend' => [
                                                                'type' => 'number',
                                                                'name' => esc_html__('Weekend Price', 'routiz'),
                                                                'min' => 0,
                                                                'step' => 0.01,
                                                                'col' => 6,
                                                            ],

                                                        ]
                                                    ],

                                                ]
                                            ]);

                                        }

                                        if(
                                            $action_fields->allow_extra_pricing and (
                                                $actions->has('booking') or
                                                $actions->has('booking_hourly') or
                                                $actions->has('purchase')
                                            )
                                        ) {

                                            $panel->form->render([
                                                'type' => 'repeater',
                                                'id' => 'rz_extra_pricing',
                                                'name' => esc_html__('Add extra service pricing', 'routiz'),
                                                'button' => [
                                                    'label' => esc_html__('Add service', 'routiz')
                                                ],
                                                'templates' => [

                                                    /*
                                                     * service
                                                     *
                                                     */
                                                    'service' => [
                                                        'name' => esc_html__('Service', 'routiz'),
                                                        'heading' => 'name',
                                                        'fields' => [

                                                            'name' => [
                                                                'type' => 'text',
                                                                'name' => esc_html__('Service name', 'routiz'),
                                                                'placeholder' => esc_html__('Enter service name', 'routiz'),
                                                                'col' => 4,
                                                            ],
                                                            'type' => [
                                                                'type' => 'select',
                                                                'name' => esc_html__('Service type', 'routiz'),
                                                                'options' => [
                                                                    'single_fee' => esc_html__('Single fee', 'routiz'),
                                                                    'per_day' => esc_html__('Per day', 'routiz'),
                                                                ],
                                                                'allow_empty' => false,
                                                                'col' => 4,
                                                            ],
                                                            'price' => [
                                                                'type' => 'number',
                                                                'name' => esc_html__('Service price', 'routiz'),
                                                                'min' => 0,
                                                                'step' => 0.01,
                                                                'col' => 4,
                                                            ],

                                                        ]
                                                    ],

                                                ]
                                            ]);

                                        }

                                        if( $action_fields->allow_addons ) {

                                            $panel->form->render([
                                                'type' => 'repeater',
                                                'id' => 'rz_addons',
                                                'name' => esc_html__('Add addons', 'routiz'),
                                                'button' => [
                                                    'label' => esc_html__('Add addon', 'routiz')
                                                ],
                                                'templates' => [

                                                    /*
                                                     * addon
                                                     *
                                                     */
                                                    'addon' => [
                                                        'name' => esc_html__('Addon', 'routiz'),
                                                        'heading' => 'name',
                                                        'fields' => [

                                                            'name' => [
                                                                'type' => 'text',
                                                                'name' => esc_html__('Addon name', 'routiz'),
                                                                'placeholder' => esc_html__('Enter addon name', 'routiz'),
                                                                'col' => 6,
                                                            ],
                                                            'price' => [
                                                                'type' => 'number',
                                                                'name' => esc_html__('Addon price', 'routiz'),
                                                                'min' => 0,
                                                                'step' => 0.01,
                                                                'col' => 6,
                                                            ],
                                                            'key' => [
                                                                'type' => 'auto-key',
                                                                'name' => esc_html__('Addon Id', 'routiz'),
                                                            ],

                                                        ]
                                                    ],

                                                ]
                                            ]);

                                        }

                                    }

                                    if( $actions->has_reservation_section() ) {

                                        $panel->form->render([
                                            'type' => 'tab',
                                            'name' => esc_html__( 'Reservation', 'routiz' )
                                        ]);

                                        if( $actions->has('booking') ) {
                                            $panel->form->render([
                                                'type' => 'heading',
                                                'name' => esc_html__('Date Availability', 'routiz'),
                                                'description' => esc_html__('You can manage the date availability within the listing availability calendar in your user dashboard.', 'routiz'),
                                            ]);
                                        }

                                        if( $action_fields->allow_instant ) {

                                            $panel->form->render([
                                                'type' => 'checkbox',
                                                'id' => 'rz_instant',
                                                'name' => esc_html__('Allow instant booking', 'routiz'),
                                            ]);

                                        }

                                        if( $action_fields->allow_guests ) {

                                            $panel->form->render([
                                                'type' => 'number',
                                                'id' => 'rz_guests',
                                                'name' => esc_html__('Maximum number of guests allowed', 'routiz'),
                                                'description' => esc_html__('Limit the number of guests', 'routiz'),
                                                'input_type' => 'stepper',
                                                'style' => 'v2',
                                                'value' => 1,
                                                'min' => 1,
                                                'step' => 1,
                                                'col' => $action_fields->allow_guest_pricing ? 6 : 12,
                                                'col' => $action_fields->allow_child_pricing ? 6 : 12,
                                                'col' => $action_fields->allow_child_based_pricing ? 6 : 12,
                                            ]);

                                        }

                                        if( $action_fields->allow_guests and $action_fields->allow_guest_pricing ) {

                                            $panel->form->render([
                                                'type' => 'number',
                                                'id' => 'rz_guest_price',
                                                'name' => esc_html__('Price per each additional guests', 'routiz'),
                                                'description' => esc_html__('Increase the price for each guest > 1', 'routiz'),
                                                // 'input_type' => 'stepper',
                                                // 'style' => 'v2',
                                                'min' => 0,
                                                'step' => 0.01,
                                                'col' => 6,
                                            ]);

                                        }

                                        if($action_fields->allow_guests and $action_fields->allow_child_pricing ) {

                                            $panel->form->render([
                                                'type' => 'number',
                                                'id' => 'rz_child_price',
                                                'name' => esc_html__('Discount per each childs', 'routiz'),
                                                'description' => esc_html__('Discount the price for each childs', 'routiz'),
                                                // 'input_type' => 'stepper',
                                                // 'style' => 'v2',
                                                'min' => 0,
                                                'step' => 0.01,
                                                'col' => 6,
                                            ]);

                                        }

                                        if($action_fields->allow_guests and $action_fields->allow_child_based_pricing ) {

                                            $panel->form->render([
                                                'type' => 'number',
                                                'id' => 'rz_child_sep_price',
                                                'name' => esc_html__('Price per each childs', 'routiz'),
                                                'description' => esc_html__('Separate price for each childs', 'routiz'),
                                                // 'input_type' => 'stepper',
                                                // 'style' => 'v2',
                                                'min' => 0,
                                                'step' => 0.01,
                                                'col' => 6,
                                            ]);

                                        }


                                        if( $action_fields->allow_min_max ) {

                                            $panel->form->render([
                                                'type' => 'number',
                                                'id' => 'rz_reservation_length_min',
                                                'name' => esc_html__('Minimum number of days / hours per reservation', 'routiz'),
                                                'description' => esc_html__('Leave 0 to skip limits', 'routiz'),
                                                'input_type' => 'stepper',
                                                'style' => 'v2',
                                                'value' => 0,
                                                'min' => 0,
                                                'step' => 1,
                                                'col' => 6
                                            ]);

                                            $panel->form->render([
                                                'type' => 'number',
                                                'id' => 'rz_reservation_length_max',
                                                'name' => esc_html__('Maximum number of days / hours per reservation', 'routiz'),
                                                'description' => esc_html__('Leave 0 to skip limits', 'routiz'),
                                                'input_type' => 'stepper',
                                                'style' => 'v2',
                                                'value' => 0,
                                                'min' => 0,
                                                'step' => 1,
                                                'col' => 6
                                            ]);

                                        }

                                        if( $actions->has('booking_hourly') ) {

                                            $time = [];
                                            $today = strtotime( 'today', time() );
                                            $date = new \DateTime('01:00:00');
                                            $date_end = new \DateTime('24:00:00');
                                            $date_format = get_option('time_format');

                                            while( $date <= $date_end ) {
                                                $time[ $date->getTimestamp() - $today ] = $date->format( $date_format );
                                                $date->add( new \DateInterval('PT60M') );
                                            }

                                            $panel->form->render([
                                                'type' => 'select',
                                                'id' => 'rz_reservation_start',
                                                'name' => esc_html__('Booking Start Time', 'routiz'),
                                                'options' => $time,
                                                'col' => 6
                                            ]);

                                            $panel->form->render([
                                                'type' => 'select',
                                                'id' => 'rz_reservation_end',
                                                'name' => esc_html__('Booking End Time', 'routiz'),
                                                'options' => $time,
                                                'col' => 6
                                            ]);

                                            $panel->form->render([
                                                'type' => 'checkbox',
                                                'id' => 'rz_hour_book_multi_disable',
                                                'name' => esc_html__('Disable Multiple Booking at Same Time Slot', 'routiz'),
                                                'col' => 6
                                            ]);

                                        }

                                        if( $actions->has('booking_appointments') ) {

                                            global $rz_form;
                                            $rz_form = $panel->form;

                                            Rz()->the_template('routiz/metabox/listing/reservation-availability');

                                        }

                                    }

                                }

                            ?>

                            <div class="rz-form-group rz-col-12 rz-text-center rz-mt-3">
                                <button type="submit" class="rz-button rz-large">
                                    <span><?php esc_html_e( 'Save Changes', 'routiz' ); ?></span>
                                </button>
                            </div>

                        </div>
                    </div>
                </aside>
            </section>
        </div>
    </div>
</div>
