<?php

use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Listing\Listing;

$request = Request::instance();
$listing = new Listing( $request->get('listing_id') );
$promotions = $listing->type->get_wc_promotions();

?>

<div class="rz-modal-container rz-scrollbar">
    <div class="rz-packages rz-no-select">
        <?php if( $listing->id and $listing->type ): ?>
            <?php $promotions = $listing->type->get_wc_promotions(); ?>
            <input type="hidden" id="promote-listing-id" value="<?php echo $listing->id; ?>">
            <?php wp_nonce_field( 'routiz_promote_listing_nonce', 'routiz_promote_listing' ); ?>
            <?php if( $promotions ): ?>
                <?php global $rz_package; ?>
                <?php foreach( $promotions as $rz_package ): ?>
                    <?php Rz()->the_template('routiz/account/listings/promotion/modal/row') ?>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="rz--empty rz-text-center rz-weight-600"><?php esc_html_e( 'No packages were found.', 'routiz' ); ?></p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<?php if( $promotions ): ?>
    <div class="rz-modal-footer rz--top-border rz-text-center">
        <a href="#" class="rz-button rz-button-accent rz-modal-button" data-action="promote-listing">
            <span><?php esc_html_e( 'Continue', 'routiz' ); ?></span>
            <span class="fas fa-arrow-right rz-ml-1"></span>
            <?php Rz()->preloader(); ?>
        </a>
    </div>
<?php endif; ?>