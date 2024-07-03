@include('label.index')

<label class='rz-toggle rz-no-select'>
    <input type='checkbox' value="1" {{ $value ? 'checked' : '' }} {{ $v_model ? "v-model={$v_model}" : '' }} {{ $disabled ? 'disabled' : '' }}>
    <span class='rz-transition'></span><em>{{ $html->text }}</em>
</label>

{{-- fix for empty inputs in post --}}
<input type='hidden' name="{{ $id }}" value="{{ $value }}" {{ $readonly ? 'form="fake-form-readonly"' : '' }}>
