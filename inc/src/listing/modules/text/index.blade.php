@if( ! empty( $content ) )
    <div class="rz-mod-content">
        @if( ! empty( $name ) )
            <h4>{{ $name }}</h4>
        @endif
        {!! do_shortcode( wpautop( html_entity_decode( $content ) ) ) !!}
    </div>
@endif
