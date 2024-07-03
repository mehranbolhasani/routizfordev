<?php

defined('ABSPATH') || exit;

global $rz_listing;

$review_submission = $rz_listing->type->get('rz_review_submission');
if( empty( $review_submission ) ) {
    $review_submission = 'everyone';
}

?>

<div class="rz-reviews-stats-summary">
    <div class="rz-reviews-stats-text">
            <?php echo sprintf( _n( '<strong>%s review</strong>', '<strong>%s reviews</strong>', $rz_listing->reviews->count, 'routiz' ), $rz_listing->reviews->count ); ?>
            <?php if( $rz_listing->reviews->average ): ?>
                <span class="rz-reviews-stat-num">
                    <i class="fas fa-star"></i>
                    <strong><?php echo number_format( floatval( $rz_listing->reviews->average ), 2 ); ?></strong>
                </span>
            <?php endif; ?>
    </div>
    <?php if( $review_submission == 'everyone' ): ?>
        <div class="">
            <a href="#" class="rz-button rz-button-accent" data-modal="<?php echo is_user_logged_in() ? 'add-review' : 'signin'; ?>" data-params="<?php echo get_the_ID(); ?>">
                <span><?php esc_html_e( 'Submit a Review', 'routiz' ); ?></span>
            </a>
        </div>
    <?php endif; ?>
</div>

<?php if( $rz_listing->type->get('rz_enable_review_ratings') and $rz_listing->reviews->count ): ?>

    <div class="rz-reviews-stats">
        <div class="rz-grid">
            <?php $ratings = Rz()->jsoning( 'rz_review_ratings', $rz_listing->type->id ); ?>
            <?php foreach( $ratings as $key => $rating ): ?>
                <?php
                    $rating_average = round( floatval( Rz()->get_meta( sprintf( 'rz_review_rating_average_%s', $rating->fields->key ) ) ), 2 );
                ?>
                <div class="rz-col-6 rz-col-sm-12">
                    <div class="rz-reviews-stat">
                        <div class="rz-grid">
                            <div class="rz-col-6 rz-col-rz-6">
                                <?php echo esc_attr( $rating->fields->name ); ?>
                            </div>
                            <div class="rz-col-6 rz-col-rz-6">
                                <div class="rz-reviews-stat-bar">
                                    <div class="rz-reviews-progress rz-flex rz-flex-column rz-justify-center">
                                        <div class="rz-progress">
                                            <div class="rz-progress-bar"
                                                role="progressbar"
                                                aria-valuenow="<?php echo round( 20 * $rating_average ); ?>"
                                                aria-valuemin="0"
                                                aria-valuemax="100"
                                                style="width:<?php echo round( 20 * $rating_average ); ?>%">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="rz-reviews-num">
                                        <?php if( $rating_average ): ?>
                                            <strong><?php echo number_format( $rating_average, 1 ); ?></strong>
                                        <?php else: ?>
                                            &nbsp;
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
