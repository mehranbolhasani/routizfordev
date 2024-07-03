<?php

use \Routiz\Inc\Src\Admin\Panel;

defined('ABSPATH') || exit;

$panel = Panel::instance();
$panel->form->set( $panel->form::Storage_Meta );

?>

<div class="rz-outer">
    <div class="rz-panel rz-loading" id="rz-panel" data-tab-start="general/setup" :class="{ 'rz-ready' : ready }">

        <input type="hidden" name="routiz_current_tab" v-bind:value="tab">

        <div class="rz-header">

            <div class="rz--brand">
                <h3 class="rz--name"><?php esc_html_e( 'Routiz', 'routiz' ); ?></h3>
            </div>

            <nav class="rz-nav">
                <ul>
                    <li :class="{ 'rz-active': tabMain == 'general' }">
                        <a href="#" v-on:click.prevent="tabClick('general/setup')">
                            <?php esc_html_e( 'General', 'routiz' ); ?>
                        </a>
                    </li>
                    <li :class="{ 'rz-active': tabMain == 'listing' }">
                        <a href="#" v-on:click.prevent="tabClick('listing/fields')">
                            <?php esc_html_e( 'Listing', 'routiz' ); ?>
                        </a>
                    </li>
                    <li :class="{ 'rz-active': tabMain == 'display' }">
                        <a href="#" v-on:click.prevent="tabClick('display/box')">
                            <?php esc_html_e( 'Display', 'routiz' ); ?>
                        </a>
                    </li>
                    <li :class="{ 'rz-active': tabMain == 'explore' }">
                        <a href="#" v-on:click.prevent="tabClick('explore/general')">
                            <?php esc_html_e( 'Explore', 'routiz' ); ?>
                        </a>
                    </li>

                    <li :class="{ 'rz-active': tabMain == 'monetize' }">
                        <a href="#" v-on:click.prevent="tabClick('monetize/plans')">
                            <?php esc_html_e( 'Monetize', 'routiz' ); ?>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="rz--docs">
                <!-- <a href="#" target="_blank">
                    <i class="fas fa-book"></i>
                    <span class="rz-ml-1"><?php esc_html_e( 'User Guide', 'routiz' ); ?></span>
                </a> -->
            </div>

        </div> <!-- rz-header -->

        <div class="rz-sub-navs">

            <!-- general -->
            <ul class="rz-sub-nav rz-bg" v-if="tabMain == 'general'">
                <li :class="{ 'rz-active': tabSub == 'setup' }">
                    <a href="#" v-on:click.prevent="tabClick('general/setup')">
                        <?php esc_html_e( 'Setup', 'routiz' ); ?>
                    </a>
                </li>
                <li :class="{ 'rz-active': tabSub == 'submission' }">
                    <a href="#" v-on:click.prevent="tabClick('general/submission')">
                        <?php esc_html_e( 'Submission', 'routiz' ); ?>
                    </a>
                </li>
            </ul>

            <!-- listing -->
            <ul class="rz-sub-nav rz-bg" v-if="tabMain == 'listing'">
                <li :class="{ 'rz-active': tabSub == 'fields' }">
                    <a href="#" v-on:click.prevent="tabClick('listing/fields')">
                        <?php esc_html_e( 'Fields', 'routiz' ); ?>
                    </a>
                </li>
                <li :class="{ 'rz-active': tabSub == 'action' }">
                    <a href="#" v-on:click.prevent="tabClick('listing/action')">
                        <?php esc_html_e( 'Action', 'routiz' ); ?>
                    </a>
                </li>
                <li :class="{ 'rz-active': tabSub == 'pricing' }">
                    <a href="#" v-on:click.prevent="tabClick('listing/pricing')">
                        <?php esc_html_e( 'Pricing', 'routiz' ); ?>
                    </a>
                </li>
                <li :class="{ 'rz-active': tabSub == 'reservations' }">
                    <a href="#" v-on:click.prevent="tabClick('listing/reservations')">
                        <?php esc_html_e( 'Reservations', 'routiz' ); ?>
                    </a>
                </li>
                <li :class="{ 'rz-active': tabSub == 'reviews' }">
                    <a href="#" v-on:click.prevent="tabClick('listing/reviews')">
                        <?php esc_html_e( 'Reviews', 'routiz' ); ?>
                    </a>
                </li>
                <li :class="{ 'rz-active': tabSub == 'nearby' }">
                    <a href="#" v-on:click.prevent="tabClick('listing/nearby')">
                        <?php esc_html_e( 'Nearby', 'routiz' ); ?>
                    </a>
                </li>
                <li :class="{ 'rz-active': tabSub == 'similar' }">
                    <a href="#" v-on:click.prevent="tabClick('listing/similar')">
                        <?php esc_html_e( 'Similar', 'routiz' ); ?>
                    </a>
                </li>
            </ul>

            <!-- display -->
            <ul class="rz-sub-nav rz-bg" v-if="tabMain == 'display'">
                <li :class="{ 'rz-active': tabSub == 'box' }">
                    <a href="#" v-on:click.prevent="tabClick('display/box')">
                        <?php esc_html_e( 'Listing Box', 'routiz' ); ?>
                    </a>
                </li>
                <li :class="{ 'rz-active': tabSub == 'single' }">
                    <a href="#" v-on:click.prevent="tabClick('display/single')">
                        <?php esc_html_e( 'Single Page', 'routiz' ); ?>
                    </a>
                </li>
                <li :class="{ 'rz-active': tabSub == 'marker' }">
                    <a href="#" v-on:click.prevent="tabClick('display/marker')">
                        <?php esc_html_e( 'Map Marker', 'routiz' ); ?>
                    </a>
                </li>
                <li :class="{ 'rz-active': tabSub == 'explore' }">
                    <a href="#" v-on:click.prevent="tabClick('display/explore')">
                        <?php esc_html_e( 'Explore Page', 'routiz' ); ?>
                    </a>
                </li>
            </ul>

            <!-- explore -->
            <ul class="rz-sub-nav rz-bg" v-if="tabMain == 'explore'">
                <li :class="{ 'rz-active': tabSub == 'general' }">
                    <a href="#" v-on:click.prevent="tabClick('explore/general')">
                        <?php esc_html_e( 'General', 'routiz' ); ?>
                    </a>
                </li>
            </ul>

            <!-- monetize -->
            <ul class="rz-sub-nav rz-bg" v-if="tabMain == 'monetize'">
                <li :class="{ 'rz-active': tabSub == 'plans' }">
                    <a href="#" v-on:click.prevent="tabClick('monetize/plans')">
                        <?php esc_html_e( 'Plans', 'routiz' ); ?>
                    </a>
                </li>
                <li :class="{ 'rz-active': tabSub == 'promotions' }">
                    <a href="#" v-on:click.prevent="tabClick('monetize/promotions')">
                        <?php esc_html_e( 'Promotions', 'routiz' ); ?>
                    </a>
                </li>
                <li :class="{ 'rz-active': tabSub == 'payments' }">
                    <a href="#" v-on:click.prevent="tabClick('monetize/payments')">
                        <?php esc_html_e( 'Payments', 'routiz' ); ?>
                    </a>
                </li>
                <li :class="{ 'rz-active': tabSub == 'banner' }">
                    <a href="#" v-on:click.prevent="tabClick('monetize/banner')">
                        <?php esc_html_e( 'Banner Space', 'routiz' ); ?>
                    </a>
                </li>
            </ul>

        </div>

        <div class="rz-content">

            <section class="rz-sections" :class="{'rz-none': tabMain !== 'general'}">

                <aside class="rz-section rz-section-large" :class="{'rz-none': tabSub !== 'setup'}">

                    <div class="rz-panel-heading">
                        <h3 class="rz-title"><?php esc_html_e( 'General', 'routiz' ); ?></h3>
                    </div>

                    <div class="rz-form">
                        <div class="rz-grid">
                            <?php

                                $panel->form->render([
                                    'type' => 'text',
                                    'id' => 'name',
                                    'name' => esc_html__('Listing Singular Name', 'routiz'),
                                    'col' => 4,
                                ]);

                                $panel->form->render([
                                    'type' => 'text',
                                    'id' => 'name_plural',
                                    'name' => esc_html__('Listing Plural Name', 'routiz'),
                                    'col' => 4,
                                ]);

                                $panel->form->render([
                                    'type' => 'key',
                                    'id' => 'slug',
                                    'name' => esc_html__('Listing Slug', 'routiz'),
                                    'placeholder' => 'listing-slug',
                                    'col' => 4,
                                    'defined' => false,
                                ]);

                                $panel->form->render([
                                    'type' => 'icon',
                                    'id' => 'icon',
                                    'name' => esc_html__('Listing Icon', 'routiz'),
                                ]);

                                $panel->form->render([
                                    'type' => 'checkbox',
                                    'id' => 'json_ld',
                                    'name' => esc_html__('Enable Json-ld data', 'routiz'),
                                    'description' => esc_html__('Structured data is a standardized format for providing information about a page and classifying the page content', 'routiz'),
                                    'value' => true
                                ]);

                                $panel->form->render([
                                    'type' => 'checkbox',
                                    'id' => 'hide_global',
                                    'name' => esc_html__('Hide from Global Search', 'routiz'),
                                    'description' => esc_html__('This option will hide the listing type for the explore page, when no listing type is selected', 'routiz'),
                                ]);

                                $panel->form->render([
                                    'type' => 'checkbox',
                                    'id' => 'disable_user_submission',
                                    'name' => esc_html__('Disable user submission', 'routiz'),
                                ]);

                            ?>

                            <div class="rz-form-group rz-col-12 rz-text-center rz-mt-3">
                                <button type="submit" class="rz-button rz-large">
                                    <span><?php esc_html_e( 'Save Changes', 'routiz' ); ?></span>
                                </button>
                            </div>

                        </div>
                    </div>

                </aside>

                <aside class="rz-section rz-section-large" :class="{'rz-none': tabSub !== 'submission'}">

                    <div class="rz-panel-heading">
                        <h3 class="rz-title"><?php esc_html_e( 'General', 'routiz' ); ?></h3>
                    </div>

                    <div class="rz-form">
                        <div class="rz-grid">
                            <?php

                                $panel->form->render([
                                    'type' => 'checkbox',
                                    'id' => 'requires_admin_approval',
                                    'name' => esc_html__( 'Requires Admin Approval', 'routiz' ),
                                    'description' => esc_html__( 'Listing submissions under this group will be inserted as "draft" and will be pending for admin approval in order to be visible.', 'routiz' ),
                                ]);

                                $panel->form->render([
                                    'type' => 'checkbox',
                                    'id' => 'requires_admin_approval_after_update',
                                    'name' => esc_html__( 'Requires Admin Approval after Update', 'routiz' ),
                                    'description' => esc_html__( 'After user update the status of the listing will be changed to "Pending".', 'routiz' ),
                                ]);

                                $panel->form->render([
                                    'type' => 'checkbox',
                                    'id' => 'gallery_as_featured',
                                    'name' => esc_html__( 'Use the gallery first image as featured image', 'routiz' ),
                                ]);

                            ?>

                            <div class="rz-form-group rz-col-12 rz-text-center rz-mt-3">
                                <button type="submit" class="rz-button rz-large">
                                    <span><?php esc_html_e( 'Save Changes', 'routiz' ); ?></span>
                                </button>
                            </div>

                        </div>

                    </div>

                </aside>

            </section>

            <section class="rz-sections" :class="{'rz-none': tabMain !== 'listing'}">

                <aside class="rz-section rz-section-large" :class="{'rz-none': tabSub !== 'fields'}">

                    <div class="rz-panel-heading">
                        <h3 class="rz-title"><?php esc_html_e( 'Fields', 'routiz' ); ?></h3>
                        <p>
                            <?php esc_html_e( 'Customize your listing fields. Organize and group multiple field into sections using the "Tab" for better understanding. Every group will appear as a new step in the submission form.', 'routiz' ); ?>
                        </p>
                    </div>

                    <div class="rz-form">
                        <div class="rz-grid">
                            <?php

                                $panel->form->render([
                                    'type' => 'repeater',
                                    'id' => 'fields',
                                    'name' => esc_html__('Fields', 'routiz'),
                                    'templates' => [

                                        /*
                                         * plain text
                                         *
                                         */
                                        'plain_text' => [
                                            'name' => esc_html__( 'Plain Text ( Static )', 'routiz' ),
                                            'heading' => 'name',
                                            'fields' => [
                                                'name' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Name', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'key' => [
                                                    'type' => 'key',
                                                    'name' => esc_html__( 'Key', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'content' => [
                                                    'type' => 'textarea',
                                                    'name' => esc_html__( 'Content', 'routiz' ),
                                                ],
                                                'is_submit_form' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Show in Submission Form', 'routiz' ),
                                                    'value' => true,
                                                    'col' => 6
                                                ],
                                                'is_admin_page' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Show in Admin Edit Page', 'routiz' ),
                                                    'value' => true,
                                                    'col' => 6
                                                ],
                                                'col' => [
                                                    'type' => 'select',
                                                    'name' => esc_html__( 'Column Size', 'routiz' ),
                                                    'value' => 12,
                                                    'options' => [
                                                        12 => esc_html__('100%', 'routiz'),
                                                        6 => esc_html__('50%', 'routiz'),
                                                        4 => esc_html__('25%', 'routiz'),
                                                        3 => esc_html__('20%', 'routiz'),
                                                    ],
                                                    'allow_empty' => false,
                                                    'col' => 6
                                                ]
                                            ]
                                        ],

                                        /*
                                         * text
                                         *
                                         */
                                        'text' => [
                                            'name' => esc_html__( 'Text', 'routiz' ),
                                            'heading' => 'name',
                                            'fields' => [
                                                'name' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Name', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'key' => [
                                                    'type' => 'key',
                                                    'name' => esc_html__( 'Key', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'placeholder' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Placeholder', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'description' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Description', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'required' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Required Field', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'is_submit_form' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Show in Submission Form', 'routiz' ),
                                                    'value' => true,
                                                    'col' => 6
                                                ],
                                                'is_admin_page' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Show in Admin Edit Page', 'routiz' ),
                                                    'value' => true,
                                                    'col' => 6
                                                ],
                                                'col' => [
                                                    'type' => 'select',
                                                    'name' => esc_html__( 'Column Size', 'routiz' ),
                                                    'value' => 12,
                                                    'options' => [
                                                        12 => esc_html__('100%', 'routiz'),
                                                        6 => esc_html__('50%', 'routiz'),
                                                        4 => esc_html__('25%', 'routiz'),
                                                        3 => esc_html__('20%', 'routiz'),
                                                    ],
                                                    'allow_empty' => false,
                                                    'col' => 6
                                                ]
                                            ]
                                        ],

                                        /*
                                         * textarea
                                         *
                                         */
                                        'textarea' => [
                                            'name' => esc_html__( 'Textarea', 'routiz' ),
                                            'heading' => 'name',
                                            'fields' => [
                                                'name' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Name', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'key' => [
                                                    'type' => 'key',
                                                    'name' => esc_html__( 'Key', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'placeholder' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Placeholder', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'description' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Description', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'required' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Required Field', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'is_submit_form' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Show in Submission Form', 'routiz' ),
                                                    'value' => true,
                                                    'col' => 6
                                                ],
                                                'is_admin_page' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Show in Admin Edit Page', 'routiz' ),
                                                    'value' => true,
                                                    'col' => 6
                                                ],
                                                'col' => [
                                                    'type' => 'select',
                                                    'name' => esc_html__( 'Column Size', 'routiz' ),
                                                    'value' => 12,
                                                    'options' => [
                                                        12 => esc_html__('100%', 'routiz'),
                                                        6 => esc_html__('50%', 'routiz'),
                                                        4 => esc_html__('25%', 'routiz'),
                                                        3 => esc_html__('20%', 'routiz'),
                                                    ],
                                                    'allow_empty' => false,
                                                    'col' => 6
                                                ]
                                            ]
                                        ],

                                        /*
                                         * editor
                                         *
                                         */
                                        'editor' => [
                                            'name' => esc_html__( 'Editor', 'routiz' ),
                                            'heading' => 'name',
                                            'fields' => [
                                                'name' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Name', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'key' => [
                                                    'type' => 'key',
                                                    'name' => esc_html__( 'Key', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'description' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Description', 'routiz' ),
                                                ],
                                                'required' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Required Field', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'is_submit_form' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Show in Submission Form', 'routiz' ),
                                                    'value' => true,
                                                    'col' => 6
                                                ],
                                                'is_admin_page' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Show in Admin Edit Page', 'routiz' ),
                                                    'value' => true,
                                                    'col' => 6
                                                ],
                                                'col' => [
                                                    'type' => 'select',
                                                    'name' => esc_html__( 'Column Size', 'routiz' ),
                                                    'value' => 12,
                                                    'options' => [
                                                        12 => esc_html__('100%', 'routiz'),
                                                        6 => esc_html__('50%', 'routiz'),
                                                        4 => esc_html__('25%', 'routiz'),
                                                        3 => esc_html__('20%', 'routiz'),
                                                    ],
                                                    'allow_empty' => false,
                                                    'col' => 6
                                                ]
                                            ]
                                        ],

                                         /*
                                         * faq
                                         *
                                         */
                                        'faq' => [
                                            'name' => esc_html__( 'Faq', 'routiz' ),
                                            'heading' => 'name',
                                            'fields' => [
                                                'name' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Name', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'key' => [
                                                    'type' => 'key',
                                                    'name' => esc_html__( 'Key', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'description' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Description', 'routiz' ),
                                                ],
                                                'required' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Required Field', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'is_submit_form' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Show in Submission Form', 'routiz' ),
                                                    'value' => true,
                                                    'col' => 6
                                                ],
                                                'is_admin_page' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Show in Admin Edit Page', 'routiz' ),
                                                    'value' => true,
                                                    'col' => 6
                                                ],
                                                'col' => [
                                                    'type' => 'select',
                                                    'name' => esc_html__( 'Column Size', 'routiz' ),
                                                    'value' => 12,
                                                    'options' => [
                                                        12 => esc_html__('100%', 'routiz'),
                                                        6 => esc_html__('50%', 'routiz'),
                                                        4 => esc_html__('25%', 'routiz'),
                                                        3 => esc_html__('20%', 'routiz'),
                                                    ],
                                                    'allow_empty' => false,
                                                    'col' => 6
                                                ]
                                            ]
                                        ],

                                        /*
                                         * upload
                                         *
                                         */
                                        'upload' => [
                                            'name' => esc_html__( 'Upload', 'routiz' ),
                                            'heading' => 'name',
                                            'fields' => [
                                                'name' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Name', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'key' => [
                                                    'type' => 'key',
                                                    'name' => esc_html__( 'Key', 'routiz' ),
                                                    'col' => 6,
                                                    'value' => 'rz_gallery'
                                                ],
                                                'multiple_upload' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Allow Multiple Uploads?', 'routiz' ),
                                                ],
                                                'upload_type' => [
                                                    'type' => 'select',
                                                    'name' => esc_html__( 'Upload Type', 'routiz' ),
                                                    'value' => 'image',
                                                    'options' => [
                                                        'image' => esc_html__( 'Image', 'routiz' ),
                                                        'file' => esc_html__( 'File', 'routiz' ),
                                                    ],
                                                    'allow_empty' => false,
                                                    'col' => 6
                                                ],
                                                'description' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Description', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'required' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Required Field', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'is_submit_form' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Show in Submission Form', 'routiz' ),
                                                    'value' => true,
                                                    'col' => 6
                                                ],
                                                'is_admin_page' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Show in Admin Edit Page', 'routiz' ),
                                                    'value' => true,
                                                    'col' => 6
                                                ],
                                                'col' => [
                                                    'type' => 'select',
                                                    'name' => esc_html__( 'Column Size', 'routiz' ),
                                                    'value' => 12,
                                                    'options' => [
                                                        12 => esc_html__('100%', 'routiz'),
                                                        6 => esc_html__('50%', 'routiz'),
                                                        4 => esc_html__('25%', 'routiz'),
                                                        3 => esc_html__('20%', 'routiz'),
                                                    ],
                                                    'allow_empty' => false,
                                                    'col' => 6
                                                ]
                                            ]
                                        ],

                                        /*
                                         * number
                                         *
                                         */
                                        'number' => [
                                            'name' => esc_html__( 'Number', 'routiz' ),
                                            'heading' => 'name',
                                            'fields' => [
                                                'name' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Name', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'key' => [
                                                    'type' => 'key',
                                                    'name' => esc_html__( 'Key', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'input_type' => [
                                                    'type' => 'select',
                                                    'name' => esc_html__('Input Type', 'routiz'),
                                                    'value' => 'number',
                                                    'options' => [
                                                        'number' => esc_html__('Number', 'routiz'),
                                                        'range' => esc_html__('Range', 'routiz'),
                                                        'stepper' => esc_html__('Stepper', 'routiz'),
                                                    ],
                                                    'col' => 6
                                                ],
                                                'description' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Description', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'style' => [
                                                    'type' => 'select',
                                                    'name' => esc_html__( 'Style', 'routiz' ),
                                                    'options' => [
                                                        'v1' => 'V1',
                                                        'v2' => 'V2',
                                                    ],
                                                    'value' => 'v1',
                                                    'allow_empty' => false,
                                                    'dependency' => [
                                                        'id' => 'input_type',
                                                        'compare' => '=',
                                                        'value' => 'stepper',
                                                    ],
                                                ],
                                                'format' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Format', 'routiz' ),
                                                    'value' => '%s',
                                                    'dependency' => [
                                                        'id' => 'input_type',
                                                        'compare' => '=',
                                                        'value' => [ 'range', 'stepper' ],
                                                    ],
                                                ],
                                                'min' => [
                                                    'type' => 'number',
                                                    'name' => esc_html__( 'Minimum Value', 'routiz' ),
                                                    'col' => 6,
                                                ],
                                                'max' => [
                                                    'type' => 'number',
                                                    'name' => esc_html__( 'Maximum Value', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'step' => [
                                                    'type' => 'number',
                                                    'name' => esc_html__( 'Step Size', 'routiz' ),
                                                    'step' => 0.01,
                                                    'col' => 6
                                                ],
                                                'value' => [
                                                    'type' => 'number',
                                                    'name' => esc_html__( 'Value', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'required' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Required Field', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'is_submit_form' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Show in Submission Form', 'routiz' ),
                                                    'value' => true,
                                                    'col' => 6
                                                ],
                                                'is_admin_page' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Show in Admin Edit Page', 'routiz' ),
                                                    'value' => true,
                                                    'col' => 6
                                                ],
                                                'col' => [
                                                    'type' => 'select',
                                                    'name' => esc_html__( 'Column Size', 'routiz' ),
                                                    'value' => 12,
                                                    'options' => [
                                                        12 => esc_html__('100%', 'routiz'),
                                                        6 => esc_html__('50%', 'routiz'),
                                                        4 => esc_html__('25%', 'routiz'),
                                                        3 => esc_html__('20%', 'routiz'),
                                                    ],
                                                    'allow_empty' => false,
                                                    'col' => 6
                                                ]
                                            ]
                                        ],

                                        /*
                                         * checkbox
                                         *
                                         */
                                        'checkbox' => [
                                            'name' => esc_html__( 'Checkbox', 'routiz' ),
                                            'heading' => 'name',
                                            'fields' => [
                                                'name' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Name', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'key' => [
                                                    'type' => 'key',
                                                    'name' => esc_html__( 'Key', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'description' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Description', 'routiz' ),
                                                ],
                                                'required' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Required Field', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'is_submit_form' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Show in Submission Form', 'routiz' ),
                                                    'value' => true,
                                                    'col' => 6
                                                ],
                                                'is_admin_page' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Show in Admin Edit Page', 'routiz' ),
                                                    'value' => true,
                                                    'col' => 6
                                                ],
                                                'col' => [
                                                    'type' => 'select',
                                                    'name' => esc_html__( 'Column Size', 'routiz' ),
                                                    'value' => 12,
                                                    'options' => [
                                                        12 => esc_html__('100%', 'routiz'),
                                                        6 => esc_html__('50%', 'routiz'),
                                                        4 => esc_html__('25%', 'routiz'),
                                                        3 => esc_html__('20%', 'routiz'),
                                                    ],
                                                    'allow_empty' => false,
                                                    'col' => 6
                                                ]
                                            ]
                                        ],

                                        /*
                                         * choice
                                         *
                                         */
                                        'choice' => [
                                            'name' => esc_html__( 'Choice', 'routiz' ),
                                            'heading' => 'name',
                                            'fields' => [
                                                'name' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Name', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'key' => [
                                                    'type' => 'key',
                                                    'name' => esc_html__( 'Key', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'choice' => [
                                                    'type' => 'select',
                                                    'name' => esc_html__('Type', 'routiz'),
                                                    'value' => 'select',
                                                    'options' => [
                                                        'select' => esc_html__('Select', 'routiz'),
                                                        'select2' => esc_html__('Select2', 'routiz'),
                                                        'radio' => esc_html__('Radio Buttons', 'routiz'),
                                                        // 'radio_image' => esc_html__('Radio Images', 'routiz'),
                                                        'buttons' => esc_html__('Buttons', 'routiz'),
                                                        'checklist' => esc_html__('Checklist', 'routiz'),
                                                    ],
                                                    'allow_empty' => false,
                                                    'col' => 6
                                                ],
                                                'description' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Description', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'multiple' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Multiple Choices?', 'routiz' ),
                                                    'dependency' => [
                                                        'id' => 'choice',
                                                        'compare' => '=',
                                                        'value' => [ 'select', 'select2' ],
                                                    ],
                                                ],
                                                'options' => [
                                                    'type' => 'options',
                                                    'name' => esc_html__( 'Options', 'routiz' ),
                                                ],
                                                'required' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Required Field', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'is_submit_form' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Show in Submission Form', 'routiz' ),
                                                    'value' => true,
                                                    'col' => 6
                                                ],
                                                'is_admin_page' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Show in Admin Edit Page', 'routiz' ),
                                                    'value' => true,
                                                    'col' => 6
                                                ],
                                                'col' => [
                                                    'type' => 'select',
                                                    'name' => esc_html__( 'Column Size', 'routiz' ),
                                                    'value' => 12,
                                                    'options' => [
                                                        12 => esc_html__('100%', 'routiz'),
                                                        6 => esc_html__('50%', 'routiz'),
                                                        4 => esc_html__('25%', 'routiz'),
                                                        3 => esc_html__('20%', 'routiz'),
                                                    ],
                                                    'allow_empty' => false,
                                                    'col' => 6
                                                ]
                                            ]
                                        ],

                                        /*
                                         * taxonomy
                                         *
                                         */
                                        'taxonomy' => [
                                            'name' => esc_html__( 'Taxonomy', 'routiz' ),
                                            'heading' => 'name',
                                            'fields' => [
                                                'name' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Name', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'key' => [
                                                    'type' => 'taxonomy_types',
                                                    'name' => esc_html__('Taxonomy', 'routiz'),
                                                    'col' => 6,
                                                    'allow_empty' => false
                                                ],
                                                'display' => [
                                                    'type' => 'select',
                                                    'name' => esc_html__('Type', 'routiz'),
                                                    'value' => 'select',
                                                    'options' => [
                                                        'select' => esc_html__('Select', 'routiz'),
                                                        'select2' => esc_html__('Select2', 'routiz'),
                                                        'radio' => esc_html__('Radio Buttons', 'routiz'),
                                                        'buttons' => esc_html__('Buttons', 'routiz'),
                                                        'checklist' => esc_html__('Checklist', 'routiz'),
                                                    ],
                                                    'allow_empty' => false,
                                                    'col' => 6
                                                ],
                                                'description' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Description', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                // TODO: fix this, when multiple is check and is radio button, it worn\'t work, because it is not single
                                                'multiple' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Multiple Choices?', 'routiz' ),
                                                    'dependency' => [
                                                        'id' => 'display',
                                                        'compare' => '=',
                                                        'value' => [ 'select', 'select2' ],
                                                        'style' => 'rz-opacity-30',
                                                    ],
                                                ],
                                                'required' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Required Field', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'is_submit_form' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Show in Submission Form', 'routiz' ),
                                                    'value' => true,
                                                    'col' => 6
                                                ],
                                                'is_admin_page' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Show in Admin Edit Page', 'routiz' ),
                                                    'value' => true,
                                                    'col' => 6
                                                ],
                                                'col' => [
                                                    'type' => 'select',
                                                    'name' => esc_html__( 'Column Size', 'routiz' ),
                                                    'value' => 12,
                                                    'options' => [
                                                        12 => esc_html__('100%', 'routiz'),
                                                        6 => esc_html__('50%', 'routiz'),
                                                        4 => esc_html__('25%', 'routiz'),
                                                        3 => esc_html__('20%', 'routiz'),
                                                    ],
                                                    'allow_empty' => false,
                                                    'col' => 6
                                                ]
                                            ]
                                        ],

                                        /*
                                         * map
                                         *
                                         */
                                        'map' => [
                                            'name' => esc_html__( 'Location', 'routiz' ),
                                            'heading' => 'name',
                                            'fields' => [
                                                'name' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Name', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'key' => [
                                                    'type' => 'key',
                                                    'name' => esc_html__( 'Key', 'routiz' ),
                                                    'value' => 'rz_location',
                                                    'col' => 6
                                                ],
                                                'description' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Description', 'routiz' ),
                                                ],
                                                'default' => [
                                                    'type' => 'map',
                                                    'name' => esc_html__( 'Default Location', 'routiz' ),
                                                ],
                                                'required' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Required Field', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'is_submit_form' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Show in Submission Form', 'routiz' ),
                                                    'value' => true,
                                                    'col' => 6
                                                ],
                                                'is_admin_page' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Show in Admin Edit Page', 'routiz' ),
                                                    'value' => true,
                                                    'col' => 6
                                                ],
                                                'col' => [
                                                    'type' => 'select',
                                                    'name' => esc_html__( 'Column Size', 'routiz' ),
                                                    'value' => 12,
                                                    'options' => [
                                                        12 => esc_html__('100%', 'routiz'),
                                                        6 => esc_html__('50%', 'routiz'),
                                                        4 => esc_html__('25%', 'routiz'),
                                                        3 => esc_html__('20%', 'routiz'),
                                                    ],
                                                    'allow_empty' => false,
                                                    'col' => 6
                                                ]
                                            ]
                                        ],

                                        /*
                                         * open hours
                                         *
                                         */
                                        'open_hours' => [
                                            'name' => esc_html__( 'Open Hours', 'routiz' ),
                                            'heading' => 'name',
                                            'fields' => [
                                                'name' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Name', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'description' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Description', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'key' => [
                                                    'type' => 'hidden',
                                                    'value' => 'rz_open_hours',
                                                ],
                                                'required' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Required Field', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'is_submit_form' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Show in Submission Form', 'routiz' ),
                                                    'value' => true,
                                                    'col' => 6
                                                ],
                                                'is_admin_page' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Show in Admin Edit Page', 'routiz' ),
                                                    'value' => true,
                                                    'col' => 6
                                                ],
                                            ]
                                        ],

                                        /*
                                         * menu
                                         *
                                         */
                                        'menu' => [
                                            'name' => esc_html__( 'Menu', 'routiz' ),
                                            'heading' => 'name',
                                            'fields' => [
                                                'name' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Name', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'description' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Description', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'key' => [
                                                    'type' => 'hidden',
                                                    'value' => 'rz_menu',
                                                ],
                                                'required' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Required Field', 'routiz' ),
                                                    'col' => 6
                                                ],
                                                'is_submit_form' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Show in Submission Form', 'routiz' ),
                                                    'value' => true,
                                                    'col' => 6
                                                ],
                                                'is_admin_page' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Show in Admin Edit Page', 'routiz' ),
                                                    'value' => true,
                                                    'col' => 6
                                                ],
                                            ]
                                        ],

                                        /*
                                         * tab
                                         *
                                         */
                                        'tab' => [
                                            'name' => esc_html__( 'Tab', 'routiz' ),
                                            'heading' => 'name',
                                            'fields' => [
                                                'name' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Name', 'routiz' ),
                                                ],
                                                'is_admin_page' => [
                                                    'type' => 'hidden',
                                                    'name' => esc_html__( 'Show in Admin Edit Page', 'routiz' ),
                                                    'value' => true,
                                                ],
                                            ]
                                        ],

                                    ]
                                ]);

                            ?>

                            <div class="rz-form-group rz-col-12 rz-text-center rz-mt-3">
                                <button type="submit" class="rz-button rz-large">
                                    <span><?php esc_html_e( 'Save Changes', 'routiz' ); ?></span>
                                </button>
                            </div>

                        </div>
                    </div>
                </aside>

                <aside class="rz-section rz-section-large" :class="{'rz-none': tabSub !== 'action'}">

                    <div class="rz-panel-heading">
                        <h3 class="rz-title"><?php esc_html_e('Action', 'routiz'); ?></h3>
                    </div>

                    <div class="rz-form">
                        <div class="rz-grid">

                            <?php

                                $panel->form->render([
                                    'type' => 'repeater',
                                    'id' => 'action_types',
                                    'name' => esc_html__('Action Types', 'routiz'),
                                    'button' => [
                                        'label' => esc_html__('Add Action Type', 'routiz')
                                    ],
                                    'templates' => [

                                        /*
                                         * plain text
                                         *
                                         */
                                        'plain_text' => [
                                            'name' => esc_html__('Plain Text', 'routiz'),
                                            'fields' => [
                                                'text' => [
                                                    'type' => 'textarea',
                                                    'name' => esc_html__( 'Content', 'routiz' ),
                                                ]
                                            ]
                                        ],

                                        /*
                                         * button
                                         *
                                         */
                                        'button' => [
                                            'name' => esc_html__('Button', 'routiz'),
                                            'fields' => [
                                                'enable_author' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Display Author Details', 'routiz' ),
                                                    'value' => true,
                                                ],
                                                'botton_target_blank' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Open in a New Tab?', 'routiz' ),
                                                ],
                                                'request_login' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Request Login?', 'routiz' ),
                                                    'description' => esc_html__( 'If you check this option, only logged-in users will be able to click the button.', 'routiz' ),
                                                ],
                                                'title' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Custom Title', 'routiz' ),
                                                ],
                                                'meta' => [
                                                    'type' => 'repeater',
                                                    'name' => esc_html__('Fields', 'routiz'),
                                                    'description' => esc_html__('Add additional fields', 'routiz'),
                                                    'button' => [
                                                        'label' => esc_html__('Add Field', 'routiz')
                                                    ],
                                                    'templates' => [

                                                        'field' => [
                                                            'name' => esc_html__('Text Field', 'routiz'),
                                                            'heading' => 'name',
                                                            'fields' => [
                                                                'name' => [
                                                                    'type' => 'text',
                                                                    'name' => esc_html__( 'Name', 'routiz' ),
                                                                    'value' => esc_html__( 'Field name', 'routiz' )
                                                                ],
                                                                'id' => [
                                                                    'type' => 'use_field',
                                                                    'name' => esc_html__('Select Field', 'routiz'),
                                                                    'description' => esc_html__('Select field to display', 'routiz'),
                                                                    'exclude' => [
                                                                        'post_title',
                                                                        'post_content',
                                                                    ],
                                                                    'include' => [
                                                                        'rz_location' => esc_html__('Address', 'routiz')
                                                                    ],
                                                                    'col' => 6
                                                                ],
                                                                'format' => [
                                                                    'type' => 'text',
                                                                    'name' => esc_html__('Format', 'routiz'),
                                                                    'description' => esc_html__('{field} will be replaced with the field value. You can add additional text, example format: "{field} bedrooms", will print "4 bedrooms".', 'routiz'),
                                                                    'value' => '{field}',
                                                                    'col' => 6
                                                                ],
                                                                'icon' => [
                                                                    'type' => 'icon',
                                                                    'name' => esc_html__( 'Icon', 'routiz' )
                                                                ],
                                                                'type' => [
                                                                    'type' => 'select',
                                                                    'name' => esc_html__('Type', 'routiz'),
                                                                    'options' => [
                                                                        'text' => esc_html__('Text', 'routiz'),
                                                                        'address' => esc_html__('Address', 'routiz'),
                                                                        'url' => esc_html__('URL', 'routiz'),
                                                                        'email' => esc_html__('Email', 'routiz'),
                                                                        'phone' => esc_html__('Phone number', 'routiz'),
                                                                        'price' => esc_html__('Price', 'routiz'),
                                                                    ],
                                                                    'allow_empty' => false
                                                                ],
                                                                'type_url_label' => [
                                                                    'type' => 'text',
                                                                    'name' => esc_html__( 'URL Text', 'routiz' ),
                                                                    'dependency' => [
                                                                        'id' => 'type',
                                                                        'compare' => '=',
                                                                        'value' => [ 'url' ],
                                                                    ],
                                                                ],
                                                            ]
                                                        ],

                                                    ],
                                                ],
                                                'show_button' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Show button', 'routiz' ),
                                                    'value' => true,
                                                ],
                                                'button_label' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Button Label', 'routiz' ),
                                                    'placeholder' => esc_html__( 'Button', 'routiz' ),
                                                ],
                                                'button_field' => [
                                                    'type' => 'use_field',
                                                    'name' => esc_html__('Select Button Field', 'routiz'),
                                                    'description' => esc_html__('Select the field you want to be used for the button url', 'routiz'),
                                                    'exclude' => [
                                                        'post_title',
                                                        'post_content',
                                                    ]
                                                ],
                                                'hide_if_field_empty' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Hide  action type if the field value is empty', 'routiz' ),
                                                ],
                                            ]
                                        ],

                                        /*
                                         * contact
                                         *
                                         */
                                        'contact' => [
                                            'name' => esc_html__('Contact', 'routiz'),
                                            'fields' => [
                                                'enable_author' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Display Author Details', 'routiz' ),
                                                    'value' => true,
                                                ]
                                            ]
                                        ],

                                        /*
                                         * location
                                         *
                                         */
                                        'location' => [
                                            'name' => esc_html__('Location', 'routiz'),
                                            'fields' => [
                                                'fields' => [

                                                    'type' => 'repeater',
                                                    'id' => 'action_type_location_fields',
                                                    'name' => esc_html__('Location Fields', 'routiz'),
                                                    'description' => esc_html__('Add additional fields', 'routiz'),
                                                    'button' => [
                                                        'label' => esc_html__('Add Field', 'routiz')
                                                    ],
                                                    'templates' => [

                                                        'field' => [
                                                            'name' => esc_html__('Text Field', 'routiz'),
                                                            'heading' => 'name',
                                                            'fields' => [
                                                                'name' => [
                                                                    'type' => 'text',
                                                                    'name' => esc_html__( 'Name', 'routiz' ),
                                                                    'value' => esc_html__( 'Field name', 'routiz' )
                                                                ],
                                                                'id' => [
                                                                    'type' => 'use_field',
                                                                    'name' => esc_html__('Select Field', 'routiz'),
                                                                    'description' => esc_html__('Select field to display', 'routiz'),
                                                                    'exclude' => [
                                                                        'post_title',
                                                                        'post_content',
                                                                    ],
                                                                    'include' => [
                                                                        'rz_location' => esc_html__('Address', 'routiz')
                                                                    ],
                                                                    'col' => 6
                                                                ],
                                                                'format' => [
                                                                    'type' => 'text',
                                                                    'name' => esc_html__('Format', 'routiz'),
                                                                    'description' => esc_html__('{field} will be replaced with the field value. You can add additional text, example format: "{field} bedrooms", will print "4 bedrooms".', 'routiz'),
                                                                    'value' => '{field}',
                                                                    'col' => 6
                                                                ],
                                                                'icon' => [
                                                                    'type' => 'icon',
                                                                    'name' => esc_html__( 'Icon', 'routiz' )
                                                                ],
                                                                'type' => [
                                                                    'type' => 'select',
                                                                    'name' => esc_html__('Type', 'routiz'),
                                                                    'options' => [
                                                                        'text' => esc_html__('Text', 'routiz'),
                                                                        'address' => esc_html__('Address', 'routiz'),
                                                                        'url' => esc_html__('URL', 'routiz'),
                                                                        'email' => esc_html__('Email', 'routiz'),
                                                                        'phone' => esc_html__('Phone number', 'routiz'),
                                                                        'price' => esc_html__('Price', 'routiz'),
                                                                    ],
                                                                    'allow_empty' => false
                                                                ],
                                                                'type_url_label' => [
                                                                    'type' => 'text',
                                                                    'name' => esc_html__( 'URL Text', 'routiz' ),
                                                                    'dependency' => [
                                                                        'id' => 'type',
                                                                        'compare' => '=',
                                                                        'value' => [ 'url' ],
                                                                    ],
                                                                ],
                                                            ]
                                                        ],

                                                    ],

                                                ]
                                            ]
                                        ],

                                        /*
                                         * open hours
                                         *
                                         */
                                        'open_hours' => [
                                            'name' => esc_html__('Open Hours', 'routiz'),
                                            'fields' => [
                                                'heading' => [
                                                    'type' => 'heading',
                                                    'name' => esc_html__( 'Open Hours', 'routiz' ),
                                                    'description' => esc_html__( 'This action type requires to create "Open Hors" field by going to "Listing > Fields". If the fields empty ( the user left blank ), the whole action type won\'t appear in the listing details page.', 'routiz' ),
                                                ],
                                                'is_expanded' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Expanded by Default', 'routiz' ),
                                                ]
                                            ]
                                        ],

                                        /*
                                         * application
                                         *
                                         */
                                        'application' => [
                                            'name' => esc_html__('Application', 'routiz'),
                                            'fields' => [
                                                'enable_author' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__( 'Display Author Details', 'routiz' ),
                                                    'value' => true,
                                                ],
                                                'application_button_label' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Application Button Label', 'routiz' ),
                                                    'placeholder' => esc_html__( 'Send Application', 'routiz' ),
                                                ],
                                                'form' => [
                                                    'type' => 'repeater',
                                                    'name' => esc_html__('Application Form Fields', 'routiz'),
                                                    'description' => esc_html__('Create your application form. Specify all the fields that you need to display on the front-end.', 'routiz'),
                                                    'button' => [
                                                        'label' => esc_html__('Add Form Field', 'routiz')
                                                    ],
                                                    'templates' => [

                                                        // text
                                                        'text' => [
                                                            'name' => esc_html__('Text', 'routiz'),
                                                            'heading' => 'name',
                                                            'fields' => [
                                                                'name' => [
                                                                    'type' => 'text',
                                                                    'name' => esc_html__( 'Name', 'routiz' ),
                                                                    'value' => esc_html__( 'Field name', 'routiz' ),
                                                                    'col' => 6
                                                                ],
                                                                'key' => [
                                                                    'type' => 'key',
                                                                    'name' => esc_html__( 'Key', 'routiz' ),
                                                                    'value' => 'field-key',
                                                                    'defined' => false,
                                                                    'col' => 6
                                                                ],
                                                                'placeholder' => [
                                                                    'type' => 'text',
                                                                    'name' => esc_html__( 'Placeholder', 'routiz' ),
                                                                    'col' => 6
                                                                ],
                                                                'description' => [
                                                                    'type' => 'text',
                                                                    'name' => esc_html__( 'Description', 'routiz' ),
                                                                    'col' => 6
                                                                ],
                                                                'required' => [
                                                                    'type' => 'checkbox',
                                                                    'name' => esc_html__( 'Required Field', 'routiz' ),
                                                                ],
                                                                'show_if_guest' => [
                                                                    'type' => 'checkbox',
                                                                    'name' => esc_html__( 'Show only when the user is not logged in', 'routiz' ),
                                                                ],
                                                                'col' => [
                                                                    'type' => 'select',
                                                                    'name' => esc_html__( 'Column Size', 'routiz' ),
                                                                    'value' => 12,
                                                                    'options' => [
                                                                        12 => esc_html__('100%', 'routiz'),
                                                                        6 => esc_html__('50%', 'routiz'),
                                                                        4 => esc_html__('25%', 'routiz'),
                                                                        3 => esc_html__('20%', 'routiz'),
                                                                    ],
                                                                    'allow_empty' => false,
                                                                ],
                                                            ]
                                                        ],

                                                        // textarea
                                                        'textarea' => [
                                                            'name' => esc_html__('Textarea', 'routiz'),
                                                            'heading' => 'name',
                                                            'fields' => [
                                                                'name' => [
                                                                    'type' => 'text',
                                                                    'name' => esc_html__( 'Name', 'routiz' ),
                                                                    'value' => esc_html__( 'Field name', 'routiz' ),
                                                                    'col' => 6
                                                                ],
                                                                'key' => [
                                                                    'type' => 'key',
                                                                    'name' => esc_html__( 'Key', 'routiz' ),
                                                                    'value' => 'field-key',
                                                                    'defined' => false,
                                                                    'col' => 6
                                                                ],
                                                                'placeholder' => [
                                                                    'type' => 'text',
                                                                    'name' => esc_html__( 'Placeholder', 'routiz' ),
                                                                    'col' => 6
                                                                ],
                                                                'description' => [
                                                                    'type' => 'text',
                                                                    'name' => esc_html__( 'Description', 'routiz' ),
                                                                    'col' => 6
                                                                ],
                                                                'required' => [
                                                                    'type' => 'checkbox',
                                                                    'name' => esc_html__( 'Required Field', 'routiz' ),
                                                                ],
                                                                'show_if_guest' => [
                                                                    'type' => 'checkbox',
                                                                    'name' => esc_html__( 'Show only when the user is not logged in', 'routiz' ),
                                                                ],
                                                                'col' => [
                                                                    'type' => 'select',
                                                                    'name' => esc_html__( 'Column Size', 'routiz' ),
                                                                    'value' => 12,
                                                                    'options' => [
                                                                        12 => esc_html__('100%', 'routiz'),
                                                                        6 => esc_html__('50%', 'routiz'),
                                                                        4 => esc_html__('25%', 'routiz'),
                                                                        3 => esc_html__('20%', 'routiz'),
                                                                    ],
                                                                    'allow_empty' => false,
                                                                ],
                                                            ]
                                                        ],

                                                        // checkbox
                                                        'checkbox' => [
                                                            'name' => esc_html__( 'Checkbox', 'routiz' ),
                                                            'heading' => 'name',
                                                            'fields' => [
                                                                'name' => [
                                                                    'type' => 'text',
                                                                    'name' => esc_html__( 'Name', 'routiz' ),
                                                                    'value' => esc_html__( 'Field name', 'routiz' ),
                                                                    'col' => 6
                                                                ],
                                                                'key' => [
                                                                    'type' => 'key',
                                                                    'name' => esc_html__( 'Key', 'routiz' ),
                                                                    'value' => 'field-key',
                                                                    'defined' => false,
                                                                    'col' => 6
                                                                ],
                                                                'description' => [
                                                                    'type' => 'text',
                                                                    'name' => esc_html__( 'Description', 'routiz' ),
                                                                ],
                                                                'required' => [
                                                                    'type' => 'checkbox',
                                                                    'name' => esc_html__( 'Required Field', 'routiz' ),
                                                                ],
                                                                'show_if_guest' => [
                                                                    'type' => 'checkbox',
                                                                    'name' => esc_html__( 'Show only when the user is not logged in', 'routiz' ),
                                                                ],
                                                                'col' => [
                                                                    'type' => 'select',
                                                                    'name' => esc_html__( 'Column Size', 'routiz' ),
                                                                    'value' => 12,
                                                                    'options' => [
                                                                        12 => esc_html__('100%', 'routiz'),
                                                                        6 => esc_html__('50%', 'routiz'),
                                                                        4 => esc_html__('25%', 'routiz'),
                                                                        3 => esc_html__('20%', 'routiz'),
                                                                    ],
                                                                    'allow_empty' => false,
                                                                    'col' => 6
                                                                ]
                                                            ]
                                                        ],

                                                        // choice
                                                        'choice' => [
                                                            'name' => esc_html__( 'Choice', 'routiz' ),
                                                            'heading' => 'name',
                                                            'fields' => [
                                                                'name' => [
                                                                    'type' => 'text',
                                                                    'name' => esc_html__( 'Name', 'routiz' ),
                                                                    'value' => esc_html__( 'Field name', 'routiz' ),
                                                                    'col' => 6
                                                                ],
                                                                'key' => [
                                                                    'type' => 'key',
                                                                    'name' => esc_html__( 'Key', 'routiz' ),
                                                                    'value' => 'field-key',
                                                                    'defined' => false,
                                                                    'col' => 6
                                                                ],
                                                                'choice' => [
                                                                    'type' => 'hidden',
                                                                    'value' => 'select'
                                                                ],
                                                                'multiple' => [
                                                                    'type' => 'checkbox',
                                                                    'name' => esc_html__( 'Multiple Choices?', 'routiz' ),
                                                                    'dependency' => [
                                                                        'id' => 'choice',
                                                                        'compare' => '=',
                                                                        'value' => [ 'select', 'select2' ],
                                                                    ],
                                                                ],
                                                                'options' => [
                                                                    'type' => 'options',
                                                                    'name' => esc_html__( 'Options', 'routiz' ),
                                                                ],
                                                                'required' => [
                                                                    'type' => 'checkbox',
                                                                    'name' => esc_html__( 'Required Field', 'routiz' ),
                                                                ],
                                                                'show_if_guest' => [
                                                                    'type' => 'checkbox',
                                                                    'name' => esc_html__( 'Show only when the user is not logged in', 'routiz' ),
                                                                ],
                                                                'col' => [
                                                                    'type' => 'select',
                                                                    'name' => esc_html__( 'Column Size', 'routiz' ),
                                                                    'value' => 12,
                                                                    'options' => [
                                                                        12 => esc_html__('100%', 'routiz'),
                                                                        6 => esc_html__('50%', 'routiz'),
                                                                        4 => esc_html__('25%', 'routiz'),
                                                                        3 => esc_html__('20%', 'routiz'),
                                                                    ],
                                                                    'allow_empty' => false,
                                                                    'col' => 6
                                                                ]
                                                            ]
                                                        ],

                                                        // upload
                                                        'upload' => [
                                                            'name' => esc_html__( 'Upload', 'routiz' ),
                                                            'heading' => 'name',
                                                            'fields' => [
                                                                'name' => [
                                                                    'type' => 'text',
                                                                    'name' => esc_html__( 'Name', 'routiz' ),
                                                                    'value' => esc_html__( 'Field name', 'routiz' ),
                                                                    'col' => 6
                                                                ],
                                                                'key' => [
                                                                    'type' => 'key',
                                                                    'name' => esc_html__( 'Key', 'routiz' ),
                                                                    'value' => 'field-key',
                                                                    'defined' => false,
                                                                    'col' => 6
                                                                ],
                                                                'multiple_upload' => [
                                                                    'type' => 'checkbox',
                                                                    'name' => esc_html__( 'Allow Multiple Uploads?', 'routiz' ),
                                                                ],
                                                                'upload_type' => [
                                                                    'type' => 'select',
                                                                    'name' => esc_html__( 'Upload Type', 'routiz' ),
                                                                    'value' => 'image',
                                                                    'options' => [
                                                                        'image' => esc_html__( 'Image', 'routiz' ),
                                                                        'file' => esc_html__( 'File', 'routiz' ),
                                                                    ],
                                                                    'allow_empty' => false,
                                                                ],
                                                                'required' => [
                                                                    'type' => 'checkbox',
                                                                    'name' => esc_html__( 'Required Field', 'routiz' ),
                                                                ],
                                                                'show_if_guest' => [
                                                                    'type' => 'checkbox',
                                                                    'name' => esc_html__( 'Show only when the user is not logged in', 'routiz' ),
                                                                ],
                                                                'col' => [
                                                                    'type' => 'select',
                                                                    'name' => esc_html__( 'Column Size', 'routiz' ),
                                                                    'value' => 12,
                                                                    'options' => [
                                                                        12 => esc_html__('100%', 'routiz'),
                                                                        6 => esc_html__('50%', 'routiz'),
                                                                        4 => esc_html__('25%', 'routiz'),
                                                                        3 => esc_html__('20%', 'routiz'),
                                                                    ],
                                                                    'allow_empty' => false,
                                                                    'col' => 6
                                                                ]
                                                            ]
                                                        ],

                                                    ],
                                                ],
                                            ]
                                        ],

                                        /*
                                         * day bookings
                                         *
                                         */
                                        'booking' => [
                                            'name' => esc_html__('Booking ( Day )', 'routiz'),
                                            'fields' => [
                                                'action_type_product' => [
                                                    'type' => 'wc_products',
                                                    'name' => esc_html__( 'Select Product', 'routiz' ),
                                                    'description' => esc_html__('The WooCommerce product of type `Listing Booking` that will be used to create order.', 'routiz'),
                                                    'product_type' => 'listing_booking',
                                                    'choice' => 'select',
                                                    'allow_empty' => true,
                                                    'error_message' => esc_html__( 'There are no available listing booking products, please create one by going to Products > Add New with the type "Listing Booking"', 'routiz' ),
                                                ],
                                                'selection_type' => [
                                                    'type' => 'select',
                                                    'name' => esc_html__( 'Calendar Selection Type', 'routiz' ),
                                                    'options' => [
                                                        'single' => esc_html__('Single day', 'routiz'),
                                                        'range' => esc_html__('Date range', 'routiz'),
                                                    ],
                                                    'value' => 'range',
                                                    'allow_empty' => false,
                                                ],
                                                'reservation_overlap' => [
                                                    'type' => 'checkbox',
                                                    'id' => 'enable_res_overlap',
                                                    'name' => esc_html__('Enable Reservation Overlapping', 'routiz'),
                                                    'description' => __('If you check this option, the visitors will be able to reserve the same dates multiple times', 'routiz'),
                                                ],
                                                'title' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Title ( Optional )', 'routiz' ),
                                                ],
                                                'summary' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Summary ( Optional )', 'routiz' ),
                                                ],
                                                'entity_text' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Entity Text', 'routiz' ),
                                                    'placeholder' => esc_html__( 'Per night', 'routiz' ),
                                                ]
                                            ]
                                        ],

                                        /*
                                         * hourly bookings
                                         *
                                         */
                                        'booking_hourly' => [
                                            'name' => esc_html__('Booking ( Hour )', 'routiz'),
                                            'fields' => [
                                                'action_type_product' => [
                                                    'type' => 'wc_products',
                                                    'name' => esc_html__( 'Select Product', 'routiz' ),
                                                    'description' => esc_html__('The WooCommerce product of type `Listing Booking` that will be used to create order.', 'routiz'),
                                                    'product_type' => 'listing_booking',
                                                    'choice' => 'select',
                                                    'allow_empty' => true,
                                                    'error_message' => esc_html__( 'There are no available listing booking products, please create one by going to Products > Add New with the type "Listing Booking"', 'routiz' ),
                                                ],
                                                'action_type_stack' => [
                                                    'type' => 'select',
                                                    'name' => esc_html__( 'Time Stack', 'routiz' ),
                                                    'options' => [
                                                        15 => esc_html__( '15 minutes', 'routiz' ),
                                                        30 => esc_html__( '30 minutes', 'routiz' ),
                                                        60 => esc_html__( '1 hour', 'routiz' ),
                                                    ],
                                                    'value' => 60,
                                                    'allow_empty' => false,
                                                ],
                                                'title' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Title ( Optional )', 'routiz' ),
                                                ],
                                                'summary' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Summary ( Optional )', 'routiz' ),
                                                ],
                                                'entity_text' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Entity Text', 'routiz' ),
                                                    'placeholder' => esc_html__( 'Per hour', 'routiz' ),
                                                ]
                                            ]
                                        ],

                                        /*
                                         * hourly appointments
                                         *
                                         */
                                        'booking_appointments' => [
                                            'name' => esc_html__('Booking ( Appointments )', 'routiz'),
                                            'fields' => [
                                                'action_type_product' => [
                                                    'type' => 'wc_products',
                                                    'name' => esc_html__( 'Select Product', 'routiz' ),
                                                    'description' => esc_html__('The WooCommerce product of type `Listing Booking` that will be used to create order.', 'routiz'),
                                                    'product_type' => 'listing_booking',
                                                    'choice' => 'select',
                                                    'allow_empty' => true,
                                                    'error_message' => esc_html__( 'There are no available listing booking products, please create one by going to Products > Add New with the type "Listing Booking"', 'routiz' ),
                                                ],
                                                'hide_date' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__('Hide Date Selection', 'routiz'),
                                                    'description' => esc_html__('Suitable for single appointments', 'routiz'),
                                                ],
                                                'display_name' => [
                                                    'type' => 'checkbox',
                                                    'name' => esc_html__('Display Appointment Name', 'routiz'),
                                                ],
                                                'title' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Title', 'routiz' ),
                                                ],
                                                'description' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Description', 'routiz' ),
                                                ],
                                                'fields' => [
                                                    'type' => 'repeater',
                                                    'name' => esc_html__('Additional Fields', 'routiz'),
                                                    'description' => esc_html__('Add additional fields', 'routiz'),
                                                    'button' => [
                                                        'label' => esc_html__('Add Field', 'routiz')
                                                    ],
                                                    'templates' => [

                                                        'field' => [
                                                            'name' => esc_html__('Text Field', 'routiz'),
                                                            'heading' => 'name',
                                                            'fields' => [
                                                                'name' => [
                                                                    'type' => 'text',
                                                                    'name' => esc_html__( 'Name', 'routiz' ),
                                                                    'value' => esc_html__( 'Field name', 'routiz' )
                                                                ],
                                                                'id' => [
                                                                    'type' => 'use_field',
                                                                    'name' => esc_html__('Select Field', 'routiz'),
                                                                    'description' => esc_html__('Select field to display', 'routiz'),
                                                                    'exclude' => [
                                                                        'post_title',
                                                                        'post_content',
                                                                    ],
                                                                    'include' => [
                                                                        'rz_location' => esc_html__('Address', 'routiz')
                                                                    ],
                                                                    'col' => 6
                                                                ],
                                                                'format' => [
                                                                    'type' => 'text',
                                                                    'name' => esc_html__('Format', 'routiz'),
                                                                    'description' => esc_html__('{field} will be replaced with the field value. You can add additional text, example format: "{field} bedrooms", will print "4 bedrooms".', 'routiz'),
                                                                    'value' => '{field}',
                                                                    'col' => 6
                                                                ],
                                                                'icon' => [
                                                                    'type' => 'icon',
                                                                    'name' => esc_html__( 'Icon', 'routiz' )
                                                                ],
                                                                'type' => [
                                                                    'type' => 'select',
                                                                    'name' => esc_html__('Type', 'routiz'),
                                                                    'options' => [
                                                                        'text' => esc_html__('Text', 'routiz'),
                                                                        'address' => esc_html__('Address', 'routiz'),
                                                                        'url' => esc_html__('URL', 'routiz'),
                                                                        'email' => esc_html__('Email', 'routiz'),
                                                                        'phone' => esc_html__('Phone number', 'routiz'),
                                                                        'price' => esc_html__('Price', 'routiz'),
                                                                    ],
                                                                    'allow_empty' => false
                                                                ],
                                                                'type_url_label' => [
                                                                    'type' => 'text',
                                                                    'name' => esc_html__( 'URL Text', 'routiz' ),
                                                                    'dependency' => [
                                                                        'id' => 'type',
                                                                        'compare' => '=',
                                                                        'value' => [ 'url' ],
                                                                    ],
                                                                ],
                                                            ]
                                                        ],

                                                    ],

                                                ]
                                            ]
                                        ],

                                        /*
                                         * purchase
                                         *
                                         */
                                        'purchase' => [
                                            'name' => esc_html__('Purchase', 'routiz'),
                                            'fields' => [
                                                'action_type_product' => [
                                                    'type' => 'wc_products',
                                                    'name' => esc_html__( 'Select Product', 'routiz' ),
                                                    'description' => esc_html__('The WooCommerce product of type `Listing Purchase` that will be used to create order.', 'routiz'),
                                                    'product_type' => 'listing_purchase',
                                                    'choice' => 'select',
                                                    'allow_empty' => true,
                                                    'error_message' => esc_html__( 'There are no available listing purchase products, please create one by going to Products > Add New with the type "Listing Purchase"', 'routiz' ),
                                                ],
                                                'title' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Title', 'routiz' ),
                                                ],
                                                'description' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Description', 'routiz' ),
                                                ],
                                                'button_label' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Button Label', 'routiz' ),
                                                    'placeholder' => esc_html__( 'Purchase', 'routiz' ),
                                                ],
                                            ]
                                        ],

                                        /*
                                         * report
                                         *
                                         */
                                        'report' => [
                                            'name' => esc_html__('Report', 'routiz'),
                                            'fields' => []
                                        ],

                                        /*
                                         * claim
                                         *
                                         */
                                        'claim' => [
                                            'name' => esc_html__('Claim', 'routiz'),
                                            'fields' => [
                                                'claim_button_label' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Claim Button Label', 'routiz' ),
                                                    'placeholder' => esc_html__( 'Claim this business', 'routiz' ),
                                                ],
                                                'claim_title' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Claim Title', 'routiz' ),
                                                    'value' => esc_html__( 'Is this your business?', 'routiz' ),
                                                ],
                                                'claim_description' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Claim Description', 'routiz' ),
                                                    'value' => esc_html__( 'Claim your business to immediately update business information, respond to reviews, and more!', 'routiz' ),
                                                ],
                                                'action_product' => [
                                                    'type' => 'wc_products',
                                                    'name' => esc_html__( 'Select Product', 'routiz' ),
                                                    'description' => esc_html__('The WooCommerce product of type `Listing Claim` that will be used to create order.', 'routiz'),
                                                    'product_type' => 'listing_claim',
                                                    'choice' => 'select',
                                                    'allow_empty' => true,
                                                ]
                                            ]
                                        ],

                                    ]
                                ]);

                                $panel->form->render([
                                    'type' => 'checkbox',
                                    'id' => 'enable_action_sticky',
                                    'name' => esc_html__('Enable Sticky Action', 'routiz'),
                                    'description' => __('Enable this option if you want to stick the action sidebar to your screen when scrolling.', 'routiz'),
                                ]);

                                $panel->form->render([
                                    'type' => 'text',
                                    'id' => 'action_mobile_button_label',
                                    'name' => esc_html__('Mobile Action Button Label', 'routiz'),
                                    'description' => esc_html__('You can also use the pre-defined field price, like: [price].', 'routiz'),
                                    'placeholder' => esc_html__('View', 'routiz'),
                                ]);

                                /*$panel->form->render([
                                    'type' => 'select',
                                    'id' => 'action_mobile_display',
                                    'name' => esc_html__('Mobile Display', 'routiz'),
                                    'description' => esc_html__('The field that will be displayed next to the action button', 'routiz'),
                                    'options' => [
                                        'none' => esc_html__('None', 'routiz'),
                                        'price' => esc_html__('Price', 'routiz'),
                                        // 'author' => esc_html__('Author', 'routiz'),
                                        'review' => esc_html__('Review', 'routiz'),
                                    ],
                                    'allow_empty' => false
                                ]);*/

                                /*$panel->form->render([
                                    'type' => 'text',
                                    'id' => 'action_mobile_price_entity',
                                    'name' => esc_html__('Mobile Action Price Entity', 'routiz'),
                                    'placeholder' => esc_html__('per night', 'routiz'),
                                    'dependency' => [
                                        'id' => 'action_mobile_display',
                                        'compare' => '=',
                                        'value' => 'price',
                                    ],
                                ]);*/

                            ?>

                            <div class="rz-form-group rz-col-12 rz-text-center rz-mt-3">
                                <button type="submit" class="rz-button rz-large">
                                    <span><?php esc_html_e( 'Save Changes', 'routiz' ); ?></span>
                                </button>
                            </div>

                        </div>
                    </div>
                </aside>

                <aside class="rz-section rz-section-large" :class="{'rz-none': tabSub !== 'pricing'}">

                    <div class="rz-panel-heading">
                        <h3 class="rz-title"><?php esc_html_e('Pricing', 'routiz'); ?></h3>
                    </div>

                    <div class="rz-form">
                        <div class="rz-grid">

                            <?php

                                $panel->form->render([
                                    'type' => 'checkbox',
                                    'id' => 'allow_pricing',
                                    'name' => esc_html__('Allow Pricing', 'routiz'),
                                    'description' => esc_html__('Check this option if you want to display pricing in your listings. The pricing will automatically appear in your submission for and listings.', 'routiz'),
                                ]);

                                $panel->form->render([
                                    'type' => 'checkbox',
                                    'id' => 'allow_not_required_price',
                                    'name' => esc_html__('Price is not required', 'routiz'),
                                    'description' => esc_html__('Check this option if your listings can go for free.', 'routiz'),
                                    'dependency' => [
                                        'id' => 'allow_pricing',
                                        'compare' => '=',
                                        'value' => true,
                                    ],
                                ]);

                                $panel->form->render([
                                    'type' => 'checkbox',
                                    'id' => 'allow_seasons',
                                    'name' => esc_html__('Allow seasonal pricing', 'routiz'),
                                    'description' => esc_html__('Enable dynamic pricing based on weekdays and months.', 'routiz'),
                                    'dependency' => [
                                        'id' => 'allow_pricing',
                                        'compare' => '=',
                                        'value' => true,
                                    ],
                                ]);

                                $panel->form->render([
                                    'type' => 'checkbox',
                                    'id' => 'allow_long_term',
                                    'name' => esc_html__( 'Allow long term discounts', 'routiz' ),
                                    'description' => esc_html__( 'Check this if you want to allow the users to apply discounts for 7+ and 30+ reservations.', 'routiz' ),
                                    'dependency' => [
                                        'id' => 'allow_pricing',
                                        'compare' => '=',
                                        'value' => true,
                                    ],
                                ]);

                                $panel->form->render([
                                    'type' => 'checkbox',
                                    'id' => 'allow_security_deposit',
                                    'name' => esc_html__( 'Allow security deposit', 'routiz' ),
                                    'dependency' => [
                                        'id' => 'allow_pricing',
                                        'compare' => '=',
                                        'value' => true,
                                    ],
                                ]);

                                $panel->form->render([
                                    'type' => 'checkbox',
                                    'id' => 'allow_extra_pricing',
                                    'name' => esc_html__( 'Allow extra pricing', 'routiz' ),
                                    'description' => esc_html__( 'Check this if you want to allow the users to add additional prices like additional fees.', 'routiz' ),
                                    'dependency' => [
                                        'id' => 'allow_pricing',
                                        'compare' => '=',
                                        'value' => true,
                                    ],
                                ]);

                                $panel->form->render([
                                    'type' => 'checkbox',
                                    'id' => 'allow_addons',
                                    'name' => esc_html__( 'Allow addons', 'routiz' ),
                                    'description' => esc_html__( 'Check this if you want to allow the users to add additional addon prices like airport pickup.', 'routiz' ),
                                    'dependency' => [
                                        'id' => 'allow_pricing',
                                        'compare' => '=',
                                        'value' => true,
                                    ],
                                ]);

                                $panel->form->render([
                                    'type' => 'text',
                                    'id' => 'addon_label',
                                    'name' => esc_html__( 'Addon label', 'routiz' ),
                                    'placeholder' => esc_html__( 'Select services', 'routiz' ),
                                    'dependency' => [
                                        'id' => 'allow_pricing',
                                        'compare' => '=',
                                        'value' => true,
                                    ],
                                ]);

                            ?>

                            <div class="rz-form-group rz-col-12 rz-text-center rz-mt-3">
                                <button type="submit" class="rz-button rz-large">
                                    <span><?php esc_html_e( 'Save Changes', 'routiz' ); ?></span>
                                </button>
                            </div>

                        </div>
                    </div>
                </aside>

                <aside class="rz-section rz-section-large" :class="{'rz-none': tabSub !== 'reservations'}">

                    <div class="rz-panel-heading">
                        <h3 class="rz-title"><?php esc_html_e('Reservations', 'routiz'); ?></h3>
                    </div>

                    <div class="rz-form">
                        <div class="rz-grid">

                            <?php

                                $panel->form->render([
                                    'type' => 'checkbox',
                                    'id' => 'allow_instant',
                                    'name' => esc_html__( 'Allow instant booking', 'routiz' ),
                                    'description' => esc_html__( 'If you enable this option, the hosts will be able to enable instant booking on their listings. Instant booking doesn\'t require approval and will lead the customer directly to the checkout page. This method doesn\'t allow booking cancellation by the host.', 'routiz' ),
                                ]);

                                $panel->form->render([
                                    'type' => 'checkbox',
                                    'id' => 'allow_guests',
                                    'name' => esc_html__( 'Allow Guest Selection', 'routiz' ),
                                ]);

                                $panel->form->render([
                                    'type' => 'checkbox',
                                    'id' => 'allow_guest_pricing',
                                    'name' => esc_html__( 'Allow Guest Based Pricing', 'routiz' ),
                                    'description' => esc_html__( 'If you check this option, the listings in this group will have the option to increase the final price, based on the number of guests.', 'routiz' ),
                                    'dependency' => [
                                        'id' => 'allow_guests',
                                        'compare' => '=',
                                        'value' => true,
                                        'style' => 'rz-opacity-30',
                                    ],
                                ]);

                                $panel->form->render([
                                    'type' => 'checkbox',
                                    'id' => 'allow_child_pricing',
                                    'name' => esc_html__( 'Allow Child Based Discount Pricing', 'routiz' ),
                                    'description' => esc_html__( 'If you check this option, the listings in this group will have the option to decrease the final price, based on the number of childs.', 'routiz' ),
                                    'dependency' => [
                                        'id' => 'allow_guests',
                                        'compare' => '=',
                                        'value' => true,
                                        'style' => 'rz-opacity-30',
                                    ],
                                ]);

                                $panel->form->render([
                                    'type' => 'checkbox',
                                    'id' => 'allow_child_based_pricing',
                                    'name' => esc_html__( 'Allow Child Separate Pricing', 'routiz' ),
                                    'description' => esc_html__( 'If you check this option, the listings in this group will have the option to Increase the final price, based on the number of childs.', 'routiz' ),
                                    'dependency' => [
                                        'id' => 'allow_guests',
                                        'compare' => '=',
                                        'value' => true,
                                        'style' => 'rz-opacity-30',
                                    ],
                                ]);


                                $panel->form->render([
                                    'type' => 'checkbox',
                                    'id' => 'allow_min_max',
                                    'name' => esc_html__( 'Allow Min / Max lenth of reservations', 'routiz' ),
                                    'description' => esc_html__('This option will enable your customers to select custom minimum and maximum length for the bookings', 'routiz'),
                                ]);

                                $panel->form->render([
                                    'type' => 'checkbox',
                                    'id' => 'enable_ical',
                                    'name' => esc_html__( 'Enable iCalendar', 'routiz' ),
                                    'description' => esc_html__('Check this option if you want to generate booking availability feed. Synchronise all your booking portals in real time and increase your productivity.', 'routiz'),
                                ]);

                                $panel->form->render([
                                    'type' => 'checkbox',
                                    'id' => 'enable_reservation_webhook',
                                    'name' => esc_html__( 'Enable Reservation Webhook', 'routiz' ),
                                    'description' => esc_html__('Check this option if you want to trigger any action on completed reservation through a webhook.', 'routiz'),
                                ]);

                                $panel->form->render([
                                    'type' => 'heading',
                                    'description' => sprintf(
                                        esc_html__("The webhook is a way for an app to provide other applications with real-time information.\r\n %s.\r\n %s.", 'routiz'),
                                        '<a href="https://utillz.com/documentation/account/notifications/webhooks/" target="_blank">' . esc_html__('Learn more abount webhooks', 'routiz') . '</a>',
                                        '<a href="#" data-modal="panel-webhook-fields"><strong>' . esc_html__('What fields are being sent to the webhook url', 'routiz') . '</strong></a>'
                                    ),
                                    'dependency' => [
                                        'id' => 'enable_reservation_webhook',
                                        'compare' => '=',
                                        'value' => true
                                    ],
                                    'class' => [
                                        'rz-mb-0'
                                    ]
                                ]);

                                $panel->form->render([
                                    'type' => 'text',
                                    'id' => 'reservation_webhook',
                                    'name' => esc_html__( 'Reservation Webhook URL', 'routiz' ),
                                    'dependency' => [
                                        'id' => 'enable_reservation_webhook',
                                        'compare' => '=',
                                        'value' => true
                                    ],
                                ]);

                                $panel->form->render([
                                    'type' => 'text',
                                    'id' => 'reservation_webhook_custom_fields',
                                    'name' => esc_html__('Reservation Webhook Custom Fields', 'routiz'),
                                    'description' => sprintf(
                                        esc_html__('%s: you can add additional custom meta fields using a simple comma-separated string, like: %s. webhook_param_name stands for the parameter name that will be sent to the webhook and listing_meta_key stands for the post meta key, that you want to extract from the listing and send. The post meta value will be collected from the listing post type.', 'routiz'),
                                        '<strong>' . esc_html__('Advanced users only', 'routiz') . '</strong>',
                                        '<strong>webhook_param_name:listing_meta_key</strong>'
                                    ),
                                    'dependency' => [
                                        'id' => 'enable_reservation_webhook',
                                        'compare' => '=',
                                        'value' => true
                                    ],
                                ]);

                            ?>

                            <div class="rz-form-group rz-field rz-col-12" data-dependency='{"id":"enable_reservation_webhook","compare":"=","value":true}'>
                                <a href="#" class="rz-button rz-small" data-action="trigger-webhook" data-for="reservation_webhook">
                                    <span><?php esc_html_e( 'Trigger webhook', 'routiz' ); ?></span>
                                    <?php echo Rz()->preloader(); ?>
                                </a>
                            </div>

                            <div class="rz-form-group rz-col-12 rz-text-center rz-mt-3">
                                <button type="submit" class="rz-button rz-large">
                                    <span><?php esc_html_e( 'Save Changes', 'routiz' ); ?></span>
                                </button>
                            </div>

                        </div>
                    </div>
                </aside>

                <aside class="rz-section rz-section-large" :class="{'rz-none': tabSub !== 'reviews'}">

                    <div class="rz-panel-heading">
                        <h3 class="rz-title"><?php esc_html_e('Reviews', 'routiz'); ?></h3>
                    </div>

                    <div class="rz-form">
                        <div class="rz-grid">

                            <?php

                                $panel->form->render([
                                    'type' => 'heading',
                                    'description' => __('Customize your reviews, add ratings, enable media upload. Don\'t forget to add the review module inside the single page by going to <strong>Display > Single Page > Content > add the "Review" module</strong>.', 'routiz'),
                                ]);

                                $panel->form->render([
                                    'type' => 'select',
                                    'id' => 'review_submission',
                                    'name' => esc_html__('Review Submission', 'routiz'),
                                    'options' => [
                                        'everyone' => esc_html__('Everyone can submit a review', 'routiz'),
                                        'engaged' => esc_html__('Only engaged users', 'routiz'),
                                    ],
                                    'value' => 'everyone',
                                    'allow_empty' => false
                                ]);

                                $panel->form->render([
                                    'type' => 'number',
                                    'id' => 'reviews_per_page',
                                    'name' => esc_html__('Reviews per Page', 'routiz'),
                                    'input_type' => 'stepper',
                                    'min' => 1,
                                    'max' => 50,
                                    'value' => 10,
                                ]);

                                $panel->form->render([
                                    'type' => 'checkbox',
                                    'id' => 'review_moderation',
                                    'name' => esc_html__('Comment must be manually approved', 'routiz'),
                                ]);

                                $panel->form->render([
                                    'type' => 'checkbox',
                                    'id' => 'multiple_reviews',
                                    'name' => esc_html__('Users can submit multiple reviews on listings', 'routiz'),
                                ]);

                                $panel->form->render([
                                    'type' => 'checkbox',
                                    'id' => 'enable_review_media',
                                    'name' => esc_html__('Enable Media Uploads', 'routiz'),
                                    'description' => esc_html__('Allow users to upload images to their reviews.', 'routiz'),
                                ]);

                                $panel->form->render([
                                    'type' => 'checkbox',
                                    'id' => 'enable_review_ratings',
                                    'name' => esc_html__('Enable Review Ratings', 'routiz'),
                                ]);

                                $panel->form->render([
                                    'type' => 'repeater',
                                    'id' => 'review_ratings',
                                    'name' => esc_html__('Add Rating Criteria', 'routiz'),
                                    'button' => [
                                        'label' => esc_html__('Add Criteria', 'routiz')
                                    ],
                                    'templates' => [

                                        /*
                                         * rating
                                         *
                                         */
                                        'rating' => [
                                            'name' => esc_html__('Rating', 'routiz'),
                                            'heading' => 'name',
                                            'fields' => [
                                                'name' => [
                                                    'type' => 'text',
                                                    'name' => esc_html__( 'Name', 'routiz' ),
                                                    'value' => 'Rating Name',
                                                    'col' => 6
                                                ],
                                                'key' => [
                                                    'type' => 'key',
                                                    'name' => esc_html__( 'Key', 'routiz' ),
                                                    'value' => 'rating-key',
                                                    'defined' => false,
                                                    'col' => 6
                                                ],
                                            ]
                                        ],

                                    ],
                                    'dependency' => [
                                        'id' => 'enable_review_ratings',
                                        'compare' => '=',
                                        'value' => true,
                                        'style' => 'rz-opacity-30',
                                    ],
                                ]);

                            ?>

                            <div class="rz-form-group rz-col-12 rz-text-center rz-mt-3">
                                <button type="submit" class="rz-button rz-large">
                                    <span><?php esc_html_e( 'Save Changes', 'routiz' ); ?></span>
                                </button>
                            </div>

                        </div>
                    </div>
                </aside>

                <aside class="rz-section rz-section-large" :class="{'rz-none': tabSub !== 'nearby'}">

                    <div class="rz-panel-heading">
                        <h3 class="rz-title"><?php esc_html_e('Nearby Listings', 'routiz'); ?></h3>
                    </div>

                    <div class="rz-form">
                        <div class="rz-grid">

                            <?php

                                $panel->form->render([
                                    'type' => 'heading',
                                    'description' => __('Display nearby listings by location distance.', 'routiz'),
                                ]);

                                $panel->form->render([
                                    'type' => 'checkbox',
                                    'id' => 'enable_nearby',
                                    'name' => esc_html__('Enable Nearby Listings', 'routiz'),
                                ]);

                                $panel->form->render([
                                    'type' => 'number',
                                    'id' => 'nearby_distance',
                                    'name' => sprintf( esc_html__('Distance ( %s )', 'routiz'), get_option('rz_measure_system') == 'metric' ? 'km' : 'mi' ),
                                    'description' => __('Distance to search for nearby listings. Closer listings will appear first.', 'routiz'),
                                    'input_type' => 'range',
                                    'min' => 1,
                                    'max' => 100,
                                    'value' => 1,
                                    'dependency' => [
                                        'id' => 'enable_nearby',
                                        'compare' => '=',
                                        'value' => 1,
                                        'style' => 'rz-opacity-30',
                                    ],
                                ]);

                            ?>

                            <div class="rz-form-group rz-col-12 rz-text-center rz-mt-3">
                                <button type="submit" class="rz-button rz-large">
                                    <span><?php esc_html_e( 'Save Changes', 'routiz' ); ?></span>
                                </button>
                            </div>

                        </div>
                    </div>
                </aside>

                <aside class="rz-section rz-section-large" :class="{'rz-none': tabSub !== 'similar'}">

                    <div class="rz-panel-heading">
                        <h3 class="rz-title"><?php esc_html_e('Similar Listings', 'routiz'); ?></h3>
                    </div>

                    <div class="rz-form">
                        <div class="rz-grid">

                            <?php

                                $panel->form->render([
                                    'type' => 'heading',
                                    'description' => __('Display similar listings by specific criterias.', 'routiz'),
                                ]);

                                $panel->form->render([
                                    'type' => 'checkbox',
                                    'id' => 'enable_similar',
                                    'name' => esc_html__('Enable Similar Listings', 'routiz'),
                                ]);

                                $panel->form->render([
                                    'type' => 'taxonomy_types',
                                    'id' => 'similar_taxonomy',
                                    'name' => esc_html__('Similar Listing Relation:', 'routiz'),
                                    'description' => __('Choose how to make the relation between the listings.', 'routiz'),
                                    'allow_empty' => false,
                                    'dependency' => [
                                        'id' => 'enable_similar',
                                        'compare' => '=',
                                        'value' => 1,
                                        'style' => 'rz-opacity-30',
                                    ],
                                ]);

                            ?>

                            <div class="rz-form-group rz-col-12 rz-text-center rz-mt-3">
                                <button type="submit" class="rz-button rz-large">
                                    <span><?php esc_html_e( 'Save Changes', 'routiz' ); ?></span>
                                </button>
                            </div>

                        </div>
                    </div>
                </aside>

            </section>

            <section class="rz-sections" :class="{'rz-none': tabMain !== 'display'}">

                <aside class="rz-section" :class="{'rz-none': tabSub !== 'box'}">

                    <div class="rz-panel-heading">
                        <h3 class="rz-title"><?php esc_html_e( 'Listing Box', 'routiz' ); ?></h3>
                    </div>

                    <div class="rz-grid">

                        <?php

                            $panel->form->render([
                                'type' => 'preview_listing',
                                'id' => 'preview_listing',
                                'name' => esc_html__('Preview', 'routiz'),
                            ]);

                        ?>

                    </div>
                </aside>

                <aside class="rz-section rz-section-large" :class="{'rz-none': tabSub !== 'single'}">

                    <div class="rz-panel-heading">
                        <h3 class="rz-title"><?php esc_html_e( 'Single Page', 'routiz' ); ?></h3>
                    </div>

                    <div class="rz-grid">

                        <?php

                            $panel->form->render([
                                'type' => 'select',
                                'id' => 'single_cover_type',
                                'name' => esc_html__('Cover Type', 'routiz'),
                                'value' => 'none',
                                'options' => [
                                    'none' => esc_html__('None', 'routiz'),
                                    'gallery' => esc_html__('Boxed Grid', 'routiz'),
                                    'wall' => esc_html__('Wide Wall', 'routiz'),
                                ],
                                'allow_empty' => false
                            ]);

                            $panel->form->render([
                                'type' => 'tab',
                                'id' => 'tab_content',
                                'name' => esc_html__('Content', 'routiz'),
                            ]);

                            $panel->form->render([
                                'type' => 'repeater',
                                'id' => 'display_single_content',
                                'name' => esc_html__('Content Modules', 'routiz'),
                                'templates' => [

                                    'content' => [
                                        'name' => 'Content',
                                        'heading' => 'name',
                                        'fields' => [
                                            'name' => [
                                                'type' => 'text',
                                                'name' => esc_html__('Name', 'routiz'),
                                            ],
                                            'key' => [
                                                'type' => 'use_field',
                                                'name' => esc_html__('Select Field', 'routiz'),
                                            ],
                                        ]
                                    ],

                                    'text' => [
                                        'name' => 'Text',
                                        'heading' => 'name',
                                        'fields' => [
                                            'name' => [
                                                'type' => 'text',
                                                'name' => esc_html__('Name', 'routiz'),
                                            ],
                                            'content' => [
                                                'type' => 'textarea',
                                                'name' => esc_html__('Content', 'routiz'),
                                            ],
                                        ]
                                    ],

                                    'taxonomy' => [
                                        'name' => 'Taxonomy',
                                        'heading' => 'name',
                                        'fields' => [
                                            'name' => [
                                                'type' => 'text',
                                                'name' => esc_html__('Name', 'routiz'),
                                            ],
                                            'key' => [
                                                'type' => 'use_field',
                                                'group' => 'taxonomy',
                                                'name' => esc_html__('Select Field', 'routiz'),
                                            ],
                                            'style' => [
                                                'type' => 'select',
                                                'name' => esc_html__('Style', 'routiz'),
                                                'options' => [
                                                    'inline' => esc_html__('Inline', 'routiz'),
                                                    'list' => esc_html__('List', 'routiz'),
                                                ],
                                                'value' => 'inline',
                                                'allow_empty' => false
                                            ],
                                        ]
                                    ],

                                    'meta' => [
                                        'name' => 'Meta',
                                        'heading' => 'name',
                                        'fields' => [
                                            'name' => [
                                                'type' => 'text',
                                                'name' => esc_html__('Name', 'routiz'),
                                            ],
                                            'style' => [
                                                'type' => 'select',
                                                'name' => esc_html__('Style', 'routiz'),
                                                'options' => [
                                                    'inline' => esc_html__('Inline', 'routiz'),
                                                    'list' => esc_html__('List', 'routiz'),
                                                    'sorted' => esc_html__('Sorted List', 'routiz'),
                                                ],
                                                'value' => 'inline',
                                                'allow_empty' => false
                                            ],
                                            'highlight' => [
                                                'type' => 'checkbox',
                                                'name' => esc_html__('Highlight', 'routiz'),
                                            ],

                                            'items' => [
                                                'type' => 'repeater',
                                                'name' => esc_html__('Meta Items', 'routiz'),
                                                'templates' => [

                                                    'item' => [
                                                        'name' => 'Item',
                                                        'heading' => 'name',
                                                        'fields' => [
                                                            'name' => [
                                                                'type' => 'text',
                                                                'name' => esc_html__('Name', 'routiz'),
                                                            ],
                                                            'key' => [
                                                                'type' => 'use_field',
                                                                'name' => esc_html__('Select Field', 'routiz'),
                                                                'group' => 'any',
                                                                'include' => [
                                                                    'rz_guests' => esc_html__('Guests', 'routiz'),
                                                                    'rz_price' => esc_html__('Price', 'routiz'),
                                                                ]
                                                            ],
                                                            'format' => [
                                                                'type' => 'text',
                                                                'name' => esc_html__('Format', 'routiz'),
                                                                'description' => esc_html__('{field} will be replaced with the field value. You can add additional text, example format: "{field} bedrooms", will print "4 bedrooms".', 'routiz'),
                                                                'value' => '{field}'
                                                            ],
                                                            'icon' => [
                                                                'type' => 'icon',
                                                                'name' => esc_html__('Icon', 'routiz'),
                                                            ],
                                                            'type' => [
                                                                'type' => 'select',
                                                                'name' => esc_html__('Type', 'routiz'),
                                                                'options' => [
                                                                    'text' => esc_html__('Text', 'routiz'),
                                                                    'address' => esc_html__('Address', 'routiz'),
                                                                    'url' => esc_html__('URL', 'routiz'),
                                                                    'email' => esc_html__('Email', 'routiz'),
                                                                    'phone' => esc_html__('Phone number', 'routiz'),
                                                                    'price' => esc_html__('Price', 'routiz'),
                                                                ],
                                                                'allow_empty' => false
                                                            ],
                                                            'type_url_label' => [
                                                                'type' => 'text',
                                                                'name' => esc_html__( 'URL Text', 'routiz' ),
                                                                'dependency' => [
                                                                    'id' => 'type',
                                                                    'compare' => '=',
                                                                    'value' => [ 'url' ],
                                                                ],
                                                            ],
                                                        ]
                                                    ],
                                                ]
                                            ]

                                        ]
                                    ],

                                    'location' => [
                                        'name' => 'Location',
                                        'heading' => 'name',
                                        'fields' => [
                                            'name' => [
                                                'type' => 'text',
                                                'name' => esc_html__('Name', 'routiz'),
                                            ],
                                            'show_address' => [
                                                'type' => 'checkbox',
                                                'name' => esc_html__('Show Address?', 'routiz'),
                                            ],
                                        ]
                                    ],

                                    'reviews' => [
                                        'name' => 'Reviews',
                                        'heading' => 'name',
                                        'fields' => [
                                            'name' => [
                                                'type' => 'text',
                                                'name' => esc_html__('Name', 'routiz'),
                                            ],
                                        ]
                                    ],

                                    'author' => [
                                        'name' => 'Author',
                                        'heading' => 'name',
                                        'fields' => [
                                            'name' => [
                                                'type' => 'text',
                                                'name' => esc_html__('Name', 'routiz'),
                                            ],
                                            'format' => [
                                                'type' => 'text',
                                                'name' => esc_html__('Format', 'routiz'),
                                                'value' => '%s'
                                            ],
                                        ]
                                    ],

                                    'menu' => [
                                        'name' => esc_html__('Menu', 'routiz'),
                                        'heading' => 'name',
                                        'fields' => [
                                            'name' => [
                                                'type' => 'text',
                                                'name' => esc_html__('Name', 'routiz'),
                                            ],
                                        ]
                                    ],

                                    'gallery' => [
                                        'name' => esc_html__('Gallery', 'routiz'),
                                        'heading' => 'name',
                                        'fields' => [
                                            'name' => [
                                                'type' => 'text',
                                                'name' => esc_html__('Name', 'routiz'),
                                            ],
                                            'display' => [
                                                'type' => 'select',
                                                'name' => esc_html__('Display', 'routiz'),
                                                'options' => [
                                                    'adaptive' => esc_html__('Adaptive gallery', 'routiz'),
                                                    'files' => esc_html__('Downloadable files', 'routiz'),
                                                ],
                                                'allow_empty' => false,
                                                'col' => 6
                                            ],
                                            'key' => [
                                                'type' => 'use_field',
                                                'name' => esc_html__('Select field', 'routiz'),
                                                'group' => 'media',
                                                'col' => 6
                                            ],
                                        ]
                                    ],

                                ]
                            ]);

                        ?>

                        <div class="rz-form-group rz-col-12 rz-text-center rz-mt-3">
                            <button type="submit" class="rz-button rz-large">
                                <span><?php esc_html_e( 'Save Changes', 'routiz' ); ?></span>
                            </button>
                        </div>

                    </div>
                </aside>

                <aside class="rz-section rz-section-large" :class="{'rz-none': tabSub !== 'marker'}">

                    <div class="rz-panel-heading">
                        <h3 class="rz-title"><?php esc_html_e( 'Map Marker', 'routiz' ); ?></h3>
                    </div>

                    <div class="rz-grid">

                        <?php

                            $panel->form->render([
                                'type' => 'preview_marker',
                                'id' => 'preview_marker',
                                'name' => esc_html__('Preview', 'routiz'),
                            ]);

                        ?>

                    </div>
                </aside>

                <aside class="rz-section rz-section-large" :class="{'rz-none': tabSub !== 'explore'}">

                    <div class="rz-panel-heading">
                        <h3 class="rz-title"><?php esc_html_e( 'Explore Page', 'routiz' ); ?></h3>
                    </div>

                    <div class="rz-form">
                        <div class="rz-grid">

                            <?php

                                $panel->form->render([
                                    'type' => 'select',
                                    'id' => 'display_explore_type',
                                    'name' => esc_html__('Explore Page Type', 'routiz'),
                                    'options' => apply_filters('routiz/display/explore/type', [
                                        'map' => esc_html__('Map, 2 columns', 'routiz'),
                                        'map_x3' => esc_html__('Map, 3 columns', 'routiz'),
                                        'full' => esc_html__('No map, full-width', 'routiz'),
                                    ]),
                                    'allow_empty' => false
                                ]);

                            ?>

                        </div>
                    </div>
                </aside>

            </section>

            <section class="rz-sections" :class="{'rz-none': tabMain !== 'explore'}">

                <aside class="rz-section rz-section-large" :class="{'rz-none': tabSub !== 'general'}">

                    <div class="rz-panel-heading">
                        <h3 class="rz-title"><?php esc_html_e( 'General', 'routiz' ); ?></h3>
                    </div>

                    <div class="rz-form">
                        <div class="rz-grid">

                            <?php

                                $panel->form->render([
                                    'type' => 'select',
                                    'id' => 'search_form',
                                    'name' => esc_html__( 'Select Search Form', 'routiz' ),
                                    'description' => esc_html__( 'Filters will appear when you explore the listing type.', 'routiz' ),
                                    'options' => [
                                        'query' => [
                                            'post_type' => 'rz_search_form',
                                            'posts_per_page' => -1,
                                        ]
                                    ],
                                    'col' => 6
                                ]);

                                $panel->form->render([
                                    'type' => 'select',
                                    'id' => 'search_form_more',
                                    'name' => esc_html__( 'Select Search Form ( More )', 'routiz' ),
                                    'description' => esc_html__( 'Additional filters that will appear in a popup.', 'routiz' ),
                                    'options' => [
                                        'query' => [
                                            'post_type' => 'rz_search_form',
                                            'posts_per_page' => -1,
                                        ]
                                    ],
                                    'col' => 6
                                ]);

                                $panel->form->render([
                                    'type' => 'checkbox',
                                    'id' => 'open_listing_new_tab',
                                    'name' => esc_html__('Open Listings in a New Tab', 'routiz'),
                                ]);

                                $panel->form->render([
                                    'type' => 'number',
                                    'id' => 'listings_per_page',
                                    'name' => esc_html__('Listings per Page', 'routiz'),
                                    'input_type' => 'stepper',
                                    'format' => '%s listings',
                                    'min' => 1,
                                    'max' => 50,
                                    'step' => 1,
                                    'value' => 10,
                                ]);

                            ?>

                            <div class="rz-form-group rz-col-12 rz-text-center rz-mt-3">
                                <button type="submit" class="rz-button rz-large">
                                    <span><?php esc_html_e( 'Save Changes', 'routiz' ); ?></span>
                                </button>
                            </div>

                        </div>
                    </div>
                </aside>

            </section>

            <section class="rz-sections" :class="{'rz-none': tabMain !== 'monetize'}">

                <aside class="rz-section rz-section-large" :class="{'rz-none': tabSub !== 'plans'}">

                    <div class="rz-panel-heading">
                        <h3 class="rz-title"><?php esc_html_e( 'Plans', 'routiz' ); ?></h3>
                        <p>
                            <?php esc_html_e( 'Plans are a simple WooCommerce products created with the type "Listing Plan". By adding plans to specif listing type, the customers will be asked to select one in the submission process.', 'routiz' ); ?>
                        </p>
                    </div>

                    <div class="rz-form">
                        <div class="rz-grid">

                            <?php

                                $panel->form->render([
                                    'type' => 'checkbox',
                                    'id' => 'enable_plans',
                                    'name' => esc_html__( 'Enable Plans for this listing type', 'routiz' ),
                                ]);

                                $panel->form->render([
                                    'type' => 'wc_products',
                                    'id' => 'plans',
                                    'name' => esc_html__( 'Select Plan', 'routiz' ),
                                    'product_type' => [
                                        'listing_plan',
                                        'listing_subscription_plan',
                                    ],
                                    'choice' => 'checklist',
                                    'allow_empty' => false,
                                    'error_message' => esc_html__( 'There are no available listing package products, please create some by going to Products > Add New with the type "Listing Package"', 'routiz' ),
                                    'dependency' => [
                                        'id' => 'enable_plans',
                                        'compare' => '=',
                                        'value' => 1,
                                        'style' => 'rz-opacity-30',
                                    ],
                                ]);

                            ?>

                            <div class="rz-form-group rz-col-12 rz-text-center rz-mt-3">
                                <button type="submit" class="rz-button rz-large">
                                    <span><?php esc_html_e( 'Save Changes', 'routiz' ); ?></span>
                                </button>
                            </div>

                        </div>
                    </div>
                </aside>

                <aside class="rz-section rz-section-large" :class="{'rz-none': tabSub !== 'promotions'}">

                    <div class="rz-panel-heading">
                        <h3 class="rz-title"><?php esc_html_e( 'Promotions', 'routiz' ); ?></h3>
                        <p>
                            <?php esc_html_e( 'Promotions promotion are a simple WooCommerce products created with the type "Listing Promotion". By adding packages to specif listing type, the customers will be able to boost their listings in the search results for limited time.', 'routiz' ); ?>
                        </p>
                    </div>

                    <div class="rz-form">
                        <div class="rz-grid">

                            <?php

                                $panel->form->render([
                                    'type' => 'checkbox',
                                    'id' => 'enable_promotions',
                                    'name' => esc_html__( 'Enable promotions for this listing type', 'routiz' ),
                                ]);

                                $panel->form->render([
                                    'type' => 'wc_products',
                                    'id' => 'promotions',
                                    'name' => esc_html__( 'Select Promotion Packages', 'routiz' ),
                                    'product_type' => 'listing_promotion',
                                    'choice' => 'checklist',
                                    'allow_empty' => false,
                                    'error_message' => esc_html__( 'There are no available listing package products, please create some by going to Products > Add New with the type "Listing Package"', 'routiz' ),
                                    'dependency' => [
                                        'id' => 'enable_promotions',
                                        'compare' => '=',
                                        'value' => 1,
                                        'style' => 'rz-opacity-30',
                                    ],
                                ]);

                            ?>

                            <div class="rz-form-group rz-col-12 rz-text-center rz-mt-3">
                                <button type="submit" class="rz-button rz-large">
                                    <span><?php esc_html_e( 'Save Changes', 'routiz' ); ?></span>
                                </button>
                            </div>

                        </div>
                    </div>
                </aside>

                <aside class="rz-section rz-section-large" :class="{'rz-none': tabSub !== 'payments'}">

                    <div class="rz-panel-heading">
                        <h3 class="rz-title"><?php esc_html_e( 'Payments', 'routiz' ); ?></h3>
                        <p>
                            <?php esc_html_e( 'Fees will affect the final price of the listings', 'routiz' ); ?>
                        </p>
                    </div>

                    <div class="rz-form">
                        <div class="rz-grid">
                            <div class="rz-col-6">
                                <div class="rz-grid">
                                    <?php

                                        $panel->form->render([
                                            'type' => 'select',
                                            'id' => 'service_fee_type',
                                            'name' => esc_html__( 'Service Fee Type', 'routiz' ),
                                            'value' => 'none',
                                            'options' => [
                                                'none' => esc_html__('No Fee', 'routiz'),
                                                'fixed' => esc_html__('Fixed Amount', 'routiz'),
                                                'percentage' => esc_html__('Percentage', 'routiz'),
                                            ],
                                            'allow_empty' => false,
                                        ]);

                                        $panel->form->render([
                                            'type' => 'number',
                                            'id' => 'service_fee_amount_fixed',
                                            'name' => esc_html__( 'Service Fee Amount', 'routiz' ),
                                            'description' => esc_html__( 'Number only', 'routiz' ),
                                            'min' => 0.01,
                                            'step' => 0.01,
                                            'dependency' => [
                                                [
                                                    'id' => 'service_fee_type',
                                                    'compare' => '=',
                                                    'value' => 'fixed',
                                                ]
                                            ],
                                        ]);

                                        $panel->form->render([
                                            'type' => 'number',
                                            'id' => 'service_fee_amount_percentage',
                                            'name' => esc_html__( 'Service Fee Amount ( Percentage )', 'routiz' ),
                                            'description' => esc_html__( 'Number only', 'routiz' ),
                                            'min' => 0.01,
                                            'max' => 100,
                                            'step' => 0.01,
                                            'dependency' => [
                                                [
                                                    'id' => 'service_fee_type',
                                                    'compare' => '=',
                                                    'value' => 'percentage',
                                                ]
                                            ],
                                        ]);

                                    ?>
                                </div>
                            </div>
                            <div class="rz-col-6">
                                <div class="rz-grid">
                                    <?php

                                        $panel->form->render([
                                            'type' => 'select',
                                            'id' => 'host_fee_type',
                                            'name' => esc_html__( 'Host Fee Type', 'routiz' ),
                                            'value' => 'none',
                                            'options' => [
                                                'none' => esc_html__('No Fee', 'routiz'),
                                                'percentage' => esc_html__('Percentage', 'routiz'),
                                            ],
                                            'allow_empty' => false,
                                        ]);

                                        $panel->form->render([
                                            'type' => 'number',
                                            'id' => 'host_fee_amount_percentage',
                                            'name' => esc_html__( 'Host Fee Amount ( Percentage )', 'routiz' ),
                                            'description' => esc_html__( 'Number only', 'routiz' ),
                                            'min' => 0.01,
                                            'max' => 100,
                                            'step' => 0.01,
                                            'dependency' => [
                                                [
                                                    'id' => 'host_fee_type',
                                                    'compare' => '=',
                                                    'value' => 'percentage',
                                                ]
                                            ],
                                        ]);

                                    ?>
                                </div>
                            </div>

                            <?php

                                $panel->form->render([
                                    'type' => 'select',
                                    'id' => 'payment_processing',
                                    'name' => esc_html__( 'Payment processing', 'routiz' ),
                                    'description' => esc_html__( 'Select the type of collecting payments when purchasing or booking a listing.', 'routiz' ),
                                    'options' => Rz()->get_payment_processing_types(),
                                    'value' => 'full',
                                    'allow_empty' => false
                                ]);

                                $panel->form->render([
                                    'type' => 'number',
                                    'id' => 'payment_processing_percentage',
                                    'name' => esc_html__( 'Payment processing percentage', 'routiz' ),
                                    'description' => esc_html__( 'Number only', 'routiz' ),
                                    'min' => 0.01,
                                    'max' => 100,
                                    'step' => 0.01,
                                    'dependency' => [
                                        [
                                            'id' => 'payment_processing',
                                            'compare' => '=',
                                            'value' => 'percentage',
                                        ]
                                    ],
                                ]);

                            ?>

                            <div class="rz-form-group rz-col-12 rz-text-center rz-mt-3">
                                <button type="submit" class="rz-button rz-large">
                                    <span><?php esc_html_e( 'Save Changes', 'routiz' ); ?></span>
                                </button>
                            </div>

                        </div>
                    </div>
                </aside>

                <aside class="rz-section rz-section-large" :class="{'rz-none': tabSub !== 'banner'}">

                    <div class="rz-panel-heading">
                        <h3 class="rz-title"><?php esc_html_e( 'Banner Space', 'routiz' ); ?></h3>
                        <p>
                            <?php esc_html_e( 'Here you can control your banner positions', 'routiz' ); ?>
                        </p>
                    </div>

                    <div class="rz-form">
                        <div class="rz-grid">

                            <?php

                                $panel->form->render([
                                    'type' => 'checkbox',
                                    'id' => 'enable_explore_banner',
                                    'name' => esc_html__( 'Enable Explore Page Banner Space', 'routiz' )
                                ]);

                                $panel->form->render([
                                    'type' => 'number',
                                    'id' => 'explore_banner_inject_num',
                                    'name' => esc_html__( 'Inject the Banner After N Listing', 'routiz' ),
                                    'description' => esc_html__( 'Select the number of listing after which you want to inject the banner space', 'routiz' ),
                                    'input_type' => 'stepper',
                                    'min' => 0,
                                    'max' => 50,
                                    'value' => 4,
                                    'dependency' => [
                                        'id' => 'enable_explore_banner',
                                        'compare' => '=',
                                        'value' => true,
                                        'style' => 'rz-opacity-30'
                                    ]
                                ]);

                                $panel->form->render([
                                    'type' => 'textarea',
                                    'id' => 'explore_banner',
                                    'name' => esc_html__( 'Explore Page Banner Content', 'routiz' ),
                                    'description' => esc_html__( 'Here you can insert your banner code', 'routiz' ),
                                    'dependency' => [
                                        'id' => 'enable_explore_banner',
                                        'compare' => '=',
                                        'value' => true,
                                        'style' => 'rz-opacity-30'
                                    ]
                                ]);

                            ?>

                            <div class="rz-form-group rz-col-12 rz-text-center rz-mt-3">
                                <button type="submit" class="rz-button rz-large">
                                    <span><?php esc_html_e( 'Save Changes', 'routiz' ); ?></span>
                                </button>
                            </div>

                        </div>
                    </div>
                </aside>

            </section>

        </div>
    </div>

    <?php Rz()->the_template('routiz/admin/panel/modal/webhook-fields'); ?>

</div>
