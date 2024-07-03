<?php

defined('ABSPATH') || exit;

global $rz_submission;

$listing_types = get_posts([
    'post_type' => 'rz_listing_type',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'meta_query' => [
        [
            'key' => 'rz_disable_user_submission',
            'value' => '',
            'compare' => '='
        ],
    ]
]);

?>

<div class="rz-submission-heading">
    <h3 class="rz--title">
        <?php esc_html_e( 'Select Submission Type', 'routiz' ); ?>
    </h3>
</div>

<form method="get" name="rz-submit-type" action="<?php echo Rz()->is_submission(); ?>">

    <div class="rz-submission-types rz-no-select">
        <?php foreach( $listing_types as $type ): ?>
            <label class="rz--type">
                <span class="rz--radio">
                    <input type="radio" name="type" value="<?php echo esc_html( Rz()->get_meta( 'rz_slug', $type->ID ) ); ?>">
                    <span></span>
                </span>
                <span class="rz--image">
                    <?php if( has_post_thumbnail( $type->ID ) ): ?>
                        <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $type->ID ) ); ?>
                        <span class="rz--img" style="background-image: url(<?php echo esc_url( $image[0] ); ?>);"></span>
                    <?php else: ?>
                        <?php $icon = Rz()->get_meta('rz_icon', $type->ID); ?>
                        <?php echo Rz()->dummy( $icon ? $icon : 'fas fa-toolbox', 100 ); ?>
                    <?php endif; ?>
                </span>
                <span class="rz--content">
                    <span class="rz--title"><?php echo Rz()->get_meta( 'rz_name', $type->ID ); ?></span>
                    <?php if( get_option('rz_enable_submission_listing_counter') ): ?>
                        <span>
                            <?php
                                $total_type_listings = new \WP_Query([
                                    'post_type' => 'rz_listing',
                                    'post_status' => 'publish',
                                    'meta_query' => [
                                        [
                                            'key' => 'rz_listing_type',
                                            'value' => $type->ID,
                                            'compare' => '=',
                                        ]
                                    ]
                                ]);
                            ?>
                            <?php echo sprintf( esc_html__( '%s listings', 'routiz' ), number_format( $total_type_listings->post_count, 0, '.', ',' ) ); ?>
                        </span>
                    <?php endif; ?>
                </span>
                <span class="rz--action">
                    <span class="rz-button">
                        <span><?php esc_html_e( 'Select', 'routiz' ); ?></span>
                        <?php Rz()->preloader(); ?>
                    </span>
                </span>
            </label>
        <?php endforeach; ?>
    </div>

    <div class="rz-submission-error">
        <div class="rz--error">
            <div class="rz--content">
                <i class="fas fa-frown-open rz-mr-1"></i>
                <?php esc_html_e( 'Please select a listing type', 'routiz' ); ?>
            </div>
        </div>
    </div>

    <!-- <div class="rz-submission-footer">
        <a href="#" class="rz-button rz-button-accent rz-large" <?php echo is_user_logged_in() ? 'data-action="submission-submit-type"' : 'data-modal="signin"'; ?>>
            <span><?php esc_html_e( 'Continue', 'routiz' ); ?></span>
            <span class="fas fa-arrow-right rz-ml-1"></span>
        </a>
    </div> -->

</form>
