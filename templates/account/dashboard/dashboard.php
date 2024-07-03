<?php

use \Routiz\Inc\Src\Listing\Views;
use \Routiz\Inc\Src\Wallet;

defined('ABSPATH') || exit;

// views
$today_views = Views::get_all_today_views();

// $yesterday_views = Views::get_all_today_views( null, date('Y-m-d', strtotime('-1 days') ) );
// $views_difference_percent = round( ( 1 - ( $today_views / $yesterday_views ) ) * 100 );

// earnings
$today_earnings = Wallet::get_earnings();

// waller
$wallet = new Wallet();
$payouts = $wallet->get_payouts();

$user = new \Routiz\Inc\Src\User( get_current_user_id() );
$user_data = $user->get_userdata();
$user_role = get_user_meta( get_current_user_id(), 'rz_role', true );

/*
 * customer
 *
 */
if( empty( $user_role ) or $user_role == 'customer' ):
?>

<?php if( get_option('rz_enable_standard_role') ): ?>
    <p class="rz-weight-600 rz-text-right">
        <a class="rz-no-decoration" href="<?php echo esc_url( add_query_arg( 'rz_switch_user_role', '', wc_get_account_endpoint_url( 'dashboard' ) ) ); ?>">
            <?php esc_html_e( 'Switch to business', 'routiz' ); ?><i class="fas fa-arrow-right rz-ml-1"></i>
        </a>
    </p>
<?php endif; ?>

<div class="rz-dashboard">
    <div class="rz-grid">
        <div class="rz-col-8 rz-col-xl-12">
            <div class="rz-grid">
                <div class="rz-col-6 rz-col-xl-12">
                    <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-account' ) ); ?>" class="rz--box">
                        <i class="material-icon-style"></i>
                        <h3 class="rz--title"><?php esc_html_e( 'Account details', 'routiz' ); ?><i class="fas fa-caret-right rz-ml-1"></i></h3>
                        <p class="rz-mt-1 rz-mb-0"><?php esc_html_e( 'Provide personal details and how we can reach you', 'routiz' ); ?></p>
                    </a>
                </div>
                <div class="rz-col-6 rz-col-xl-12">
                    <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'messages' ) ); ?>" class="rz--box">
                        <i class="material-icon-chat"></i>
                        <h3 class="rz--title"><?php esc_html_e( 'Messages', 'routiz' ); ?><i class="fas fa-caret-right rz-ml-1"></i></h3>
                        <p class="rz-mt-1 rz-mb-0"><?php esc_html_e( 'Track all your important conversations with the world', 'routiz' ); ?></p>
                    </a>
                </div>
                <div class="rz-col-6 rz-col-xl-12">
                    <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>" class="rz--box">
                        <i class="material-icon-library_add_check"></i>
                        <h3 class="rz--title"><?php esc_html_e( 'My orders', 'routiz' ); ?><i class="fas fa-caret-right rz-ml-1"></i></h3>
                        <p class="rz-mt-1 rz-mb-0"><?php esc_html_e( 'Get information about your orders, payments and details', 'routiz' ); ?></p>
                    </a>
                </div>
                <div class="rz-col-6 rz-col-xl-12">
                    <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'notification-settings' ) ); ?>" class="rz--box">
                        <i class="material-icon-notifications_active"></i>
                        <h3 class="rz--title"><?php esc_html_e( 'Notification settings', 'routiz' ); ?><i class="fas fa-caret-right rz-ml-1"></i></h3>
                        <p class="rz-mt-1 rz-mb-0"><?php esc_html_e( 'Enable notifications for important actions and activities', 'routiz' ); ?></p>
                    </a>
                </div>
            </div>
        </div>
        <div class="rz-col-4 rz-col-xl-12 rz-flex rz-flex-column">
            <div class="rz--box rz-box-author rz-text-center">
                <div class="rz--avatar">
                    <?php $user_avatar = $user->get_avatar(); ?>
                    <?php if( $user_avatar ): ?>
                        <img src="<?php echo esc_url( $user_avatar ); ?>" alt="">
                    <?php else: ?>
                        <i class="material-icon-person"></i>
                    <?php endif; ?>
                </div>
                <p class="rz-mb-0"><?php esc_html_e( 'Hello', 'routiz' ); ?></p>
                <h3 class="rz--title"><?php echo esc_html( $user_data->display_name ); ?>!</h3>
                <p><?php echo esc_html( wp_trim_words( $user_data->description, 20 ) ); ?></p>
                <div class="rz-mt-auto">
                    <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-account' ) ); ?>" class="rz-button">
                        <?php esc_html_e( 'Edit account details', 'routiz' ); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

