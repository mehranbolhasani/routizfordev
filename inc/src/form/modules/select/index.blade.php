@include('label.index')

<div class="rz-select rz-select-{{ $single ? 'single' : 'multiple' }}">
    <select
        name="{{ $id }}{{ ( ! $single and $id ) ? '[]' : '' }}"
        {{ ! $single ? 'multiple' : '' }}
        {{ $v_model ? "v-model={$v_model}" : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $readonly ? 'form="fake-form-readonly"' : '' }}
        class="{{ $html->class }}"
        @if( $html->id )
            id="{{ $html->id }}"
        @endif
        >
        @if( $allow_empty and $single )
            <option value="">{{ $label }}</option>
        @endif
        @foreach( $options as $option_key => $option_name )
            <option value="{{ $option_key }}" {{ ( $single ? $option_key == $value : ( is_array( $value ) and in_array( $option_key, $value ) ) ) ? 'selected' : '' }}>{{ $option_name }}</option>
        @endforeach
    </select>
</div>

{{-- fix for empty inputs in post --}}
@if( ! $single )
    <input name="{{ $id }}[]" type="hidden" value="" disabled>
@endif
