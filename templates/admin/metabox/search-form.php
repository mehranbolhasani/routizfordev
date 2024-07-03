<?php

use \Routiz\Inc\Src\Entry\Component as Entry;

defined('ABSPATH') || exit;

$panel = \Routiz\Inc\Src\Admin\Panel::instance();
$panel->form->set( $panel->form::Storage_Meta );

?>

<div class="rz-outer">
    <div class="rz-panel rz-ml-auto rz-mr-auto">

        <div class="rz-content">
            <section class="rz-sections">
                <aside class="rz-section">
                    <div class="rz-form">
                        <div class="rz-grid">

                            <?php

                                $panel->form->render([
                                    'id' => 'listing_type',
                                    'type' => 'listing_types',
                                    'name' => esc_html__( 'Select Listing Type ( Optional )', 'routiz' ),
                                    'description' => esc_html__( 'Select listing type where to point the search filter', 'routiz' ),
                                    'return_ids' => false
                                ]);

                                $search_filters = \Routiz\Templates\Admin\Metabox\Filters::get_fields('search_fields');

                                $panel->form->render( $search_filters );

                            ?>

                        </div>
                    </div>
                </aside>
            </section>
        </div>

    </div>
</div>
