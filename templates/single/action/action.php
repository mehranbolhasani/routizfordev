<?php

defined('ABSPATH') || exit;

global $rz_listing;
$action_types = Rz()->json_decode( $rz_listing->type->get('rz_action_types') );

$booking_action_types = [
    'booking',
    'booking_hourly',
    'booking_appointments'
];

?>

<div class="rz--heading">
    <div class="rz--col-heading">
        <h4 class="rz--title rz-ellipsis">
            <?php // echo Rz()->get_action_type_label( $action_type ); ?>
        </h4>
    </div>
    <div class="rz--col-close">
        <a href="#" class="rz-close" data-action="toggle-mobile-action">
            <i class="fas fa-times"></i>
        </a>
    </div>
</div>

<div class="rz-listing-action">
    <?php

        $action = \Routiz\Inc\Src\Listing\Action\Component::instance();
        $has_booking_action_type = false;

        foreach( $action_types as $action_type_id => $action_type ) {

            if( in_array( $action_type->template->id, $booking_action_types ) ) {
                if( $has_booking_action_type ) {
                    echo '<div class="rz-notice rz-notice-alert rz-mb-3">' . esc_html__('You can use only one booking action type') . '</div>';
                    continue;
                }else{
                    $has_booking_action_type = true;
                }
            }

            $action->render( array_merge( (array) $action_type->fields, [
                'type' => $action_type->template->id,
            ]));

        }

    ?>
</div>
