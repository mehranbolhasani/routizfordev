<?php

defined('ABSPATH') || exit;

use \Routiz\Inc\Src\Wallet;
use \Routiz\Inc\Src\Request\Request;

$payouts = Wallet::get_all_payouts();
$request = Request::instance();
$page = $request->has('onpage') ? $request->get('onpage') : 1;

?>

<div class="rz-meta-container rz-container-left">
    <h1><?php esc_html_e( 'Payouts', 'routiz' ); ?></h1>
</div>

<?php if( $payouts->max_num_pages > 1 ): ?>
    <div class="rz-outer">
        <div class="rz-paging">
            <?php

                echo Rz()->pagination([
                    'base' => add_query_arg([ 'onpage' => '%#%' ], 'edit.php?post_type=rz_listing_type&page=rz_payouts'),
                    'format' => '?onpage=%#%',
                    'current' => $page,
                    'total' => $payouts->max_num_pages,
                ]);

            ?>
        </div>
    </div>
<?php endif; ?>

<div class="wrap">

    <table class="wp-list-table widefat striped">
        <thead>
            <tr>
                <th><strong><?php esc_html_e( 'ID', 'routiz' ); ?></strong></th>
                <th><strong><?php esc_html_e( 'Status', 'routiz' ); ?></strong></th>
                <th><strong><?php esc_html_e( 'User', 'routiz' ); ?></strong></th>
                <th><strong><?php esc_html_e( 'Amount', 'routiz' ); ?></strong></th>
			    <th><strong><?php esc_html_e( 'Method', 'routiz' ); ?></strong></th>
                <th><strong><?php esc_html_e( 'Address', 'routiz' ); ?></strong></th>
                <th><strong><?php esc_html_e( 'Actions', 'routiz' ); ?></strong></th>
            </tr>
        </thead>

        <tbody>

            <?php if( $payouts->results ): ?>

                <?php foreach( $payouts->results as $payout ): ?>

                    <?php

                    // dd( $payout );

                    $id = (int) $payout->id;
                    $user_id = (int) $payout->user_id;
					$userdata = get_userdata( $user_id );
                	$amount = Rz()->format_price( $payout->amount );
                	$status = $payout->status;
					$method	= $payout->payment_method;
					$address = (object) maybe_unserialize( $payout->address );

                    if( ! isset( $address->paypal_email ) and ! isset( $address->bank_account_name ) ) {
                        return;
                    }

                    $address_html = '';

                    $actions = [];

                    if( in_array( $status, [ 'pending' ], true ) and current_user_can( 'manage_options' ) ) {
                        $actions['approve'] = [
                            'action' => 'approve',
                            'name' => esc_html__( 'Approve', 'routiz' ),
                            'url' => wp_nonce_url( add_query_arg([ 'action' => 'approve', 'id' => $id ] ), "routiz_payout{$id}" ),
                            'icon' => 'fas fa-check',
                        ];
                    }

                    if( in_array( $status, [ 'pending' ], true ) and current_user_can( 'manage_options' ) ) {
                        $actions['decline'] = [
                            'action' => 'decline',
                            'name' => esc_html__( 'Decline', 'routiz' ),
                            'url' => wp_nonce_url( add_query_arg([ 'action' => 'decline', 'id' => $id ] ), "routiz_payout{$id}" ),
                            'icon' => 'fas fa-times',
                        ];
                    }

                    switch( $method ) {
                        case 'paypal':
                            $address_html .= '<div><strong>' . esc_html__( 'Email', 'routiz' ) . '</strong>: ' . $address->paypal_email . '</div>';
                            break;
                        case 'bank_transfer':
                            $address_html .= '<div><strong>' . esc_html__( 'Account name', 'routiz' ) . '</strong>: ' . $address->bank_account_name . '</div>';
                            $address_html .= '<div><strong>' . esc_html__( 'Account number', 'routiz' ) . '</strong>: ' . $address->bank_account_number . '</div>';
                            $address_html .= '<div><strong>' . esc_html__( 'IBAN', 'routiz' ) . '</strong>: ' . $address->bank_iban . '</div>';
                            $address_html .= '<div><strong>' . esc_html__( 'BIC / Swift', 'routiz' ) . '</strong>: ' . $address->bank_bic . '</div>';
                            $address_html .= '<div><strong>' . esc_html__( 'Bank name', 'routiz' ) . '</strong>: ' . $address->bank_name . '</div>';
                            $address_html .= '<div><strong>' . esc_html__( 'Routing number', 'routiz' ) . '</strong>: ' . $address->bank_routing_number . '</div>';
                            break;
                    }

                ?>

                <tr>
                    <td>#<?php echo $id; ?></td>
                    <td>
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
                    </td>
                    <td><a href="<?php echo esc_url( get_edit_user_link( $userdata->ID ) ); ?>"><?php echo $userdata->display_name; ?></td>
                    <td><?php echo $amount; ?></td>
					<td>
                        <?php
                            switch( $method ) {
                                case 'paypal': esc_html_e( 'PayPal', 'routiz' ); break;
                                case 'bank_transfer': esc_html_e( 'Bank transfer', 'routiz' ); break;
                            }
                        ?>
                    </td>
					<td><?php echo $address_html; ?></td>
					<td>
                        <div class="rz-actions">
                            <?php
                                if( $actions ) {
                                    foreach( $actions as $action ) {
                                        if ( is_array( $action ) ) {
                                            printf(
                                                '<a class="button button-icon tips icon-%1$s" href="%2$s" data-tip="%3$s"><i class="%4$s"></i></a>',
                                                esc_attr( $action['action'] ),
                                                esc_url( $action['url'] ),
                                                esc_attr( $action['name'] ),
                                                esc_html( $action['icon'] )
                                            );
                                        }else{
                                            echo wp_kses_post( str_replace( 'class="', 'class="button ', $action ) );
                                        }
                                    }
                                }else{
                                    echo '&dash;';
                                }
                            ?>
                        </div>
                    </td>
                </tr>

            <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td class="center" colspan="6"><?php esc_html_e( 'There is no withrawal requests', 'routiz' ); ?></td>
                </tr>

            <?php endif; ?>

        </tbody>
        <tfoot>
            <tr>
                <th><strong><?php esc_html_e( 'ID', 'routiz' ); ?></strong></th>
                <th><strong><?php esc_html_e( 'Status', 'routiz' ); ?></strong></th>
                <th><strong><?php esc_html_e( 'User', 'routiz' ); ?></strong></th>
                <th><strong><?php esc_html_e( 'Amount', 'routiz' ); ?></strong></th>
			    <th><strong><?php esc_html_e( 'Method', 'routiz' ); ?></strong></th>
                <th><strong><?php esc_html_e( 'Address', 'routiz' ); ?></strong></th>
                <th><strong><?php esc_html_e( 'Actions', 'routiz' ); ?></strong></th>
            </tr>
        </tfoot>
    </table>
</div>
