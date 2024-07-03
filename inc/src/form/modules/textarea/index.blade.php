@include('label.index')

<textarea

    type="text"
    name="{{ $id }}"
    placeholder="{{ $placeholder }}"
    {{ $v_model ? "v-model={$v_model}" : '' }}
    {{ $disabled ? 'disabled' : '' }}>{!! $value !!}</textarea>
