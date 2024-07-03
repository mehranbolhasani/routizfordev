<?php

use \Routiz\Inc\Src\Listing\Listing;
use \Routiz\Inc\Src\Listing\Views;

defined('ABSPATH') || exit;

global $wpdb;

$listing = new Listing();
$views = new Views( $listing->id );
$status = $listing->get_status();
$image = $listing->get_first_from_gallery( 'thumbnail' );
$promotions_enabled = $listing->type->get('rz_enable_promotions');
$promoted_expiration = (int) Rz()->get_meta( 'rz_promotion_expires' );
$promotion_packages = $listing->type->get_wc_promotions();
$expires = Rz()->get_meta('rz_listing_expires');
// $rating_average = $listing->get('rz_review_rating_average');
$comments_num = (int) $wpdb->get_var( $wpdb->prepare("
	SELECT COUNT( comment_ID )
	FROM {$wpdb->comments}
	WHERE comment_post_ID = %d
	AND comment_type = 'rz-review'
	AND comment_parent = 0
", $listing->id ) );

$action = $listing->get_action();

?>

<div class="rz--cell">
    <div class="rz-box">
        <div class="rz--heading">
            <div class="rz--image">
                <a href="<?php the_permalink(); ?>">
                    <?php if( $image ): ?>
                        <?php echo '<img src="' . esc_url( $image ) . '" alt="">'; ?>
                    <?php else: ?>
                        <?php echo Rz()->dummy( 'fas fa-map-marker-alt', 100 ); ?>
                    <?php endif; ?>
                </a>
            </div>
            <div class="rz--title">
                <h4>
                    <a href="<?php the_permalink(); ?>">
                        <?php echo wp_trim_words( get_the_title(), 8 ); ?>
                    </a>
                </h4>
            </div>
        </div>
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
                        <td><?php echo esc_html( $listing->type->get('rz_name') ); ?></td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e( 'Created', 'routiz' ); ?></td>
                        <td><?php echo get_the_date(); ?></td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e( 'Expires', 'routiz' ); ?></td>
                        <td><?php echo $expires ? date_i18n( get_option( 'date_format' ), strtotime( $expires ) ) : '-'; ?></td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e( 'Rated', 'routiz' ); ?></td>
                        <?php if( $listing->reviews->average and $comments_num ): ?>
                            <td><?php echo sprintf( esc_html__( '%s out of %s reviews', 'routiz' ), $listing->reviews->average, $comments_num ); ?></td>
                        <?php else: ?>
                            <td>-</td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <td><?php esc_html_e( 'Today views', 'routiz' ); ?></td>
                        <td><?php echo $views->get_today_views(); ?></td>
                    </tr>
                    <?php if( $promoted_expiration ): ?>
                        <tr>
                            <td><?php esc_html_e( 'Promotion expires', 'routiz' ); ?></td>
                            <td><?php echo date( get_option('date_format'), $promoted_expiration ); ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="rz--actions">
            <div class="rz--main-actions">
                <ul>
                    <li>
                        <a href="#" data-modal="listing-edit" data-params="<?php echo (int) $listing->id; ?>">
							<i class="fas fa-pen"></i>
						</a>
                    </li>
					<?php if( $action->has('booking') or $action->has('booking_hourly') ): ?>
						<li>
	                        <a href="#" data-modal="listing-edit-booking-calendar" data-params="<?php echo (int) $listing->id; ?>">
								<i class="fas fa-calendar-alt"></i>
							</a>
	                    </li>
					<?php endif; ?>
					<?php if( $action->has('booking') ): ?>
						<?php if( $listing->type->get('rz_enable_ical') ): ?>
							<li>
		                        <a href="#" data-modal="listing-edit-booking-ical" data-params='{"id": <?php echo (int) $listing->id; ?>}'>
									<i class="fas fa-calendar-check"></i>
								</a>
		                    </li>
						<?php endif; ?>
					<?php endif; ?>
                    <?php
                      $listing_action_url = add_query_arg([
                        'action' => 'listing_status_change',
                        'status' => $status,
                        'id' => get_the_ID()
                    ]);
                    
                    $listing_action_url = wp_nonce_url( $listing_action_url, 'routiz_account_listing_action' );
                   
                    ?>
                    <?php if($status == "publish"): ?>
                    <li>
                        <a href="<?php echo esc_url( $listing_action_url ); ?>" data-action="account-listing-delete" data-confirmation="<?php esc_html_e('Are you sure do you want to pending the status?', 'routiz'); ?>">
                        <?php echo ($status == "pending_listing") ? "<i class='fa fa-check'></i>" : "<i class='fa fa-times'></i>"; ?>
                       
                        </a>
                    </li>
                    <?php elseif($status == "pending_listing") : ?>
                        <li>
                        <a href="<?php echo esc_url( $listing_action_url ); ?>" data-action="account-listing-delete" data-confirmation="<?php esc_html_e('Are you sure do you want to publish this item?', 'routiz'); ?>">
                        <?php echo ($status == "publish") ? "<i class='fa fa-times'></i>" : "<i class='fa fa-check'></i>"; ?>
                       
                        </a>
                    </li>
                     <?php endif; ?>

                    <?php
                        $action_url = add_query_arg([
                            'action' => 'delete_listing',
                            'id' => get_the_ID()
                        ]);
                        $action_url = wp_nonce_url( $action_url, 'routiz_account_listing_action' );
                    ?>
                    <li>
                        <a href="<?php echo esc_url( $action_url ); ?>" data-action="account-listing-delete" data-confirmation="<?php esc_html_e('Are you sure you want to delete this item?', 'routiz'); ?>">
							<i class="fas fa-trash-alt"></i>
						</a>
                    </li>
                </ul>
            </div>
            <div class="rz--more-actions">
                <?php if( $promotions_enabled ): ?>
                    <?php if( $promotion_packages and $listing->post->post_status == 'publish' ): ?>
                        <?php if( $promoted_expiration ): ?>
                            <a href="#" class="rz-button rz-disabled">
                                <i class="fas fa-fire-alt rz-mr-1"></i>
                                <?php esc_html_e( 'Promoted', 'routiz' ) ?>
                            </a>
                        <?php else: ?>
                            <a href="#" class="rz-button" data-modal="promote" data-params="<?php the_ID(); ?>">
                                <i class="fas fa-fire-alt rz-mr-1"></i>
                                <?php esc_html_e( 'Promote', 'routiz' ) ?>
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
