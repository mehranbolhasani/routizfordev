@include('label.index')

<div class="rz-range-type-{{ $input_type }}" data-type="{{ $input_type }}">

    <div class="rz-range-number">

        <div class="rz-range-field" data-type="from">
            @include('range.input', [
                'id' => $id,
                'value' => isset( $value[0] ) ? $value[0] : '',
                'placeholder' => $placeholder[0],
                'suffix' => $suffix,
                'number_attr' => $number_attrs,
            ])
        </div>

        <div class="rz-range-separator rz-flex rz-flex-column rz-justify-center">
            {{ $separator }}
        </div>

        <div class="rz-range-field" data-type="to">
            @include('range.input', [
                'id' => $id,
                'value' => isset( $value[1] ) ? $value[1] : '',
                'placeholder' => $placeholder[1],
                'suffix' => $suffix,
                'number_attr' => $number_attrs,
            ])
        </div>

    </div>

</div>
