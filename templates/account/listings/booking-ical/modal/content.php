<?php

use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Request\Custom_Request;
use \Routiz\Inc\Src\Form\Component as Form;
use \Routiz\Inc\Src\Listing\Listing;

$request = Request::instance();
$custom_request = new Custom_Request('input');
$form = new Form( Form::Storage_Meta );
$listing = new Listing( $request->get('id') );

if( ! $listing->id ) {
    return;
}

$success_message = false;

switch( $request->get('type') ) {
    case 'save':

        $cal_import_source = $custom_request->get('rz_ical_import_source');

        if( ! empty( $cal_import_source ) ) {

            // update external source
            update_post_meta( $listing->id, 'rz_ical_import_source', $cal_import_source );

            // synchronize
            do_action( 'routiz/ical/sync', $listing->id );

            // display success message
            $success_message = true;

        }

        break;
}

$ical_feed_url = add_query_arg( 'ical', '', get_the_permalink( $listing->id ) );

// set global post object
global $post;
$post = get_post( $listing->id, OBJECT );
setup_postdata( $post );

?>

<div class="rz-modal-container rz-scrollbar">

    <p><strong><?php esc_html_e( 'Export iCal feed url', 'routiz' ); ?></strong>:</p>

    <div class="rz-ical-export">
        <pre><?php echo esc_url( $ical_feed_url ); ?></pre>
        <a href="<?php echo esc_url( $ical_feed_url ); ?>" target="_blank">
            <i class="fas fa-external-link-alt"></i>
        </a>
    </div>

    <form class="rz-form" method="post">
        <div class="rz-grid">

            <?php

                $form->render([
                    'type' => 'repeater',
                    'id' => 'ical_import_source',
                    'name' => esc_html__( 'Import iCal from external source', 'routiz' ),
                    'button' => [
                        'label' => esc_html__('Add New Source', 'routiz')
                    ],
                    'templates' => [
                        'source' => [
                            'name' => esc_html__( 'Source', 'routiz' ),
                            'heading' => 'name',
                            'fields' => [
                                'name' => [
                                    'type' => 'text',
                                    'name' => esc_html__( 'Source Name', 'routiz' ),
                                    'col' => 6
                                ],
                                'url' => [
                                    'type' => 'text',
                                    'name' => esc_html__( 'Source URI', 'routiz' ),
                                    'col' => 6
                                ],
                            ]
                        ]
                    ]
                ]);

            ?>

        </div>
    </form>

    <?php if( $success_message ): ?>
        <div class="rz-action-success rz-block">
            <?php esc_html_e('iCalendar saved and synchronized successfully!', 'routiz'); ?>
        </div>
    <?php endif; ?>

</div>

<div class="rz-modal-footer rz--top-border rz-text-center">
    <a href="#" class="rz-button rz-button-accent rz-modal-button" data-action="ical-save" data-type="save">
        <span><?php esc_html_e( 'Save Changes and Synchronize', 'routiz' ); ?></span>
        <?php Rz()->preloader(); ?>
    </a>
</div>
