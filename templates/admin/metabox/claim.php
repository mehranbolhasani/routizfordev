<?php

defined('ABSPATH') || exit;

global $post;

$panel = \Routiz\Inc\Src\Admin\Panel::instance();
$panel->form->set( $panel->form::Storage_Meta );

?>

<div class="rz-outer">
    <div class="rz-panel rz-ml-auto rz-mr-auto">

        <div class="rz-content">
            <section class="rz-sections">
                <aside class="rz-section">

                    <div class="rz-form">

                        <?php $user = new \Routiz\Inc\Src\User( $post->post_author ); ?>
                        <?php if( $user->id ): ?>
                            <div class="rz--panel-title">
                                <?php esc_html_e( 'Claimed by', 'routiz' ); ?>
                            </div>
                            <div class="rz--panel-row">
                                <div class="rz--image">
                                    <a href="<?php echo esc_url( get_edit_user_link( $user->id ) ); ?>">
                                        <i class="fas fa-user"></i>
                                    </a>
                                </div>
                                <div class="rz--content">
                                    <?php $user_data = get_userdata( $user->id ); ?>
                                    <a href="<?php echo esc_url( get_edit_user_link( $user->id ) ); ?>"><?php echo esc_html( $user_data->display_name ); ?></a>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php $product_id = get_post_meta( get_the_ID(), 'rz_product_id', true ); ?>

                        <div class="rz--panel-title">
                            <?php esc_html_e( 'Product', 'routiz' ); ?>
                        </div>
                        <?php if( $product_id ): ?>
                            <div class="rz--panel-row">
                                <div class="rz--image">
                                    <a href="<?php echo esc_url( get_edit_post_link( $product_id ) ); ?>">
                                        <i class="fas fa-store"></i>
                                    </a>
                                </div>
                                <div class="rz--content">
                                    <a href="<?php echo esc_url( get_edit_post_link( $product_id ) ); ?>"><?php echo get_the_title( $product_id ); ?></a>
                                </div>
                            </div>
                        <?php else: ?>
                            <p>-</p>
                        <?php endif; ?>

                        <?php $order_id = get_post_meta( get_the_ID(), 'rz_order_id', true ); ?>

                        <div class="rz--panel-title">
                            <?php esc_html_e( 'Order', 'routiz' ); ?>
                        </div>
                        <?php if( $order_id ): ?>
                            <div class="rz--panel-row">
                                <div class="rz--image">
                                    <a href="<?php echo esc_url( get_edit_post_link( $order_id ) ); ?>">
                                        <i class="fas fa-archive"></i>
                                    </a>
                                </div>
                                <div class="rz--content">
                                    <a href="<?php echo esc_url( get_edit_post_link( $order_id ) ); ?>"><?php echo get_the_title( $order_id ); ?></a>
                                </div>
                            </div>
                        <?php else: ?>
                            <p>-</p>
                        <?php endif; ?>


                        <?php $listing_id = get_post_meta( get_the_ID(), 'rz_listing', false ); ?>
                        <?php if( is_array( $listing_id ) ): ?>
                            <div class="rz--panel-title">
                                <?php esc_html_e( 'Listing claimed', 'routiz' ); ?>
                            </div>
                            <?php
                                $attached = new \WP_Query([
                                    'post_type' => 'rz_listing',
                                    'post__in' => $listing_id,
                                ]);
                            ?>
                            <?php if( $attached->found_posts ): ?>
                                <?php foreach( $attached->posts as $attached ): ?>
                                    <div class="rz--panel-row">
                                        <div class="rz--image">
                                            <a href="<?php echo esc_url( get_edit_post_link( $attached->ID ) ); ?>">
                                                <?php $listing = new \Routiz\Inc\Src\Listing\Listing( $attached->ID ); ?>
                                                <?php $image = $listing->get_first_from_gallery( 'thumbnail' ); ?>
                                                <?php if( $image ): ?>
                                                    <img src="<?php echo esc_url( $image ); ?>" alt="">
                                                <?php else: ?>
                                                    <i class="fas fa-map-marker-alt"></i>
                                                <?php endif; ?>
                                            </a>
                                        </div>
                                        <div class="rz--content">
                                            <?php $title = get_the_title( $attached->ID ); ?>
                                            <a href="<?php echo esc_url( get_edit_post_link( $attached->ID ) ); ?>"><?php echo $title ? esc_html( $title ) : esc_html__( '( empty )', 'routiz' ); ?></a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        <?php endif; ?>

                        <div class="rz-grid">

                            <?php

                                $panel->form->render([
                                    'type' => 'textarea',
                                    'id' => 'rz_claim_comment',
                                    'name' => esc_html__( 'Comment', 'routiz' ),
                                    'disabled' => true,
                                ]);


                            ?>

                        </div>
                    </div>
                </aside>
            </section>
        </div>
    </div>
</div>
