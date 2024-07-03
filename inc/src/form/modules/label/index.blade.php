@if( ( isset( $name ) and ! empty( $name ) ) or ( isset( $description ) and ! empty( $description ) ) )
    <div class="rz-heading">

        @if( $name )
            <label class="@if( ! empty( $description ) ) mb-0 @endif">
                {!! $name !!}
                @if( $required )
                    <i class="rz-required"></i>
                @endif
            </label>
        @endif

        @if( $description )
            <p>{!! nl2br( wp_kses_post( html_entity_decode( $description ) ) ) !!}</p>
        @endif

    </div>
@endif
