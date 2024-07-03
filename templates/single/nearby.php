<?php

defined('ABSPATH') || exit;

global $rz_listing, $rz_nearby_post_ids;
$rz_listing = new \Routiz\Inc\Src\Listing\Listing( get_the_ID() );

if( $rz_listing->type->get('rz_enable_nearby') ):

    $nearby = new Routiz\Inc\Src\Listing\Nearby( $rz_listing->id );
    $listings = $nearby->query();
    $rz_nearby_post_ids = $nearby->post_ids;
    $measure_system = get_option('rz_measure_system');

    ?>

    <div class="rz-mod-listing rz-nearby">
        <div class="rz-mod-content">
            <h4><?php esc_html_e( 'Nearby Listings', 'routiz' ); ?></h4>
            <?php if( $listings->have_posts() ): ?>
                <div class="rz-listings-outer">
                    <ul class="rz-listings" data-cols="3">
                        <?php while ( $listings->have_posts() ): $listings->the_post(); ?>
                            <li class="rz-listing-item <?php Rz()->listing_class(); ?>">
                                <?php Rz()->the_template('routiz/explore/listing/listing'); ?>
                                <?php if( isset( $nearby->results_keyed[ get_the_ID() ] ) ): ?>
                                    <div class="rz-distance-outer">
                                        <span class="rz-distance">
                                            <?php
                                                printf(
                                                    esc_html__( '%s %s away', 'routiz' ),
                                                    number_format( $nearby->results_keyed[ get_the_ID() ]->distance, 2 ),
                                                    $measure_system == 'metric' ? 'km' : 'mi'
                                                );
                                            ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </li>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </ul>
                </div>
            <?php else: ?>
                <p><?php esc_html_e( 'No nearby listings were found', 'routiz' ); ?></p>
            <?php endif; ?>
        </div>
    </div>

<?php endif; ?>
