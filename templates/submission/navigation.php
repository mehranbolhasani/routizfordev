<?php

defined('ABSPATH') || exit;

global $rz_submission;

?>

<div class="rz-submission-bar">
    <div class="rz--inner">
        <div class="rz--name">
            <?php Rz()->the_template('routiz/submission/wizard'); ?>
        </div>
        <div class="rz--actions">
            <!-- <div class="rz-submission-nav"> -->
                <a href="#" class="rz-link rz-action rz-disabled" data-action="submission-back"><?php esc_html_e( 'Go Back', 'routiz' ); ?></a>
                <a href="#" class="rz-button rz-button-accent rz-action" <?php echo is_user_logged_in() ? 'data-action="submission-continue"' : 'data-modal="signin"'; ?>>
                    <span class="rz--text"><?php esc_html_e( 'Continue', 'routiz' ); ?></span>
                    <span class="fas fa-arrow-right rz-ml-1"></span>
                    <?php Rz()->preloader(); ?>
                </a>
            <!-- </div> -->
        </div>
    </div>
</div>