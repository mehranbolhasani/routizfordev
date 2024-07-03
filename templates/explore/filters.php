<?php

defined('ABSPATH') || exit;

global $rz_explore, $rz_search_form, $rz_search_form_more;

$rz_search_form = $rz_explore->type ? $rz_explore->type->get('rz_search_form') : get_option('rz_global_search_form');
$rz_search_form_more = $rz_explore->type ? $rz_explore->type->get('rz_search_form_more') : get_option('rz_global_search_form_more');

?>

<div class="rz-dynamic rz-dynamic-filter">

    <div class="rz-search">

        <?php // if( $rz_explore->type ): ?>

            <?php do_action('routiz/explore/filters/before'); ?>

            <?php if( $rz_search_form ): ?>
                <?php $filters = Rz()->json_decode( Rz()->get_meta( 'rz_search_fields', $rz_search_form ) ); ?>
                <?php if( $filters ): ?>
                    <div class="rz-search-filter rz-search-filter--inline" data-form-id="<?php echo (int) $rz_search_form; ?>">

                        <div class="rz-search-bar">
                            <?php

                                global $rz_explore;
                                $id = 'rz_listing_region';
                                $term = null;

                                $value = $rz_explore->request->get( Rz()->unprefix( $id ) );

                                if( ! empty( $value ) ) {
                                    $term = get_term_by( 'slug', $value, $id );
                                }

                            ?>

                            <?php if( $term ): ?>
                                <div class="rz--action">
                                    <a href="<?php echo esc_url( Rz()->get_explore_page_url( [], false ) ); ?>" class="rz--close rz-action-dynamic-explore">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </div>
                            <?php endif; ?>

                            <div class="rz--title">
                                <span class="rz--name rz-ellipsis">
                                    <?php
                                        if( $term ) {
                                            echo esc_html( sprintf( '%s', $term->name ) );
                                        }else{
                                            echo esc_html_e( 'Explore', 'routiz' );
                                        }
                                    ?>
                                </span>
                            </div>

                            <div class="rz--icon-filters">
                                <a href="#" data-action="toggle-search-filters"><i class="fas fa-sliders-h"></i></a>
                            </div>

                        </div>

                        <div class="rz--content">
                            <div class="rz-search-filter-inner">

                                <?php if( current_user_can('manage_options') and ! get_option('rz_hide_edit_search_form') ): ?>
                                    <div class="rz-mb-2">
                                        <a href="<?php echo esc_url( get_edit_post_link( $rz_search_form ) ); ?>" class="rz-no-decoration rz-text-right" target="_blank">
                                            <?php esc_html_e( 'Edit search form', 'routiz' ); ?>
                                            <i class="fas fa-external-link-alt rz-ml-1"></i>
                                        </a>
                                    </div>
                                <?php endif; ?>

                                <div class="rz-form">
                                    <div class="rz-grid">

                                        <?php

                                            // hidden listing type slug
                                            $rz_explore->component->render([
                                                'type' => 'hidden',
                                                'id' => 'type',
                                            ]);

                                            // listing type filters
                                            if( ! empty( $filters ) ) {
                                                $rz_explore->component->tabs( $filters );
                                            }

                                            do_action('routiz/explore/filters/after');

                                        ?>

                                    </div>
                                </div>
                            </div>

                            <div class="rz-search-footer">

                                <div class="rz--submit">
                                    <a href="#" class="rz-button rz-button-accent rz-action-filter">
                                        <span class="fas fa-search rz-mr-1"></span>
                                        <span><?php esc_html_e( 'Search', 'routiz' ); ?></span>
                                        <?php Rz()->preloader(); ?>
                                    </a>
                                </div>

                                <?php if( $rz_search_form_more ): ?>
                                    <?php $filters_more = Rz()->json_decode( Rz()->get_meta( 'rz_search_fields', $rz_search_form_more ) ); ?>
                                    <?php if( $filters_more ): ?>

                                        <div class="">
                                            <?php $active_filters = $rz_explore->component->count_active_filters( $rz_search_form_more ); ?>
                                            <a href="#" class="rz-link rz-more-filters" data-modal="more-filters" data-params=''>
                                                <i class="fas fa-filter rz-mr-1"></i>
                                                <?php esc_html_e( 'More filters', 'routiz' ); ?>
                                                <?php if( $active_filters ): ?>
                                                    <span class="rz--active"><?php echo (int) $active_filters; ?></span>
                                                <?php endif; ?>
                                            </a>
                                        </div>

                                    <?php endif; ?>
                                <?php endif; ?>

                                <div class="rz-ml-auto">
                                    <?php
                                        $args = [];
                                        if( $rz_explore->type ) {
                                            $args['type'] = $rz_explore->type->get('rz_slug');
                                        }
                                    ?>
                                    <a href="<?php echo esc_url( Rz()->get_explore_page_url( $args, false ) ); ?>" class="rz-link rz-search-clear rz-action-dynamic-explore">
                                        <i class="fas fa-eraser rz-mr-1"></i>
                                        <?php esc_html_e( 'Clear', 'routiz' ); ?>
                                    </a>
                                </div>

                            </div>

                        </div>


                    </div>
                <?php endif; ?>
            <?php endif; ?>

        <?php // endif; ?>

    </div>

    <?php Rz()->the_template('routiz/explore/more-filters/modal'); ?>

</div>
