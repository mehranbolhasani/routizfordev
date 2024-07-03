<?php

use \Routiz\Inc\Src\Form\Component as Form;
use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Listing\Listing;

defined('ABSPATH') || exit;

$form = new Form( Form::Storage_Meta );
$request = Request::instance();

$listing = new Listing( $request->get('listing_id') );

// valid listing
if( ! $listing->id ) {
    return;
}

$image = $listing->get_first_from_gallery('thumbnail');

// check if owner
if( (int) $listing->post->post_author !== get_current_user_id() ) {
    return;
}

// set global post object
global $post;
$post = get_post( $listing->id, OBJECT );
setup_postdata( $post );

?>

<div class="rz-modal-listing">
    <div class="rz--image">
        <?php if( $image ): ?>
            <span class="rz--img" style="background-image: url('<?php echo esc_url( $image ); ?>');"></span>
        <?php else: ?>
            <?php echo Rz()->dummy(); ?>
        <?php endif; ?>
    </div>
    <div class="rz--content">
        <h4 class="rz--title"><?php echo get_the_title( $listing->id ); ?></h4>
    </div>
</div>

<div class="rz-modal-container rz-scrollbar">
    <div class="rz-listing-edit">
        <form class="rz-form" method="post">
            <div class="rz-grid">

                <input type="hidden" name="listing_id" value="<?php echo (int) $listing->id; ?>">
                <?php wp_nonce_field( 'routiz_account_listing_update', 'security' ); ?>

                <?php

                    if( ! $listing->get('rz_listing_type') ) {
                        return;
                    }

                    $form->render([
                        'type' => 'tab',
                        'name' => esc_html__( 'General', 'routiz' )
                    ]);

                    $items = Rz()->jsoning( 'rz_fields', $listing->get('rz_listing_type') );

                    foreach( $items as $item ) {
                        if( isset( $item->fields->is_submit_form ) and $item->fields->is_submit_form ) {

                            $field = $form->create( $item );

                            if( ! Rz()->is_error( $field ) ) {
                                echo $field->get();
                            }
                        }
                    }

                    /*
                     * pricing
                     *
                     */
                    $action_fields = \Routiz\Inc\Src\Listing_Type\Action::get_action_fields( $listing->type );
                    $actions = $listing->type->get_action();

                    if( $action_fields->allow_pricing ) {

                        $form->render([
                            'type' => 'tab',
                            'name' => esc_html__( 'Pricing', 'routiz' )
                        ]);

                        if( $action_fields->allow_seasons ) {

                            $form->render([
                                'type' => 'number',
                                'id' => 'rz_price',
                                'name' => esc_html__('Base Price', 'routiz'),
                                'min' => 0,
                                'step' => 0.01,
                                'required' => boolval( ! $action_fields->allow_not_required_price ),
                                'col' => 6
                            ]);

                            $form->render([
                                'type' => 'number',
                                'id' => 'rz_price_weekend',
                                'name' => esc_html__('Weekend Price', 'routiz'),
                                'min' => 0,
                                'step' => 0.01,
                                'col' => 6
                            ]);

                        }else{

                            $form->render([
                                'type' => 'number',
                                'id' => 'rz_price',
                                'name' => esc_html__('Base Price', 'routiz'),
                                'min' => 0,
                                'step' => 0.01,
                                'required' => boolval( ! $action_fields->allow_not_required_price ),
                            ]);

                        }

                        if( $action_fields->allow_security_deposit ) {

                            $form->render([
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

                                $form->render([
                                    'type' => 'number',
                                    'id' => 'rz_long_term_week',
                                    'name' => esc_html__('Apply 7+ days discount in percentage', 'routiz'),
                                    'min' => 0,
                                    'step' => 0.01,
                                    'col' => 6,
                                ]);

                                $form->render([
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

                            $form->render([
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
                                                'id' => 'rz_price',
                                                'name' => esc_html__('Base Price', 'routiz'),
                                                'min' => 0,
                                                'step' => 0.01,
                                                'col' => 6,
                                            ],
                                            'price_weekend' => [
                                                'type' => 'number',
                                                'id' => 'rz_price_weekend',
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
                                $actions->has('booking_hourly')
                            )
                        ) {

                            $form->render([
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

                            $form->render([
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

                        $form->render([
                            'type' => 'tab',
                            'name' => esc_html__( 'Reservation', 'routiz' )
                        ]);

                        if( $actions->has('booking') ) {
                            $form->render([
                                'type' => 'heading',
                                'name' => esc_html__('Date Availability', 'routiz'),
                                'description' => esc_html__('You can manage the date availability within the listing availability calendar in your user dashboard.', 'routiz'),
                            ]);
                        }

                        if( $action_fields->allow_instant ) {

                            $form->render([
                                'type' => 'checkbox',
                                'id' => 'rz_instant',
                                'name' => esc_html__('Allow instant booking', 'routiz'),
                            ]);

                        }

                        if( $action_fields->allow_guests ) {

                            $form->render([
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

                            $form->render([
                                'type' => 'number',
                                'id' => 'rz_guest_price',
                                'name' => esc_html__('Price per each additional guests', 'routiz'),
                                'description' => esc_html__('Add additional price for each guest > 1', 'routiz'),
                                'input_type' => 'stepper',
                                'style' => 'v2',
                                'min' => 0,
                                'step' => 0.01,
                                'col' => 6,
                            ]);

                        }

                        if($action_fields->allow_guests and $action_fields->allow_child_pricing ) {

                            $form->render([
                                'type' => 'number',
                                'id' => 'rz_child_price',
                                'name' => esc_html__('Discount per each childs', 'routiz'),
                                'description' => esc_html__('Discount price for each childs', 'routiz'),
                                'input_type' => 'stepper',
                                'style' => 'v2',
                                'min' => 0,
                                'step' => 0.01,
                                'col' => 6,
                            ]);

                        }

                        if($action_fields->allow_guests and $action_fields->allow_child_based_pricing ) {

                            $form->render([
                                'type' => 'number',
                                'id' => 'rz_child_sep_price',
                                'name' => esc_html__('Price per each childs', 'routiz'),
                                'description' => esc_html__('Separate price for each childs', 'routiz'),
                                'input_type' => 'stepper',
                                'style' => 'v2',
                                'min' => 0,
                                'step' => 0.01,
                                'col' => 6,
                            ]);

                        }

                        if( $action_fields->allow_min_max ) {

                            $form->render([
                                'type' => 'number',
                                'id' => 'rz_reservation_length_min',
                                'name' => $actions->has('booking') ? esc_html__('Minimum number of days per reservation', 'routiz') : esc_html__('Minimum number of hours per reservation', 'routiz'),
                                'description' => esc_html__('Leave 0 to skip limits', 'routiz'),
                                'input_type' => 'stepper',
                                'style' => 'v2',
                                'value' => 0,
                                'min' => 0,
                                'step' => 1,
                                'col' => 6
                            ]);

                            $form->render([
                                'type' => 'number',
                                'id' => 'rz_reservation_length_max',
                                'name' => $actions->has('booking') ? esc_html__('Maximum number of days per reservation', 'routiz') : esc_html__('Maximum number of hours per reservation', 'routiz'),
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

                            $form->render([
                                'type' => 'select',
                                'id' => 'rz_reservation_start',
                                'name' => esc_html__('Booking Start Time', 'routiz'),
                                'options' => $time,
                                'col' => 6
                            ]);

                            $form->render([
                                'type' => 'select',
                                'id' => 'rz_reservation_end',
                                'name' => esc_html__('Booking End Time', 'routiz'),
                                'options' => $time,
                                'col' => 6
                            ]);

                            $form->render([
                                    'type' => 'checkbox',
                                    'id' => 'rz_hour_book_multi_disable',
                                    'name' => esc_html__('Disable Multiple Booking at Same Time Slot', 'routiz'),
                                    'col' => 6
                                ]);

                        }

                        if( $actions->has('booking_appointments') ) {

                            global $rz_form;
                            $rz_form = $form;

                            Rz()->the_template('routiz/metabox/listing/reservation-availability');

                        }

                    }

                ?>

            </div>
        </form>
    </div>
</div>

<div class="rz-modal-footer rz--top-border rz-text-center">
    <a href="#" class="rz-button rz-button-accent rz-modal-button" data-action="listing-save">
        <span><?php esc_html_e( 'Save Changes', 'routiz' ); ?></span>
        <?php Rz()->preloader(); ?>
    </a>
</div>
