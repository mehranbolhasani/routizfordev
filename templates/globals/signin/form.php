<?php
    $enable_google_auth = get_option('rz_enable_google_auth');
    $enable_facebook_auth = get_option('rz_enable_facebook_auth');
    $enable_standard_role = get_option('rz_enable_standard_role');
    $enable_signup_phone = get_option('rz_enable_signup_phone');
    $enable_signup_terms = get_option('rz_enable_signup_terms');
    $signup_terms_text = get_option('rz_signup_terms_text');
    $enable_signup_captcha = get_option('rz_enable_signup_captcha');
    $signup_captcha_key = get_option('rz_signup_captcha_key');
?>

<?php if( get_option( 'users_can_register' ) ): ?>
    <div class="rz-signin-tabs">
        <ul>
            <li class="rz-active" data-for="sign-in" data-label="<?php esc_html_e( 'Sign in', 'routiz' ); ?>"><a href="#"><?php esc_html_e( 'Sign in', 'routiz' ); ?></a></li>
            <li data-for="create-account" data-label="<?php esc_html_e( 'Create account', 'routiz' ); ?>"><a href="#"><?php esc_html_e( 'Create account', 'routiz' ); ?></a></li>
        </ul>
    </div>
<?php endif; ?>

<form class="rz-form rz-signin-section rz-active" data-id="sign-in" autocomplete="nope">

    <?php if( get_option( 'users_can_register' ) ): ?>
        <?php if( $enable_google_auth or $enable_facebook_auth ): ?>

            <div class="rz-signin-social">
                <ul>
                        <li>
                           <?php echo do_shortcode('[TheChamp-Login]'); ?>
                        </li>
                </ul>
            </div>

            <div class="rz-signin-or">
                <span><?php esc_html_e( 'OR', 'routiz' ); ?></span>
            </div>

        <?php endif; ?>
    <?php endif; ?>

    <div class="rz-grid">
        <!-- fix browser autocomplete -->
        <div style="position:absolute;z-index:-1;opacity:0;">
            <input type="text" name="username">
            <input type="password" name="password">
        </div>
        <!-- // fix browser autocomplete -->
        <div class="rz-form-group rz-col-12">
            <input type="text" name="user_email" value="<?php echo esc_html( apply_filters('routiz/login/user_email', '' ) ); ?>" placeholder="<?php esc_html_e( 'Username or email', 'routiz' ); ?>">
        </div>
        <div class="rz-form-group rz-col-12">
            <input type="password" name="user_password" value="<?php echo esc_html( apply_filters('routiz/login/user_password', '' ) ); ?>" placeholder="<?php esc_html_e( 'Password', 'routiz' ); ?>">
        </div>
        <div class="rz-form-group rz-inline-group rz-col-12">
            <button type="submit" name="" class="rz-button rz-button-accent rz-block rz-w-100 rz-modal-button">
                <span><?php esc_html_e( 'Sign in', 'routiz' ); ?></span>
                <?php Rz()->preloader(); ?>
            </button>
        </div>
        <div class="rz-signin-errors">
            <!-- output -->
        </div>
        <div class="rz-form-group rz-col-12 rz-text-center">
            <p class="rz-mb-0">
                <a href="#" data-for="reset-password" class="rz-lost-pass-link" data-label="<?php esc_html_e( 'Reset password', 'routiz' ); ?>">
                    <i class="fas fa-unlock rz-mr-1"></i><?php esc_html_e( 'Lost your password?', 'routiz' ); ?>
                </a>
            </p>
        </div>
    </div>
</form>

