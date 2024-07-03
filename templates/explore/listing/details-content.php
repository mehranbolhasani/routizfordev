<?php

defined('ABSPATH') || exit;

global $rz_listing;

?>

<?php $details = $rz_listing->get_details('rz_display_listing_content'); ?>
<?php if( $details ): ?>
    <div class="rz-listing-details rz-listing-details-content">
        <ul>
            <?php foreach( $details as $detail ): ?>
                <li>
                    <span>
                        <?php if( $detail->icon ): ?>
                            <i class="<?php echo esc_html( $detail->icon ); ?>"></i>
                        <?php endif; ?>
                        <?php echo wp_kses( $detail->content, Rz()->allowed_html() ); ?>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
