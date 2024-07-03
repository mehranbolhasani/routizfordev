<?php

defined('ABSPATH') || exit;

use \Routiz\Inc\Src\Wallet;
use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Form\Component as Form;

$request = Request::instance();
$form = new Form( Form::Storage_Request );

$page = $request->has('onpage') ? $request->get('onpage') : 1;

$user = null;
if( ! $request->is_empty('user_email') ) {
    $user = get_user_by( 'email', $request->get('user_email') );
}


?>

<div class="rz-meta-container rz-container-left">
    <h1><?php esc_html_e( 'Earnings', 'routiz' ); ?></h1>
</div>

<div class="rz-outer">
    <div class="rz-panel" style="max-width: 50%;">
        <div class="rz-content">
            <section class="rz-sections">
                <aside class="rz-section" style="padding: 1.5rem .75rem;">

                    <div class="rz-grid rz-no-gutter">
                        <div class="rz-col-6">

                            <form method="get">
                                <div class="rz-form">

                                    <?php

                                        $form->render([
                                            'type' => 'hidden',
                                            'id' => 'page',
                                            'value' => 'rz_earnings',
                                        ]);

                                        $form->render([
                                            'type' => 'tab',
                                            'id' => 'tab_user',
                                            'name' => esc_html__('User', 'routiz'),
                                        ]);

                                        $form->render([
                                            'type' => 'text',
                                            'id' => 'user_email',
                                            'name' => esc_html__('Search by user email', 'routiz'),
                                            'placeholder' => esc_html__('Email', 'routiz'),
                                        ]);

                                    ?>

                                    <div class="rz-form-group rz-col-12 rz-mb-0">
                                        <button type="submit" class="rz-button rz-large">
                                            <span><?php esc_html_e( 'Search', 'routiz' ); ?></span>
                                        </button>
                                    </div>

                                </div>
                            </form>

                        </div>
                        <?php if( isset( $user->ID ) ): ?>
                            <div class="rz-col-6">

                                <form method="post">
                                    <div class="rz-form">

                                            <?php

                                                $form->render([
                                                    'type' => 'tab',
                                                    'id' => 'tab_transaction',
                                                    'name' => esc_html__('Add new transaction', 'routiz'),
                                                ]);

                                                $form->render([
                                                    'type' => 'hidden',
                                                    'id' => 'user_email',
                                                ]);

                                                $form->render([
                                                    'type' => 'select',
                                                    'id' => 'type',
                                                    'name' => esc_html__('Transaction type', 'routiz'),
                                                    'options' => [
                                                        'credit' => esc_html__('Credit ( + )', 'routiz'),
                                                        'debit' => esc_html__('Debit ( - )', 'routiz'),
                                                    ],
                                                ]);

                                                $form->render([
                                                    'type' => 'number',
                                                    'id' => 'amount',
                                                    'name' => esc_html__('Transaction amount', 'routiz'),
                                                    'placeholder' => esc_html__('0.00', 'routiz'),
                                                    'min' => 0,
                                                    'step' => 0.01,
                                                ]);

                                            ?>

                                            <?php if( $request->get('success') == 'no' and $request->get('msg') ): ?>
                                                <div class="rz-form-group rz-col-12 rz-mt-3 rz-mb-0">
                                                    <strong style="color:red;"><?php echo esc_html( $request->get('msg') ); ?></strong>
                                                </div>
                                            <?php endif; ?>

                                            <?php if( $request->get('success') == 'yes' and $request->get('msg') ): ?>
                                                <div class="rz-form-group rz-col-12 rz-mt-3 rz-mb-0">
                                                    <strong style="color:green;"><?php echo esc_html( $request->get('msg') ); ?></strong>
                                                </div>
                                            <?php endif; ?>

                                            <div class="rz-form-group rz-col-12 rz-mt-3 rz-mb-0">
                                                <button type="submit" class="rz-button rz-large">
                                                    <span><?php esc_html_e( 'Add transaction', 'routiz' ); ?></span>
                                                </button>
                                            </div>

                                    </div>
                                </form>

                            </div>
                        <?php endif; ?>
                    </div>

                </aside>
            </section>
        </div>
    </div>
</div>

<?php if( ! $request->is_empty('user_email') and ! isset( $user->ID ) ): ?>
    <h3 class="rz-mt-3"><strong><?php esc_html_e('This user was not found.', 'routiz'); ?></strong></h3>
