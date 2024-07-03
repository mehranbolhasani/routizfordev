<div class="rz-modal rz-modal-panel-webhook-fields" data-id="panel-webhook-fields">
    <a href="#" class="rz-close">
        <i class="fas fa-times"></i>
    </a>
    <div class="rz-modal-heading rz--border">
        <h4 class="rz--title"><?php esc_html_e( 'Webhook Fields', 'routiz' ); ?></h4>
    </div>
    <div class="rz-modal-content">
        <div class="rz-modal-append">
            <div class="rz-modal-container">
                <p><?php esc_html_e( 'Here is the list of pre-defined fields that will be sent to your webhook url', 'routiz' ); ?>:</p>

                <p><strong><?php esc_html_e( 'Receiver', 'routiz' ); ?></strong></p>
                <p>
                    <code>user_id</code>
                    <code>user_first_name</code>
                    <code>user_last_name</code>
                    <code>user_display_name</code>
                    <code>user_email</code>
                    <code>user_billing_email</code>
                    <code>user_billing_phone</code>
                    <code>user_billing_country</code>
                    <code>user_billing_city</code>
                    <code>user_billing_postcode</code>
                </p>

                <p><strong><?php esc_html_e( 'Sender', 'routiz' ); ?></strong></p>
                <p>
                    <code>from_user_id</code>
                    <code>from_user_first_name</code>
                    <code>from_user_last_name</code>
                    <code>from_user_display_name</code>
                    <code>from_user_email</code>
                    <code>from_user_billing_phone</code>
                </p>

                <p><strong><?php esc_html_e( 'Listing', 'routiz' ); ?></strong></p>
                <p>
                    <code>listing_id</code>
                    <code>listing_name</code>
                    <code>listing_url</code>
                </p>

            </div>
        </div>
        <?php Rz()->preloader(); ?>
    </div>
</div>
