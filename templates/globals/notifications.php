<?php

defined('ABSPATH') || exit;

$site_notifications = routiz()->notify->get_latest_site();
$active_site = routiz()->notify->get_active_site();

?>

<div class="rz-notifications<?php echo $active_site ? ' rz-has-active' : ''; ?>">
    <a href="#" class="rz-notification-icon">
        <i class="far fa-bell"></i>
        <?php if( $active_site ): ?>
            <span><?php echo (int) $active_site; ?></span>
        <?php endif; ?>
    </a>
    <div class="rz-notification-list">
        <ul class="rz-listed">

            <li><span><?php esc_html_e( 'Notifications', 'routiz' ); ?></span></li>

            <?php if( $site_notifications ): ?>
                <?php foreach( $site_notifications as $notification ): ?>
                    <?php $tag = isset( $notification->url ) ? 'a' : 'em'; ?>
                    <li class="<?php if( $notification->active ) { echo 'rz-active'; } ?>">
                        <<?php echo $tag; ?> href="<?php echo esc_url( $notification->url ); ?>" class="rz-no-transition">
                            <div class="rz-auto-icon rz-flex rz-flex-column rz-justify-center rz-text-center">
                                <i class="<?php echo isset( $notification->icon ) ? $notification->icon : 'fas fa-map-marker-alt'; ?>"></i>
                            </div>
                            <div class="rz-auto-content">
                                <?php if( isset( $notification->text ) ) { echo $notification->text; } ?>
                            </div>
                        </<?php echo $tag; ?>>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li><em><?php esc_html_e( 'You don\'t have any notifications', 'routiz' ); ?></em></li>
            <?php endif; ?>

        </ul>
    </div>
</div>
