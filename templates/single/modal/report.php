<?php

defined('ABSPATH') || exit;

use \Routiz\Inc\Src\User;
use \Routiz\Inc\Src\Listing\Listing;
use \Routiz\Inc\Src\Form\Component as Form;

global $post;

$form = new Form( Form::Storage_Field );
$user = User::instance();
$listing = new Listing( $post->ID );
$has_reported = $listing->has_reported();

?>

<div class="rz-modal rz-modal-report" data-id="report">
    <a href="#" class="rz-close">
        <i class="fas fa-times"></i>
    </a>
    <div class="rz-modal-heading rz--border">
        <h4 class="rz--title"><?php esc_html_e( 'Send listing report', 'routiz' ); 
        ?></h4>
    </div>
    <div class="rz-modal-content">
        <div class="rz-modal-append">
            <div class="rz-modal-container rz-scrollbar">

                <?php if( (int) $listing->post->post_author == $user->id ): ?>

                    <div class="rz-reported">
                        <div class="rz--icon">
                            <i class="fas fa-ban"></i>
                            <p><?php esc_html_e( 'You can\'t report your own listing', 'routiz' ); ?></p>
                        </div>
                    </div>
                <?php endif; 
                $report_users = get_post_meta($post->ID, 'report_user_id', true);
                $meta_values_array = explode(',', $report_users);
                  
                ?>
<?php if (in_array($user->id, $meta_values_array) && (int) $listing->post->post_author != $user->id ) : ?>
                <div class="rz-reported">
                        <div class="rz--icon">
                            <i class="fas fa-ban"></i>
                            <p><?php esc_html_e( 'You already reported this listing', 'routiz' ); ?></p>
                        </div>
                </div>
                <?php else: 
                    if ((int) $listing->post->post_author !== $user->id): ?>
                <div class="rz-report-submit">
                            <p><?php esc_html_e( 'This is private and won\'t be shared with the owner.', 'routiz' ); ?></p>
                            <div class="rz-form">
                                <?php wp_nonce_field( 'routiz_report_nonce', 'routiz_report' ); ?>
                                <div class="rz-grid">
                                    <?php

                                        $form->render([
                                            'type' => 'radio',
                                            'id' => 'rz_report_reason',
                                            'options' => [
                                                'incorrect' => esc_html__( 'Inaccurate or incorrect', 'routiz' ),
                                                'not_real' => esc_html__( 'Not a real listing', 'routiz' ),
                                                'scam' => esc_html__( 'It\'s a scam', 'routiz' ),
                                                'offensive' => esc_html__( 'It\'s offensive', 'routiz' ),
                                                'else' => esc_html__( 'Something else', 'routiz' )
                                            ]
                                        ]);

                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="rz-modal-footer rz--top-border rz-text-center">
                            <a href="#" id="rz-send-report" class="rz-button rz-button-accent rz-modal-button">
                                <span><?php esc_html_e( 'Send Report', 'routiz' ); ?></span>
                                <?php Rz()->preloader(); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    <?php endif; ?>
            </div>
            <div class="rz-reported <?php if( $has_reported ) { echo 'rz-none'; } ?>">
                        <div class="rz--icon">
                            <i class="fas fa-check"></i>
                            <p><?php esc_html_e( 'Your report sucessfully send', 'routiz' ); ?></p>
                        </div>
            </div>
        </div>
        <?php Rz()->preloader(); ?>
    </div>
</div>
