<div class="rz-action-application">

    @if( $enable_author )
        {{ Rz()->the_template('routiz/single/action/author') }}
    @endif

    <div class="rz-action-footer rz-text-center">
        <div class="rz--action">
            <a href="#" class="rz-button rz-button-accent rz-large" data-modal="{{ is_user_logged_in() ? 'action-application' : 'signin' }}" data-params='{{ $listing_id }}'>
                <span>
                    {{ $application_button_label ? $application_button_label : $strings->button_label }}
                </span>
            </a>
        </div>
    </div>

</div>