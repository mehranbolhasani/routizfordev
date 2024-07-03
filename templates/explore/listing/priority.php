<?php

defined('ABSPATH') || exit;

global $rz_listing;
$priority_label = $rz_listing->get_priority_label();

?>

<?php if( $priority_label ): ?>
    <div class="rz-listing-priority">
        <span class="rz--tag">
            <?php echo $priority_label; ?>
        </span>
    </div>
<?php endif; ?>
