<?php

use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Wallet;

defined('ABSPATH') || exit;

$wallet = new Wallet();
$payouts = $wallet->get_payouts();
$request = Request::instance();
$page = $request->has('onpage') ? $request->get('onpage') : 1;
$min_payout = (float) get_option( 'rz_min_payout', 0 );

?>

<div class="rz-boxes-action">
    <div>
        <div>
            <strong>
                <?php
                    echo sprintf(
                        esc_html__( 'Your current balance is: %s', 'routiz' ),
                        Rz()->format_price( floor( $wallet->get_balance() * 100 ) / 100 )
                    );
                ?>
            </strong>
        </div>
        <?php if( $min_payout ): ?>
            <div>
                <?php
                    echo sprintf(
                        esc_html__( 'Minimum payout amount: %s', 'routiz' ),
                        Rz()->format_price( $min_payout )
                    );
                ?>
            </div>
        <?php endif; ?>
    </div>
    <div>
        <a href="#" class="rz-button" data-modal="request-payout">
            <span><?php esc_html_e( 'Request Payout', 'routiz' ); ?></span>
        </a>
    </div>
</div>

<?php if( $payouts->results ): ?>
    <div class="rz-boxes">
        <?php foreach( $payouts->results as $payout ): ?>
            <div class="rz--cell">
                <div class="rz-box">
                    <div class="rz--heading">
                        <div class="rz--title">
                            <h4><?php echo Rz()->format_price( $payout->amount ); ?></h4>
                        </div>
                    </div>
                    <div class="rz--status">
                        <div class="rz-post-status rz-status-<?php echo $payout->status; ?>">
                            <span>
                                <?php
                                    switch( $payout->status ) {
                                        case 'pending': esc_html_e( 'Pending', 'routiz' ); break;
                                        case 'approved': esc_html_e( 'Approved', 'routiz' ); break;
                                        case 'declined': esc_html_e( 'Declined', 'routiz' ); break;
                                    }
                                ?>
                            </span>
                        </div>
                    </div>
                    <div class="rz--content">
                        <table>
                            <tbody>
                                <tr>
                                    <td><?php esc_html_e( 'Type', 'routiz' ); ?></td>
                                    <td>
                                        <?php
                                            switch( $payout->payment_method ) {
                                                case 'paypal': esc_html_e( 'PayPal', 'routiz' ); break;
                                                case 'bank_transfer': esc_html_e( 'Direct Bank Transfer', 'routiz' ); break;
                                            }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php esc_html_e( 'Request date', 'routiz' ); ?></td>
                                    <td><?php echo date_i18n( get_option('date_format'), strtotime( $payout->created_at ) ); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <?php if( $payout->status == 'pending' ): ?>
                        <div class="rz--actions">
                            <div class="rz--main-actions">
                                <ul>
                                    <li>
                                        <?php
                                            $action_url = add_query_arg([
                                                'action' => 'cancel_payout',
                                                'id' => $payout->id
                                            ]);
                                            $action_url = wp_nonce_url( $action_url, 'routiz_account_cancel_payout' );
                                        ?>
                                        <a href="<?php echo esc_url( $action_url ); ?>" data-action="payout-cancel"><i class="fas fa-trash-alt"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p class="brk-text-center brk-weight-700"><?php esc_html_e( 'No payouts were found', 'routiz' ); ?></p>
<?php endif; ?>

<div class="rz-paging">
    <?php

        echo Rz()->pagination([
            'base' => add_query_arg( [ 'onpage' => '%#%' ], wc_get_account_endpoint_url( 'payouts' ) ),
            'format' => '?onpage=%#%',
            'current' => $page,
            'total' => $payouts->max_num_pages,
        ]);

    ?>
</div>

<?php Rz()->the_template( 'routiz/account/payouts/modal/modal' ); ?>
