<?php global $rz_package; ?>

<label class="rz-package">
    <div class="rz--radio">
        <input type="radio" name="package_id" value="<?php echo (int) $rz_package->get_id(); ?>">
        <span></span>
    </div>
    <div class="rz--image">
        <?php if( $rz_package->get_image_id() ): ?>
            <?php echo wp_kses_post( $rz_package->get_image('thumbnail') ); ?>
        <?php else: ?>
            <?php echo Rz()->dummy('fas fa-toolbox', 100 ); ?>
        <?php endif; ?>
    </div>
    <div class="rz--content">
        <h4 class="rz--title"><?php echo $rz_package->get_name() ?></h4>
        <?php $duration = $rz_package->get_meta( '_rz_promotion_duration' ); ?>
        <?php $price = sprintf( get_woocommerce_price_format(), get_woocommerce_currency_symbol(), $rz_package->get_price() ); ?>
        <p><?php echo sprintf( _n( '%s, promotion lasts for %s day', '%s, promotion lasts for %s days', $duration, 'routiz' ), $price, $duration ); ?></p>
    </div>
    <div class="rz--action">
        <span class="rz-button"><?php esc_html_e( 'Select', 'routiz' ); ?></span>
    </div>
</label>
