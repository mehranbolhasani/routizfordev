<h4 class="rz--title">{{ $title }}</h4>

@if( $fields )
    <form>
        <div class="rz-grid">
            @foreach( $fields as $item )
                @if( $item->fields->is_submit_form )
                    {{ $form->render( Rz()->prefix_item( $item ) ) }}
                @endif
            @endforeach
        </div>
    </form>
@else
    <div class="rz-submission-error rz-block">
        <div class="rz--error">
            <div class="rz--content">
                {{ $strings->not_found }}
            </div>
        </div>
    </div>
@endif