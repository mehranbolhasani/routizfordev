<?php

use \Routiz\Inc\Src\User;

defined('ABSPATH') || exit;

global $rz_listing;

if( ! $rz_listing->post ) {
    return;
}

$user = new User( $rz_listing->post->post_author );

if( ! $user->id ) {
    return;
}

$userdata = get_userdata( $user->id );

?>

<a href="<?php the_permalink(); ?>" class="rz-listing-author">
    <div class="rz--image">
        <?php $user->the_avatar(); ?>
    </div>
    <div class="rz--name">
        <?php echo esc_html( $userdata->display_name ); ?>
    </div>
</a>
