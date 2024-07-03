<div class="rz-modal rz-modal-share" data-id="share">

    <a href="#" class="rz-close">
        <i class="fas fa-times"></i>
    </a>

    <div class="rz-modal-heading rz--border">
        <h4 class="rz--title"><?php esc_html_e( 'Share', 'routiz' ); ?></h4>
    </div>

    <div class="rz-modal-content">
        <div class="rz-modal-append">
            <div class="rz-modal-container rz-scrollbar">

                <div class="rz-signin-social rz-mb-1">
                    <ul>
                        <li>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo get_permalink(); ?>" class="rz-button rz--gg" target="_blank">
                                <span class="fab fa-facebook rz-mr-1"></span>
                                <span><?php esc_html_e( 'Share with Facebook', 'routiz' ); ?></span>
                            </a>
                        </li>
                        <li class="rz-mt-2">
                            <a href="https://twitter.com/share?url=<?php echo get_permalink(); ?>&text=<?php echo urlencode( esc_html( get_the_title() ) ); ?>" class="rz-button rz--gg" target="_blank">
                                <span class="fab fa-twitter rz-mr-1"></span>
                                <span><?php esc_html_e( 'Share with Twitter', 'routiz' ); ?></span>
                            </a>
                        </li>
                        <li class="rz-mt-2">
                            <a href="https://pinterest.com/pin/create/button/?url=<?php echo get_permalink(); ?>&media=&description=<?php echo urlencode( esc_html( get_the_title() ) ); ?>" class="rz-button rz--gg" target="_blank">
                                <span class="fab fa-pinterest rz-mr-1"></span>
                                <span><?php esc_html_e( 'Share with Pinterest', 'routiz' ); ?></span>
                            </a>
                        </li>
                        <li class="rz-mt-2">
                            <a href="mailto:?&subject=<?php echo esc_html( get_the_title() ); ?>&body=<?php echo get_permalink(); ?>" class="rz-button rz--gg" target="_blank">
                                <span class="far fa-paper-plane rz-mr-1"></span>
                                <span><?php esc_html_e( 'Share by email', 'routiz' ); ?></span>
                            </a>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
        <?php Rz()->preloader(); ?>
    </div>

</div>
