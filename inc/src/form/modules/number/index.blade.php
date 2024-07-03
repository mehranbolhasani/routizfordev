@include('label.index')

<div class="rz-number-type-{{ $input_type }}" data-type="{{ $input_type }}">

    @if( $input_type == 'stepper' )

        <div class="rz-stepper rz--{{ $style }}">
            <input class="{{ $style == 'v1' ? 'rz-none' : '' }}" type="number" name="{{ $id }}" value="{{ $value }}" {!! Rz()->attrs( $number_attrs ) !!} {{ $v_model ? "v-model={$v_model}" : '' }} {{ $readonly ? 'form="fake-form-readonly"' : '' }}>
            <div class="rz--row">
                <div class="rz--cell">
                    <a href="#" class="rz-stepper-button" data-action="decrease">
                        <i class="fas fa-minus"></i>
                    </a>
                </div>
                <div class="rz--cell rz--cell-text rz-flex rz-flex-column rz-justify-center">
                    <p class="rz-stepper-text">{!! sprintf( $format, $value ) !!}</p>
                </div>
                <div class="rz--cell">
                    <a href="#" class="rz-stepper-button" data-action="increase">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
            </div>
        </div>

    @else

        @if( $input_type == 'range' )
            <p class="rz-number-range-text">{!! sprintf( $format, $value ) !!}</p>
        @endif
        <input type="{{ $input_type }}" name="{{ $id }}" value="{{ $value }}" placeholder="{{ $placeholder }}" {!! Rz()->attrs( $number_attrs ) !!} {{ $v_model ? "v-model={$v_model}" : '' }} {{ $readonly ? 'form="fake-form-readonly"' : '' }}>

    @endif

</div>
