@include('label.index')

<div class="rz-guests rz-no-select @if( ! $guests_num ) rz-is-placeholder @endif"
    data-num-guests="{{ $num_guests }}"
    data-text-singular="{{ $strings->one_guest }}"
    data-text-plural="{{ $strings->n_guest }}"
    data-text-placeholder="{{ $strings->select_guests }}">
    <span class="rz--label">
        <span>
            @if( $guests_num )
                {{ sprintf( $strings->n_guest, $guests_num ) }}
            @else
                {{ $strings->select_guests }}
            @endif
        </span>
    </span>
    <div class="rz--dropdown">
        <div class="rz--inner">
            <span class="rz--title">{{ $strings->select_guests }}</span>
            <div class="rz-none">
                {{ $component->render([
                    'type' => 'number',
                    'id' => 'guests',
                    'min' => 0,
                    'value' => 0,
                ]) }}
            </div>
            <table>
                <tbody>
                    <tr>
                        <td class="rz--name"><strong>{{ $strings->adults }}</strong></td>
                        <td class="rz--field">
                            {{ $component->render([
                                'type' => 'number',
                                'id' => 'guest_adults',
                                'input_type' => 'stepper',
                                'value' => $guests->adults,
                                'min' => 0,
                            ]) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="rz--name"><strong>{{ $strings->children }}</strong></td>
                        <td class="rz--field">
                            {{ $component->render([
                                'type' => 'number',
                                'id' => 'guest_children',
                                'input_type' => 'stepper',
                                'value' => $guests->children,
                                'min' => 0,
                            ]) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="rz--name"><strong>{{ $strings->infants }}</strong></td>
                        <td class="rz--field">
                            {{ $component->render([
                                'type' => 'number',
                                'id' => 'guest_infants',
                                'input_type' => 'stepper',
                                'value' => $guests->infants,
                                'min' => 0,
                                'max' => 999,
                            ]) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="rz--footer">
            @if( $show_info_guests )
                <p class="rz--info">{!! $info_guests ? $info_guests : sprintf( _n( $strings->guest_max, $strings->guests_max, $num_guests, 'routiz' ), $num_guests > 30 ? '30+' : $num_guests ) !!}</p>
            @endif
            <div class="rz-text-right">
                <a href="#" class="rz-button rz-small" data-action="guests-close">
                    <span>{{ $strings->close }}</span>
                    {{ Rz()->preloader() }}
                </a>
            </div>
        </div>
    </div>
</div>
