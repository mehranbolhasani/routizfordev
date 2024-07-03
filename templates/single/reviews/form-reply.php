<?php

defined('ABSPATH') || exit;

use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Form\Component as Form;

$request = Request::instance();
$form = new Form( Form::Storage_Field );

?>

<div class="rz-modal-container rz-scrollbar">
    <div class="rz-reviews-form">
        <form class="rz-form" autocomplete="nope">
            <div class="rz-grid">

                <?php

                    $form->render([
                        'type' => 'hidden',
                        'id' => 'comment_id',
                        'value' => $request->get('comment_id'),
                    ]);

                    $form->render([
                        'type' => 'textarea',
                        'id' => 'comment',
                        'name' => esc_html__( 'Enter your comment', 'routiz' ),
                    ]);

                ?>

            </div>
        </form>

    </div>

    <span class="rz--icon rz-none">
        <div class="rz-text-center">
            <i class="fas fa-check"></i>
            <p><?php esc_html_e( 'Your reply was sent successfully', 'routiz' ); ?></p>
        </div>
    </span>

</div>

<div class="rz-modal-footer rz--top-border rz-text-center">
    <a href="#" class="rz-button rz-button-accent rz-modal-button" id="rz-submit-review-reply">
        <span><?php esc_html_e( 'Submit Comment', 'routiz' ); ?></span>
        <?php Rz()->preloader(); ?>
    </a>
</div>
