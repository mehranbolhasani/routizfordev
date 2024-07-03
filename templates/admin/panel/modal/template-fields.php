<div class="rz-modal rz-modal-panel-template-fields" data-id="panel-template-fields">
    <a href="#" class="rz-close">
        <i class="fas fa-times"></i>
    </a>
    <div class="rz-modal-heading rz--border">
        <h4 class="rz--title"><?php esc_html_e( 'Template Fields', 'routiz' ); ?></h4>
    </div>
    <div class="rz-modal-content">
        <div class="rz-modal-append">
            <div class="rz-modal-container">
                <h3><?php esc_html_e( 'Pre-defined field', 'routiz' ); ?></h3>
                <p><?php esc_html_e( 'Here is the full list of pre-defined fields that you can use in your templates.', 'routiz' ); ?>:</p>

                <p><strong><?php esc_html_e( 'Receiver', 'routiz' ); ?></strong></p>
                <p>
                    <code>{user_id}</code>
                    <code>{user_first_name}</code>
                    <code>{user_last_name}</code>
                    <code>{user_display_name}</code>
                    <code>{user_email}</code>
                    <code>{user_billing_phone}</code>
                </p>

                <p><strong><?php esc_html_e( 'Sender', 'routiz' ); ?></strong></p>
                <p>
                    <code>{from_user_id}</code>
                    <code>{from_user_first_name}</code>
                    <code>{from_user_last_name}</code>
                    <code>{from_user_display_name}</code>
                    <code>{from_user_email}</code>
                    <code>{from_user_billing_phone}</code>
                </p>

                <p><strong><?php esc_html_e( 'Listing', 'routiz' ); ?></strong></p>
                <p>
                    <code>{listing_id}</code>
                    <code>{listing_name}</code>
                    <code>{listing_url}</code>
                </p>

                <h3><?php esc_html_e( 'Custom meta field', 'routiz' ); ?></h3>
                <p><u><?php esc_html_e( 'Advanced', 'routiz' ); ?></u>: <?php esc_html_e( 'you can also get some custom meta fields from your listings. Here is an example how to get meta field with key `my_custom_meta_field`', 'routiz' ); ?>: </p>
                <p><code>{listing:my_custom_meta_field}</code></p>
            </div>
        </div>
        <?php Rz()->preloader(); ?>
    </div>
</div>
