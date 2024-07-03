<div class="rz-mod-content">
    @if( ! empty( $name ) )
        <h4>{{ $name }}</h4>
    @endif
    <div class="rz-author">
        <div class="rz--heading">
            <div class="rz--avatar">
                <a href="{{ get_author_posts_url( $user->id ) }}">
                    {!! $user->avatar() !!}
                </a>
            </div>
            <div class="rz--meta">
                <div class="rz--name">
                    <a href="{{ get_author_posts_url( $user->id ) }}">
                        {{ $display_name }}
                        @if( get_user_meta( $user->id, 'rz_verified', true ) )
                            <i class="rz--verified fas fa-check-circle"></i>
                            <span>{{ $strings->verified }}</span>
                        @endif
                    </a>
                    @if( $total_reviews )
                        <div class="rz--reviews">
                            <i class="fas fa-star"></i>
                            <span>{{ sprintf( _n( $strings->review, $strings->reviews, $total_reviews, 'routiz' ), $total_reviews ) }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @if( $user_description )
            <div class="rz--description">
                {!! wpautop( $user_description ) !!}
            </div>
        @endif
        <div class="rz--action">
            <a href="#" class="rz-button rz-button-accent" data-modal="{{ is_user_logged_in() ? 'conversation' : 'signin' }}" data-params='{"id":{{ $listing->id }}}'>
                <span>{{ sprintf( $strings->contact, $userdata->display_name ) }}</span>
            </a>
        </div>
    </div>
</div>
