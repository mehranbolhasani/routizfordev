<?php

defined('ABSPATH') || exit;

use \Routiz\Inc\Src\Form\Component as Form;

?>

<div class="rz-outer">
    <form method="post" autocomplete="nope">

        <div class="rz-meta-container rz-container-left">
            <h1><?php esc_html_e( 'Settings', 'routiz' ); ?></h1>
        </div>

        <div class="rz-panel rz-loading" id="rz-panel" data-tab-start="general/units" :class="{ 'rz-ready' : ready }">

            <input type="hidden" name="routiz_current_tab" v-bind:value="tab">

            <div class="rz-header">

                <div class="rz--brand">
                    <h3 class="rz--name"><?php esc_html_e( 'Routiz', 'routiz' ); ?></h3>
                </div>

                <nav class="rz-nav">
                    <ul>

                        <li :class="{ 'rz-active': tabMain == 'general' }">
                            <a href="#" v-on:click.prevent="tabClick('general/units')">
                                <?php esc_html_e( 'General', 'routiz' ); ?>
                            </a>
                        </li>
                        <li :class="{ 'rz-active': tabMain == 'listings' }">
                            <a href="#" v-on:click.prevent="tabClick('listings/taxonomies')">
                                <?php esc_html_e( 'Listings', 'routiz' ); ?>
                            </a>
                        </li>
                        <li :class="{ 'rz-active': tabMain == 'explore' }">
                            <a href="#" v-on:click.prevent="tabClick('explore/general')">
                                <?php esc_html_e( 'Explore', 'routiz' ); ?>
                            </a>
                        </li>
                        <li :class="{ 'rz-active': tabMain == 'integration' }">
                            <a href="#" v-on:click.prevent="tabClick('integration/maps')">
                                <?php esc_html_e( 'Integration', 'routiz' ); ?>
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
                    <li :class="{ 'rz-active': tabSub == 'units' }">
                        <a href="#" v-on:click.prevent="tabClick('general/units')">
                            <?php esc_html_e( 'Units', 'routiz' ); ?>
                        </a>
                    </li>
                    <li :class="{ 'rz-active': tabSub == 'pages' }">
                        <a href="#" v-on:click.prevent="tabClick('general/pages')">
                            <?php esc_html_e( 'Pages', 'routiz' ); ?>
                        </a>
                    </li>
                    <li :class="{ 'rz-active': tabSub == 'social' }">
                        <a href="#" v-on:click.prevent="tabClick('general/social')">
                            <?php esc_html_e( 'Social', 'routiz' ); ?>
                        </a>
                    </li>
                    <li :class="{ 'rz-active': tabSub == 'payouts' }">
                        <a href="#" v-on:click.prevent="tabClick('general/payouts')">
                            <?php esc_html_e( 'Payouts', 'routiz' ); ?>
                        </a>
                    </li>
                    <li :class="{ 'rz-active': tabSub == 'dev' }">
                        <a href="#" v-on:click.prevent="tabClick('general/dev')">
                            <?php esc_html_e( 'Development', 'routiz' ); ?>
                        </a>
                    </li>
                    <li :class="{ 'rz-active': tabSub == 'notifications' }">
                        <a href="#" v-on:click.prevent="tabClick('general/notifications')">
                            <?php esc_html_e( 'Notifications', 'routiz' ); ?>
                        </a>
                    </li>
                </ul>

                <!-- listings -->
                <ul class="rz-sub-nav rz-bg" v-if="tabMain == 'listings'">
                    <li :class="{ 'rz-active': tabSub == 'taxonomies' }">
                        <a href="#" v-on:click.prevent="tabClick('listings/taxonomies')">
                            <?php esc_html_e( 'Taxonomies', 'routiz' ); ?>
                        </a>
                    </li>
                    <li :class="{ 'rz-active': tabSub == 'social' }">
                        <a href="#" v-on:click.prevent="tabClick('listings/social')">
                            <?php esc_html_e( 'Social', 'routiz' ); ?>
                        </a>
                    </li>
                    <li :class="{ 'rz-active': tabSub == 'submission' }">
                        <a href="#" v-on:click.prevent="tabClick('listings/submission')">
                            <?php esc_html_e( 'Submission', 'routiz' ); ?>
                        </a>
                    </li>
                    <li :class="{ 'rz-active': tabSub == 'booking' }">
                        <a href="#" v-on:click.prevent="tabClick('listings/booking')">
                            <?php esc_html_e( 'Booking', 'routiz' ); ?>
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

                <!-- integration -->
                <ul class="rz-sub-nav rz-bg" v-if="tabMain == 'integration'">
                    <li :class="{ 'rz-active': tabSub == 'maps' }">
                        <a href="#" v-on:click.prevent="tabClick('integration/maps')">
                            <?php esc_html_e( 'Maps', 'routiz' ); ?>
                        </a>
                    </li>
                    <li :class="{ 'rz-active': tabSub == 'auth' }">
                        <a href="#" v-on:click.prevent="tabClick('integration/auth')">
                            <?php esc_html_e( 'Auth', 'routiz' ); ?>
                        </a>
                    </li>
                </ul>

            </div>

            <div class="rz-content">

                <section class="rz-sections" :class="{'rz-none': tabMain !== 'general'}">

                    <aside class="rz-section rz-section-large" :class="{'rz-none': tabSub !== 'units'}">
                        <div class="rz-panel-heading">
                            <h3 class="rz-title"><?php esc_html_e( 'General', 'routiz' ); ?></h3>
                        </div>
                        <div class="rz-form">
                            <div class="rz-grid">
                                <?php

                                    $form = new Form( Form::Storage_Option );

                                    $form->render([
                                        'type' => 'key',
                                        'id' => 'listing_slug',
                                        'name' => esc_html__('Listing Slug', 'routiz'),
                                        'description' => esc_html__('You need to re-save the permalink format after changing this option in order to apply the slug correctly', 'routiz'),
                                        'placeholder' => 'listing',
                                        'defined' => false,
                                    ]);

                                    $form->render([
                                        'type' => 'select',
                                        'id' => 'currency_place',
                                        'name' => esc_html__('Currency Place', 'routiz'),
                                        'options' => [
                                            'left' => 'Left',
                                            'right' => 'Right',
                                        ],
                                        'value' => 'left',
                                        'allow_empty' => false,
                                        'col' => 6
                                    ]);

                                    $form->render([
                                        'type' => 'text',
                                        'id' => 'currency_symbol',
                                        'name' => esc_html__('Currency Symbol', 'routiz'),
                                        'value' => '$',
                                        'col' => 6
                                    ]);

                                    $form->render([
                                        'type' => 'select',
                                        'id' => 'measure_system',
                                        'name' => esc_html__('Measuring System', 'routiz'),
                                        'value' => 'imperial',
                                        'options' => [
                                            'imperial' => esc_html__('British Imperial System ( Miles )', 'routiz'),
                                            'metric' => esc_html__('Metric System ( Kilometers )', 'routiz'),
                                        ],
                                        'allow_empty' => false
                                    ]);

                                    $form->render([
                                        'type' => 'select',
                                        'id' => 'weekends',
                                        'name' => esc_html__('Select the days to apply weekend pricing', 'routiz'),
                                        'value' => 'imperial',
                                        'options' => [
                                            'sat_sun' => esc_html__('Saturday and Sunday', 'routiz'),
                                            'sun_mon' => esc_html__('Sunday and Monday', 'routiz'),
                                            'fri_sat' => esc_html__('Friday and Saturday', 'routiz'),
                                            'fri_sat_sun' => esc_html__('Friday, Saturday and Sunday', 'routiz'),
                                        ],
                                        'allow_empty' => false
                                    ]);

                                    $form->render([
                                        'type' => 'select',
                                        'id' => 'decimals',
                                        'name' => esc_html__('Number Format Decimals', 'routiz'),
                                        'value' => 2,
                                        'options' => [
                                            0 => 0,
                                            1 => 1,
                                            2 => 2,
                                        ],
                                        'allow_empty' => false,
                                        'col' => 4
                                    ]);

                                    $form->render([
                                        'type' => 'text',
                                        'id' => 'decimal_separator',
                                        'name' => esc_html__('Number Format Decimal Separator', 'routiz'),
                                        'value' => '.',
                                        'col' => 4
                                    ]);

                                    $form->render([
                                        'type' => 'text',
                                        'id' => 'thousands_separator',
                                        'name' => esc_html__('Number Format Thousands Separator', 'routiz'),
                                        'value' => ',',
                                        'col' => 4
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

                    <aside class="rz-section rz-section-large" :class="{'rz-none': tabSub !== 'pages'}">
                        <div class="rz-panel-heading">
                            <h3 class="rz-title"><?php esc_html_e( 'Pages', 'routiz' ); ?></h3>
                        </div>
                        <div class="rz-form">
                            <div class="rz-grid">
                                <?php

                                    $form->render([
                                        'type' => 'select2',
                                        'id' => 'page_explore',
                                        'name' => esc_html__('Explore Page', 'routiz'),
                                        'options' => [
                                            'query' => [
                                                'post_type' => 'page',
                                                'posts_per_page' => -1,
                                            ]
                                        ]
                                    ]);

                                    $form->render([
                                        'type' => 'select2',
                                        'id' => 'page_submission',
                                        'name' => esc_html__('Submission Page', 'routiz'),
                                        'options' => [
                                            'query' => [
                                                'post_type' => 'page',
                                                'posts_per_page' => -1,
                                            ]
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

                    <aside class="rz-section rz-section-large" :class="{'rz-none': tabSub !== 'social'}">
                        <div class="rz-panel-heading">
                            <h3 class="rz-title"><?php esc_html_e( 'Social', 'routiz' ); ?></h3>
                        </div>
                        <div class="rz-form">
                            <div class="rz-grid">
                                <?php

                                    $form->render([
                                        'type' => 'checkbox',
                                        'id' => 'enable_social_icons',
                                        'name' => esc_html__('Enable Social Icons', 'routiz'),
                                    ]);

                                    $form->render([
                                        'type' => 'text',
                                        'id' => 'social_fb',
                                        'name' => esc_html__('Facebook Link', 'routiz'),
                                        'dependency' => [
                                            'id' => 'enable_social_icons',
                                            'value' => true,
                                            'compare' => '=',
                                        ]
                                    ]);

                                    $form->render([
                                        'type' => 'text',
                                        'id' => 'social_tw',
                                        'name' => esc_html__('Twitter Link', 'routiz'),
                                        'dependency' => [
                                            'id' => 'enable_social_icons',
                                            'value' => true,
                                            'compare' => '=',
                                        ]
                                    ]);

                                    $form->render([
                                        'type' => 'text',
                                        'id' => 'social_yt',
                                        'name' => esc_html__('YouTube Link', 'routiz'),
                                        'dependency' => [
                                            'id' => 'enable_social_icons',
                                            'value' => true,
                                            'compare' => '=',
                                        ]
                                    ]);

                                    $form->render([
                                        'type' => 'text',
                                        'id' => 'social_dr',
                                        'name' => esc_html__('Dribbble', 'routiz'),
                                        'dependency' => [
                                            'id' => 'enable_social_icons',
                                            'value' => true,
                                            'compare' => '=',
                                        ]
                                    ]);

                                    $form->render([
                                        'type' => 'text',
                                        'id' => 'social_in',
                                        'name' => esc_html__('Instagram', 'routiz'),
                                        'dependency' => [
                                            'id' => 'enable_social_icons',
                                            'value' => true,
                                            'compare' => '=',
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

                    <aside class="rz-section rz-section-large" :class="{'rz-none': tabSub !== 'payouts'}">
                        <div class="rz-panel-heading">
                            <h3 class="rz-title"><?php esc_html_e( 'Payouts', 'routiz' ); ?></h3>
                        </div>
                        <div class="rz-form">
                            <div class="rz-grid">
                                <?php

                                    $form->render([
                                        'type' => 'checkbox',
                                        'id' => 'enable_payouts',
                                        'name' => esc_html__('Enable Payouts', 'routiz'),
                                    ]);

                                    $form->render([
                                        'type' => 'checklist',
                                        'id' => 'payout_methods',
                                        'name' => esc_html__('Payout Methods', 'routiz'),
                                        'value' => [],
                                        'options' => [
                                            'paypal' => 'PayPal',
                                            'bank_transfer' => 'Direct Bank Transfer',
                                        ],
                                        'dependency' => [
                                            'id' => 'enable_payouts',
                                            'value' => 1,
                                            'compare' => '=',
                                            'style' => 'rz-opacity-30',
                                        ],
                                    ]);

                                    $form->render([
                                        'type' => 'number',
                                        'id' => 'min_payout',
                                        'name' => esc_html__('Minimum Payouts Amount', 'routiz'),
                                        'min' => 0,
                                        'step' => 0.01,
                                        'dependency' => [
                                            'id' => 'enable_payouts',
                                            'value' => 1,
                                            'compare' => '=',
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

                    <aside class="rz-section rz-section-large" :class="{'rz-none': tabSub !== 'dev'}">
                        <div class="rz-panel-heading">
                            <h3 class="rz-title"><?php esc_html_e( 'Development', 'routiz' ); ?></h3>
                        </div>

                        <div class="rz-form">
                            <div class="rz-grid">
                                <div class="rz-form-group rz-col-12">
                                    <div class="rz-development">
                                        <div class="rz--export">
                                            <a href="#" class="rz-button" data-action="dev-export-options">
                                                <span><?php esc_html_e( 'Export Options', 'routiz' ); ?></span>
                                                <?php Rz()->preloader(); ?>
                                            </a>
                                        </div>
                                        <div class="rz--output">
                                            <!-- // -->
                                        </div>
                                    </div>
                                </div>
                                <div class="rz-form-group rz-col-12 rz-none" id="rz-input-options-export">
                                    <div class="rz-heading">
                                        <label class="rz-ellipsis"><?php esc_html_e( 'Options Export JSON', 'routiz' ); ?></label>
                                    </div>
                                    <textarea type="text"></textarea>
                                </div>
                            </div>
                        </div>

                    </aside>

                    <aside class="rz-section rz-section-large" :class="{'rz-none': tabSub !== 'notifications'}">
                        <div class="rz-panel-heading">
                            <h3 class="rz-title"><?php esc_html_e( 'Notifications', 'routiz' ); ?></h3>
                            <p><?php esc_html_e( 'Here you can manage site notifications email templates and webhooks', 'routiz' ); ?></p>
                        </div>

                        <?php $notifications = new \DirectoryIterator( RZ_PATH . 'inc/src/notification/notifications'); ?>

                        <div class="rz-settings-split">
                            <div class="rz--sidebar">
                                <p class="rz-mb-2"><?php esc_html_e( 'All available notifications', 'routiz' ); ?>:</p>
                                <ul>
                                    <?php $active = false; foreach( $notifications as $file ): if( $file->isDot() ) { continue; } ?>
                                            <li <?php if( ! $active ) { echo 'class="rz--active"'; } ?> data-for="<?php echo esc_attr( $file->getBasename( '.' .$file->getExtension() ) ); ?>">
                                                <a href="#">
                                                    <?php echo esc_html( ucfirst( str_replace( '-', ' ', $file->getBasename( '.' .$file->getExtension() ) ) ) ); ?>
                                                </a>
                                            </li>
                                        <?php $active = true; ?>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <div class="rz--content">

                                <div class="rz-form">
                                    <?php $active = false; foreach( $notifications as $file ): ?>

                                        <?php

                                            $name = $file->getBasename( '.' .$file->getExtension() );
                                            $namespace = sprintf( '\Routiz\Inc\Src\Notification\Notifications\%s', str_replace( '-', '_', $name ) );

                                            // notification not found
                                            if( ! class_exists( $namespace ) ) {
                                                continue;
                                            }

                                            if( $file->isDot() ) {
                                                continue;
                                            }

                                        ?>

                                        <div class="rz--section<?php if( ! $active ) { echo ' rz--active'; } ?>" data-id="<?php echo esc_attr( $file->getBasename( '.' .$file->getExtension() ) ); ?>">
                                            <div class="rz-grid">
                                                <?php

                                                    $name = $file->getBasename( '.' .$file->getExtension() );
                                                    $namespace = sprintf( '\Routiz\Inc\Src\Notification\Notifications\%s', str_replace( '-', '_', $name ) );
                                                    $notif = new $namespace;
                                                    $id = $notif->get_id();

                                                    $form->render([
                                                        'type' => 'checkbox',
                                                        'id' => sprintf('is_notification_site_%s', $id ),
                                                        'name' => esc_html__('Send Site Notification', 'routiz'),
                                                        'html' => [
                                                            'text' => esc_html__( 'Enable', 'routiz' )
                                                        ],
                                                    ]);

                                                    ?>

                                                    <div class="rz-form-group rz-field rz-col-12" data-dependency='{"id":"is_notification_site_<?php echo esc_attr( $id ); ?>","value":true,"compare":"="}'>
                                                        <p class="rz-mb-1"><?php esc_html_e( 'Preview', 'routiz' ); ?>:</p>
                                                        <div class="rz-in-site-preview">
                                                            <div class="rz--inner">
                                                                <?php if( $icon = $notif->get_site_icon() ): ?>
                                                                    <div class="rz--icon">
                                                                        <i class="<?php echo $icon ? esc_html( $icon ) : 'fas fa-map-marker-alt'; ?>"></i>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <?php if( $text = $notif->get_site_message() ): ?>
                                                                    <div class="rz--text">
                                                                        <?php echo esc_html( $text ); ?>
                                                                        <span class="rz--date">
                                                                            <?php echo date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( date('U') ) ); ?>
                                                                        </span>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <div class="rz--dot">
                                                                    <span></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <?php

                                                    $form->render([
                                                        'type' => 'separator',
                                                    ]);

                                                    $form->render([
                                                        'type' => 'checkbox',
                                                        'id' => sprintf('is_notification_email_%s', $id ),
                                                        'name' => esc_html__('Send Email to User', 'routiz'),
                                                        'html' => [
                                                            'text' => esc_html__( 'Enable', 'routiz' )
                                                        ],
                                                    ]);

                                                    $email_template = $form->create([
                                                        'type' => 'textarea',
                                                        'id' => sprintf('notification_email_template_%s', $id ),
                                                        'name' => esc_html__('Email Template to User', 'routiz'),
                                                        'description' => sprintf( esc_html__('The use of HTML and %s is allowed.', 'routiz'), '<a href="#" data-modal="panel-template-fields"><strong>' . esc_html__('custom fields', 'routiz') . '</strong></a>' ),
                                                        'dependency' => [
                                                            'id' => sprintf('is_notification_email_%s', $id ),
                                                            'value' => true,
                                                            'compare' => '=',
                                                        ],
                                                    ]);

                                                    if( empty( $email_template->props->value ) ) {
                                                        $email_template->props->value = $notif->get_email_admin_template();
                                                    }

                                                    echo $email_template->get();

                                                    $form->render([
                                                        'type' => 'separator',
                                                    ]);

                                                    $form->render([
                                                        'type' => 'checkbox',
                                                        'id' => sprintf('is_notification_email_admin_%s', $id ),
                                                        'name' => esc_html__('Send Email to the Admin', 'routiz'),
                                                        'html' => [
                                                            'text' => esc_html__( 'Enable', 'routiz' )
                                                        ],
                                                    ]);

                                                    $email_template_admin = $form->create([
                                                        'type' => 'textarea',
                                                        'id' => sprintf('notification_email_template_admin_%s', $id ),
                                                        'name' => esc_html__('Email Template to Admin', 'routiz'),
                                                        'description' => sprintf( esc_html__('The use of HTML and %s is allowed.', 'routiz'), '<a href="#" data-modal="panel-template-fields"><strong>' . esc_html__('custom fields', 'routiz') . '</strong></a>' ),
                                                        'dependency' => [
                                                            'id' => sprintf('is_notification_email_admin_%s', $id ),
                                                            'value' => true,
                                                            'compare' => '=',
                                                        ],
                                                    ]);

                                                    if( empty( $email_template_admin->props->value ) ) {
                                                        $email_template_admin->props->value = $notif->get_email_admin_template();
                                                    }

                                                    echo $email_template_admin->get();

                                                    $form->render([
                                                        'type' => 'separator',
                                                    ]);

                                                    $form->render([
                                                        'type' => 'checkbox',
                                                        'id' => sprintf('is_notification_webhook_%s', $id ),
                                                        'name' => esc_html__('Enable Webhook', 'routiz'),
                                                        'html' => [
                                                            'text' => esc_html__( 'Enable', 'routiz' )
                                                        ],
                                                    ]);

                                                    $form->render([
                                                        'type' => 'heading',
                                                        'description' => sprintf(
                                                            esc_html__("The webhook is a way for an app to provide other applications with real-time information.\r\n %s.\r\n %s.", 'routiz'),
                                                            '<a href="https://utillz.com/documentation/account/notifications/webhooks/" target="_blank">' . esc_html__('Learn more abount webhooks', 'routiz') . '</a>',
                                                            '<a href="#" data-modal="panel-webhook-fields"><strong>' . esc_html__('What fields are being sent to the webhook url', 'routiz') . '</strong></a>'
                                                        ),
                                                        'dependency' => [
                                                            'id' => sprintf('is_notification_webhook_%s', $id ),
                                                            'value' => true,
                                                            'compare' => '=',
                                                        ],
                                                        'class' => [
                                                            'rz-mb-0'
                                                        ]
                                                    ]);

                                                    $form->render([
                                                        'type' => 'text',
                                                        'id' => sprintf('notification_webhook_url_%s', $id ),
                                                        'name' => esc_html__('Webhook URL', 'routiz'),
                                                        'dependency' => [
                                                            'id' => sprintf('is_notification_webhook_%s', $id ),
                                                            'value' => true,
                                                            'compare' => '=',
                                                        ],
                                                    ]);

                                                    $form->render([
                                                        'type' => 'text',
                                                        'id' => sprintf('notification_webhook_custom_fields_%s', $id ),
                                                        'name' => esc_html__('Webhook Custom Fields', 'routiz'),
                                                        'description' => sprintf(
                                                            esc_html__('%s: you can add additional custom meta fields using a simple comma-separated string, like: %s. webhook_param_name stands for the parameter name that will be sent to the webhook and listing_meta_key stands for the post meta key, that you want to extract from the listing and send. The post meta value will be collected from the listing post type.', 'routiz'),
                                                            '<strong>' . esc_html__('Advanced users only', 'routiz') . '</strong>',
                                                            '<strong>webhook_param_name:listing_meta_key</strong>'
                                                        ),
                                                        'dependency' => [
                                                            'id' => sprintf('is_notification_webhook_%s', $id ),
                                                            'value' => true,
                                                            'compare' => '=',
                                                        ],
                                                    ]);

                                                    ?>

                                                    <div class="rz-form-group rz-field rz-col-12" data-dependency='{"id":"is_notification_webhook_<?php echo esc_attr( $id ); ?>","value":true,"compare":"="}'>
                                                        <a href="#" class="rz-button rz-small" data-action="trigger-webhook" data-for="<?php echo sprintf('notification_webhook_url_%s', $id ); ?>">
                                                            <span><?php esc_html_e( 'Trigger webhook', 'routiz' ); ?></span>
                                                            <?php echo Rz()->preloader(); ?>
                                                        </a>
                                                    </div>

                                                    <?php

                                                    $active = true;

                                                ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <div class="rz-form-group rz-col-12 rz-text-center rz-mt-3">
                                    <button type="submit" class="rz-button rz-large">
                                        <span><?php esc_html_e( 'Save Changes', 'routiz' ); ?></span>
                                    </button>
                                </div>

                            </div>
                        </div>

                    </aside>

                </section>

                <section class="rz-sections" :class="{'rz-none': tabMain !== 'listings'}">

                    <aside class="rz-section rz-section-large" :class="{'rz-none': tabSub !== 'taxonomies'}">
                        <div class="rz-panel-heading">
                            <h3 class="rz-title"><?php esc_html_e( 'Taxonomies', 'routiz' ); ?></h3>
                        </div>
                        <div class="rz-form">
                            <div class="rz-grid">
                                <?php

                                    $form->render([
                                        'type' => 'repeater',
                                        'id' => 'custom_taxonomies',
                                        'name' => esc_html__('Custom Taxonomies', 'routiz'),
                                        'button' => [
                                            'label' => esc_html__('Add Taxonomy', 'routiz')
                                        ],
                                        'templates' => [

                                            /*
                                             * custom taxonomy
                                             *
                                             */
                                            'text' => [
                                                'name' => 'Taxonomy',
                                                'heading' => 'name',
                                                'fields' => [
                                                    'name' => [
                                                        'type' => 'text',
                                                        'name' => esc_html__( 'Name', 'routiz' ),
                                                        'placeholder' => esc_html__( 'Amenities', 'routiz' ),
                                                        'col' => 6
                                                    ],
                                                    'slug' => [
                                                        'type' => 'key',
                                                        'name' => esc_html__( 'Taxonomy Slug', 'routiz' ),
                                                        'placeholder' => 'amenities',
                                                        'defined' => false,
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
                        </div>
                    </aside>

                    <aside class="rz-section rz-section-large" :class="{'rz-none': tabSub !== 'social'}">
                        <div class="rz-panel-heading">
                            <h3 class="rz-title"><?php esc_html_e( 'Social', 'routiz' ); ?></h3>
                        </div>
                        <div class="rz-form">
                            <div class="rz-grid">
                                <?php

                                    $form->render([
                                        'type' => 'checkbox',
                                        'id' => 'enable_share',
                                        'name' => esc_html__('Enable Listing Sharing', 'routiz'),
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
                            <h3 class="rz-title"><?php esc_html_e( 'Submission', 'routiz' ); ?></h3>
                        </div>
                        <div class="rz-form">
                            <div class="rz-grid">
                                <?php

                                    $form->render([
                                        'type' => 'checkbox',
                                        'id' => 'enable_submission_listing_counter',
                                        'name' => esc_html__('Enable Submission Listing Counter?', 'routiz'),
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

                    <aside class="rz-section rz-section-large" :class="{'rz-none': tabSub !== 'booking'}">
                        <div class="rz-panel-heading">
                            <h3 class="rz-title"><?php esc_html_e( 'Booking', 'routiz' ); ?></h3>
                        </div>
                        <div class="rz-form">
                            <div class="rz-grid">
                                <?php

                                    $form->render([
                                        'type' => 'checkbox',
                                        'id' => 'is_pending_booking_expiration',
                                        'name' => esc_html__('Enable Pending Booking Expiration', 'routiz'),
                                        'description' => esc_html__('Check this option if you want to add expiration days for pending booking dates', 'routiz'),
                                    ]);

                                    /*$form->render([
                                        'type' => 'number',
                                        'id' => 'pending_booking_expiration_days',
                                        'name' => esc_html__('Pending Booking Expiration in Days', 'routiz'),
                                        'min' => 1,
                                        'max' => 30,
                                        'step' => 1,
                                        'dependency' => [
                                            'id' => 'is_pending_booking_expiration',
                                            'value' => 1,
                                            'compare' => '=',
                                        ],
                                    ]);*/

                                    $form->render([
                                        'type' => 'number',
                                        'id' => 'days_booking_pending_payment',
                                        'name' => esc_html__( 'After how many hours a reservation would be canceled?', 'routiz' ),
                                        'description' => esc_html__( 'Insert the number of hours a pending reservation must be canceled if the customer does not make the payment after the listing author has confirmed the availability.', 'routiz' ),
                                        'min' => 1,
                                        'max' => 168,
                                        'step' => 1,
                                        'value' => 24,
                                        'placeholder' => 24,
                                        'dependency' => [
                                            'id' => 'is_pending_booking_expiration',
                                            'value' => 1,
                                            'compare' => '=',
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

                <section class="rz-sections" :class="{'rz-none': tabMain !== 'explore'}">

                    <aside class="rz-section rz-section-large" :class="{'rz-none': tabSub !== 'general'}">
                        <div class="rz-panel-heading">
                            <h3 class="rz-title"><?php esc_html_e( 'Explore', 'routiz' ); ?></h3>
                        </div>
                        <div class="rz-form">
                            <div class="rz-grid">
                                <?php

                                    // $form->render([
                                    //     'type' => 'listing-types',
                                    //     'id' => 'main_listing_type',
                                    //     'name' => esc_html__('Select your main listing type', 'routiz'),
                                    //     'description' => esc_html__('The main listing type will be the one you search if no listing type was selected', 'routiz'),
                                    //     'allow_empty' => false,
                                    //     'return_ids' => true
                                    // ]);

                                    $form->render([
                                        'type' => 'select',
                                        'id' => 'global_explore_type',
                                        'name' => esc_html__('Global Explore Page Type', 'routiz'),
                                        'options' => [
                                            'map' => esc_html__('Map', 'routiz'),
                                            'full' => esc_html__('Full-width, no map', 'routiz'),
                                        ],
                                        'allow_empty' => false
                                    ]);

                                    /*$form->render([
                                        'type' => 'select',
                                        'id' => 'global_explore_style',
                                        'name' => esc_html__('Global Explore Page Style', 'routiz'),
                                        'options' => [
                                            '1_3' => esc_html__('1/3 map', 'routiz'),
                                            '1_2' => esc_html__('1/2 map', 'routiz'),
                                        ],
                                        'allow_empty' => false
                                    ]);*/

                                    $form->render([
                                        'type' => 'text',
                                        'id' => 'global_map_lat',
                                        'name' => esc_html__('Default Map Latitude Coordinates', 'routiz'),
                                        'col' => 6,
                                    ]);

                                    $form->render([
                                        'type' => 'text',
                                        'id' => 'global_map_lng',
                                        'name' => esc_html__('Default Map Longitude Coordinates', 'routiz'),
                                        'col' => 6,
                                    ]);

                                    $form->render([
                                        'type' => 'checkbox',
                                        'id' => 'hide_edit_search_form',
                                        'name' => esc_html__( 'Hide Search Form Edit Button', 'routiz' ),
                                        'description' => esc_html__( 'Hide the edit button that appears when you are logged in as administrator', 'routiz' ),
                                    ]);

                                    $form->render([
                                        'type' => 'select',
                                        'id' => 'global_search_form',
                                        'name' => esc_html__( 'Global Search Form', 'routiz' ),
                                        'description' => esc_html__( 'Filters will appear when you explore the main explore page with no listing type selected.', 'routiz' ),
                                        'options' => [
                                            'query' => [
                                                'post_type' => 'rz_search_form',
                                                'posts_per_page' => -1,
                                            ]
                                        ],
                                        'col' => 6
                                    ]);

                                    $form->render([
                                        'type' => 'select',
                                        'id' => 'global_search_form_more',
                                        'name' => esc_html__( 'Global Search Form ( More )', 'routiz' ),
                                        'description' => esc_html__( 'Additional filters that will appear in a popup.', 'routiz' ),
                                        'options' => [
                                            'query' => [
                                                'post_type' => 'rz_search_form',
                                                'posts_per_page' => -1,
                                            ]
                                        ],
                                        'col' => 6
                                    ]);

                                    $form->render([
                                        'type' => 'number',
                                        'id' => 'global_listings_per_page',
                                        'name' => esc_html__('Global Listings per Page', 'routiz'),
                                        'input_type' => 'stepper',
                                        'format' => '%s listings',
                                        'min' => 1,
                                        'max' => 50,
                                        'step' => 1,
                                        'value' => 10,
                                    ]);

                                    $form->render([
                                        'type' => 'radio',
                                        'id' => 'promoted_listings_display',
                                        'name' => esc_html__('Promoted Listings Display', 'routiz'),
                                        'options' => [
                                            'inline' => esc_html__('Inline - sort promoted listings by priority level and date of publishing', 'routiz'),
                                            'random' => esc_html__('Random - display promoted listings randomly. This option will ignore the priority level', 'routiz'),
                                        ],
                                        'value' => 'inline',
                                    ]);

                                    $form->render([
                                        'type' => 'number',
                                        'id' => 'random_promoted_listings_num',
                                        'name' => esc_html__('Select the Number of Promoted Listings to Display', 'routiz'),
                                        'input_type' => 'stepper',
                                        'format' => '%s listings',
                                        'min' => 1,
                                        'max' => 50,
                                        'step' => 1,
                                        'value' => 2,
                                        'dependency' => [
                                            'id' => 'promoted_listings_display',
                                            'value' => 'random',
                                            'compare' => '=',
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

                <section class="rz-sections" :class="{'rz-none': tabMain !== 'integration'}">

                    <aside class="rz-section rz-section-large" :class="{'rz-none': tabSub !== 'maps'}">
                        <div class="rz-panel-heading">
                            <h3 class="rz-title"><?php esc_html_e( 'Maps', 'routiz' ); ?></h3>
                        </div>
                        <div class="rz-form">
                            <div class="rz-grid">
                                <?php

                                    $form->render([
                                        'type' => 'select',
                                        'id' => 'map_provider',
                                        'name' => esc_html__('Select Map Provider', 'routiz'),
                                        'options' => [
                                            'google' => esc_html__('Google Maps', 'routiz'),
                                            'mapbox' => esc_html__('Mapbox', 'routiz'),
                                        ],
                                        'allow_empty' => false
                                    ]);

                                    $form->render([
                                        'type' => 'text',
                                        'id' => 'google_api_key',
                                        'name' => esc_html__('Google Map Api Key', 'routiz'),
                                        'description' => __('To use the Maps JavaScript API you must have an API key. The API key is a unique identifier that is used to authenticate requests associated with your project for usage and billing purposes. Get one <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">here</a>.', 'routiz'),
                                        'dependency' => [
                                            'id' => 'map_provider',
                                            'value' => 'google',
                                        ],
                                    ]);

                                    $form->render([
                                        'type' => 'text',
                                        'id' => 'geolocation_restrictions',
                                        'name' => esc_html__('Geolocation Restrictions', 'routiz'),
                                        'description' => esc_html__('You can restrict the geolocation autofill by selected countries. Example: ES. You can also add multiple counties with comma, example: ES, FR, DE', 'routiz'),
                                        'dependency' => [
                                            'id' => 'map_provider',
                                            'value' => 'google',
                                        ],
                                    ]);

                                    $form->render([
                                        'type' => 'text',
                                        'id' => 'mapbox_access_token',
                                        'name' => esc_html__('Mapbox Access Token', 'routiz'),
                                        'description' => __('To use Mapbox GL JS, you need to have a Mapbox <a href="https://docs.mapbox.com/help/getting-started/access-tokens/" target="_blank">access token</a>. This access token associates your map with a Mapbox account. For more information on creating and using access tokens, see our <a href="https://docs.mapbox.com/accounts/guides/tokens/" target="_blank">token management documentation</a>.', 'routiz'),
                                        'dependency' => [
                                            'id' => 'map_provider',
                                            'value' => 'mapbox',
                                        ],
                                    ]);

                                    $form->render([
                                        'type' => 'text',
                                        'id' => 'mapbox_style_url',
                                        'name' => esc_html__('Mapbox Style URL', 'routiz'),
                                        'placeholder' => 'mapbox://styles/mapbox/streets-v11',
                                        'description' => __('Learn more about <a href="https://docs.mapbox.com/api/maps/styles/" target="_blank">Mapbox styles</a>', 'routiz'),
                                        'dependency' => [
                                            'id' => 'map_provider',
                                            'value' => 'mapbox',
                                        ],
                                    ]);

                                    $form->render([
                                        'type' => 'radio_image',
                                        'id' => 'google_map_style',
                                        'name' => esc_html__( 'Google Map Style', 'routiz' ),
                                        'description' => __( 'Add your personal touch by customizing the map. You can use our pre-defined map styles or click "Custom" to apply your own JavaScript style array.', 'routiz' ),
                                        'value' => 'image_1',
                                        'options' => [
                                            'default' => [
                                                'label' => esc_html__( 'Default', 'routiz' ),
                                                'image' => RZ_URI . 'assets/dist/images/admin/map-styles/default.png',
                                            ],
                                            'lost-in-the-desert' => [
                                                'label' => esc_html__( 'Lost in the Desert', 'routiz' ),
                                                'image' => RZ_URI . 'assets/dist/images/admin/map-styles/lost-in-the-desert.png',
                                            ],
                                            'blue-essense' => [
                                                'label' => esc_html__( 'Blue Essense', 'routiz' ),
                                                'image' => RZ_URI . 'assets/dist/images/admin/map-styles/blue-essense.png',
                                            ],
                                            'ultra-light-with-labels' => [
                                                'label' => esc_html__( 'Ultra Light with Labels', 'routiz' ),
                                                'image' => RZ_URI . 'assets/dist/images/admin/map-styles/ultra-light-with-labels.png',
                                            ],
                                            'xxxxxx' => [
                                                'label' => esc_html__( 'Xxxxxx', 'routiz' ),
                                                'image' => RZ_URI . 'assets/dist/images/admin/map-styles/xxxxxx.png',
                                            ],
                                            'shades-of-grey' => [
                                                'label' => esc_html__( '38 Shades of Grey', 'routiz' ),
                                                'image' => RZ_URI . 'assets/dist/images/admin/map-styles/shades-of-grey.png',
                                            ],
                                            'modest' => [
                                                'label' => esc_html__( 'Modest', 'routiz' ),
                                                'image' => RZ_URI . 'assets/dist/images/admin/map-styles/modest.png',
                                            ],
                                            'light-dream' => [
                                                'label' => esc_html__( 'Light Dream', 'routiz' ),
                                                'image' => RZ_URI . 'assets/dist/images/admin/map-styles/light-dream.png',
                                            ],
                                            'apple-maps-esque' => [
                                                'label' => esc_html__( 'Apple Maps Esque', 'routiz' ),
                                                'image' => RZ_URI . 'assets/dist/images/admin/map-styles/apple-maps-esque.png',
                                            ],
                                            'multi-brand-network' => [
                                                'label' => esc_html__( 'Multi Brand Network', 'routiz' ),
                                                'image' => RZ_URI . 'assets/dist/images/admin/map-styles/multi-brand-network.png',
                                            ],
                                            'light-and-dark' => [
                                                'label' => esc_html__( 'Light and Dark', 'routiz' ),
                                                'image' => RZ_URI . 'assets/dist/images/admin/map-styles/light-and-dark.png',
                                            ],
                                            'custom' => [
                                                'label' => esc_html__( 'Custom', 'routiz' ),
                                                'image' => '<div class="rz--label"><i class="rz-map-style-label rz--map-custom"><em class="fas fa-map-marker-alt"></em></i></div>',
                                            ],
                                        ],
                                        'dependency' => [
                                            'id' => 'map_provider',
                                            'value' => 'google',
                                        ],
                                    ]);

                                    $form->render([
                                        'type' => 'textarea',
                                        'id' => 'google_map_style_custom',
                                        'name' => esc_html__('Custom Map Styles', 'routiz'),
                                        'description' => __('Insert your map style here. Customize colors, roads, labels and more. You can browse ready to use map styles <a target="_blank" href="https://snazzymaps.com/">here</a>. Just copy the JavaScript style array.', 'routiz'),
                                        'options' => [
                                            'query' => [
                                                'post_type' => 'page',
                                                'posts_per_page' => -1,
                                            ]
                                        ],
                                        'dependency' => [
                                            'relation' => 'and',
                                            [
                                                'id' => 'map_provider',
                                                'value' => 'google',
                                            ],
                                            [
                                                'id' => 'google_map_style',
                                                'value' => 'custom',
                                                'compare' => '=',
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

                    <aside class="rz-section rz-section-large" :class="{'rz-none': tabSub !== 'auth'}">
                        <div class="rz-panel-heading">
                            <h3 class="rz-title"><?php esc_html_e( 'Authentication', 'routiz' ); ?></h3>
                        </div>
                        <div class="rz-form">
                            <div class="rz-grid">

                                <?php if( ! get_option( 'users_can_register' ) ): ?>
                                    <div class="rz-form-group rz-col-12">
                                        <div class="rz-notice rz-notice-alert">
                                            <?php esc_html_e( 'User registration is disable for the current site. You can enable it from the WordPress settings', 'routiz' ); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php

                                    $form->render([
                                        'type' => 'checkbox',
                                        'id' => 'enable_standard_pass',
                                        'name' => esc_html__('Enable Standard Sign up Password Input', 'routiz'),
                                        'description' => esc_html__('Enable the option and allow your visitors to set their own password in the sign up process', 'routiz'),
                                    ]);

                                    $form->render([
                                        'type' => 'checkbox',
                                        'id' => 'enable_standard_role',
                                        'name' => esc_html__('Enable Standard Sign up Role Input', 'routiz'),
                                        'description' => esc_html__('Enable the option and allow your visitors to set their role', 'routiz'),
                                    ]);

                                    $form->render([
                                        'type' => 'select',
                                        'id' => 'default_user_role',
                                        'name' => esc_html__('Default User Role', 'routiz'),
                                        'options' => [
                                            'customer' => esc_html__('Customer', 'routiz'),
                                            'business' => esc_html__('Business', 'routiz'),
                                        ],
                                        'value' => 'user',
                                        'allow_empty' => false,
                                        'dependency' => [
                                            'id' => 'enable_standard_role',
                                            'value' => 1,
                                            'compare' => '!=',
                                            'style' => 'rz-opacity-30',
                                        ],
                                    ]);

                                    $form->render([
                                        'type' => 'checkbox',
                                        'id' => 'enable_signup_phone',
                                        'name' => esc_html__('Enable Sign Up Phone Number Field', 'routiz'),
                                    ]);

                                    $form->render([
                                        'type' => 'checkbox',
                                        'id' => 'is_signup_phone_required',
                                        'name' => esc_html__('Make Phone Number Required', 'routiz'),
                                        'dependency' => [
                                            'id' => 'enable_signup_phone',
                                            'value' => 1,
                                            'compare' => '=',
                                        ],
                                    ]);
                                    $form->render([
                                        'type' => 'checkbox',
                                        'id' => 'enable_author_details',
                                        'name' => esc_html__('Enable User or Author Contact Details On User Profile', 'routiz'),
                                        'value' => 0,
                                        'compare' => '=',
                                    ]);

                                    $form->render([
                                        'type' => 'checkbox',
                                        'id' => 'enable_signup_captcha',
                                        'name' => esc_html__('Enable Sign Up Google Captcha', 'routiz'),
                                    ]);

                                    $form->render([
                                        'type' => 'text',
                                        'id' => 'signup_captcha_key',
                                        'name' => esc_html__('Google Captcha Site Key', 'routiz'),
                                        'description' => __('Get one <a href="https://www.a2hosting.in/kb/security/obtaining-google-recaptcha-site-key-and-secret-key" target="_blank">here</a>.', 'routiz'),
                                        'dependency' => [
                                            'id' => 'enable_signup_captcha',
                                            'value' => 1,
                                            'compare' => '=',
                                        ],
                                    ]);

                                    $form->render([
                                        'type' => 'checkbox',
                                        'id' => 'enable_signup_terms',
                                        'name' => esc_html__('Enable Sign Up Terms Field', 'routiz'),
                                    ]);

                                    $form->render([
                                        'type' => 'text',
                                        'id' => 'signup_terms_text',
                                        'name' => esc_html__('Sign Up Terms Text', 'routiz'),
                                        'dependency' => [
                                            'id' => 'enable_signup_terms',
                                            'value' => 1,
                                            'compare' => '=',
                                        ],
                                    ]);

                                    $form->render([
                                        'type' => 'separator',
                                    ]);

                                    $form->render([
                                        'type' => 'checkbox',
                                        'id' => 'enable_facebook_auth',
                                        'name' => esc_html__('Enable Facebook Sign In', 'routiz'),
                                    ]);

                                    $form->render([
                                        'type' => 'text',
                                        'id' => 'facebook_app_id',
                                        'name' => esc_html__('Facebook Api ID', 'routiz'),
                                        'dependency' => [
                                            'id' => 'enable_facebook_auth',
                                            'value' => 1,
                                            'compare' => '=',
                                            'style' => 'rz-opacity-30',
                                        ],
                                        'col' => 6
                                    ]);

                                    $form->render([
                                        'type' => 'text',
                                        'id' => 'facebook_app_secret',
                                        'name' => esc_html__('Facebook Api Secret', 'routiz'),
                                        'dependency' => [
                                            'id' => 'enable_facebook_auth',
                                            'value' => 1,
                                            'compare' => '=',
                                            'style' => 'rz-opacity-30',
                                        ],
                                        'col' => 6
                                    ]);

                                    $form->render([
                                        'type' => 'separator',
                                    ]);

                                    $form->render([
                                        'type' => 'checkbox',
                                        'id' => 'enable_google_auth',
                                        'name' => esc_html__('Enable Google Sign In', 'routiz'),
                                    ]);

                                    $form->render([
                                        'type' => 'text',
                                        'id' => 'google_client_id',
                                        'name' => esc_html__('Google Client ID', 'routiz'),
                                        'dependency' => [
                                            'id' => 'enable_google_auth',
                                            'value' => 1,
                                            'compare' => '=',
                                            'style' => 'rz-opacity-30',
                                        ],
                                        'col' => 6
                                    ]);

                                    $form->render([
                                        'type' => 'text',
                                        'id' => 'google_client_secret',
                                        'name' => esc_html__('Google Client Secret', 'routiz'),
                                        'dependency' => [
                                            'id' => 'enable_google_auth',
                                            'value' => 1,
                                            'compare' => '=',
                                            'style' => 'rz-opacity-30',
                                        ],
                                        'col' => 6
                                    ]);

                                ?>

                                <div class="rz-form-group rz-col-12 rz-text-center rz-mt-3">
                                    <button id="social_login_btn" type="submit" class="rz-button rz-large">
                                        <span><?php esc_html_e( 'Save Changes', 'routiz' ); ?></span>
                                    </button>
                                </div>

                            </div>
                        </div>
                    </aside>

                </section>

            </div>
        </div>

    </form>

    <?php Rz()->the_template('routiz/admin/panel/modal/template-fields'); ?>
    <?php Rz()->the_template('routiz/admin/panel/modal/webhook-fields'); ?>

</div>
