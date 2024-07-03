<?php if( get_option( 'rz_enable_social_icons' ) ): ?>
    <div class="rz--social">
        <ul>
            <?php if( $social_fb = get_option( 'rz_social_fb' ) ): ?>
                <li class="rz--facebook">
                    <a target="_blank" href="<?php echo esc_url( $social_fb ); ?>">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                </li>
            <?php endif; ?>
            <?php if( $social_tw = get_option( 'rz_social_tw' ) ): ?>
                <li class="rz--twitter">
                    <a target="_blank" href="<?php echo esc_url( $social_tw ); ?>">
                        <i class="fab fa-twitter"></i>
                    </a>
                </li>
            <?php endif; ?>
            <?php if( $social_yt = get_option( 'rz_social_yt' ) ): ?>
                <li class="rz--google">
                    <a target="_blank" href="<?php echo esc_url( $social_yt ); ?>">
                        <i class="fab fa-youtube"></i>
                    </a>
                </li>
            <?php endif; ?>
            <?php if( $social_dr = get_option( 'rz_social_dr' ) ): ?>
                <li class="rz--dribbble">
                    <a target="_blank" href="<?php echo esc_url( $social_dr ); ?>">
                        <i class="fab fa-dribbble"></i>
                    </a>
                </li>
            <?php endif; ?>
            <?php if( $social_in = get_option( 'rz_social_in' ) ): ?>
                <li class="rz--instagram">
                    <a target="_blank" href="<?php echo esc_url( $social_in ); ?>">
                        <i class="fab fa-instagram"></i>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>

<?php endif; ?>