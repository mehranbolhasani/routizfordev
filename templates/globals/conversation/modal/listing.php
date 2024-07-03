<?php

defined('ABSPATH') || exit;

global $rz_conversation;

$image = $rz_conversation->listing->get_first_from_gallery();

?>

<div class="rz-modal-listing">
    <div class="rz--image">
        <?php if( $image ): ?>
            <span class="rz--img" style="background-image: url('<?php echo esc_url( $image ); ?>');"></span>
        <?php else: ?>
            <?php echo Rz()->dummy(); ?>
        <?php endif; ?>
    </div>
    <div class="rz--content">
        <h4 class="rz--title"><?php echo get_the_title( $rz_conversation->listing->id ); ?></h4>
    </div>
</div>