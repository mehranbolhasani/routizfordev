<?php

global $rz_upcoming;


$request = \Routiz\Inc\Src\Request\Request::instance();
$listing = new \Routiz\Inc\Src\Listing\Listing( $request->get('listing_id') );
$action = $listing->type->get_action('booking_appointments');

$appointment_key = 0;

if( ! is_array( $rz_upcoming ) ) {
    return;
}

echo '<div class="rz-appointment-table">';

foreach( $rz_upcoming as $time ):
    foreach( $time as $appointment ): ?>

        <div class="rz--item">
            <div class="rz--content">

                <div class="rz--name">
                    <?php if( $action->get_field('display_name') and ! empty( $appointment['period']->name ) ): ?>
                        <span><?php echo esc_html( $appointment['period']->name ); ?>,</span>
                    <?php endif; ?>
                    <span class="rz--date"><?php echo esc_html( $appointment['print']->date ); ?></span>
                    <?php if( $appointment['pricing']->total ): ?>
                        <span class="rz--price">- <?php echo do_shortcode( Rz()->format_price( $appointment['pricing']->total ) ); ?></span>
                    <?php endif; ?>
                </div>

                <?php if( $appointment['print']->time ): ?>
                    <span class="rz--time"><?php echo esc_html( $appointment['print']->time ); ?></span>
                <?php endif; ?>

            </div>
            <div class="rz--action">
                <div>
                    <?php if( $appointment['is_available'] ): 
                            if(is_user_logged_in()){ ?>
                                <a href="#" class="rz-button rz-button-accent rz-small"
                                    data-action="action-add-appointment"
                                    data-id="<?php echo esc_attr( $appointment['period']->id ); ?>"
                                    data-checkin="<?php echo (int) $appointment['date']->getTimestamp(); ?>"
                                    data-limit="<?php echo (int) $appointment['period']->limit; ?>"
                                    data-booked="<?php echo (int) $appointment['booked']; ?>"
                                    >
                                    
                                    <span><?php esc_html_e('Select', 'routiz'); ?></span>
                                    <?php Rz()->preloader(); ?>
                                </a>
                            <?php } else { ?>
                                  <a href="#" class="rz-button rz-button-accent rz-small" data-modal="signin">
                                    <span><?php esc_html_e('Select', 'routiz'); ?></span> 
                                </a>     
                            <?php } ?>
                    <?php else: ?>
                        <a href="#" class="rz-button rz-gray rz-small"
                            data-limit="<?php echo (int) $appointment['period']->limit; ?>"
                            data-booked="<?php echo (int) $appointment['booked']; ?>"
                            >
                            <?php esc_html_e('Sold out', 'routiz'); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php

        $appointment_key++;

    endforeach;
endforeach;

if( ! $appointment_key ) {
    echo '<p class="rz-text-center rz-weight-700">' . esc_html__('No appointments were found', 'routiz') . '</p>';
}

echo '</div>';
