@php
    global $rz_explore;
    $args = [];
    if( $rz_explore->type ) {
        $args['type'] = $rz_explore->type->get('rz_slug');
    }
@endphp

<div class="rz-form-group rz-col-12">

    @if( $name )
        <div class="rz-heading">
            <label class="rz-ellipsis">
                {!! $name !!}
            </label>
        </div>
    @endif

    <div class="rz-filter-tab @if( ! empty( $labels ) ) rz-active @endif">
        <div class="rz-tab-title rz-is-placeholder rz-no-select" data-label="{{ $label }}">
            @if( ! empty( $labels ) )
                {!! $labels !!}
            @else
                <span>{{ $label }}</span>
            @endif
        </div>
        <div class="rz-tab-flyout">
            <div class="rz-tab-content rz-scrollbar">
                <div class="rz-grid">
                    @foreach( $content as $filter )
                        {{ $component->render( array_merge( (array) $filter->fields, [
                            'type' => $filter->template->id,
                        ])) }}
                    @endforeach
                </div>
            </div>
            <div class="rz-tab-footer">
                <div>
                    <a href="#" class="rz-button rz-small">
                        <span>{{ $strings->close }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
