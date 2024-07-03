@php
    global $rz_listing;
@endphp

@if( ! empty( $items ) )
    <div class="rz-mod-content">
        @if( ! empty( $name ) )
            <h4>{{ $name }}</h4>
        @endif
        <div class="rz--meta rz--style-{{ $style }} {{ $highlight ? 'rz--is-highlight' : '' }}">
            <ul>

                @foreach( $items as $item )

                    @php

                        $field_item = $rz_listing->get_field_item( $item->fields->key );
                        $type = isset( $item->fields->type ) ? $item->fields->type : '';

                        // custom field
                        if( $field_item ) {

                            // multiple
                            if( $field_item->template->id == 'taxonomy' ) {
                                $meta_value = Rz()->get_meta( $item->fields->key, $rz_listing->id, false );

                                $values = [];
                                if( is_array( $meta_value ) ) {
                                    foreach( $meta_value as $value ) {
                                        $term = get_term_by( 'id', $value, Rz()->prefix( $field_item->fields->key ) );
                                        if( ! $term ) {
                                            continue;
                                        }
                                        $values[] = (object) [
                                            'name' => $term->name,
                                            'icon' => get_term_meta( $term->term_id, 'rz_icon', true ),
                                        ];
                                    }
                                }

                                $meta_value = $values;

                            }
                            // single
                            else{
                                $meta_value = $rz_listing->get( $item->fields->key );
                            }

                            if( ! $meta_value ) {
                                continue;
                            }

                        }
                        // built-in field
                        else{
                            $meta_value = $rz_listing->get( $item->fields->key );
                        }

                    @endphp

                    {{-- single --}}
                    @if( ! is_array( $meta_value ) )

                        @if( $meta_value )

                            <li>

                                @if( isset( $item->fields->icon ) and ! empty( $item->fields->icon ) )
                                    <i class="{{ $item->fields->icon }}"></i>
                                @endif

                                @switch( $type )

                                    @case('address')
                                        <a href="{{ add_query_arg( [ 'api' => 1, 'query' => urlencode( esc_html( $meta_value ) ) ], 'https://www.google.com/maps/search/' ) }}" target="_blank">
                                            {{ str_replace( '{field}', $meta_value, $item->fields->format ) }}
                                        </a>
                                        @break

                                    @case('url')
                                        <a href="{{ esc_url( str_replace( '{field}', $meta_value, $item->fields->format ) ) }}" target="_blank">
                                            @if( isset( $item->fields->type_url_label ) and ! empty( $item->fields->type_url_label ) )
                                                {{ $item->fields->type_url_label }}
                                            @else
                                                {{ str_replace( '{field}', $meta_value, $item->fields->format ) }}
                                            @endif
                                        </a>
                                        @break

                                    @case('phone')
                                        <a href="tel:{{ $meta_value }}" target="_blank">
                                            {{ str_replace( '{field}', $meta_value, $item->fields->format ) }}
                                        </a>
                                        @break

                                    @case('email')
                                        <a href="mailto:{{ $meta_value }}" target="_blank">
                                            {{ str_replace( '{field}', $meta_value, $item->fields->format ) }}
                                        </a>
                                        @break

                                    @case('price')
                                        <span>
                                            {!! str_replace( '{field}', Rz()->format_price( $meta_value ), $item->fields->format ) !!}
                                        </span>
                                        @break

                                    @default

                                        <span>
                                            {{ str_replace( '{field}', $meta_value, $item->fields->format ) }}
                                        </span>

                                @endswitch

                            </li>

                        @endif

                    {{-- multiple --}}
                    @else

                        @foreach( $meta_value as $value )
                            @if( ! is_object( $value ) )
                                @continue;
                            @endif
                            <li>
                                @if( $value->icon )
                                    <i class="{{ $value->icon }}"></i>
                                @endif
                                <span>
                                    {{ str_replace( '{field}', $value->name, $item->fields->format ) }}
                                </span>
                            </li>
                        @endforeach

                    @endif

                @endforeach

            </ul>
        </div>
    </div>
@endif
