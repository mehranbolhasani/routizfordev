<?php

use \Routiz\Inc\Src\Listing\Component;

defined('ABSPATH') || exit;

global $rz_listing;

$modules = Component::instance();
$module_items = Rz()->jsoning( 'rz_display_single_content', $rz_listing->type->id );

?>

<div class="rz-single-heading">
    <?php if( $rz_listing->type->get('rz_enable_review_ratings') and $rz_listing->reviews->count ): ?>
        <div class="rz-single-heading-rating">
            <div class="rz--rating rz--bg">
                <span class="rz-reviews-stat-num">
                    <i class="fas fa-star"></i>
                    <strong><?php echo floatval( $rz_listing->reviews->average ); ?></strong>
                </span>
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
            echo '<div class="score-description">' . $description . '</div>';
        }
        ?>
            </div>
        </div>
    <?php endif; ?> 
    <div class="rz-single-heading-title">
        <h1 class="rz-title"><?php the_title(); ?></h1>
    </div>
</div>

<div class="rz-single-content">
    <?php

        foreach( $module_items as $module_item ):
            $modules->render( $module_item );
        endforeach;

    ?>
</div>
