<?php

defined('ABSPATH') || exit;

global $rz_listing;

?>

<?php if( $rz_listing->is_owner() ): ?>
    <?php $promoted_expiration = (int) Rz()->get_meta( 'rz_promotion_expires' ); ?>
    <?php if( $promoted_expiration and $promoted_expiration > time() ): ?>
        <div class="rz--listing-promoted">
            <i class="fas fa-fire-alt rz-mr-1"></i>
            <span>
                <?php
                    echo sprintf(
                        __( 'Promoted, expires: %s', 'routiz' ),
                        date_i18n(
                            get_option('date_format'),
                            $promoted_expiration
                        )
                    );
                ?>
            </span>
        </div>
    <?php endif; ?>
<?php endif; ?>