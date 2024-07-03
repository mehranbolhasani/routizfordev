<?php

defined('ABSPATH') || exit;

?>

<div class="rz-user-action">
    <a href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>">
        <?php if( is_user_logged_in() ): ?>
            <span class="rz-user-name rz-ellipsis">
                <?php
                    $user = new \Routiz\Inc\Src\User();
                    $user_data = get_userdata( $user->id );
                    echo esc_html( $user_data->display_name );
                ?>
            </span>
        <?php else: ?>
            <?php esc_html_e( 'Sign In', 'routiz' ); ?>
        <?php endif; ?>
    </a>
</div>
