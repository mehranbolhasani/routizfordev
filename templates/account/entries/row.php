<?php

use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Listing\Listing;
use \Routiz\Inc\Src\Listing\Conversation;

defined('ABSPATH') || exit;

$request = Request::instance();
$listing = new Listing( Rz()->get_meta('rz_listing') );

$status = get_post_status( get_the_ID() );
$entry_type = Rz()->get_meta( 'rz_entry_type' );
$image = $listing->get_first_from_gallery( 'thumbnail' );
$request_user_id = Rz()->get_meta( 'rz_request_user_id' );
$current_user_id = get_current_user_id();
$conversation_id = null;
$userdata = get_userdata( $request_user_id );
$checkin = Rz()->get_meta('rz_checkin_date');

if( $listing->post ) {
    $reciever_id = ( $request_user_id == $current_user_id ) ? $listing->post->post_author : $current_user_id;
    $conversation_id = Conversation::get_or_create_by_user_id( $listing->id, $request_user_id, $reciever_id );
}

if( $listing->type ) {
    $review_submission = $listing->type->get('rz_review_submission');
    if( empty( $review_submission ) ) {
        $review_submission = 'everyone';
    }
}

?>

<div class="rz--cell">
    <div class="rz-box">
        <?php if( $listing->post ): ?>
            <div class="rz--heading">
                <div class="rz--image">
                    <?php $tag = $listing->post->post_status == 'publish' ? 'a' : 'span'; ?>
                    <<?php echo esc_attr( $tag ); ?> href="<?php the_permalink( $listing->id ); ?>">
                        <?php if( $image ): ?>
                            <?php echo '<img src="' . esc_url( $image ) . '" alt="">'; ?>
                        <?php else: ?>
                            <?php echo Rz()->dummy( 'fas fa-map-marker-alt', 100 ); ?>
                        <?php endif; ?>
                    </<?php echo esc_attr( $tag ); ?>>
                </div>
                <div class="rz--title">
                    <h4>
                        <<?php echo esc_attr( $tag ); ?> href="<?php the_permalink( $listing->id ); ?>">
                            <?php echo wp_trim_words( get_the_title( $listing->id ), 8 ); ?>
                        </<?php echo esc_attr( $tag ); ?>>
                    </h4>
                </div>
            </div>
        <?php endif; ?>
        <div class="rz--status">
            <div class="rz-post-status rz-status-<?php echo $status; ?>">
                <span><?php echo Rz()->get_status(); ?></span>
            </div>
        </div>
        <div class="rz--content">
            <table>
                <tbody>
                    <tr>
                        <td><?php esc_html_e( 'Type', 'routiz' ); ?></td>
                        <td><?php echo esc_html( Rz()->get_entry_type( Rz()->get_meta( 'rz_entry_type' ) ) ); ?></td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e( 'Created', 'routiz' ); ?></td>
                        <td><?php echo get_the_date(); ?></td>
                    </tr>
                    <?php if( $entry_type == 'booking' ): ?>
                        <tr>
                            <td><?php esc_html_e( 'Checkin', 'routiz' ); ?></td>
                            <td><?php echo date_i18n( get_option('date_format'), Rz()->get_meta('rz_checkin_date') ); ?></td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'Checkout', 'routiz' ); ?></td>
                            <td><?php echo date_i18n( get_option('date_format'), Rz()->get_meta('rz_checkout_date') ); ?></td>
                        </tr>
                    <?php endif; ?>
                    <?php if( $entry_type == 'booking_hourly' ): ?>
                        <tr>
                            <td><?php esc_html_e( 'Reservation start', 'routiz' ); ?></td>
                            <?php /* ?><td><?php echo Rz()->local_time_i18n( Rz()->get_meta('rz_checkin_date') ); ?></td><?php */ ?>
                            <td><?php echo date_i18n( get_option('date_format') . ' ' . get_option('time_format'), Rz()->get_meta('rz_checkin_date') ); ?></td>
                        </tr>
                    <?php endif; ?>
                    <?php if( $entry_type == 'booking_appointments' ): ?>
                        <tr>
                            <td><?php esc_html_e( 'Reservation start', 'routiz' ); ?></td>
                            <?php /* ?><td><?php echo Rz()->local_time_i18n( Rz()->get_meta('rz_checkin_date') ); ?></td><?php */ ?>
                            <td><?php echo Rz()->local_datetime_i18n( $checkin ); ?></td>
                        </tr>
                    <?php endif; ?>
                   <?php if( $entry_type == 'booking_hourly' ): ?>
                        <tr>
                            <td><?php esc_html_e( 'Reservation end', 'routiz' ); ?></td>
                            <td><?php echo date_i18n( get_option('date_format') . ' ' . get_option('time_format'), Rz()->get_meta('rz_checkout_date') ); ?></td>
                        </tr>
                    <?php endif; ?>
                    <?php if( $request->get('type') == 'sent' ): ?>
                        <?php $owner = get_userdata( $listing->post->post_author ); ?>
                        <?php if( isset( $owner->display_name ) ): ?>
                            <tr>
                                <td><?php esc_html_e( 'Sent to', 'routiz' ); ?></td>
                                <td><?php echo esc_html( $owner->display_name ); ?></td>
                            </tr>
                        <?php endif; ?>
                    <?php else: ?>
                      <?php if (isset($userdata->display_name)) : ?>
                        <tr>
                            <td><?php esc_html_e('Requested by', 'routiz'); ?></td>
                            <td>
                                <a href="<?php echo get_author_posts_url($userdata->ID); ?>">
                                    <?php echo esc_html($userdata->display_name); ?>
                                </a>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <?php endif; ?>
                    <?php if( $entry_type == 'purchase' ): ?>
                        <?php $pricing = Rz()->json_decode( Rz()->get_meta('rz_pricing') ); ?>
                        <?php if( $pricing ): ?>
                            <?php if( isset( $pricing->total ) ): ?>
                                <tr>
                                    <td><?php esc_html_e( 'Total', 'routiz' ); ?></td>
                                    <td><?php echo Rz()->format_price( $pricing->total ); ?></td>
                                </tr>
                            <?php endif; ?>
                            <?php if( isset( $pricing->processing ) ): ?>
                                <tr>
                                    <td><?php esc_html_e( 'Total payed', 'routiz' ); ?></td>
                                    <td><?php echo Rz()->format_price( $pricing->processing ); ?></td>
                                </tr>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if( $listing->id ): ?>
            <div class="rz--actions">
                <div class="rz--main-actions">
                    <ul>
                        <?php if( (int) $conversation_id > 0 ): ?>
                            <li>
                                <a href="#" class="rz--toggle-active" data-modal="conversation" data-params='{"id":<?php echo (int) $conversation_id; ?>}'>
                                    <i class="fas fa-comment"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if( $entry_type == 'booking' or $entry_type == 'booking_hourly' or $entry_type == 'booking_appointments' or $entry_type == 'purchase' ): ?>
                            <li>
                                <a href="#" data-modal="account-entry" data-params='{"id":<?php the_ID(); ?>}'>
                                    <i class="fas fa-pen"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if( $status == 'publish' and ( $review_submission == 'engaged' or $review_submission == 'everyone' ) and $request_user_id == get_current_user_id() ): ?>
                            <li>
                                <a href="#" data-modal="add-review" data-params='<?php echo (int) $listing->id; ?>'>
                                    <i class="fas fa-star"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
                <?php if( $entry_type == 'application' ): ?>
                    <div class="rz--more-actions">
                        <a href="#" class="rz-button" data-modal="account-entry" data-params='{"id":<?php the_ID(); ?>}'>
                            <i class="fas fa-eye rz-mr-1"></i>
                            <?php esc_html_e( 'View', 'routiz' ) ?>
                        </a>
                    </div>
                <?php endif; ?>
                <?php if( $entry_type == 'booking' or $entry_type == 'booking_hourly' or $entry_type == 'booking_appointments' ): ?>
                    <div class="rz--more-actions">
                        <?php if( $status == 'pending' ): ?>
                            <?php if( get_post_field('post_author') == $current_user_id ): ?>
                                <a href="#" class="rz-button" data-modal="account-entry" data-params='{"id":<?php the_ID(); ?>,"type":"approve"}'>
                                    <i class="fas fa-check rz-mr-1"></i>
                                    <?php esc_html_e( 'Approve', 'routiz' ) ?>
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if( $status == 'pending_payment' ): ?>
                            <?php if( $request_user_id == $current_user_id ): ?>
                                <a href="#" class="rz-button" data-modal="account-entry" data-params='{"id":<?php the_ID(); ?>,"type":"payment"}'>
                                    <i class="fas fa-hand-holding-usd rz-mr-1"></i>
                                    <?php esc_html_e( 'Pay now', 'routiz' ) ?>
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
