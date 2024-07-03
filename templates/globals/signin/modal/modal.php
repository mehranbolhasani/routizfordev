<div class="rz-modal rz-modal-signin" data-id="signin" data-signup="<?php echo get_option('rz_enable_standard_pass') ? 'pass' : 'email'; ?>">
    <a href="#" class="rz-close">
        <i class="fas fa-times"></i>
    </a>
    <div class="rz-modal-heading rz--border">
        <h4 class="rz--title"><?php esc_html_e( 'Sign in', 'routiz' ); ?></h4>
    </div>
    <div class="rz-modal-content">
        <div class="rz-modal-append">
            <div class="rz-modal-container">
                <?php Rz()->the_template('routiz/globals/signin/form'); ?>
            </div>
            <div class="rz-modal-footer rz--top-border rz-text-center">
                <a href="#" class="rz-button rz-width-100 rz-button-accent rz-large rz-modal-button" data-action="">
                    <span><?php esc_html_e( 'Sign in', 'routiz' ); ?></span>
                    <?php Rz()->preloader(); ?>
                </a>
            </div>
        </div>
        <?php Rz()->preloader(); ?>
    </div>
</div>
