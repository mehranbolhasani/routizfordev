<?php

use \Routiz\Inc\Src\Form\Component as Form;

$form = new Form( Form::Storage_Field );
$current_user_id = get_current_user_id();
$notif_count = 0;

do_action('routiz/account/notifications/update');

?>

<p><?php esc_html_e( 'Manage your notifications', 'routiz' ); ?></p>

<form method="post" autocomplete="nope">
    <div class="rz-form">
        <div class="rz-grid">
            <?php foreach( scandir( RZ_PATH . 'inc/src/notification/notifications' ) as $file ): ?>

                <?php

                    if( $file == '.' or $file == '..' ) { continue; }

                    $namespace = sprintf( '\Routiz\Inc\Src\Notification\Notifications\%s', str_replace( '-', '_', basename( $file, '.php' ) ) );

                    // notification not found
                    if( ! class_exists( $namespace ) ) {
                        continue;
                    }

                    $email = new $namespace;

                    // site disabled
                    if( ! $email->is_site_enabled() ) {
                        continue;
                    }

                    // notification is not managable by the users
                    if( ! $email->user_can_manage ) {
                        continue;
                    }

                    $notif_count++;

                    $form->render([
                        'type' => 'checkbox',
                        'id' => sprintf( 'rz_is_user_notification_%s', $email->get_id() ),
                        'name' => $email->get_name(),
                        'value' => ! get_user_meta( $current_user_id, sprintf( 'rz_is_user_notification_%s', $email->get_id() ), true ),
                        'html' => [
                            'text' => esc_html__( 'Enable', 'routiz' )
                        ],
                        'col' => 4
                    ]);

                ?>

            <?php endforeach; ?>

            <?php if( ! $notif_count ): ?>
                <div class="rz-form-group rz-col-12 rz-mb-0">
                    <p><strong><?php esc_html_e( 'No notification were found.', 'routiz' ); ?></strong></p>
                </div>
            <?php endif; ?>

            <div class="rz-form-group rz-col-12 rz-mt-3">
                <button type="submit" class="rz-button rz-button-accent rz-large">
                    <span><?php esc_html_e( 'Save Changes', 'routiz' ); ?></span>
                </button>
            </div>

        </div>
    </div>
</form>
