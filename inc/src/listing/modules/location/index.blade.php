@if( ! empty( $lat ) and ! empty( $lng ) )
    <div class="rz-mod-content @if( $show_address and $address ) rz--has-address @endif">
        @if( ! empty( $name ) )
            <h4>{{ $name }}</h4>
        @endif
        <div class="rz-map-outer">
            <div class="rz-map" data-lat="{{ $lat }}" data-lng="{{ $lng }}"></div>
            <div class="rz-map-zoom">
                <a href="#" class="rz--zoom-in" data-action="explore-map-zoom-in"><i class="fas fa-plus"></i></a>
                <a href="#" class="rz--zoom-out" data-action="explore-map-zoom-out"><i class="fas fa-minus"></i></a>
            </div>
            <div class="rz-mod-listing-marker-content rz-none">
                <span class="rz-mod-listing-marker">
                    <i class="{{ $icon }}"></i>
                </span>
            </div>
            @if( $show_address and $address )
                <div class="rz--address">
                    <i class="rz--icon fas fa-map-marker-alt"></i>
                    <a href="{{ add_query_arg( [ 'api' => 1, 'query' => urlencode( $address ) ], 'https://www.google.com/maps/search/' ) }}" target="_blank">
                        {{ $address }}
                    </a>
                </div>
            @endif
        </div>
    </div>
@else
    <div class="rz-mod-content">
        <div class="rz-not-specified">
            <i class="fas fa-map-marker-alt"></i>
            <p>{{ $strings->not_specified }}</p>
        </div>
    </div>
@endif

