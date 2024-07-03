<?php

// use \Routiz\Inc\Src\Form\Component as Form;
use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Listing\Listing;
use \Routiz\Inc\Src\Entry\Component;

defined('ABSPATH') || exit;

global $post;

$request = Request::instance();
$component = Component::instance();

$post = get_post( (int) $request->get('id'), OBJECT );

if( ! $post ) {
    return;
}

?>

<?php $listing = new Listing( Rz()->get_meta('rz_listing') ); ?>
<?php if( $listing->id ): ?>
    <?php $image = $listing->get_first_from_gallery( 'thumbnail' ); ?>
    <div class="rz-modal-listing">
        <div class="rz--image">
            <?php if( $image ): ?>
                <span class="rz--img" style="background-image: url('<?php echo esc_url( $image ); ?>');"></span>
            <?php else: ?>
                <?php echo Rz()->dummy(); ?>
            <?php endif; ?>
        </div>
        <div class="rz--content">
            <h4 class="rz--title"><?php echo get_the_title( $listing->id ); ?></h4>
        </div>
    </div>
<?php endif; ?>

<?php

    $item = $component->render([
        'type' => Rz()->get_meta('rz_entry_type'),
    ]);

?>
