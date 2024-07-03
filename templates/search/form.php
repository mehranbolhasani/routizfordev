<?php

defined('ABSPATH') || exit;

global $rz_explore, $rz_atts, $rz_search_form;

if( ! $rz_explore ) {
    $rz_explore = \Routiz\Inc\Src\Explore\Explore::instance();
}

extract( shortcode_atts([
    'id' => 0
], $rz_atts ) );

$rz_search_form = $id;

$listing_type = Rz()->get_meta( 'rz_listing_type', $rz_search_form );

?>

<div class="rz-search-form" data-form-id="<?php echo (int) $rz_search_form ?>">
    <form action="<?php echo Rz()->get_explore_page_url(); ?>" method="get" autocomplete="nope">

        <?php if( $listing_type ): ?>
            <input type="hidden" name="type" value="<?php echo esc_html( $listing_type ); ?>">
        <?php endif; ?>

        <div class="rz--form">
            <div class="rz-search-mods">
                <?php

                    $filters = Rz()->jsoning( 'rz_search_fields', $id );
                    if( ! empty( $filters ) ) {
                        $rz_explore->component->tabs( $filters );
                    }

                ?>
            </div>

            <div class="rz-search-submit">
                <button type="submit" class="rz-button rz-button-accent">
                    <span class="rz--icon"><i class="fas fa-search"></i></span>
                    <?php Rz()->preloader(); ?>
                </button>
            </div>

        </div>

    </form>
</div>
