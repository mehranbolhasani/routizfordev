<?php

defined('ABSPATH') || exit;

global $rz_listing, $rz_explore;

$has_favorite = $rz_listing->type->get( 'rz_display_listing_favorite' );

?>

<ul class="rz-listing-badges">

    <?php if( $has_favorite ): ?>
        <li>
            <a href="<?php echo esc_url( Rz()->non_logged_in_account_page() ); ?>" class="rz-listing-favorite <?php if( is_object( $rz_explore ) and $rz_explore->user->id and $rz_explore->user->is_favorite( $rz_listing->id ) ) { echo 'rz-active'; } ?>" <?php if( is_user_logged_in() ) { echo 'data-action="add-favorite"'; }else{ echo 'data-modal="signin"'; } ?> data-id="<?php echo $rz_listing->id; ?>">
                <i class="far fa-heart"></i>
            </a>
        </li>
    <?php endif; ?>
</ul>
