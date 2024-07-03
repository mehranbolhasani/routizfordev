<?php

defined('ABSPATH') || exit;

use \Routiz\Inc\Src\Listing\Listing;

global $rz_row;

$request = \Routiz\Inc\Src\Request\Request::instance();

// taxonomy url args
$taxonomy_url_args = [ $rz_row->taxonomy => $rz_row->slug ];

// set listing type
if( ! $request->is_empty('search_form_id') ) {
    if( $listing_type_slug = get_post_meta( $request->get('search_form_id'), 'rz_listing_type', true ) ) {
        $taxonomy_url_args['type'] = $listing_type_slug;
    }
}

?>

<li>
    <a href="#" class="rz-selectable rz-no-transition" data-taxonomy="<?php echo esc_attr( $rz_row->taxonomy ); ?>" data-value="<?php echo esc_attr( $rz_row->slug ); ?>">
        <?php if( $rz_row->icon ): ?>
        <div class="rz-auto-icon rz-flex rz-flex-column rz-justify-center rz-text-center">
            <i class="<?php echo esc_attr( $rz_row->icon ); ?>"></i>
        </div>
        <?php endif; ?>
        <div class="rz-auto-content rz-ellipsis">
            <?php echo $rz_row->name; ?>
        </div>
    </a>
</li>
