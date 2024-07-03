<?php

use \Routiz\Inc\Src\Request\Request;

defined('ABSPATH') || exit;

$is_business = get_user_meta(get_current_user_id(), 'rz_role', true) == 'business';
$request = Request::instance();
$page = $request->has('onpage') ? $request->get('onpage') : 1;

$entry_status = isset($_GET['rz_filter_entry_status']) ? sanitize_text_field($_GET['rz_filter_entry_status']) : '';

// Determine the type of entries to retrieve
$entry_type = (isset($_GET['type']) || !$is_business) ? 'outgoing' : 'ingoing';

// Fetch the entries with the status filter
$entries = \Routiz\Inc\Src\Woocommerce\Account\Account::get_entries($entry_type);

?>

<form class="posts-filter" method="get" action="<?php echo esc_url(wc_get_account_endpoint_url('entries')); ?>">
    <?php if (isset($_GET['type']) && $_GET['type'] === 'outgoing'): ?>
        <input type="hidden" name="type" value="outgoing">
    <?php endif; ?>
    <select name="rz_filter_entry_status">
        <option value=""><?php esc_html_e('All entry status', 'brikk-utilities'); ?></option>
        <option value="publish" <?php selected(isset($_GET['rz_filter_entry_status']) ? $_GET['rz_filter_entry_status'] : '', 'publish'); ?>>Active</option>
        <option value="pending" <?php selected(isset($_GET['rz_filter_entry_status']) ? $_GET['rz_filter_entry_status'] : '', 'pending'); ?>>Pending Approval</option>
        <option value="pending_payment" <?php selected(isset($_GET['rz_filter_entry_status']) ? $_GET['rz_filter_entry_status'] : '', 'pending_payment'); ?>>Pending Payment</option>
        <option value="declined" <?php selected(isset($_GET['rz_filter_entry_status']) ? $_GET['rz_filter_entry_status'] : '', 'declined'); ?>>Declined</option>
    </select>
    <input type="submit" class="button filter-btn" value="<?php esc_attr_e('Filter', 'brikk-utilities'); ?>">
</form>



<?php if ($is_business): ?>

    <div class="rz-boxes-tabs">
        <ul>
            <li class="<?php if (!isset($_GET['type'])) { echo 'rz--active'; } ?>">
                <a href="<?php echo esc_url(wc_get_account_endpoint_url('entries')); ?>">
                    <?php esc_html_e('Incoming', 'routiz'); ?>
                </a>
            </li>
            <li class="<?php if (isset($_GET['type'])) { echo 'rz--active'; } ?>">
                <a href="<?php echo esc_url(add_query_arg(['type' => 'outgoing'], wc_get_account_endpoint_url('entries'))); ?>">
                    <?php esc_html_e('Outgoing', 'routiz'); ?>
                </a>
            </li>
        </ul>
    </div>

    <?php if (!isset($_GET['type'])): ?>
        <p><?php esc_html_e('Entries that you have received from other users', 'routiz'); ?></p>
    <?php else: ?>
        <p><?php esc_html_e('Entries that you have sent to other users', 'routiz'); ?></p>
    <?php endif; ?>

<?php endif; ?>

<?php if ($entries->have_posts()): ?>
    <div class="rz-boxes">
        <?php while ($entries->have_posts()): $entries->the_post(); ?>
            <?php 
                Rz()->the_template('routiz/account/entries/row'); 
            ?>
        <?php endwhile; wp_reset_postdata(); ?>
    </div>
<?php else: ?>
    <p><strong><?php esc_html_e('No entries were found', 'routiz'); ?></strong></p>
<?php endif; ?>

<div class="rz-paging">
    <?php
    echo Rz()->pagination([
        'base' => add_query_arg(['onpage' => '%#%'], wc_get_account_endpoint_url('entries')),
        'format' => '?onpage=%#%',
        'current' => $page,
        'total' => $entries->max_num_pages,
    ]);
    ?>
</div>

<?php Rz()->the_template('routiz/account/entries/modal/modal'); ?>
<?php Rz()->the_template('routiz/single/modal/submit-review'); ?>
<?php // Rz()->the_template('routiz/account/entries/modal/action/modal'); ?>
