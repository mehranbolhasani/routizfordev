<?php

defined('ABSPATH') || exit;

global $rz_listing;

$verified = get_user_meta( $rz_listing->post->post_author, 'rz_verified', true );

?>

<div class="rz--heading">
    <div class="rz--main">
        <div class="rz-title">
            <h4>
                <?php if( $verified ): ?>
                    <i class="rz--verified fas fa-check-circle"></i>
                <?php endif; ?>
                <?php echo do_shortcode( $rz_listing->get( $rz_listing->type->get('rz_display_listing_title') ) ); ?>
            </h4>
        </div>
        <?php if( $tagline_field = $rz_listing->type->get('rz_display_listing_tagline') ): ?>
            <?php if( $tagline = $rz_listing->get( $tagline_field ) ): ?>
                <div class="rz-listing-tagline">
                    <?php echo do_shortcode( wp_trim_words( $tagline, 12 ) ); ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <?php if( $rz_listing->type->get('rz_display_listing_review') and $rz_listing->reviews->average ): ?>
        <div class="rz--review">
            <div class="rz-listing-review">
                <i class="fas fa-star"></i>
                <span><?php echo number_format( $rz_listing->reviews->average, 2 ); ?></span>
            </div>
            <?php
    $rating = floatval( $rz_listing->reviews->average );
    $description = '';

    if ($rating >= 0 && $rating < 1) {
        $description = 'Very Bad';
    } elseif ($rating >= 1 && $rating < 2) {
        $description = 'Bad';
    } elseif ($rating >= 2 && $rating < 3) {
        $description = 'Fair';
    } elseif ($rating >= 3 && $rating < 4) {
        $description = 'Good';
    } elseif ($rating >= 4 && $rating < 5) {
        $description = 'Very Good';
    } elseif ($rating == 5) {
        $description = 'Excellent';
    }

    if (!empty($description)) {
        echo '<div class="score-description" style="color: #d80566; font-weight: bold">' . $description . '</div>';
    }
    ?>
        </div>
    <?php endif; ?>
</div>
