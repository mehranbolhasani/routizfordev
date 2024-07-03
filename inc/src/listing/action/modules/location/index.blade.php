<div class="rz-action-location">

    @if( $lat and $lng )

        <div class="rz-action-map-holder">
            <div class="rz-action-map" data-lat="{{ $lat }}" data-lng="{{ $lng }}"></div>

            <div class="rz-map-zoom">
                <a href="#" class="rz--zoom-in" data-action="explore-map-zoom-in"><i class="fas fa-plus"></i></a>
                <a href="#" class="rz--zoom-out" data-action="explore-map-zoom-out"><i class="fas fa-minus"></i></a>
            </div>
        </div>

        @if( $fields )
            <div class="rz-action-info">
                @foreach( $fields as $field )

                    @php
                        $field_value = $listing->get( $field->fields->id )
                    @endphp

                    @if( $field_value )
                        <p>

                            @if( $field->fields->icon )
                                <i class="rz--icon {{ $field->fields->icon }}"></i>
                            @endif

                            @switch( $field->fields->type )

                                @case('address')
                                    <a href="{{ add_query_arg( [ 'api' => 1, 'query' => urlencode( esc_html( $field_value ) ) ], 'https://www.google.com/maps/search/' ) }}" target="_blank">
                                        {{ str_replace( '{field}', $field_value, $field->fields->format ) }}
                                    </a>
                                    @break

                                @case('url')
                                    <a href="{{ esc_url( str_replace( '{field}', $field_value, $field->fields->format ) ) }}" target="_blank">
                                        @if( isset( $field->fields->type_url_label ) and ! empty( $field->fields->type_url_label ) )
                                            {{ $field->fields->type_url_label }}
                                        @else
                                            {{ str_replace( '{field}', $field_value, $field->fields->format ) }}
                                        @endif
                                    </a>
                                    @break

                                @case('phone')
                                    <a href="tel:{{ $field_value }}" target="_blank">
                                        {{ str_replace( '{field}', $field_value, $field->fields->format ) }}
                                    </a>
                                    @break

                                @case('email')
                                    <a href="mailto:{{ $field_value }}" target="_blank">
                                        {{ str_replace( '{field}', $field_value, $field->fields->format ) }}
                                    </a>
                                    @break

                                @case('price')
                                    <span>
                                        {!! str_replace( '{field}', Rz()->format_price( $field_value ), $item->fields->format ) !!}
                                    </span>
                                    @break

                                @default
                                    <span>
                                        {{ str_replace( '{field}', $field_value, $field->fields->format ) }}
                                    </span>

                            @endswitch

                        </p>
                    @endif

                @endforeach
            </div>
        @endif

        <div class="rz--marker rz-none">
            <span class="rz-mod-listing-marker">
                <i class="{{ $icon ? $icon : 'fas fa-map-marker-alt' }}"></i>
            </span>
        </div>

    @else

        <div class="rz--empty">
            <i class="fas fa-map-marker-alt"></i>
            <p>{{ $strings->not_specified }}</p>
        </div>

    @endif

</div>
