<?php

use \Routiz\Inc\Src\Request\Request;

defined('ABSPATH') || exit;

global $rz_listing;

$request = Request::instance();

$page = max( 1, $request->get('onpage') );
$comments_per_page = $rz_listing->type->get('rz_reviews_per_page');

$comment_query = new \WP_Comment_Query;
$comments = $comment_query->query([
    'post_id' => $rz_listing->id,
    'status' => 'approve',
    'parent' => 0,
    'hierarchical' => false,
    'orderby' => 'comment_date',
    'order' => 'DESC',
    'number' => $comments_per_page,
    'offset' => ( $page - 1 ) * $comments_per_page,
    'paged' => $page
]);

if( ! $comments ) {
    return;
}

?>

<ul class="rz-comments">
    <?php foreach( $comments as $comment ): ?>
        <?php

            $user = new \Routiz\Inc\Src\User( $comment->user_id );
            $userdata = $user->get_userdata();
            $is_content_large = str_word_count( $comment->comment_content ) > 40;
            $average = get_comment_meta( $comment->comment_ID, 'rz_rating_average', true );
            $media = Rz()->json_decode( get_comment_meta( $comment->comment_ID, 'rz_gallery', true ) );

        ?>
        <li data-id="<?php echo (int) $comment->comment_ID; ?>">
            <div class="rz-comment-content">
                <div class="rz-comment-heading">
                    <div class="rz-comment-image">
                        <?php echo $user->avatar(); ?>
                    </div>
                    <div class="rz-comment-meta rz-flex rz-flex-column rz-justify-center">

                        <div class="rz-grid rz-justify-space">
                            <div class="rz-col-auto">
                                <div class="rz-comment-user">
                                    <?php if( isset( $userdata->display_name ) ): ?>
                                        <span class="rz-comment-name rz-font-heading">
                                            <?php echo $userdata->display_name; ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if( $average ): ?>
                                        <span class="rz-comment-rating">
                                            <i class="fas fa-star"></i>
                                            <strong><?php echo number_format( floatval( $average ), 2 ); ?></strong>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="rz-comment-date">
                                    <?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $comment->comment_date ) ) ); ?>
                                </div>
                            </div>
                            <?php if( is_user_logged_in() and $rz_listing->post->post_author == get_current_user_id() ): ?>
                                <div class="rz-col-auto rz-flex rz-flex-column rz-justify-center">
                                    <div class="rz-comments-reply rz-text-right">
                                        <a href="#" class="rz-button rz-tiny rz-lighter" data-modal="review-reply" data-params="<?php echo (int) $comment->comment_ID; ?>">
                                            <?php esc_html_e( 'Reply', 'routiz' ); ?>
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
                <div class="rz-comment-text" data-id="<?php echo (int) $comment->comment_ID; ?>">
                    <?php echo wpautop( wp_kses( $comment->comment_content, Rz()->allowed_html() ) ); ?>
                    <?php if( is_array( $media ) and ! empty( $media ) ): ?>
                        <div class="rz-comment-media">
                            <ul class="brk-lightbox-stack">
                                <?php foreach( $media as $image ): ?>
                                    <?php $image_src = wp_get_attachment_image_src( $image->id, 'thumbnail' ); ?>
                                    <?php if( isset( $image_src[0] ) ): ?>
                                        <?php $image_src_large = wp_get_attachment_image_src( $image->id, 'rz_gallery_large' ); ?>
                                        <li class="brk-lightbox" data-image="<?php echo esc_url( $image_src_large[0] ); ?>">
                                            <a href="#">
                                                <img src="<?php echo esc_url( $image_src[0] ); ?>" alt="">
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <?php

                        $children = get_comments([
                            'status' => 'approve',
                            'order' => 'DESC',
                            'parent' => $comment->comment_ID,
                        ]);

                        if( $children ) {
                            foreach( $children as $child ) {
                                echo '<div class="rz-comment-child"><p class="rz--author rz-font-heading">' . ( ( $child->user_id == $rz_listing->post->post_author ) ? esc_html( get_the_author_meta('display_name', $rz_listing->post->post_author ) ) : esc_attr( $child->comment_author ) ) . '</p>' . wpautop( $child->comment_content ) . '</div>';
                            }
                        }

                    ?>
                </div>
            </div>
        </li>
    <?php endforeach; ?>
</ul>
