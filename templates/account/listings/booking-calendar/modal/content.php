<?php

use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Listing\Listing;

$request = Request::instance();
$listing = new Listing( $request->get('listing_id') );

include __DIR__ . '/calendar.php';
$calendar = new Calendar();

$months = $calendar->generate_calendar_months();

?>

<div class="rz-modal-container rz-scrollbar">

    <div class="rz-calendar-inline rz-no-select">

        <div class="rz--nav">
            <div class="">
                <a href="#" class="rz-handle<?php if( (int) $request->get('month') == 0 ) { echo ' rz-disabled'; } ?>" data-action="prev">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i>
                </a>
            </div>
            <div class="rz--name">
                <?php echo sprintf( '%s %s', date_i18n( 'F', mktime( 0, 0, 0, $months[0]->month, 10 ) ), esc_attr( $months[0]->year ) ); ?>
            </div>
            <div class="">
                <a href="#" class="rz-handle" data-action="next">
                    <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <?php foreach( $months as $month ): ?>
            <div class="rz--month<?php if( $month->id <= 1 ): ?> rz-active<?php endif; ?>" data-month="<?php echo (int) $month->id; ?>">
                <ul class="rz--weekdays">
                    <?php foreach( $calendar->generate_week_days() as $week_day ): ?>
                        <li data-name="<?php echo esc_html( $week_day->name ); ?>">
                            <?php echo esc_html( $week_day->initial ); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <ul class="rz--days">
                    <?php foreach( $month->days as $d_index => $day ): ?>
                        <li class="<?php echo implode( ' ', $day->class ); ?>"
                            <?php if( isset( $day->timestamp ) ): ?>data-timestamp="<?php echo esc_attr( $day->timestamp ); ?>"<?php endif; ?>
                            <?php if( isset( $day->date ) ): ?>data-date="<?php echo esc_attr( $day->date ); ?>"<?php endif; ?>>
                            <?php if( $day->day ): ?>
                                <div class="rz--day">
                                    <span><?php echo (int) (int) $day->day; ?></span>
                                    <?php /*if( isset( $day->user ) and ! empty( $day->user ) ): ?>
                                        <div class="rz--user">
                                            <?php $user_avatar = $day->user->get_avatar(); ?>
                                            <?php $user_data = $day->user->get_userdata(); ?>
                                            <div class="rz--avatar">
                                                <?php if( $user_avatar ): ?>
                                                    <img src="<?php echo esc_url( $user_avatar ); ?>" alt="<?php echo esc_attr( $user_data->display_name ); ?>">
                                                <?php else: ?>
                                                    <i class="material-icon-person"></i>
                                                <?php endif; ?>
                                            </div>
                                            <p>
                                                <?php if( in_array( 'rz--is-pending', $day->class ) ): ?>
                                                    <i class="fas fa-stopwatch"></i>
                                                <?php elseif( in_array( 'rz--is-pending-payment', $day->class ) ): ?>
                                                    <i class="fas fa-hand-holding-usd"></i>
                                                <?php endif; ?>
                                                <?php echo esc_html( $user_data->display_name ); ?>
                                            </p>
                                        </div>
                                    <?php endif;*/ ?>
                                </div>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
        <div class="rz-calendar-schema">
            <p class="rz--name"><?php esc_html_e( 'Schema', 'routiz' ); ?></p>
            <ul>
                <li class="rz--available">
                    <span></span>
                    <p><?php esc_html_e( 'Available', 'routiz' ); ?></p>
                </li>
                <li class="rz--past">
                    <span></span>
                    <p><?php esc_html_e( 'Past day', 'routiz' ); ?></p>
                </li>
                <li class="rz--pending">
                    <span></span>
                    <p><?php esc_html_e( 'Pending', 'routiz' ); ?></p>
                </li>
                <li class="rz--booked">
                    <span></span>
                    <p><?php esc_html_e( 'Booked', 'routiz' ); ?></p>
                </li>
                <li class="rz--unavailable">
                    <span></span>
                    <p><?php esc_html_e( 'Unavailable', 'routiz' ); ?></p>
                </li>
                <li class="rz--toggle">
                    <a href="#" data-action="calendar-toggle-all"><?php esc_html_e( 'Toggle all dates', 'routiz' ); ?></a>
                </li>
            </ul>
        </div>
    </div>


</div>
