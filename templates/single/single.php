<?php

defined('ABSPATH') || exit;

global $rz_listing;
$rz_listing = new \Routiz\Inc\Src\Listing\Listing();

?>

<section id="primary" class="content-area">
    <div class="site-main">

        <input type="hidden" id="rz_listing_id" value="<?php the_ID(); ?>">
        <div class="rz-cover">
            <?php Rz()->the_template('routiz/single/cover'); ?>
        </div>
        <div class="rz-grid">
            <div class="rz-col">

                <div class="rz-single">
                    <div class="rz-container">
                        <div class="rz-content">
                            <?php Rz()->the_template('routiz/single/content'); ?>
                        </div>
                        <?php Rz()->the_template('routiz/single/sidebar'); ?>
                    </div>
                    <?php Rz()->the_template('routiz/single/nearby'); ?>
                    <?php Rz()->the_template('routiz/single/similar'); ?>
                </div>

            </div>
        </div>

    </div>
</section>

<?php Rz()->the_template('routiz/single/modal/report'); ?>
