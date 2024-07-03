<?php

defined('ABSPATH') || exit;

global $rz_listing;

?>

<?php if( $rz_listing->reviews->count ): ?>
    <div class="rz-reviews-comments">

        <?php Rz()->the_template('routiz/single/reviews/comments/list'); ?>

        <?php if( $rz_listing->reviews->count > (int) $rz_listing->type->get('rz_reviews_per_page') ): ?>
            <div class="rz-text-center">
                <a href="#" class="rz-button rz-mt-5" data-action="load-more-reviews">
                    <span><?php esc_html_e( 'Load More', 'routiz' ); ?></span>
                    <?php Rz()->preloader(); ?>
                </a>
            </div>
        <?php endif; ?>

    </div>
<?php endif; ?>
