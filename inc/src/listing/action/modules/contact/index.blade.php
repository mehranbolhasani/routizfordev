<div class="rz-action-contact">

    @if( $enable_author )
        {{ Rz()->the_template('routiz/single/action/author') }}
    @endif

    <div class="rz-action-footer rz-text-center">
        <div class="rz--action">
            <a href="#" class="rz-button rz-large" data-modal="{{ is_user_logged_in() ? 'conversation' : 'signin' }}" data-params='{"id":{{ $listing_id }}}'>
                <span>{{ $strings->send_message }}</span>
            </a>
        </div>
    </div>

</div>