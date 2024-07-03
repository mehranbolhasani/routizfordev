<?php

defined('ABSPATH') || exit;

global $rz_favorite_id, $rz_favotite_time;
$listing = new \Routiz\Inc\Src\Listing\Listing( $rz_favorite_id );
$image = $listing->get_first_from_gallery( 'thumbnail' );
// $review_rating_average = $listing->get( 'rz_review_rating_average' );

?>

<?php if( $listing->id ): ?>
    <li>
        <a href="<?php echo get_the_permalink( $listing->id ); ?>" class="rz--image">

            <?php if( $image ): ?>
                <img src="<?php echo esc_url( $image ); ?>" alt="">
            <?php else: ?>
                <?php echo Rz()->dummy( null, 100 ); ?>
            <?php endif; ?>

        </a>
        <div class="rz--content">
            <h4 class="rz--title">
                <a href="<?php echo get_the_permalink( $listing->id ); ?>">
                    <?php echo get_the_title( $listing->id ); ?>
                </a>
            </h4>
            <?php if( $listing->reviews->average ): ?>
                <div class="rz-listing-review">
                    <i class="fas fa-star"></i>
                    <span><?php echo number_format( $listing->reviews->average, 2 ); ?></span>
                </div>
            <?php endif; ?>
        </div>
        <div class="rz--action">
            <a href="#" class="rz-remove" data-action="add-favorite" data-id="<?php echo $listing->id; ?>">
                <i class="fas fa-trash-alt"></i>
            </a>
        </div>
    </li>
<?php endif; ?>
