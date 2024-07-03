<div class="rz-action-download">
    <h4>{{ $strings->download }}</h4>
    @if( $description )
        <div class="rz--description">
            {!! do_shortcode( wpautop( $description ) ) !!}
        </div>
    @endif
    <div class="rz-action-footer rz-text-center">
        <div class="rz--action">
            <a href="#" class="rz-button rz-large" data-modal="action-application" data-params='{{ $listing_id }}'>
                {{ $strings->buy_now }}
            </a>
        </div>
    </div>
</div>