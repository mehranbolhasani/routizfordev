<div class="rz-modal-container rz-scrollbar">
    @if( $action_application )
        <div class="rz-form">
            <div class="rz-grid">
                @php

                    foreach( $action_application->fields->form as $item ) {

                        if( $item->fields->show_if_guest and is_user_logged_in() ) {
                            continue;
                        }

                        $field = $form->create( Rz()->prefix_item( $item ) );
                        $field->props->disabled = true;

                        if( ! Rz()->is_error( $field ) ) {

                            echo $field->get();

                        }else{

                            $field->display_error();

                        }

                    }

                @endphp
            </div>
        </div>
    @else
        <p class="rz-text-center rz-weight-600">{{ $strings->action_type_not_found }}</p>
    @endif
</div>
