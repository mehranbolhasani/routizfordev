<?php

defined('ABSPATH') || exit;

global $rz_explore;

?>

<ul class="rz-dynamic-infoboxes rz-none">
    <?php // if( $rz_explore->type ) : ?>
        <?php if( $rz_explore->query()->posts->have_posts() ) : ?>
            <?php while( $rz_explore->query()->posts->have_posts() ) : $rz_explore->query()->posts->the_post(); ?>
                <?php $location = get_post_meta( get_the_ID(), 'rz_location', false ); ?>
                <?php if( $location ): ?>
                    <?php Rz()->the_template('routiz/explore/map/infobox/infobox'); ?>
                <?php endif; ?>
            <?php endwhile; ?>
        <?php endif; ?>
    <?php // endif; ?>
</ul>
