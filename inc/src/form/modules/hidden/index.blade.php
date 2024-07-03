<input
    type="hidden"
    name="{{ $id }}"
    value="{{ $value }}"
    {{ $v_model ? "v-model={$v_model}" : '' }}
    class="{{ $html->class }}"
    @if( $html->id )id="{{ $html->id }}"@endif>
