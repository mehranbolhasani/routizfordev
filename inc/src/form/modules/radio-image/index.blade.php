@include('label.index')

<div class="rz-radio-images rz-flex rz-flex-wrap">
    @foreach( $options as $option_key => $option )
        @if( isset( $option['image'] ) and isset( $option['label'] ) )
            <label class="rz-radio-image-fieldset">
                <input type="radio" name="{{ $id }}" value="{{ $option_key }}" {{ $option_key == $value ? 'checked' : '' }}>
                <div class='rz-radio-image rz-no-select'>
                    @if( substr( $option['image'], 0, 1 ) == '<' )
                        {!! $option['image'] !!}
                    @else
                        <img class="rz--label" src="{{ $option['image'] }}" alt="">
                    @endif
                    <span>{{ $option['label'] }}</span>
                </div>
            </label>
    @endif
    @endforeach
</div>
