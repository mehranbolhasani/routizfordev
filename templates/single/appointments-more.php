<?php

use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Listing\Listing;
use \Routiz\Inc\Src\Listing\Appointments;

defined('ABSPATH') || exit;

$request = Request::instance();
$listing = new Listing( $request->get('listing_id') );

$action = $listing->type->get_action('booking_appointments');
$checkin = $request->get('checkin');
$page = max( 1, (int) $request->get('page') );
$appointments = new Appointments( $listing );
$checkin_date = null;

if( $checkin ) {
    $checkin_date = new \DateTime( date( 'Y-m-d', $checkin ), $appointments->timezone );
}

$upcoming = $appointments->get( $checkin_date, 20, $page, $request->get('adults'), $request->get('children'), $request->get('addons') );

$appointment_key = 0;

$last_day = new \DateTime( date( 'Y-m-d' ), $appointments->timezone );
$last_day->modify('today');

?>

<div class="rz-modal-container rz-scrollbar">
    <div class="rz-appointment-table">

        <?php foreach( $upcoming as $time ): ?>
            <?php foreach( $time as $appointment ): ?>

                <?php $app_day = clone $appointment['date']; ?>
                <?php $app_day->modify('today'); ?>

                <?php if( $last_day != $app_day ): ?>
                    <div class="rz--day" data-unix="<?php echo $app_day->getTimestamp(); ?>">
                        <span><?php echo esc_html( $app_day->format( get_option('date_format') ) ); ?></span>
                    </div>
                <?php endif; ?>

                <?php $last_day = $app_day; ?>

                <div class="rz--item">
                    <div class="rz--content">

                        <div class="rz--name">
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

            <?php $appointment_key++; endforeach; ?>
        <?php endforeach; ?>

        <?php if( ! $appointment_key ): ?>
            <p class="rz-text-center rz-weight-700">
                <?php esc_html_e('No appointments were found', 'routiz'); ?>
            </p>
        <?php endif; ?>

    </div>
</div>

<?php if( count( $upcoming ) >= 20 ): ?>
    <div class="rz-modal-footer rz--top-border rz-text-center">
        <a href="#" class="rz-weight-700 rz-no-decoration" data-action="appointments-paginate">
            <span><?php esc_html_e('Load more dates', 'routiz'); ?></span>
        </a>
    </div>
<?php endif; ?>
