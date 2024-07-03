@php

global $rz_upcoming;
$rz_upcoming = $upcoming;

$component->form->render([
    'type' => 'nonce',
    'id' => 'security-action',
    'value' => 'booking-security-nonce',
]);

@endphp

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

@if( ! empty( $title ) )
    <h5 class="rz--title">{{ $title }}</h5>
@endif

@if( ! empty( $description ) )
    <p class="rz--description">{{ $description }}</p>
@endif

@if( $upcoming )
    <div class="rz-form">
        <div class="rz-grid">

            @if( $allow_guests )
                @php

                    $component->form->render([
                        'type' => 'guests',
                        'id' => 'rz_guests',
                        'num_guests' => $num_guests,
                        'guests' => [
                            'adults' => 0,
                            'children' => 0,
                            'infants' => 0,
                        ],
                        'class' => [ 'rz-mb-2' ],
                    ]);

                @endphp
            @endif

            @php

                if( ! boolval( $action->get_field('hide_date') ) ) {
                    $component->form->render([
                        'type' => 'flyout',
                        'id' => 'flyout-booking-date',
                        'label' => $strings->select_date,
                        'fields' => [
                            'rz_booking' => [
                                'type' => 'calendar',
                                'name' => $strings->select_start_date,
                                'range' => false,
                                'large' => false,
                                'class' => [ 'rz-mb-0' ]
                            ]
                        ],
                        'class' => [ 'rz-mb-3' ],
                    ]);
                }

            @endphp

            @if( $allow_addons )
                @php

                    $addon_options = [];
                    $items = Rz()->jsoning( 'rz_addons', $listing->id );
                    foreach( $items as $key => $item ) {
                        if( empty( $item->fields->key ) ) {
                            continue;
                        }
                        $addon_price = floatval( $item->fields->price );
                        $addon_name = [
                            $item->fields->name,
                            Rz()->format_price( $addon_price )
                        ];
                        $addon_options[ $item->fields->key ] = $addon_name;
                    }

                    if( $addon_options ) {
                        $component->form->render([
                            'type' => 'flyout',
                            'id' => 'flyout-addons',
                            'label' => $addon_label,
                            'fields' => [
                                'addons' => [
                                    'type' => 'checklist',
                                    'name' => $addon_label,
                                    'options' => $addon_options,
                                    'class' => [ 'rz-mb-0' ]
                                ]
                            ],
                            'class' => [ 'rz-mb-2' ]
                        ]);
                    }

                @endphp
            @endif

        </div>
    </div>

    {{ Rz()->the_template('routiz/single/appointments') }}

    <div class="rz-action-success">
        {{-- append success message --}}
    </div>

    <div class="rz-action-error">
        {{-- append error message --}}
    </div>

    @if( count( $upcoming ) >= 3 )
        <div class="rz-action-footer rz-text-center">
            <a href="#" class="rz-button rz--border rz-large" data-modal="appointments">
                <span>{{ $strings->see_more_dates }}</span>
                {{ Rz()->preloader() }}
            </a>
        </div>
    @endif

@else
    <p class="rz-text-center rz-weight-700 rz-mb-0">{{ $strings->no_appointments }}</p>
@endif
