<?php

defined('ABSPATH') || exit;

global $rz_explore;

?>

<div class="rz-dynamic rz-dynamic-welcome">
    <?php if( ! $rz_explore->type ): ?>

        <div class="rz-explore-mods">
            <?php

                $components = \Routiz\Inc\Src\Explore\Component::instance();

                $listing_type = get_queried_object();
                $archive_modules = Rz()->json_decode( get_option( 'rz_explore_modules' ) );

                foreach( $archive_modules as $params ) {
                    $components->render( $params );
                }

            ?>
        </div>

    <?php endif; ?>
</div>
