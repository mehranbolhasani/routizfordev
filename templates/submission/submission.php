<?php

defined('ABSPATH') || exit;

global $rz_submission;

?>

<?php if( $rz_submission->get_listing_types()->found_posts ): ?>
    <?php if( $rz_submission->is_missing_type() ): ?>
        <?php Rz()->the_template('routiz/submission/listing-type'); ?>
    <?php else: ?>
        <?php Rz()->the_template('routiz/submission/content'); ?>
    <?php endif; ?>
<?php else: ?>
    <div class="rz-submission-error rz-block">
        <div class="rz--error">
            <div class="rz--content">
                <?php esc_html_e( 'No listing types were found', 'routiz' ); ?>
            </div>
        </div>
    </div>
<?php endif; ?>
