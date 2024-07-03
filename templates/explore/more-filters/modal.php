<?php

defined('ABSPATH') || exit;

use \Routiz\Inc\Src\Request\Request;

global $rz_explore, $rz_search_form, $rz_search_form_more;

if( ! $rz_search_form ) {
    return;
}

$request = Request::instance();

?>

<div class="rz-modal rz-modal-more-filters" data-id="more-filters">
    <a href="#" class="rz-close">
        <i class="fas fa-times"></i>
    </a>
    <div class="rz-modal-heading rz--border">
        <h4 class="rz--title"><?php esc_html_e( 'More Filters', 'routiz' ); ?></h4>
    </div>
    <div class="rz-modal-content">
        <div class="rz-modal-append">
            <div class="rz-modal-container rz-scrollbar">

                <?php if( current_user_can('manage_options') and ! get_option('rz_hide_edit_search_form') ): ?>
                    <div class="rz-mb-2">
                        <a href="<?php echo esc_url( get_edit_post_link( $rz_search_form_more ) ); ?>" class="rz-no-decoration rz-text-right" target="_blank">
                            <?php esc_html_e( 'Edit search form', 'routiz' ); ?>
                            <i class="fas fa-external-link-alt rz-ml-1"></i>
                        </a>
                    </div>
                <?php endif; ?>

                <div class="rz-search-filter" data-form-id="<?php echo (int) $rz_search_form_more; ?>">
                    <div class="rz-grid">

                        <?php

                            $filters = Rz()->json_decode( Rz()->get_meta( 'rz_search_fields', $rz_search_form_more ) );
                            if( ! empty( $filters ) ) {
                                $rz_explore->component->tabs( $filters );
                            }

                        ?>

                    </div>
                </div>

            </div>

            <div class="rz-modal-footer rz--top-border rz-text-center">
                <a href="#" class="rz-button rz-button-accent rz-modal-button rz-action-filter">
                    <span><?php esc_html_e( 'Search', 'routiz' ); ?></span>
                    <?php Rz()->preloader(); ?>
                </a>
            </div>

        </div>
        <?php Rz()->preloader(); ?>
    </div>
</div>
