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

                        <div class="rz-grid">

                            <?php

                                $panel->form->render([
                                    'type' => 'radio',
                                    'id' => 'rz_report_reason',
                                    'options' => [
                                        'incorrect' => esc_html__( 'Inaccurate or incorrect', 'routiz' ),
                                        'not_real' => esc_html__( 'Not a real listing', 'routiz' ),
                                        'scam' => esc_html__( 'It\'s a scam', 'routiz' ),
                                        'offensive' => esc_html__( 'It\'s offensive', 'routiz' ),
                                        'else' => esc_html__( 'Something else', 'routiz' )
                                    ]
                                ]);


                            ?>

                        </div>
                    </div>
                </aside>
            </section>
        </div>
    </div>
</div>
