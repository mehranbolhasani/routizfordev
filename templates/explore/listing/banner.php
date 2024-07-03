<?php

defined('ABSPATH') || exit;

global $rz_listing;
$rz_listing = new \Routiz\Inc\Src\Listing\Listing();

?>

<?php if( $rz_listing->type->get('rz_enable_explore_banner') ): ?>
    <?php global $rz_explore; ?>
    <?php if( $rz_listing->type->get('rz_explore_banner_inject_num') == $rz_explore->query()->posts->current_post + 1 ): ?>
        <li class="rz-listing-space">
            <div class="rz-space rz--space-listings">
                <?php echo do_shortcode( $rz_listing->type->get('rz_explore_banner') ); ?>
            </div>
        </li>
    <?php endif; ?>
<?php endif; ?>
