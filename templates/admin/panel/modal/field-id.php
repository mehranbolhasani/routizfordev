<?php

$pre_defined = Rz()->get_pre_defined();

?>

<div class="rz-outer">
    <div class="rz-modal rz-modal-field-ids" data-id="field-ids">
        <a href="#" class="rz-close">
            <i class="fas fa-times"></i>
        </a>
        <div class="rz-modal-heading rz--border">
            <h4 class="rz--title"><?php esc_html_e( 'Field Ids', 'routiz' ); ?></h4>
        </div>
        <div class="rz-modal-content">
            <div class="rz-modal-container rz-scrollbar">
                <p>
                    <?php esc_html_e('You can use different ids to retrieve specific information. Use the ids you defined in the custom fields, or the pre-defined.', 'routiz'); ?>
                </p>
                <p>
                    <u><?php esc_html_e('Custom fields', 'routiz'); ?></u>:
                </p>
                <?php
                    if( get_post_type() == 'rz_listing_type' ) {
                        global $pagenow;
                        if( $pagenow == 'post.php' ) {
                            echo '<ul>';
                                foreach( Rz()->jsoning( 'rz_fields' ) as $k => $item ) {
                                    if( ! isset( $item->fields->key ) or in_array( $item->fields->key, $pre_defined ) ) {
                                        continue;
                                    }
                                    ?><li><?php echo esc_attr( Rz()->unprefix( $item->fields->key ) ); ?></li><?php
                                }
                            echo '</ul>';
                        }
                    }
                ?>
                <p>
                    <u><?php esc_html_e('Pre-defined fields', 'routiz'); ?></u>:
                </p>
                <ul>
                    <?php echo '<li>' . implode( '</li><li>', $pre_defined ) . '</li>'; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
