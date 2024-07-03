@include('label.index')

<div class="rz-icon{{ empty( $value ) ? ' rz--empty' : '' }}">

    <a href="#" class="rz-toggler">
        <div class="rz-grid rz-align-center rz-no-gutter">
            <div class="rz-preview rz-transition">
                <i class="{{ $value }}"></i>
            </div>
            <div class="rz-label rz-transition">
                {{ $strings->select }}
            </div>
        </div>
    </a>

    <div class="rz-icon-action">

        <div class="rz-grid">
            @php

                $form->render([
                    'type' => 'select',
                    'id' => $id . '__set',
                    'value' => $set,
                    'options' => $sets_options,
                    'allow_empty' => false,
                    'col' => 6,
                    'readonly' => $readonly
                ]);

                $form->render([
                    'type' => 'text',
                    'placeholder' => $strings->search,
                    'col' => 6,
                    'html' => [
                        'class' => 'rz-font-search'
                    ]
                ]);

            @endphp
        </div>

        <div class="rz-icon-list">
            <ul class="rz-scrollbar">
                <!-- append icons -->
            </ul>
            <?php Rz()->preloader(); ?>
        </div>

        <div class="rz-remove">
            <a href="#"><i class="fas fa-eraser rz-mr-1"></i>{{ $strings->remove }}</a>
        </div>

    </div>

    <input type="hidden" name="{{ $id }}" class="rz-icon-input" value="{{ $value }}" {{ $readonly ? 'form="fake-form-readonly"' : '' }}>

</div>
