@php

$form = new \Routiz\Inc\Src\Form\Component();

@endphp

<div class="rz-repeater-item {{ $is_item_hidden ? 'rz-item-hidden' : '' }} {{ $is_item_empty ? 'rz-item-empty' : '' }}" data-id="{{ $template->id }}" data-name="{{ $schema['name'] }}" data-heading="{{ isset( $schema['heading'] ) ? $schema['heading'] : '' }}">

    <div class="rz-item-row rz-no-select">
        <div class="rz-item-label">
            <a href="#" class="rz-item-expand">
                <i class="fas fa-angle-down"></i>
            </a>
            <em class="rz-item-name">
                {{ $template->name }}
            </em>
            <span class="rz-item-title">
                {{ isset( $template->heading_text ) ? strip_tags( $template->heading_text ) : '' }}
            </span>
        </div>
        <div class="rz-item-actions">
            @if( $can_hide )
                <a href="#" class="rz-item-hide">
                    <i class="fas fa-ban"></i>
                </a>
            @endif
            <a href="#" class="rz-item-clone">
                <i class="far fa-clone"></i>
            </a>
            <a href="#" class="rz-item-remove">
                <i class="far fa-trash-alt"></i>
            </a>
        </div>
    </div>

    <div class="rz-repeater-content rz-grid">
        @if( is_array( $schema['fields'] ) )
            @foreach( $schema['fields'] as $id => $props_default )
                @php

                    $props = [
                        'id' => $id,
                        'readonly' => true,
                    ];

                    if( isset( $parent ) ) {
                        $props['parent'] = $parent;
                    }

                    if( isset( $fields->{$id} ) ) {
                        $props['value'] = $fields->{$id};
                    }

                    if( isset( $props['value'] ) and is_string( $props['value'] ) ) {
                        $props['value'] = html_entity_decode( $props['value'] );
                    }

                    $form->render( array_merge( $props_default, $props ) );

                @endphp
            @endforeach
        @endif
    </div>

</div>
