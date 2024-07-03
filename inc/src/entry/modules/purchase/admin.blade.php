<h3>{{ $strings->purchase }}</h3>

<table>
    <tbody>
        @if( isset( $userdata->display_name ) )
            <tr>
                <td>{{ $strings->purchased_by }}:</td>
                <td class="rz-text-right">{{ $userdata->display_name }}</td>
            </tr>
        @endif
    </tbody>
</table>

@if( $listing->type->get('rz_allow_pricing') and $pricing )

    <h3>{{ $strings->pricing_details }}</h3>

    <table>
        <tbody>
            @if( isset( $pricing->base ) )
                <tr>
                    <td>{{ $strings->base_price }}:</td>
                    <td class="rz-text-right">{!! Rz()->format_price( $pricing->base ) !!}</td>
                </tr>
            @endif
            @if( isset( $pricing->security_deposit ) and ! empty( $pricing->security_deposit ) )
                <tr>
                    <td>{{ $strings->security_deposit }}:</td>
                    <td class="rz-text-right">{!! Rz()->format_price( $pricing->security_deposit ) !!}</td>
                </tr>
            @endif
            @if( isset( $pricing->service_fee ) and ! empty( $pricing->service_fee ) )
                <tr>
                    <td>{{ $strings->service_fee }}:</td>
                    <td class="rz-text-right">{!! Rz()->format_price( $pricing->service_fee ) !!}</td>
                </tr>
            @endif
            @if( isset( $pricing->extras ) and ! empty( $pricing->extras ) )
                @foreach( $pricing->extras as $key => $extra )
                    <tr>
                        <td>
                            @if( isset( $services[ $key ] ) )
                                {{ $services[ $key ]->fields->name }}:
                            @endif
                        </td>
                        <td class="rz-text-right">
                            {!! Rz()->format_price( $extra->price ) !!}
                            @if( isset( $pricing->nights ) && $extra->type == 'per_day' )
                                ✕ {{ $pricing->nights }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            @endif
            @if( isset( $pricing->addons ) and ! empty( $pricing->addons ) )
                @foreach( $pricing->addons as $addon_id => $addon )
                    <tr>
                        <td>
                        @foreach( $addons as $adn )
                            @if( $adn->fields->key == $addon_id )
                                {{ $adn->fields->name }}:
                                @break
                            @endif
                        @endforeach
                        </td>
                        <td class="rz-text-right">
                            {!! Rz()->format_price( $addon->price ) !!}
                        </td>
                    </tr>
                @endforeach
            @endif
            @if( isset( $pricing->extras_total ) and ! empty( $pricing->extras_total ) )
                <tr>
                    <td>{{ $strings->extra_service_total }}:</td>
                    <td class="rz-text-right">{!! Rz()->format_price( $pricing->extras_total ) !!}</td>
                </tr>
            @endif
            @if( isset( $pricing->payment ) and isset( $pricing->payment_processing_name ) )
                <tr>
                    <td>{{ $strings->payment }}:</td>
                    <td class="rz-text-right">{{ $pricing->payment_processing_name }}</td>
                </tr>
            @endif
            @if( isset( $pricing->total ) )
                <tr>
                    <td><strong>{{ $strings->total }}</strong></td>
                    <td class="rz-text-right"><strong>{!! Rz()->format_price( $pricing->total ) !!}</strong></td>
                </tr>
            @endif
            @if( isset( $pricing->processing ) )
                <tr>
                    <td><strong>{{ $strings->processing }}</strong></td>
                    <td class="rz-text-right"><strong>{!! Rz()->format_price( $pricing->processing ) !!}</strong></td>
                </tr>
            @endif
        </tbody>
    </table>

@endif
