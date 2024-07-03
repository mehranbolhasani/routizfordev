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
    </div>
@endif

@if( $title )
    <p class="rz-action-title">{{ $title }}</p>
@endif

@if( $description )
    <p class="rz-action-summary">{!! nl2br( wp_kses_post( html_entity_decode( $description ) ) ) !!}</p>
@endif

<div class="rz-action-purchase">
    <div class="rz-form">
        <div class="rz-grid">

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

    <div class="rz-action-pricing">
        {{-- append pricing --}}
    </div>

    <div class="rz-action-success">
        {{-- append success message --}}
    </div>

    <div class="rz-action-footer rz-text-center">
        <div class="rz--action">
            <a href="#"
                class="rz-button rz-button-accent rz-large" data-action="purchase">
                <span>
                    {{ $button_label ? $button_label : $strings->label }}
                </span>
                {{ Rz()->preloader() }}
            </a>
        </div>
    </div>

</div>
