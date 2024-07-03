<?php

defined('ABSPATH') || exit;

global $rz_explore;

?>

<div class="rz-dynamic rz-dynamic-listings">

    <div class="rz-explore-listings">

        <?php if( $rz_explore->total_types ): ?>

            <?php if( $rz_explore->query()->posts->have_posts() ): ?>

                <ul class="rz-listings" data-cols="2">
                    <?php while( $rz_explore->query()->posts->have_posts() ): $rz_explore->query()->posts->the_post(); ?>
                        <li class="rz-listing-item <?php Rz()->listing_class(); ?>">
                            <?php Rz()->the_template('routiz/explore/listing/listing'); ?>
                        </li>
                        <?php Rz()->the_template('routiz/explore/listing/banner'); ?>
                    <?php endwhile; wp_reset_postdata(); ?>
                </ul>

                <?php Rz()->the_template('routiz/explore/paging'); ?>

            <?php else: ?>

                <h5><?php esc_html_e( 'No results were found', 'routiz' ); ?></h5>

            <?php endif; ?>

        <?php else: ?>

            <h5><?php esc_html_e( 'No listing types were found', 'routiz' ); ?></h5>

        <?php endif; ?>

    </div>

</div>
