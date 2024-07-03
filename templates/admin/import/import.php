<?php

defined('ABSPATH') || exit;

use \Routiz\Inc\Src\Importer;

$importer = Importer::instance();
$verified = $importer->is_envato_verified();

?>

<div class="rz-outer">
    <form method="post" autocomplete="nope">

        <div class="rz-panel rz-loading" id="rz-panel" data-tab-start="general/units" :class="{ 'rz-ready' : ready }">

            <div class="rz-content">

                <section class="rz-sections">

                    <aside class="rz-section rz-section-medium">

                        <div class="rz-import">

                            <h3 class="rz-import-title"><?php esc_html_e( 'Demo import', 'routiz' ); ?></h3>
                            <p><?php esc_html_e( 'Select the demo you want to import and click the button below.', 'routiz' ); ?></p>

                            <?php if( get_option( 'rz_is_demo_imported' ) ): ?>
                                <div class="rz--success">
                                    <div class="rz--icon">
                                        <i class="fas fa-smile"></i>
                                    </div>
                                    <div class="rz--content">
                                        <?php esc_html_e( 'Demo has been successfully imported', 'routiz' ); ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="rz--system">
                                <h4 class="rz--title"><?php esc_html_e( 'Recommended server stats', 'routiz' ); ?>:</h4>
                                <ul>
                                    <li class="<?php if( (int) $importer->requirements['max_execution_time'] >= 180 || (int) $importer->requirements['max_execution_time'] == 0 ) { echo 'rz--ok'; } ?>"><?php echo (int) $importer->requirements['max_execution_time'] >= 180 || (int) $importer->requirements['max_execution_time'] == 0 ? '<i class="far fa-check-circle"></i>' : '<i class="far fa-times-circle"></i>'; ?>max_execution_time: <?php echo (int) $importer->requirements['max_execution_time']; ?> ( <?php echo sprintf( esc_html__('>= %s recommended', 'routiz'), 180 ); ?> )</li>
                                    <li class="<?php if( (int) $importer->requirements['memory_limit'] >= 128 ) { echo 'rz--ok'; } ?>"><?php echo (int) $importer->requirements['memory_limit'] >= 128 ? '<i class="far fa-check-circle"></i>' : '<i class="far fa-times-circle"></i>'; ?>memory_limit: <?php echo (int) $importer->requirements['memory_limit']; ?> ( <?php echo sprintf( esc_html__('>= %s recommended', 'routiz'), 128 ); ?> )</li>
                                    <li class="<?php if( (int) $importer->requirements['post_max_size'] >= 32 ) { echo 'rz--ok'; } ?>"><?php echo (int) $importer->requirements['post_max_size'] >= 32 ? '<i class="far fa-check-circle"></i>' : '<i class="far fa-times-circle"></i>'; ?>post_max_size: <?php echo (int) $importer->requirements['post_max_size']; ?> ( <?php echo sprintf( esc_html__('>= %s recommended', 'routiz'), 32 ); ?> )</li>
                                    <li class="<?php if( (int) $importer->requirements['upload_max_filesize'] >= 32 ) { echo 'rz--ok'; } ?>"><?php echo (int) $importer->requirements['upload_max_filesize'] >= 32 ? '<i class="far fa-check-circle"></i>' : '<i class="far fa-times-circle"></i>'; ?>upload_max_filesize: <?php echo (int) $importer->requirements['upload_max_filesize']; ?> ( <?php echo sprintf( esc_html__('>= %s recommended', 'routiz'), 32 ); ?> )</li>
                                </ul>
                            </div>

                            <?php $demos = $importer->get_demos(); ?>
                            <?php if( $demos ): ?>
                                <h4 class="rz--title"><?php esc_html_e( 'Available demos', 'routiz' ); ?>:</h4>
                                <div class="rz-demos">
                                    <ul>
                                        <?php foreach( $demos as $key => $demo ): ?>
                                            <li>
                                                <label>
                                                    <input type="radio" name="demo" value="<?php echo esc_attr( $key ); ?>">
                                                    <div class='rz--image rz-no-select'>
                                                        <img src="<?php echo esc_url( $demo['thumbnail'] ); ?>" alt="">
                                                        <span><?php echo esc_html( $demo['name'] ); ?></span>
                                                    </div>
                                                </label>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>

                                    <?php if( ! $verified ): ?>
                                        <div class="rz--error">
                                            <div class="rz--icon">
                                                <i class="fas fa-bell"></i>
                                            </div>
                                            <div class="rz--content">
                                                <?php esc_html_e( 'You need to verify your ownership using `Envato Market` plugin to be able to use the demo importer.', 'routiz' ); ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <div class="rz-text-center">

                                        <?php if( $verified ): ?>
                                            <a href="#" class="rz-button rz-large" data-action="import-demos">
                                                <span><?php esc_html_e( 'Start Demo Import', 'routiz' ); ?></span>
                                                <span class="fas fa-truck-loading rz-ml-1"></span>
                                                <?php Rz()->preloader(); ?>
                                            </a>
                                        <?php else: ?>
                                            <a href="#" class="rz-button rz-large rz-disabled">
                                                <span><?php esc_html_e( 'Start Demo Import', 'routiz' ); ?></span>
                                                <span class="fas fa-truck-loading rz-ml-1"></span>
                                            </a>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            <?php else: ?>
                                <p><?php esc_html_e( 'No available demos', 'routiz' ); ?></p>
                            <?php endif; ?>

                        </div>

                    </aside>

                </section>

            </div>

        </div>

    </form>

    <span class="rz-importer-overlay"></span>

    <div class="rz-importer-progress">
        <?php Rz()->preloader(); ?>
        <span class="rz--title"><?php esc_html_e( 'Preparing import', 'routiz' ); ?></span>
        <span class="rz--progress">
            <span class="rz--bar"></span>
        </span>
    </div>

</div>


