@php

    $component->form->render([
        'type' => 'nonce',
        'id' => 'security-action',
        'value' => 'booking-security-nonce',
    ]);

    $component->form->render([
        'type' => 'hidden',
        'id' => 'listing_id',
        'value' => get_the_ID(),
    ]);

@endphp

@if( $price['base'] )
    <div class="rz-action-price">
        <span class="rz--price rz-font-heading">{!! Rz()->format_price( $price['base'] ) !!}</span>
        <span class="rz--text">{{ $entity_text }}</span>
    </div>
@endif

@if( $title )
    <p class="rz-action-title">{{ $title }}</p>
@endif

@if( $summary )
    <p class="rz-action-summary">{!! wp_kses( $summary, Rz()->allowed_html() ) !!}</p>
@endif

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
                ]);

            @endphp
        @endif

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
                        'class' => [ 'rz-mb-1' ]
                    ]);
                }

            @endphp
        @endif

        @php

            $component->form->render([
                'type' => 'calendar',
                'id' => 'rz_booking',
                'range' => $selection_type !== 'single',
                'large' => false
            ]);

        @endphp

    </div>
    <div class="rz-action-success">
        {{-- append success message --}}
    </div>
    <div class="rz-action-pricing">
        {{-- append pricing --}}
    </div>
    <div class="rz-action-footer rz-text-center">
        <a href="#" class="rz-button rz-button-accent rz-large{{ is_user_logged_in() ? ' rz-add-booking' : '' }}"{!! ! is_user_logged_in() ? ' data-modal="signin"' : '' !!}>
            <span>{{ $strings->action_button_text }}</span>
            {{ Rz()->preloader() }}
        </a>
    </div>
</div>
