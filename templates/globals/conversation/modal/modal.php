<?php

defined('ABSPATH') || exit;

?>

<div class="rz-modal rz-modal-conversation" data-id="conversation">
    <a href="#" class="rz-close">
        <i class="fas fa-times"></i>
    </a>
    <div class="rz-modal-heading">
        <h4 class="rz--title"><?php esc_html_e( 'Send Message', 'routiz' ); ?></h4>
    </div>
    <div class="rz-modal-content">
        <div class="rz-modal-append"></div>
        <?php Rz()->preloader(); ?>
    </div>
</div>
