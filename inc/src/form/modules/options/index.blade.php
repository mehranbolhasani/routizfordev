@include('label.index')

<div class="rz-opts">
    <div class="rz-opts-list">
        <ul class="rz-opts-items">
            @if( is_array( $options ) )
                @foreach( $options as $option_value => $option_name )
                    <li><input type="text" value="{{ is_array( $option_name ) ? $option_name['label'] : $option_name }}" disabled></li>
                @endforeach
            @endif
        </ul>
        <a href="#" class="rz-button rz-button-opts-add">
            <span>{{ $strings->add_options }}</span>
        </a>
    </div>
    <div class="rz-opts-add">
        <p>{!! $options_description !!}</p>
        <textarea
            type="text"
            name="{{ $id }}"
            placeholder="{{ $placeholder }}"
            {{ $v_model ? "v-model={$v_model}" : '' }}>{{ $value_raw }}</textarea>
        <a href="#" class="rz-button rz-button-opts-save">
            <span>{{ $strings->save_options }}</span>
        </a>
    </div>
</div>
