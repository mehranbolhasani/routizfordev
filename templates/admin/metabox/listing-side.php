<?php

defined('ABSPATH') || exit;

$panel = \Routiz\Inc\Src\Admin\Panel::instance();
$panel->form->set( $panel->form::Storage_Meta );
$listing = new \Routiz\Inc\Src\Listing\Listing( get_the_ID() );

?>

<div class="rz-outer">
    <div class="rz-meta-container">
        <div class="rz-meta-side">
            <div class="rz-form">

                <?php

                    $promoted_expiration = (int) Rz()->get_meta( 'rz_promotion_expires' );
                    if( $promoted_expiration /*and $promoted_expiration > time()*/ ) {
                        echo '<div class="rz-notice rz-mb-2"><p class="rz-promotion-expires">' . sprintf( __( 'Promoted listing, expires:<br>%s', 'routiz' ), date_i18n( get_option('date_format') . ' ' . get_option('time_format'), $promoted_expiration ) ) . '</p></div>';
                    }

                ?>

                <div class="rz-grid">

                    <?php

                        // priority
                        $priority = (int) $listing->get('rz_priority');

                        switch( true ) {
                            case $priority == 0 : $priority_selection_value = 'normal'; break;
                            case $priority == 1 : $priority_selection_value = 'featured'; break;
                            case $priority == 2 : $priority_selection_value = 'promoted'; break;
                            case $priority >= 3 : $priority_selection_value = 'custom'; break;
                        }

                        $panel->form->render([
                            'type' => 'radio',
                            'id' => 'priority_selection',
                            'name' => __( 'Listing priority', 'routiz' ),
                            'description' => __( 'Set what priority will be given to this listing in search results.', 'routiz' ),
                            'options' => [
                                'normal' => esc_html__( 'Normal', 'routiz' ),
                                'featured' => esc_html__( 'Featured', 'routiz' ),
                                'promoted' => esc_html__( 'Promoted', 'routiz' ),
                                'custom' => esc_html__( 'Custom', 'routiz' ),
                            ],
                            'value' => $priority_selection_value
                        ]);

                        $panel->form->render([
                            'type' => 'number',
                            'id' => 'priority_custom',
                            'description' => __( 'Higher value means higher priority in search results.<br><br>Normal = 0<br>Featured = 1<br>Promoted = 2', 'routiz' ),
                            'min' => 0,
                            'max' => 100,
                            'dependency' => [
                                'id' => 'priority_selection',
                                'value' => 'custom',
                                'compare' => '=',
                            ],
                            'value' => $priority,
                        ]);

                        // claim
                        if( $listing->type ) {
                            $action = $listing->type->get_action();

                            // options - action type: claim
                            if( $action->has('claim') ) {

                                $panel->form->render([
                                    'type' => 'checkbox',
                                    'id' => 'is_claimed',
                                    'name' => __( 'Is this listing claimed?', 'routiz' ),
                                    'description' => __( 'Check this option if you want to display this listing as claimed. Other customers won\'t be able to claim it.', 'routiz' ),
                                ]);

                            }
                        }

                        // expiration
                        $listing_expires = Rz()->get_meta('rz_listing_expires');

                        $panel->form->render([
                            'type' => 'checkbox',
                            'id' => 'enable_listing_expiration',
                            'name' => __( 'Enable listing expiration', 'routiz' ),
                            'value' => boolval( $listing_expires )
                        ]);

                        $panel->form->render([
                            'type' => 'datetime',
                            'id' => 'listing_expiration',
                            'dependency' => [
                                'id' => 'enable_listing_expiration',
                                'compare' => '=',
                                'value' => true,
                            ],
                            'value' => strtotime( $listing_expires )
                        ]);

                        $panel->form->render([
                            'type' => 'hidden',
                            'id' => 'remove_listing_expiration',
                            'value' => true
                        ]);

                    ?>

                </div>
            </div>
        </div>
    </div>
</div>
