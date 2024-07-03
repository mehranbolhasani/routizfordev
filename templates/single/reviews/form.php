<?php

defined('ABSPATH') || exit;

use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Form\Component as Form;
use \Routiz\Inc\Src\Listing\Listing;
use \Routiz\Inc\Src\User;

$request = Request::instance();
$user = User::instance();
$userdata = $user->get_userdata();
$listing = new Listing( $request->get('listing_id') );
$form = new Form( Form::Storage_Field );

$reviewed = get_comments([
    'post_id' => $listing->id,
    'user_id' => get_current_user_id(),
    'parent' => 0,
]);

$ratings = Rz()->jsoning( 'rz_review_ratings', $listing->type->id );

?>

<?php if( ! $listing->type->get('rz_multiple_reviews') and $reviewed ): ?>
    <div class="rz-modal-container rz-scrollbar">
        <span class="rz--icon">
            <div class="rz-text-center">
                <i class="fas fa-check"></i>
                <p><?php esc_html_e( 'You already sent your review.', 'routiz' ); ?></p>
            </div>
        </span>
    </div>
    <?php return; ?>
<?php endif; ?>

<?php if( $listing->post->post_author == $user->id ): ?>
    <div class="rz-modal-container rz-scrollbar">
        <span class="rz--icon">
            <div class="rz-text-center">
                <i class="fas fa-ban"></i>
                <p><?php esc_html_e( 'You can\'t review your own listing', 'routiz' ); ?></p>
            </div>
        </span>
    </div>
    <?php return; ?>
<?php endif; ?>

<div class="rz-modal-container rz-scrollbar">
    <div class="rz-reviews-form">
        <form class="rz-form" autocomplete="nope">
            <div class="rz-grid">

                <?php if( $listing->type->get('rz_enable_review_ratings') and $ratings ): ?>

                    <div class="rz-col-12">
                        <h4 class="rz--title"><?php esc_html_e( 'Rate your experience', 'routiz' ); ?></h4>
                    </div>

                    <div class="rz-form-group rz-col-">
                        <div class="rz-ratings rz-grid">
                            <?php foreach( $ratings as $key => $rating ): ?>
                                <div class="rz-col-6 rz-col-sm-12">
                                    <div class="rz-rating">
                                        <div class="rz-rating-name">
                                            <p><?php echo esc_attr( $rating->fields->name ); ?></p>
                                        </div>
                                        <div class="rz-rating-stars">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <input type="hidden" name="ratings[<?php echo esc_attr( $rating->fields->key ); ?>]">
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                <?php endif; ?>

                <div class="rz-col-12">
                    <h4 class="rz--title"><?php esc_html_e( 'Leave your review', 'routiz' ); ?></h4>
                </div>

                <?php

                    $form->render([
                        'type' => 'hidden',
                        'id' => 'listing_id',
                        'value' => $request->get('listing_id'),
                    ]);

                    $form->render([
                        'type' => 'textarea',
                        'id' => 'comment',
                        // 'name' => esc_html__( 'Comment', 'routiz' ),
                    ]);

                ?>

                <?php if( $listing->type->get('rz_enable_review_media') ): ?>

                    <div class="rz-col-12">
                        <h4 class="rz--title"><?php esc_html_e( 'Add media gallery', 'routiz' ); ?></h4>
                    </div>

                    <?php

                        $form->render([
                            'type' => 'upload',
                            'id' => 'gallery',
                            'multiple_upload' => true,
                            'display_info' => false,
                        ]);

                    ?>

                <?php endif; ?>

            </div>
        </form>

    </div>

    <span class="rz--icon rz-success rz-none">
        <div class="rz-text-center">
            <i class="fas fa-check"></i>
            <p><?php esc_html_e( 'Your review was sent successfully', 'routiz' ); ?></p>
        </div>
    </span>

</div>

<div class="rz-modal-footer rz--top-border rz-text-center">
    <a href="#" class="rz-button rz-button-accent rz-modal-button" id="rz-submit-review">
        <span><?php esc_html_e( 'Submit Review', 'routiz' ); ?></span>
        <?php Rz()->preloader(); ?>
    </a>
</div>
