<?php

defined('ABSPATH') || exit;

global $rz_listing;
$modules = \Routiz\Inc\Src\Listing\Component::instance();
$action_types = Rz()->json_decode( $rz_listing->type->get('rz_action_types') );

?>

<?php if( $action_types ): ?>
    <div class="rz-sidebar">
        <div class="rz-single-sidebar<?php if( $rz_listing->type->get('rz_enable_action_sticky') ) { echo ' rz--sticky'; } ?>">
            <?php Rz()->the_template('routiz/single/action/action'); ?>
        </div>
    </div>
<?php endif; ?>