<?php
/*
 * business
 *
 */
else:
?>

<?php if( get_option('rz_enable_standard_role') ): ?>
    <p class="rz-weight-600 rz-text-right">
        <a class="rz-no-decoration" href="<?php echo apply_filters( 'routiz/account/dashboard/switch-user-role', esc_url( add_query_arg( 'rz_switch_user_role', '', wc_get_account_endpoint_url( 'dashboard' ) ) ) ); ?>" data-action="account-switch-user-role">
            <?php esc_html_e( 'Switch to customer', 'routiz' ); ?><i class="fas fa-arrow-right rz-ml-1"></i>
        </a>
    </p>
<?php endif; ?>

<div class="rz-dashboard">
    <div class="rz-grid rz-dashboard-row">
        <div class="rz-col-4 rz-col-xl-12">
            <div class="rz--box rz--has-icon">
                <i class="material-icon-remove_red_eyevisibility"></i>
                <h3 class="rz--title"><?php esc_html_e( 'Views today', 'routiz' ); ?></h3>
                <span class="rz--num brk-font-heading"><?php echo Rz()->short_number( $today_views ); ?></span>
            </div>
        </div>
        <div class="rz-col-4 rz-col-xl-12">
            <div class="rz--box rz--has-icon">
                <i class="material-icon-timeline"></i>
                <h3 class="rz--title"><?php esc_html_e( 'Today earnings', 'routiz' ); ?></h3>
                <span class="rz--num brk-font-heading"><?php echo Rz()->format_price( $today_earnings ); ?></span>
            </div>
        </div>
        <div class="rz-col-4 rz-col-xl-12">
            <div class="rz--box rz--highlight rz--has-icon">
                <i class="material-icon-account_balance_wallet"></i>
                <h3 class="rz--title"><?php esc_html_e( 'Your balance', 'routiz' ); ?></h3>
                <span class="rz--num brk-font-heading"><?php echo Rz()->format_price( $wallet->get_balance() ); ?></span>
            </div>
        </div>
    </div>

    <div class="rz-grid rz-dashboard-row">
        <div class="rz-col-12">
            <div class="rz--box">
                <div class="rz-flex">
                    <div class="">
                        <h3 class="rz--title rz-mb-0 rz-mr-1"><?php esc_html_e( 'Listing views', 'routiz' ); ?></h3>
                    </div>
                    <div class="rz-ml-auto">
                        <!-- .. -->
                    </div>
                </div>
                <div class="rz-chart" data-id="dashboard">
                    <div id="rz-chart-render"></div>
                    <?php Rz()->preloader(); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="rz-grid">
        <div class="rz-col-4 rz-col-xl-12">
            <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'listings' ) ); ?>" class="rz--box">
                <i class="material-icon-location_onplaceroom"></i>
                <h3 class="rz--title"><?php esc_html_e( 'Listings', 'routiz' ); ?><i class="fas fa-caret-right rz-ml-1"></i></h3>
                <p class="rz-mt-1 rz-mb-0"><?php esc_html_e( 'Manage all your listings and track important information', 'routiz' ); ?></p>
            </a>
        </div>
        <div class="rz-col-4 rz-col-xl-12">
            <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'entries' ) ); ?>" class="rz--box">
                <i class="material-icon-add_circle"></i>
                <h3 class="rz--title"><?php esc_html_e( 'Entries', 'routiz' ); ?><i class="fas fa-caret-right rz-ml-1"></i></h3>
                <p class="rz-mt-1 rz-mb-0"><?php esc_html_e( 'Manage your incoming and outgoing entries', 'routiz' ); ?></p>
            </a>
        </div>
        <div class="rz-col-4 rz-col-xl-12">
            <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'messages' ) ); ?>" class="rz--box">
                <i class="material-icon-chat"></i>
                <h3 class="rz--title"><?php esc_html_e( 'Messages', 'routiz' ); ?><i class="fas fa-caret-right rz-ml-1"></i></h3>
                <p class="rz-mt-1 rz-mb-0"><?php esc_html_e( 'Track all your important conversations with the world', 'routiz' ); ?></p>
            </a>
        </div>
    </div>

</div>

<?php endif; ?>