<?php if( get_option( 'users_can_register' ) ): ?>
    <form class="rz-form rz-signin-section" data-id="create-account">

        <input type="submit" name="" value="" class="rz-none">

        <?php if( $enable_standard_role ): ?>
            <div class="rz-standard-role">
                <input type="hidden" name="role" value="customer">
                <ul class="rz-no-select">
                    <li>
                        <a href="#" data-role="customer">
                            <img src="<?php echo RZ_URI; ?>assets/dist/images/signup/group.svg" alt="">
                            <span><?php esc_html_e( 'Customer', 'routiz' ); ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="#" data-role="business">
                            <img src="<?php echo RZ_URI; ?>assets/dist/images/signup/woman.svg" alt="">
                            <span><?php esc_html_e( 'Business', 'routiz' ); ?></span>
                        </a>
                    </li>
                </ul>
            </div>
        <?php endif; ?>

        <div class="rz-signin-container<?php if( $enable_standard_role ) { echo ' rz-none'; } ?>">
            <?php if( $enable_google_auth or $enable_facebook_auth ): ?>
            <div class="rz-signin-social">
                <ul>
                    
                        <li>
                            <?php echo do_shortcode('[TheChamp-Login]') ?>
                        </li>
                    
                    
                </ul>
            </div>
            <?php endif; ?>
            <?php if( $enable_google_auth || $enable_facebook_auth ): ?>
                <div class="rz-signin-or">
                    <span><?php esc_html_e( 'OR', 'routiz' ); ?></span>
                </div>
            <?php endif; ?>

            <div class="rz-grid">

                <div class="rz-form-group rz-col-12">
                    <input type="text" name="username" value="" placeholder="<?php esc_html_e( 'Username', 'routiz' ); ?>">
                </div>

                <div class="rz-form-group <?php echo boolval( $enable_signup_phone ) ? 'rz-col-6 rz-col-sm-12' : 'rz-col-12'; ?>">
                    <input type="text" name="email" value="" placeholder="<?php esc_html_e( 'Email', 'routiz' ); ?>">
                </div>

                <?php if( $enable_signup_phone ): ?>
                    <div class="rz-form-group rz-col-6 rz-col-sm-12">
                        <input type="text" name="phone" value="" placeholder="<?php esc_html_e( 'Phone number', 'routiz' ); ?>" oninput="validatePhoneNumber(this)">
                        <span class="phone-validation-error rz-signin-errors">Please enter a valid phone number.</span>
                    </div>
                <?php endif; ?>
                <div class="rz-form-group rz-col-6 rz-col-sm-12">
                    <input type="text" name="first_name" value="" placeholder="<?php esc_html_e( 'First name', 'routiz' ); ?>">
                </div>
                <div class="rz-form-group rz-col-6 rz-col-sm-12">
                    <input type="text" name="last_name" value="" placeholder="<?php esc_html_e( 'Last name', 'routiz' ); ?>">
                </div>
                <?php if( get_option('rz_enable_standard_pass') ): ?>
                    <div class="rz-form-group rz-col-6 rz-col-sm-12">
                        <input type="password" name="password" value="" placeholder="<?php esc_html_e( 'Password', 'routiz' ); ?>">
                    </div>
                    <div class="rz-form-group rz-col-6 rz-col-sm-12">
                        <input type="password" name="repeat_password" value="" placeholder="<?php esc_html_e( 'Repeat password', 'routiz' ); ?>">
                    </div>
                <?php endif; ?>
                <?php if( $enable_signup_captcha ): ?>
                    <div class="g-recaptcha" id="rcaptcha" name="rcaptcha"  data-sitekey="<?php echo $signup_captcha_key;  ?>"></div>
                <?php endif; ?>
                <?php if( !$enable_signup_captcha ): ?>
                    <div style="display:none;" class="g-recaptcha" data-sitekey="6LfTPlUUAAAAAGSUt1_LqpJXQpatx7_BzTDcU9On"></div>
                <?php endif; ?>
                <?php
                // $enable_signup_terms = get_option('rz_enable_signup_terms');
                // $signup_terms_text = get_option('rz_signup_terms_text');
                ?>

                <?php if( $enable_signup_terms ): ?>
                    <div class="rz-form-group rz-col-12">
                        <label class="rz-checkbox rz-no-select rz-mt-0">
                            <input type="checkbox" value="1" name="terms">
                            <span class="rz-transition"></span>
                            <em><?php echo wp_kses_post( html_entity_decode( Rz()->format_url( $signup_terms_text ) ) ); ?></em>
                        </label>
                    </div>
                <?php endif; ?>
               
                <div class="rz-form-group rz-inline-group rz-col-12">
                    <button type="submit" name="" class="rz-button rz-button-accent rz-block rz-w-100 rz-modal-button">
                        <span><?php esc_html_e( 'Create account', 'routiz' ); ?></span>
                        <?php Rz()->preloader(); ?>
                    </button>
                </div>
                <div class="rz-signin-errors">
                    <!-- output -->
                </div>
                <div class="rz-signin-success">
                    <?php esc_html_e( 'Your account has been created. Please check your email for more details.', 'routiz' ); ?>
                </div>
                <?php if( ! get_option('rz_enable_standard_pass') ): ?>
                <div class="rz-form-group rz-col-12 rz-text-center">
                    <p class="rz-mb-0"><?php esc_html_e( 'A password will be e-mailed to you', 'routiz' ); ?></p>
                </div>
                <?php endif; ?>
                
                

            </div>
        </div>
    </form>
<?php endif; ?>

<form class="rz-form rz-signin-section" data-id="reset-password">
    <input type="submit" name="" value="" class="rz-none">
    <div class="rz-grid">
        <div class="rz-form-group rz-col-12">
            <p><?php esc_html_e( 'Please enter your email address. You will receive a link to create a new password via email.', 'routiz' ); ?></p>
            <input type="text" name="email" value="" placeholder="<?php esc_html_e( 'Email', 'routiz' ); ?>">
        </div>
        <div class="rz-form-group rz-inline-group rz-col-12">
            <button type="submit" name="" class="rz-button rz-button-accent rz-block rz-w-100 rz-modal-button">
                <span><?php esc_html_e( 'Reset password', 'routiz' ); ?></span>
                <?php Rz()->preloader(); ?>
            </button>
        </div>
        <div class="rz-signin-errors">
            <!-- output -->
        </div>
        <div class="rz-signin-success">
            <?php esc_html_e( 'Please check your email for more details.', 'routiz' ); ?>
        </div>
    </div>
</form>

<script src='https://www.google.com/recaptcha/api.js'></script>
<script src="https://cdn.jsdelivr.net/npm/libphonenumber-js/bundle/libphonenumber-js.min.js"></script>


<script>
        function validatePhoneNumber(input) {
            var phoneNumber = input.value;
            
            // Check if the input contains only digits
            var isDigitsOnly = /^\d*$/.test(phoneNumber);

            if (!isDigitsOnly) {
                input.setCustomValidity(''); // Clear any previous validation messages
                document.querySelector('.phone-validation-error').style.display = 'block';
            } else {
                input.setCustomValidity(''); // Clear any previous validation messages
                document.querySelector('.phone-validation-error').style.display = 'none';
            }
        }
</script>


