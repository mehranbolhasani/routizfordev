<?php

use \Routiz\Inc\Src\Listing_Type\Action;

defined('ABSPATH') || exit;

global $rz_submission;

$action_fields = Action::get_action_fields( $rz_submission->listing_type );

?>

<div class="rz-submission-steps">

    <form>
        <input type="hidden" name="type" value="<?php echo esc_attr( $rz_submission->id ); ?>">
    </form>

    <?php

        if( $rz_submission->listing_type->has_plans() ) {
            $rz_submission->component->render([
                'type' => 'select-plan'
            ]);
        }

        $field_collected_ids = [];

        foreach( $rz_submission->tabs as $index => $tab ) {
            if( isset( $tab['content'] ) ) {

                $fields = [];

                foreach( $tab['content'] as $content ) {
                    if( ! in_array( $content->fields->key, $field_collected_ids ) ) {
                        $fields[] = $content;
                        $field_collected_ids[] = $content->fields->key;
                    }
                }

                $rz_submission->component->render([
                    'type' => 'fields',
                    'group' => $index,
                    'title' => $tab['title'],
                    'fields' => $fields,
                ]);

            }
        }

        $actions = $rz_submission->listing_type->get_action();

        if( $action_fields->allow_pricing ) {
            $rz_submission->component->render([
                'type' => 'pricing'
            ]);
        }

        if( $actions->has_reservation_section() ) {
            $rz_submission->component->render([
                'type' => 'reservation'
            ]);
        }

        $rz_submission->component->render([
            'type' => 'publish'
        ]);

        $rz_submission->component->render([
            'type' => 'success'
        ]);

    ?>

</div>