<?php endif; ?>

<?php if( isset( $user->ID ) ): ?>

    <?php

    $wallet = new Wallet( $user->ID );
    $transactions = $wallet->get_translations();

    ?>

    <h3>
        <strong>
            <u>
                <?php
                    echo sprintf(
                        esc_html__( 'Current user balance: %s', 'routiz' ),
                        Rz()->format_price( floor( $wallet->get_balance() * 100 ) / 100 )
                    );
                ?>
            </u>
        </strong>
    </h3>

    <?php if( $transactions->max_num_pages > 1 ): ?>
        <div class="rz-outer">
            <div class="rz-paging">
                <?php

                    echo Rz()->pagination([
                        'base' => add_query_arg([ 'onpage' => '%#%' ], "users.php?page=rz_earnings&user_email={$request->get('user_email')}"),
                        'format' => '?onpage=%#%',
                        'current' => $page,
                        'total' => $transactions->max_num_pages,
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
                    <th><strong><?php esc_html_e( 'Type', 'routiz' ); ?></strong></th>
                    <th><strong><?php esc_html_e( 'Order ID', 'routiz' ); ?></strong></th>
    			    <th><strong><?php esc_html_e( 'Source', 'routiz' ); ?></strong></th>
                    <th><strong><?php esc_html_e( 'Amount', 'routiz' ); ?></strong></th>
                    <th><strong><?php esc_html_e( 'Date', 'routiz' ); ?></strong></th>
                    <th><strong><?php esc_html_e( 'Actions', 'routiz' ); ?></strong></th>
                </tr>
            </thead>

            <tbody>

                <?php if( $transactions->results ): ?>

                    <?php foreach( $transactions->results as $transaction ): ?>

                        <?php

                        // dd( $transaction );

                        $id = (int) $transaction->id;
                        $type = $transaction->type;
                        $order_id = $transaction->order_id;
                        $source = $transaction->source;
    					$date = $transaction->created_at;

                        $actions = [];

                        if( current_user_can( 'manage_options' ) ) {
                            $actions['delete'] = [
                                'action' => 'delete',
                                'name' => esc_html__( 'Delete', 'routiz' ),
                                'url' => wp_nonce_url( add_query_arg([ 'action' => 'delete', 'id' => $id ] ), "routiz_transaction{$id}" ),
                                'icon' => 'fas fa-times',
                                'conformation_msg' => esc_html__( 'Are you sure you want to delete this item?', 'routiz' ),
                            ];
                        }

                    ?>

                    <tr>
                        <td>#<?php echo $id; ?></td>
                        <td><?php echo esc_html( $type ); ?></td>
                        <td><?php echo isset( $order_id ) ? esc_html( $order_id ) : '-'; ?></td>
                        <td><?php echo esc_html( $source ); ?></td>
                        <td><?php echo Rz()->format_price( $transaction->amount ); ?></td>
                        <td><?php echo esc_html( $date ); ?></td>
    					<td>
                            <div class="rz-actions">
                                <?php
                                    if( $actions ) {
                                        foreach( $actions as $action ) {
                                            if ( is_array( $action ) ) {
                                                printf(
                                                    '<a class="button button-icon tips icon-%1$s" href="%2$s" data-action="action-confirmation" data-confirmation-msg="%5$s" data-tip="%3$s"><i class="%4$s"></i></a>',
                                                    esc_attr( $action['action'] ),
                                                    esc_url( $action['url'] ),
                                                    esc_attr( $action['name'] ),
                                                    esc_html( $action['icon'] ),
                                                    esc_html( $action['conformation_msg'] )
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
                    <th><strong><?php esc_html_e( 'Type', 'routiz' ); ?></strong></th>
                    <th><strong><?php esc_html_e( 'Order ID', 'routiz' ); ?></strong></th>
    			    <th><strong><?php esc_html_e( 'Source', 'routiz' ); ?></strong></th>
                    <th><strong><?php esc_html_e( 'Amount', 'routiz' ); ?></strong></th>
                    <th><strong><?php esc_html_e( 'Date', 'routiz' ); ?></strong></th>
                    <th><strong><?php esc_html_e( 'Actions', 'routiz' ); ?></strong></th>
                </tr>
            </tfoot>
        </table>
    </div>

<?php endif; ?>
