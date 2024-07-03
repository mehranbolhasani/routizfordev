<?php

use \Routiz\Inc\Src\Form\Component as Form;

$form = new Form( Form::Storage_Field );

?>

<div class="rz-modal rz-modal-request-payout" data-id="request-payout">
    <a href="#" class="rz-close">
        <i class="fas fa-times"></i>
    </a>
    <div class="rz-modal-heading rz--border">
        <h4 class="rz--title"><?php esc_html_e( 'Request Payout', 'routiz' ); ?></h4>
    </div>
    <div class="rz-modal-content">
        <div class="rz-modal-append">
            <div class="rz-modal-container rz-scrollbar">

                <span class="rz--icon rz-none">
                    <div class="rz-text-center">
                        <i class="fas fa-check"></i>
                        <p><?php esc_html_e( 'Your payout request was sent', 'routiz' ); ?></p>
                    </div>
                </span>

                <form class="rz-form">
                    <div class="rz-grid">
                        <?php

                            $form->render([
                                'type' => 'text',
                                'id' => 'amount',
                                'name' => esc_html__('Amount', 'routiz'),
                                'placeholder' => '0.00'
                            ]);

                            $payout_methods = get_option('rz_payout_methods');

                            $options = [];
                            if( is_array( $payout_methods ) ) {
                                if( in_array( 'paypal', $payout_methods ) ) {
                                    $options['paypal'] = esc_html__('PayPal', 'routiz');
                                }
                                if( in_array( 'bank_transfer', $payout_methods ) ) {
                                    $options['bank_transfer'] = esc_html__('Bank Transfer', 'routiz');
                                }
                            }

                            $form->render([
                                'type' => 'select',
                                'id' => 'method',
                                'name' => esc_html__('Method', 'routiz'),
                                'options' => $options,
                            ]);

                            $form->render([
                                'type' => 'text',
                                'id' => 'paypal_email',
                                'name' => esc_html__( 'PayPal Email', 'routiz' ),
                                'dependency' => [
                                    'id' => 'method',
                                    'compare' => '=',
                                    'value' => 'paypal',
                                ],
                            ]);

                            $form->render([
                                'type' => 'text',
                                'id' => 'bank_account_name',
                                'name' => esc_html__( 'Account Name', 'routiz' ),
                                'dependency' => [
                                    'id' => 'method',
                                    'compare' => '=',
                                    'value' => 'bank_transfer',
                                ],
                                'col' => 6
                            ]);

                            $form->render([
                                'type' => 'text',
                                'id' => 'bank_account_number',
                                'name' => esc_html__( 'Account Number', 'routiz' ),
                                'dependency' => [
                                    'id' => 'method',
                                    'compare' => '=',
                                    'value' => 'bank_transfer',
                                ],
                                'col' => 6
                            ]);

                            $form->render([
                                'type' => 'text',
                                'id' => 'bank_iban',
                                'name' => esc_html__( 'Bank IBAN', 'routiz' ),
                                'dependency' => [
                                    'id' => 'method',
                                    'compare' => '=',
                                    'value' => 'bank_transfer',
                                ],
                                'col' => 6
                            ]);

                            $form->render([
                                'type' => 'text',
                                'id' => 'bank_bic',
                                'name' => esc_html__( 'BIC / Swift', 'routiz' ),
                                'dependency' => [
                                    'id' => 'method',
                                    'compare' => '=',
                                    'value' => 'bank_transfer',
                                ],
                                'col' => 6
                            ]);

                            $form->render([
                                'type' => 'text',
                                'id' => 'bank_name',
                                'name' => esc_html__( 'Bank Name', 'routiz' ),
                                'dependency' => [
                                    'id' => 'method',
                                    'compare' => '=',
                                    'value' => 'bank_transfer',
                                ],
                                'col' => 6
                            ]);

                            $form->render([
                                'type' => 'text',
                                'id' => 'bank_routing_number',
                                'name' => esc_html__( 'Routing Number', 'routiz' ),
                                'dependency' => [
                                    'id' => 'method',
                                    'compare' => '=',
                                    'value' => 'bank_transfer',
                                ],
                                'col' => 6
                            ]);

                        ?>
                    </div>
                </form>
            </div>
            <div class="rz-modal-footer rz--top-border rz-text-center">
                <a href="#" class="rz-button rz-button-accent rz-modal-button" data-action="request-payout">
                    <span><?php esc_html_e( 'Send Request', 'routiz' ); ?></span>
                    <span class="fas fa-arrow-right rz-ml-1"></span>
                    <?php Rz()->preloader(); ?>
                </a>
            </div>
        </div>
        <?php Rz()->preloader(); ?>
    </div>
</div>
