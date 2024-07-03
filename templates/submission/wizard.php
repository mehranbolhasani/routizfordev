<?php

defined('ABSPATH') || exit;

global $rz_submission;

?>

<div class="rz-wizard">
    <ul class="rz-font-heading">

        <?php if( $rz_submission->listing_type->has_plans() ): ?>
            <li><?php esc_html_e( 'Select Plan', 'routiz' ); ?></li>
        <?php endif; ?>

        <?php foreach( $rz_submission->tabs as $tab ): ?>
            <li><?php echo esc_attr( $tab['title'] ); ?></li>
        <?php endforeach; ?>

        <li><?php esc_html_e( 'Finish', 'routiz' ); ?></li>
        <li><?php esc_html_e( 'Publish', 'routiz' ); ?></li>

    </ul>
</div>
