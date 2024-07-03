<?php

use \Routiz\Inc\Src\Entry\Component as Entry;

defined('ABSPATH') || exit;

$entry = Entry::instance();

?>

<div class="rz-outer">
    <div class="rz-listing-edit">
        <?php

            $entry->render([
                'type' => Rz()->get_meta('rz_entry_type'),
            ]);

        ?>
    </div>
</div>