<?php

use \Routiz\Inc\Src\Listing\Listing;
use \Routiz\Inc\Src\Listing\Conversation;
use \Routiz\Inc\Src\User;

defined('ABSPATH') || exit;

$listing = new Listing( Rz()->get_meta('rz_listing_id') );
$last_message = Conversation::get_last_message( get_the_ID() );
$conversation = new Conversation( get_the_ID() );
$receiver_userdata = get_userdata( $conversation->receiver_id );

if( ! $last_message ) {
    return;
}

$user = new User( $last_message->sender_id );
$userdata = $user->get_userdata();

$is_active = ( $last_message->active and ( (int) $last_message->sender_id !== get_current_user_id() ) );

?>

<tr class="<?php echo $is_active ? 'rz--active' : ''; ?>">
    <td class="rz--cell-image">
        <div class="rz--image">
            <a href="<?php the_permalink( $listing->id ); ?>">
                <?php $image = $listing->get_first_from_gallery( 'thumbnail' ); ?>
                <?php if( $image ): ?>
                    <?php echo '<img src="' . esc_url( $image ) . '" alt="">'; ?>
                <?php else: ?>
                    <?php echo Rz()->dummy( 'fas fa-map-marker-alt', 100 ); ?>
                <?php endif; ?>
            </a>
        </div>
    </td>
    <td class="rz--cell-name">
        <h4 class="rz--name">
            <?php echo wp_trim_words( get_the_title( $listing->id ), 8 ); ?>
        </h4>
    </td>
    <td class="rz--cell-meta">
        <?php if( isset( $receiver_userdata->display_name ) ): ?>
            <p class="rz-mb-0">
                <?php echo esc_html( $receiver_userdata->display_name ); ?>
            </p>
        <?php endif; ?>
        <time class="rz--time">
            <?php echo date_i18n( get_option('date_format') . ' ' . get_option('time_format'), strtotime( $last_message->created_at ) ); ?>
        </time>
    </td>
    <td class="rz--cell-actions">
        <div class="rz--actions">
            <ul>
                <li>
                    <a href="<?php the_permalink( $listing->id ); ?>" target="_blank">
                        <i class="material-icon-location_onplaceroom"></i>
                    </a>
                </li>
                <li>
                    <a href="#" class="rz--toggle-active rz--click" data-modal="conversation" data-params='{"id":<?php the_ID(); ?>}'>
                        <i class="material-icon-chat_bubble"></i>
                    </a>
                </li>
            </ul>
        </div>
    </td>
</tr>
