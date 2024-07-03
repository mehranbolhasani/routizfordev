@if( $status == 'publish' )
    <div class="rz-booking-approved">
        {{ sprintf( $strings->text_approved, get_the_modified_date() ) }}
    </div>
@endif

@if( $status == 'pending_payment' )
    <div class="rz-booking-pending">
        {{ sprintf( $strings->text_pending, $cancellation_date ) }}
    </div>
@endif

@if( $status == 'declined' )
    <div class="rz-booking-declined">
        {{ sprintf( $strings->text_declined, get_the_modified_date() ) }}
    </div>
@endif

<h3>{{ $strings->reservation }}</h3>

<table>
    <tbody>
        <tr>
            <td>{{ $strings->reservation_id }}:</td>
            <td class="rz-text-right">#{{ get_the_ID() }}</td>
        </tr>
        <tr>
            <td>{{ $strings->reservation_status }}:</td>
            <td class="rz-text-right">
                <?php echo Rz()->get_status(); ?>
            </td>
        </tr>
        @if( isset( $userdata->display_name ) )
            <tr>
                <td>{{ $strings->requested_by }}:</td>
                <td class="rz-text-right">{{ $userdata->display_name }}</td>
            </tr>
        @endif
        <tr>
            <td>{{ $strings->checkin }}:</td>
            <td class="rz-text-right">{{ $checkin_date }}</td>
        </tr>
        <tr>
            <td>{{ $strings->checkout }}:</td>
            <td class="rz-text-right">{{ $checkout_date }}</td>
        </tr>
        @if( isset( $guests ) and ! empty( $guests ) )
            <tr>
                <td>{{ $strings->guests }}:</td>
                <td class="rz-text-right">{{ $guests }}</td>
            </tr>
        @endif
        @if( isset( $children ) and ! empty( $children ) )
            <tr>
                <td>{{ $strings->children }}:</td>
                <td class="rz-text-right">{{ $children }}</td>
            </tr>
        @endif
        @if( isset( $pricing->nights ) )
            <tr>
                <td>{{ $strings->nights }}:</td>
                <td class="rz-text-right">✕ {{ $pricing->nights }}</td>
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
            @if( isset( $pricing->guest_price ) )
                <tr>
                    <td>{{ $strings->guest_price }}:</td>
                    <td class="rz-text-right">{!! Rz()->format_price( $pricing->guest_price ) !!}</td>
                </tr>
            @endif
            @if( isset( $pricing->child_price ) )
                <tr>
                    <td>{{ $strings->child_price }}:</td>
                    <td class="rz-text-right">{!! Rz()->format_price( $pricing->child_price ) !!}</td>
                </tr>
            @endif
            @if( isset( $pricing->child_sep_price ) )
                <tr>
                    <td>{{ $strings->child_sep_price }}:</td>
                    <td class="rz-text-right">{!! Rz()->format_price( $pricing->child_sep_price ) !!}</td>
                </tr>
            @endif
            @if( isset( $pricing->long_term ) and ! empty( $pricing->long_term ) )
                <tr>
                    <td>{{ $strings->long_term_price }}:</td>
                    <td class="rz-text-right">{!! Rz()->format_price( $pricing->long_term ) !!}</td>
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
