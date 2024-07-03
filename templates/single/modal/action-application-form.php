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

?>

<?php if( ! is_user_logged_in() ): ?>

	<div class="rz-modal-container rz-scrollbar">
		<div class="rz--icon">
			<i class="fas fa-ban"></i>
			<p><?php esc_html_e( 'You need to login in order to send applications', 'routiz' ); ?></p>
		</div>
	</div>

<?php else: ?>

	<div class="rz-modal-container rz-scrollbar">
		<?php if( $user->id == $listing->post->post_author ): ?>

			<div class="rz--icon">
			    <i class="fas fa-ban"></i>
			    <p><?php esc_html_e( 'You can\'t send application to yourself', 'routiz' ); ?></p>
			</div>

		<?php else: ?>

			<?php $action = $listing->type->get_action_type( 'application' ); ?>

			<div class="rz--icon rz-none">
			    <i class="fas fa-check"></i>
			    <p><?php esc_html_e( 'You application was sent successfully', 'routiz' ); ?></p>
			</div>

			<form class="rz-form">

				<?php if( $action->fields->form ): ?>
					<div class="rz-grid">
						<?php

				            foreach( $action->fields->form as $item ) {

				                if( $item->fields->show_if_guest and is_user_logged_in() ) {
				                    continue;
				                }

				                $field = $form->create( Rz()->prefix_item( $item ) );

				                if( ! Rz()->is_error( $field ) ) {

				                    echo $field->get();

				                }else{

				                    $field->display_error();

				                }

				            }

				        ?>
					</div>
				<?php else: ?>
					<p class="rz-weight-600 rz-text-center rz-mb-0"><?php esc_html_e( 'Application form is empty.', 'routiz' ); ?></p>
				<?php endif; ?>

			</form>

		</div>

		<?php if( $action->fields->form ): ?>
			<div class="rz-modal-footer rz--top-border rz-text-center">
				<a href="#" class="rz-button rz-button-accent rz-modal-button" data-action="send-application">
					<span><?php esc_html_e( 'Submit Application', 'routiz' ); ?></span>
					<?php Rz()->preloader(); ?>
				</a>
			</div>
		<?php endif; ?>

	<?php endif; ?>
<?php endif; ?>

<?php

/*
 * preloader
 *
 */
Rz()->the_template( 'routiz/globals/preloader' );

?>