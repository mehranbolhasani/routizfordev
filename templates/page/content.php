<?php

defined('ABSPATH') || exit;

if( Brk()->is_elementor() ) {
    return;
}

?>

<div class="rz-row">
    <br>
    <br>
    <br>
    <div class="rz-notice rz-mt-5">
        <p><?php echo sprintf( esc_html__('Please re-create your page layouts using Elementor plugin or import one of the pre-defined pages. For more information, %s.', 'routiz'), '<a href="https://utillz.ticksy.com/article/16739/" target="blank">' . esc_html__('check this article', 'routiz') . '</a>' ); ?></p>
    </div>
</div>
