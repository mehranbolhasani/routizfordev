@include('label.index')

<div class="rz-select2">
    <select name="{{ $id }}{{ ( ! $single and $id ) ? '[]' : '' }}" {{ ! $single ? 'multiple' : '' }}>
        @if( $allow_empty and $single )
            <option value="">-</option>
        @endif
        @foreach( $options as $option_key => $option_name )
            <option value="{{ $option_key }}" {{ $self->selected( $option_key ) ? 'selected' : '' }}>{{ $option_name }}</option>
        @endforeach
    </select>
</div>

{{-- fix for empty inputs in post --}}
@if( ! $single )
    <input name="{{ $id }}[]" type="hidden" value="" disabled>
@endif
