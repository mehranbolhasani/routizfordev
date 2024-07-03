<?php

defined('ABSPATH') || exit;

global $rz_listing;
$gallery = $rz_listing->get_gallery( apply_filters('routiz/explore/listing/gallery/size', 'rz_listing') );
$cover_type = $rz_listing->type->get('rz_display_listing_cover');

?>

<div class="rz-listing-image">
    <?php if( isset( $gallery[0] ) ): ?>
        <a href="<?php echo get_permalink(); ?>" class="rz-image" style="background-image:url('<?php echo esc_url( $gallery[0] ); ?>');"<?php if( $rz_listing->type->get('rz_open_listing_new_tab') ) { echo ' target="_blank"'; } ?>></a>
        <?php if( count( $gallery ) > 1 and $cover_type == 'slider' ): ?>
            <div class="rz-listing-gallery">
                <?php foreach( $gallery as $key => $image ): ?>
                    <?php
                        $style = '';
                        if( $key == 0 ) {
                            $style = 'opacity:1;background-image:url(\'' . esc_url( $image ) . '\');';
                        }
                    ?>
                    <a href="<?php echo get_permalink(); ?>" class="rz-listing-gallery-item" style="<?php echo $style; ?>" <?php if( $key > 0 ) { echo sprintf( 'data-image="%s"', esc_url( $image ) ); } ?><?php if( $rz_listing->type->get('rz_open_listing_new_tab') ) { echo ' target="_blank"'; } ?>></a>
                <?php endforeach; ?>
            </div>
            <a href="#" class="rz-slider-nav rz-nav-prev"><span><i class="fas fa-chevron-left"></i></span></a>
            <a href="#" class="rz-slider-nav rz-nav-next"><span><i class="fas fa-chevron-right"></i></span></a>
        <?php endif; ?>
    <?php else: ?>
        <a href="<?php echo get_permalink(); ?>"<?php if( $rz_listing->type->get('rz_open_listing_new_tab') ) { echo ' target="_blank"'; } ?>>
            <?php echo Rz()->dummy(); ?>
        </a>
    <?php endif; ?>
</div>
