@include('label.index')

<div class="rz-buttons rz-no-select rz--style-{{ $style }}">
    @foreach( $options as $option_key => $option_name )
        <div class="rz-btn">
            <input type="radio" name="{{ $id }}" value="{{ $option_key }}" {{ $option_key == $value ? 'checked' : '' }}>
            <span class="rz-transition">{{ $option_name }}</span>
        </div>
    @endforeach
</div>

{{-- fix for empty inputs in post --}}
<input name="{{ $id }}" type="hidden" value="" disabled>
