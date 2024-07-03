<?php

use \Routiz\Inc\Src\Request\Request;

defined('ABSPATH') || exit;

$listings = \Routiz\Inc\Src\Woocommerce\Account\Account::get_listings();
$request = Request::instance();
$page = $request->has('onpage') ? $request->get('onpage') : 1;
$listing_types = [];
            $post_types = get_posts([
                'post_type' => 'rz_listing_type',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'meta_query' => []
            ]);

            foreach( $post_types as $post_type ) {
                $listing_types[ $post_type->ID ] = $post_type->post_title;
            }
?>
<form class="posts-filter" method="get">
<select name="rz_filter_listing_type">
                <option value=""><?php esc_html_e('All listing types', 'brikk-utilities'); ?></option>
                <?php
                    $current_v = isset( $_GET['rz_filter_listing_type'] ) ? $_GET['rz_filter_listing_type'] : '';
                    foreach( $listing_types as $listing_type_id => $listing_type_name ) {
                        printf('<option value="%s"%s>%s</option>',
                            $listing_type_id,
                            $listing_type_id == $current_v ? ' selected="selected"' : '',
                            $listing_type_name
                        );
                    }
                ?>
</select>

<select name="rz_filter_listing_status">
    <option value=""><?php esc_html_e('All listing status', 'brikk-utilities'); ?></option>
    <option value="publish" <?php selected(isset($_GET['rz_filter_listing_status']) ? $_GET['rz_filter_listing_status'] : '', 'publish'); ?>>Active</option>
    <option value="draft" <?php selected(isset($_GET['rz_filter_listing_status']) ? $_GET['rz_filter_listing_status'] : '', 'draft'); ?>>Draft</option>
    <option value="pending" <?php selected(isset($_GET['rz_filter_listing_status']) ? $_GET['rz_filter_listing_status'] : '', 'pending'); ?>>Pending Approval</option>
    <option value="pending_payment" <?php selected(isset($_GET['rz_filter_listing_status']) ? $_GET['rz_filter_listing_status'] : '', 'pending_payment'); ?>>Pending Payment</option>
    <option value="pending_listing" <?php selected(isset($_GET['rz_filter_listing_status']) ? $_GET['rz_filter_listing_status'] : '', 'pending_listing'); ?>>Pending</option>
    <option value="expired" <?php selected(isset($_GET['rz_filter_listing_status']) ? $_GET['rz_filter_listing_status'] : '', 'expired'); ?>>Expired</option>
    <option value="cancelled" <?php selected(isset($_GET['rz_filter_listing_status']) ? $_GET['rz_filter_listing_status'] : '', 'cancelled'); ?>>Cancelled</option>
</select>


<input type="submit" name="" id="post-query-submit" class="button filter-btn" value="Filter">
</form>

<?php if( $listings->have_posts() ): ?>
    <div class="rz-boxes">
        <?php while( $listings->have_posts() ) : $listings->the_post(); ?>
            <?php Rz()->the_template('routiz/account/listings/row'); ?>
        <?php endwhile; wp_reset_postdata(); ?>
    </div>
<?php else: ?>
    <p><?php esc_html_e( 'No listings were found', 'routiz' ); ?></p>
<?php endif; ?>

<div class="rz-paging">
    <?php

        echo Rz()->pagination([
            'base' => add_query_arg( [ 'onpage' => '%#%' ], wc_get_account_endpoint_url( 'listings' ) ),
            'format' => '?onpage=%#%',
            'current' => $page,
            'total' => $listings->max_num_pages,
        ]);

    ?>
</div>

<?php Rz()->the_template( 'routiz/account/listings/modal/modal' ); ?>
<?php Rz()->the_template( 'routiz/account/listings/promotion/modal/modal' ); ?>
<?php Rz()->the_template( 'routiz/account/listings/booking-calendar/modal/modal' ); ?>
<?php Rz()->the_template( 'routiz/account/listings/booking-ical/modal/modal' ); ?>