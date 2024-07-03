<?php

defined('ABSPATH') || exit;

use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Listing\Listing;
use \Routiz\Inc\Src\Form\Component as Form;
use \Routiz\Inc\Src\User;

$request = Request::instance();
$user = User::instance();
$form = new Form( Form::Storage_Request );
$listing = new Listing( $request->get('listing_id') );
$claims = $listing->type->get_wc_claim();

?>

<div class="rz-modal-container rz-scrollbar">
	<?php if( $user->id == $listing->post->post_author ): ?>

		<div class="rz--icon">
		    <i class="fas fa-ban"></i>
		    <p><?php esc_html_e( 'You already own this listing', 'routiz' ); ?></p>
		</div>

	<?php else: ?>

		<div class="rz--icon rz-none">
		    <i class="fas fa-check"></i>
		    <p><?php esc_html_e( 'Your claim application was sent successfully. It will be reviewed by administrator.', 'routiz' ); ?></p>
		</div>

		<?php if( $listing->id and $listing->type and $claims ): ?>
			<div class="rz-packages rz-mb-2 rz-no-select">
	            <input type="hidden" id="claim-listing-id" value="<?php echo $listing->id; ?>">
	            <?php wp_nonce_field( 'routiz_claim_listing_nonce', 'routiz_claim_listing' ); ?>
	            <?php if( $claims ): ?>
	                <?php foreach( $claims as $claim ): ?>

						<div class="rz-package">
						    <div class="rz--image">
						        <?php echo Rz()->dummy('fas fa-store', 100 ); ?>
						    </div>
						    <div class="rz--content">
						        <h4 class="rz--title"><?php echo $claim->get_name() ?></h4>
						        <?php $duration = $claim->get_meta( '_rz_promotion_duration' ); ?>
						        <?php $price = sprintf( get_woocommerce_price_format(), get_woocommerce_currency_symbol(), $claim->get_price() ); ?>
						        <p><?php echo sprintf( esc_html__('One time payment - %s', 'routiz'), $price ); ?></p>
						    </div>
						</div>

	                <?php endforeach; ?>
	            <?php endif; ?>
		    </div>
		<?php endif; ?>

		<form class="rz-form">
		    <div class="rz-grid">

				<div class="rz-form-group rz-col-12 rz-mb-0">
					<div class="rz-heading">
						<label class="rz-ellipsis"><?php esc_html_e( 'Comments', 'routiz' ) ?></label>
						<p><?php esc_html_e( 'Add additional comments to your application.', 'routiz' ) ?></p>
					</div>
					<textarea type="text" name="claim_comment"></textarea>
				</div>

		    </div>
		</form>

	<?php endif; ?>

</div>

<?php if( $user->id !== (int) $listing->post->post_author ): ?>
	<div class="rz-modal-footer rz--top-border rz-text-center">
		<a href="#" class="rz-button rz-button-accent rz-modal-button" data-action="send-claim">
			<span><?php esc_html_e( 'Claim this business', 'routiz' ); ?><i class="fas fa-arrow-right rz-ml-1"></i></span>
			<?php Rz()->preloader(); ?>
		</a>
	</div>
<?php endif; ?>

<?php

/*
 * preloader
 *
 */
Rz()->the_template( 'routiz/globals/preloader' );

?>