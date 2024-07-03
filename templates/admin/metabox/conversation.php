<?php

defined('ABSPATH') || exit;

use \Routiz\Inc\Src\Listing\Conversation;

global $post;

$panel = \Routiz\Inc\Src\Admin\Panel::instance();
$panel->form->set( $panel->form::Storage_Meta );

$listing_id = Rz()->get_meta('rz_listing_id');
$users = get_post_meta( get_the_ID(), 'rz_user_id' );

$conversation = new Conversation( get_the_ID() );
$messages = $conversation->get_messages('desc');

?>

<div class="rz-outer">
    <div class="rz-panel rz-ml-auto rz-mr-auto">

        <div class="rz-content">
            <section class="rz-sections">
                <aside class="rz-section">

                    <div class="rz-form">

                        <div class="rz--panel-title">
                            <?php esc_html_e( 'Listing', 'routiz' ); ?>
                        </div>

                        <div class="rz--panel-row">
                            <div class="rz--image">
                                <a href="<?php echo esc_url( get_edit_post_link( $listing_id ) ); ?>">
                                    <?php $listing = new \Routiz\Inc\Src\Listing\Listing( $listing_id ); ?>
                                    <?php $image = $listing->get_first_from_gallery( 'thumbnail' ); ?>
                                    <?php if( $image ): ?>
                                        <img src="<?php echo esc_url( $image ); ?>" alt="">
                                    <?php else: ?>
                                        <i class="fas fa-map-marker-alt"></i>
                                    <?php endif; ?>
                                </a>
                            </div>
                            <div class="rz--content">
                                <?php $title = get_the_title( $listing_id ); ?>
                                <a href="<?php echo esc_url( get_edit_post_link( $listing_id ) ); ?>"><?php echo $title ? esc_html( $title ) : esc_html__( '( empty )', 'routiz' ); ?></a>
                            </div>
                        </div>

                        <?php if( is_array( $users ) ): ?>
                            <div class="rz--panel-title">
                                <?php esc_html_e( 'Users', 'routiz' ); ?>
                            </div>
                            <?php foreach( $users as $user_id ): ?>
                                <?php $userdata = get_userdata( $user_id ); ?>
                                <?php if( $userdata instanceof \Wp_User ): ?>
                                    <div class="rz--panel-row">
                                        <div class="rz--image">
                                            <a href="<?php echo esc_url( get_edit_user_link( $user_id ) ); ?>">
                                                <i class="fas fa-user"></i>
                                            </a>
                                        </div>
                                        <div class="rz--content">
                                            <a href="<?php echo esc_url( get_edit_user_link( $user_id ) ); ?>"><?php echo esc_html( $userdata->display_name ); ?></a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>

                    </div>

                    <?php

                    $last_modified_time = get_post_modified_time('U', false);
                    $date_format = get_option('date_format') . ' ' . get_option('time_format');
                    $last_modify_date_local = date_i18n( $date_format, $last_modified_time + get_option( 'gmt_offset' ) * HOUR_IN_SECONDS );

                    ?>

                    <p><?php echo sprintf( esc_html__('Last activity: %s', 'routiz'), $last_modify_date_local ); ?></p>

                    <div class="rz--panel-title">
                        <?php esc_html_e( 'Conversation', 'routiz' ); ?>
                    </div>

                    <?php if( ! empty( $messages ) and is_array( $messages ) ): ?>

                        <div class="rz-messages">
                            <?php $last_date = null; ?>
                            <?php foreach( $messages as $message ): ?>
                                <?php $usr = get_userdata( $message->sender_id ); ?>
                                <div class="rz-message">
                                    <div class="rz-grid">
                                        <div class="rz-col">
                                            <span class="rz--title">
                                                <?php echo esc_html( $usr->display_name ); ?>
                                            </span>
                                        </div>
                                        <div class="rz-col rz-text-right">
                                            <span class="rz--time">
                                                <?php echo date_i18n( get_option('date_format') . ' ' . get_option('time_format'), strtotime( $message->created_at ) ); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="rz--content">
                                        <?php
                                            echo wp_kses( wpautop( stripslashes( $message->text ) ), [
                                                'a' => [
                                                    'href' => [],
                                                    'title' => []
                                                ],
                                                'br' => [],
                                                'em' => [],
                                                'p' => [],
                                                'strong' => []
                                            ]);
                                        ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                    <?php else: ?>
                        <p><?php esc_html_e( 'No messages', 'routiz' ); ?></p>
                    <?php endif; ?>

                </aside>
            </section>
        </div>
    </div>
</div>
