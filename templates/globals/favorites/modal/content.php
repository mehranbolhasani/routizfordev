<?php

defined('ABSPATH') || exit;

global $rz_explore;

?>

<div class="rz-modal-container rz-scrollbar">

    <?php if( $rz_explore->user->id and $rz_explore->user->favorites ): ?>
        <div class="rz-favorites-list">
            <ul>
                <?php global $rz_favorite_id, $rz_favotite_time; ?>
                <?php foreach( array_reverse( $rz_explore->user->favorites ) as $rz_favotite_time => $rz_favorite_id ): ?>
                    <?php Rz()->the_template('routiz/globals/favorites/modal/row'); ?>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <p class="rz--empty rz-text-center rz-weight-600 <?php if( $rz_explore->user->id and $rz_explore->user->favorites ) { echo 'rz-none'; } ?>">
        <?php esc_html_e( 'No favorites were found.', 'routiz' ); ?>
    </p>

</div>