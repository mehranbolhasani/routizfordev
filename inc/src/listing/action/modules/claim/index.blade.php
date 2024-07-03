<div class="rz-action-claim">

    <div class="rz--icon">
        <i class="fas fa-store"></i>
    </div>

    @if( $is_claimed )
        <h4><i class="fas fa-check-circle rz-mr-1"></i>{{ $strings->claimed }}</h4>
        <p class="rz-mb-0">{{ $strings->claimed_description }}</p>
    @else
        <h4>{{ $claim_title }}</h4>
        <p>{{ $claim_description }}</p>

        <div class="rz-action-footer rz-text-center">
            <div class="rz--action">
                <a href="#" class="rz-button rz-button-accent rz-large" data-modal="{{ is_user_logged_in() ? 'action-claim' : 'signin' }}" data-params='{{ $listing_id }}'>
                    <span>{{ $claim_button_label ? $claim_button_label : $strings->button_label }}</span>
                </a>
            </div>
        </div>
    @endif

</div>