@include('label.index')

@if( $templates_num == 0 )

    <div class="rz-notice rz-notice-alert">
        <p>
            {{ $strings->no_modules }}
        </p>
    </div>

@else

    <div class="rz-repeater @if( ! isset( $repeater->props->parent ) ) rz-repeater-collect @endif">

        @if( ! isset( $repeater->props->parent ) )
            <textarea type="text" class="rz-repeater-value rz-none" name="{{ $id }}" {{ $v_model ? "v-model={$v_model}" : '' }}>{!! htmlspecialchars( $value ) !!}</textarea>
        @endif

        <div class="rz-repeater-items">
            @if( is_array( $items ) )
                @foreach ( $items as $item )
                    @php
                        if( is_object( $item ) and isset( $templates[ $item->template->id ] ) ) {
                            $component->render( array_merge( (array) $item, [
                                'type' => 'repeater-item',
                                'schema' => $templates[ $item->template->id ],
                                'parent' => $repeater,
                            ]));
                        }
                    @endphp
                @endforeach
            @endif
        </div>

        <div class="rz-repeater-select">
            <div class="rz-select rz-select-single @if( $templates_num <= 1 ) rz-none @endif">
                <select>
                    @foreach ( $templates as $template_id => $template )
                        <option value="{{ $template_id }}">{{ $template['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="rz-text-center">
                <div class="rz-repeater-action">
                    <a href="#" class="rz-button rz-small">
                        <span>{{ $strings->add_new }}</span>
                        <?php Rz()->preloader(); ?>
                    </a>
                </div>
            </div>
        </div>

        <input type="hidden" class="rz-repeater-schema" value='{{ htmlspecialchars( json_encode( $templates ), ENT_QUOTES, 'UTF-8' ) }}'>

    </div>

@endif
