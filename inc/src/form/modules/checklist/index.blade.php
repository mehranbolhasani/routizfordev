@include('label.index')

<div class="rz-checklist">
    @if( is_array( $options ) )
        @foreach ( $options as $option_key => $option_name )
            <label class='rz-checkbox rz-no-select'>
                <input type='checkbox' name="{{ $id }}[]" value="{{ $option_key }}" {{ ( is_array( $value ) and in_array( $option_key, $value ) ) ? 'checked' : '' }}>
                <span class='rz-transition'></span>
                @if( is_array( $option_name ) )
                    <div class="rz-flex rz-space-between">
                        <div class="rz-flex-1">
                            <em>{{ $option_name[0] }}</em>
                        </div>
                        <div class="">
                            <strong>{!! $option_name[1] !!}</strong>
                        </div>
                    </div>
                @else
                    <em>{{ $option_name }}</em>
                @endif
            </label>
        @endforeach
    @endif
</div>

{{-- fix for empty inputs in post --}}
<input name="{{ $id }}[]" type="hidden" value="" disabled>
