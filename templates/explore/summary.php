<?php

defined('ABSPATH') || exit;

global $rz_explore;

$from = ( $rz_explore->query()->page - 1 ) * $rz_explore->query()->posts_per_page + 1;
$to = $from + $rz_explore->query()->posts_per_page - 1;

?>

<div class="rz-summary rz-text-center">
    <p>
        <?php echo sprintf(
            esc_html__( '%s - %s of %s listings. ', 'routiz' ),
            $from,
            $to > $rz_explore->query()->posts->found_posts ? $rz_explore->query()->posts->found_posts : $to,
            $rz_explore->query()->posts->found_posts
        ); ?>
    </p>
</div>
