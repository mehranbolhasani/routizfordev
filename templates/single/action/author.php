<?php

global $rz_listing;

$owner = new \Routiz\Inc\Src\User( $rz_listing->post->post_author );
$owner_userdata = get_userdata( $owner->id );
$owner_description = get_the_author_meta( 'description', $owner->id );

?>

<div class="rz-action-author">
    <div class="rz--avatar">
        <?php echo $owner->avatar(); ?>
    </div>
    <div class="rz--name">
        <h4><?php echo esc_html( $owner_userdata->display_name ); ?></h4>
    </div>
    <div class="rz--content">
        <?php if( $owner_description ): ?>
            <?php echo wpautop( wp_trim_words( $owner_description, 15 ) ); ?>
        <?php endif; ?>
        <div class="rz--view">
            <a href="<?php echo esc_url( get_author_posts_url( $owner->id ) ); ?>" class="rz-link">
                <?php esc_html_e( 'View user profile', 'routiz' ); ?>
                <i class="fas fa-arrow-right rz-ml-1"></i>
            </a>
        </div>
    </div>
</div>