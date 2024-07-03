@if( ! empty( $terms ) )
    <div class="rz-mod-content">
        @if( ! empty( $name ) )
            <h4>{{ $name }}</h4>
        @endif
        @if( is_array( $terms ) )
            <ul class="rz-tax-list rz--style-{{ ( isset( $style ) and ! empty( $style ) ) ? $style : '' }}">
                @foreach( $terms as $term )
                    <li>
                        @if( ! empty( $term->icon ) )
                            <div class="rz--icon">
                                <i class="{{ $term->icon }}"></i>
                            </div>
                        @endif
                        <div class="rz--label">
                            <p class="rz-mb-0"><strong>{{ $term->name }}</strong></p>
                            @if( $term->description )
                                <p class="rz-mb-1 rz-mt-1">
                                    {{ $term->description }}
                                </p>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endif
