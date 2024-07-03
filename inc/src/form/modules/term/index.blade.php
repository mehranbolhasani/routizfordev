<div class="rz-field-terms">
    <div class="rz-terms-taxonomy">
        @php

            $component->render([
                'type' => 'use_field',
                'id' => null,
                'name' => $props['name'],
                'value' => isset( $term_values->taxonomy ) ? $term_values->taxonomy : $props['value'],
                'group' => 'taxonomy',
                'is_wrapped' => false,
            ]);

        @endphp
    </div>
    <div class="rz-terms-terms @if( $terms ) rz-active @endif">
        @php

            $default = $props['multiple'] ? [] : '';

            $component->render([
                'type' => 'select',
                'id' => null,
                'name' => esc_html__('Select Terms', 'routiz'),
                'value' => isset( $term_values->term ) ? $term_values->term : $default,
                'options' => $terms,
                'is_wrapped' => false,
            ]);

        @endphp
    </div>
    <input type="text" class="rz-terms-input rz-none" name="{{ $props['id'] }}" value="{{ is_object( $props['value'] ) ? json_encode( $props['value'] ) : $props['value'] }}">
</div>
