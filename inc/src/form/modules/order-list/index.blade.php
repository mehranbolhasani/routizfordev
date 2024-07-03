@include('label.index')

@if( ! empty( $split['available'] ) or ! empty( $split['active'] ) )
    <div class="rz-order-list rz-no-select">
        <div class="rz-grid rz-no-gutter">
            <div class="rz-col-6">
                <div class="rz-list-options rz-flex rz-flex-column">
                    <p>{{ $strings->available }}:</p>
                    <ul class="" data-state="disabled">
                        @foreach( $split['available'] as $option_id => $option_name )@include('order-list.item', [
                            'id' => $id,
                            'option_id' => $option_id,
                            'option_name' => $option_name,
                            'disabled' => true
                        ])@endforeach
                    </ul>
                </div>
            </div>
            <div class="rz-col-6">
                <div class="rz-order-sort rz-list-values rz-flex rz-flex-column">
                    <p>{{ $strings->active }}:</p>
                    <ul class="" data-state="active">
                        @foreach( $split['active'] as $option_id => $option_name )@include('order-list.item', [
                            'id' => $id,
                            'option_id' => $option_id,
                            'option_name' => $option_name,
                            'disabled' => false
                        ])</li>@endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="rz-alert-error">
        <ul>
            <li>
                {{ $repeater_empty_notify }}
            </li>
        </ul>
    </div>
@endif

{{-- fix for empty inputs in post --}}
<input type="hidden" name="{{ $id }}" disabled="disabled">