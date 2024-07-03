<?php

defined('ABSPATH') || exit;

?>

<div class="rz-archive-mods">
    <?php

        $components = \Routiz\Inc\Src\Listing_Type\Component::instance();

        $listing_type = get_queried_object();
        $archive_modules = Rz()->jsoning( 'rz_archive_modules', $listing_type->ID );

        foreach( $archive_modules as $params ) {
            $components->render( $params );
        }

    ?>
</div>
