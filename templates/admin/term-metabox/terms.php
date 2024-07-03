<?php

defined('ABSPATH') || exit;

use \Routiz\Inc\Src\Form\Component as Form;

$form = new Form( Form::Storage_Term );

?>

<div class="rz-outer">
    <div class="rz-panel rz-panel-terms">
        <div class="rz-content">
            <section class="rz-sections">
                <aside class="rz-section">
                    <div class="rz-form">
                        <div class="rz-grid">
                            <?php

                                $form->render([
                                    'type' => 'tab',
                                    'id' => 'term_options',
                                    'name' => esc_html__('Term Options', 'routiz'),
                                ]);

                                $form->render([
                                    'type' => 'icon',
                                    'id' => 'icon',
                                    'name' => esc_html__('Icon', 'routiz'),
                                ]);

                            ?>
                        </div>
                    </div>
                </aside>
            </section>
        </div>
    </div>
</div>
