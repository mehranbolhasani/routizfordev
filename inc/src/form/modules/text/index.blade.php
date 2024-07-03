@include('label.index')

<input

    type="text"
    name="{{ $id }}"
    value="{{ $value }}"
    placeholder="{{ $placeholder }}"
    class="{{ $html->class }}"
    @if( $html->id )id="{{ $html->id }}"@endif

    {{ $v_model ? "v-model={$v_model}" : '' }}
    {{ $disabled ? 'disabled' : '' }}
    {{ ( isset( $readonly ) and $readonly == true ) ? 'form="fake-form-readonly"' : '' }}>
