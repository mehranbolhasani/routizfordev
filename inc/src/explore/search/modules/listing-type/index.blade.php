<label><?php echo esc_html( $name ); ?></label>

<div class="rz-form">
    <div class="rz-grid">
        <?php

            $form->render([
                'type' => 'listing-types',
                'id' => 'type',
                'choice' => 'buttons',
            ]);

        ?>
    </div>
</div>
