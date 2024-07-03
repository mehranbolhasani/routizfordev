<div class="rz-step-publish rz-text-center">
    <div>
        <span class="rz--icon"><i class="fas fa-check"></i></span>
        <h4 class="rz--title">{{ $strings->success }}</h4>
        @if( $requires_admin_approval )
            <p>{{ $strings->awaits_approval }}</p>
        @else
            <p>{{ $strings->published }}</p>
        @endif
    </div>
</div>
