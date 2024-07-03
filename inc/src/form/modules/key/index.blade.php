@include('label.index')

<div class="rz-input-group rz-input-group-custom">
    <div class="rz-flex">
        <input type="text" value="{{ $value }}" placeholder="{{ $placeholder }}" {{ $v_model ? "v-model={$v_model}" : '' }}>
        @if( $defined )
            <a class="rz-button rz-gray rz-join-left" href="#">
                {{ $strings->defined }}
            </a>
        @endif

    </div>
</div>

@if( $defined )
    <div class="rz-input-group rz-input-group-defined">
        <div class="rz-flex">
            <div class="rz-select rz-select-single">
                <select>
                    <option value="">{{ $strings->select }}</option>
                    @foreach ( $pre_defined as $pre_key => $pre_value )
                        <option value="{{ $pre_key }}" {{ $pre_key == $value ? 'selected' : '' }}>{{ $pre_value }}</option>
                    @endforeach
                </select>
            </div>
            <a class="rz-button rz-gray rz-join-left" href="#">
                {{ $strings->custom }}
            </a>
        </div>
    </div>
@endif

<input type="hidden" name="{{ $id }}" value="{{ $value }}" {{ $v_model ? "v-model={$v_model}" : '' }} {{ $readonly ? 'form="fake-form-readonly"' : '' }}>
