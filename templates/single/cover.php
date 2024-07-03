<?php

defined('ABSPATH') || exit;

global $rz_listing;

$gallery = $rz_listing->get_gallery([
    'rz_gallery_large',
    'rz_gallery_preview'
]);

$cover_type = $rz_listing->type->get('rz_single_cover_type');

?>

<?php if( $cover_type == 'gallery' ): ?>
    <?php if( $gallery ): ?>
        <div class="rz-gallery-rail">
            <div class="rz-gallery">
                <?php foreach( $gallery as $key => $image ): ?>
                    <img src="<?php echo esc_url( $image['rz_gallery_large'] ); ?>" alt="">
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>
