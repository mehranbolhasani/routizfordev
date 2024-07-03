<?php

global $rz_listing, $rz_nearby_post_ids;
$rz_listing = new \Routiz\Inc\Src\Listing\Listing( get_the_ID() );

if( ! $rz_listing->type ) {
    return;
}

if( $rz_listing->type->get('rz_enable_similar') ):

    $similar = new Routiz\Inc\Src\Listing\Similar( $rz_listing->id );
    $listings = $similar->query();

    ?>

    <div class="rz-mod-listing rz-similar">
        <div class="rz-mod-content">
            <h4><?php esc_html_e( 'Similar', 'routiz' ); ?></h4>
            <?php if( $listings->have_posts() ): ?>
                <div class="rz-listings-outer">
                    <ul class="rz-listings" data-cols="3">
                        <?php while ( $listings->have_posts() ): $listings->the_post(); ?>
                            <li class="rz-listing-item <?php Rz()->listing_class(); ?>">
                                <?php Rz()->the_template('routiz/explore/listing/listing'); ?>
                            </li>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </ul>
                </div>
            <?php else: ?>
                <p><?php esc_html_e( 'No similar items were found', 'routiz' ); ?></p>
            <?php endif; ?>
        </div>
    </div>

<?php endif; ?>
