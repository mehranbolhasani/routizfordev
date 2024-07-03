<?php

use \Routiz\Inc\Src\Entry\Component as Entry;

defined('ABSPATH') || exit;

$panel = \Routiz\Inc\Src\Admin\Panel::instance();
$panel->form->set( $panel->form::Storage_Meta );
$entry = Entry::instance();

?>

<div class="rz-outer">
    <div class="rz-panel rz-ml-auto rz-mr-auto">

        <div class="rz-content">
            <section class="rz-sections">
                <aside class="rz-section">

                    <?php

                        $entry_type = Rz()->get_meta('rz_entry_type');

                        if( $entry_type ) {
                            $entry->render([
                                'type' => $entry_type,
                            ]);
                        }

                    ?>

                </aside>
            </section>
        </div>

    </div>
</div>
