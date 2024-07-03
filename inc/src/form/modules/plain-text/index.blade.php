@include('label.index')

{!! do_shortcode(wp_kses_post($content)) !!}