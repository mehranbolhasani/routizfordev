<?php

defined('ABSPATH') || exit;

use \Routiz\Inc\Src\Form\Component as Form;

global $comment;

$form = new Form( Form::Storage_Comment );

$listing_type_id = Rz()->get_meta( 'rz_listing_type', $comment->comment_post_ID );
$ratings = Rz()->jsoning( 'rz_review_ratings', $listing_type_id );
$comment_ratings = get_comment_meta( $comment->comment_ID, 'rz_ratings', true );

?>

<div class="rz-outer">
    <div class="rz-panel rz-ml-auto rz-mr-auto">

        <div class="rz-content">
            <section class="rz-sections">
                <aside class="rz-section">

                    <div class="rz-form">

                        <?php wp_nonce_field( 'routiz_comment', 'routiz_review_comment_nonce' ); ?>

                        <?php

                            $form->render([
                                'type' => 'tab',
                                'name' => esc_html__( 'Listing Review', 'routiz' )
                            ]);

                            if( $ratings ) {
                                foreach( $ratings as $key => $rating ) {

                                    $comment_rating_value = 0;
                                    if( array_key_exists( $rating->fields->key, $comment_ratings ) ) {
                                        $comment_rating_value = (int) $comment_ratings[ $rating->fields->key ];
                                    }

                                    $field = $form->create([
                                        'type' => 'number',
                                        'id' => sprintf( 'rating_%s', $rating->fields->key ),
                                        'name' => $rating->fields->name,
                                        'input_type' => 'stepper',
                                        'format' => '%s',
                                        'min' => 0,
                                        'max' => 5,
                                    ]);

                                    $field->props->value = $comment_rating_value;
                                    echo $field->get();

                                }
                            }else{
                                $form->render([
                                    'type' => 'heading',
                                    'description' => esc_html__('No rating criteria were found', 'routiz'),
                                ]);
                            }

                            $form->render([
                                'type' => 'tab',
                                'name' => esc_html__( 'Media Uploads', 'routiz' )
                            ]);

                            #d( get_comment_meta( $comment->comment_ID, 'rz_gallery', true ) );

                            $form->render([
                                'type' => 'upload',
                                'id' => 'gallery',
                                'name' => esc_html__( 'Review Gallery', 'routiz' ),
                                'multiple_upload' => true,
                            ]);

                        ?>

                        <div class="rz-form-group rz-col-12 rz-text-center rz-mt-3">
                            <button type="submit" class="rz-button rz-large">
                                <span><?php esc_html_e( 'Save Changes', 'routiz' ); ?></span>
                            </button>
                        </div>

                    </div>

                </aside>
            </section>
        </div>

    </div>
</div>
