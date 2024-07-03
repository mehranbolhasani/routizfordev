@include('label.index')

<div class="rz-radio-fieldset">
    @foreach( $options as $option_key => $option_name )
        <label class="rz-radio">
            <input type="radio" name="{{ $id }}" value="{{ $option_key }}" {{ $option_key == $value ? 'checked' : '' }}>
            <i class="rz-transition"></i>
            <span>{{ $option_name }}</span>
        </label>
    @endforeach
</div>
