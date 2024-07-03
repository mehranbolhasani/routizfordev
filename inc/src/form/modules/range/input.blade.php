@if( $suffix )
    <div class="rz-field-suffix"><span class="rz-suffix rz-flex rz-flex-column rz-justify-center">{{ $suffix }}</span>
@endif

<input type="number" value="{{ $value }}" placeholder="{{ $placeholder }}" {!! Rz()->attrs( $number_attrs ) !!}>
<input type="hidden" name="{{ $id }}[]" value="{{ $value }}" @if( ! $value ) disabled="disabled" @endif>

@if( $suffix )
    </div>
@endif