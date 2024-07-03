<div class="rz-action-button">

    @if( $enable_author )
        {{ Rz()->the_template('routiz/single/action/author') }}
    @endif

    @if( ! empty( $title ) )
        <h5 class="rz--title">{{ $title }}</h5>
    @endif

    @if( $meta )
        <div class="rz-action-info">
            @foreach( $meta as $m )

                @php
                    $field_value = $listing->get( $m->fields->id );
                @endphp

                @if( $field_value )
                    <p>

                        @if( $m->fields->icon )
                            <i class="rz--icon {{ $m->fields->icon }}"></i>
                        @endif

                        @switch( $m->fields->type )

                            @case('address')
                                <a href="{{ add_query_arg( [ 'api' => 1, 'query' => urlencode( $field_value ) ], 'https://www.google.com/maps/search/' ) }}" target="_blank">
                                    {{ str_replace( '{field}', $field_value, $m->fields->format ) }}
                                </a>
                                @break

                            @case('url')
                                <a href="{{ esc_url( str_replace( '{field}', $field_value, $m->fields->format ) ) }}" target="_blank">
                                    @if( isset( $m->fields->type_url_label ) and ! empty( $m->fields->type_url_label ) )
                                        {{ $m->fields->type_url_label }}
                                    @else
                                        {{ str_replace( '{field}', $field_value, $m->fields->format ) }}
                                    @endif
                                </a>
                                @break

                            @case('phone')
                                <a href="tel:{{ $field_value }}" target="_blank">
                                    {{ str_replace( '{field}', $field_value, $m->fields->format ) }}
                                </a>
                                @break

                            @case('email')
                                <a href="mailto:{{ $field_value }}" target="_blank">
                                    {{ str_replace( '{field}', $field_value, $m->fields->format ) }}
                                </a>
                                @break

                            @case('price')
                                <span>
                                    {!! str_replace( '{field}', Rz()->format_price( $field_value ), $item->fields->format ) !!}
                                </span>
                                @break

                            @default
                                <span>
                                    {{ str_replace( '{field}', $field_value, $m->fields->format ) }}
                                </span>

                        @endswitch

                    </p>
                @endif

            @endforeach
        </div>
    @endif

    @if( $show_button )
        <div class="rz-action-footer rz-text-center">
            <div class="rz--action">
                <a href="{{ $url }}"
                    class="rz-button rz-button-accent rz-large"
                    {{ $target }}
                    {!! $modal !!}>
                    <span>
                        {{ $button_label ? $button_label : $strings->label }}
                    </span>
                </a>
            </div>
        </div>
    @endif

</div>
