<?php

use \Routiz\Inc\Src\Listing\Conversation;
use \Routiz\Inc\Src\Request\Request;

defined('ABSPATH') || exit;

$conversations = Conversation::get_conversations();
$request = Request::instance();
$page = $request->has('onpage') ? $request->get('onpage') : 1;

?>

<?php if( $conversations->have_posts() ): ?>
    <div class="rz-boxes-table">
    <div class="rz--inner">
        <table>
            <tbody>
                <?php while( $conversations->have_posts() ) : $conversations->the_post(); ?>
                    <?php Rz()->the_template('routiz/account/messages/row'); ?>
                <?php endwhile; wp_reset_postdata(); ?>
            </tbody>
        </table>
    </div>
    </div>
<?php else: ?>
    <p><?php esc_html_e( 'No conversations were found', 'routiz' ); ?></p>
<?php endif; ?>

<div class="rz-paging">
    <?php

        echo Rz()->pagination([
            'base' => add_query_arg( [ 'onpage' => '%#%' ], wc_get_account_endpoint_url( 'messages' ) ),
            'format' => '?onpage=%#%',
            'current' => $page,
            'total' => $conversations->max_num_pages,
        ]);

    ?>
</div>