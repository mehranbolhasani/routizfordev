@if( ! empty( $content ) )
    <div class="rz-mod-content">
        @if( ! empty( $name ) )
            <h4>{{ $name }}</h4>
        @endif
        {!! do_shortcode( wpautop( wp_kses_post( html_entity_decode( ( $content ) ) ) ) ) !!}
    </div>
@endif
