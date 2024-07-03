<?php

defined('ABSPATH') || exit;

global $rz_explore;

?>

<div class="rz-explore" id="rz-explore">
    <div class="rz-explore-sidebar">
        <?php if( substr( $rz_explore->get_display_type(), 0, 3 ) == 'map' ): ?>
            <?php Rz()->the_template('routiz/explore/map/map'); ?>
        <?php endif; ?>
        <?php Rz()->the_template('routiz/explore/filters'); ?>
    </div>
    <div class="rz-explore-content">
        <?php Rz()->the_template('routiz/explore/listings'); ?>
    </div>
</div>
