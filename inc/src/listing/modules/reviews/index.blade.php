<div class="rz-mod-content">
    @if( ! empty( $name ) )
        <h4>{{ $name }}</h4>
    @endif
    {{ Rz()->the_template('routiz/single/reviews/reviews') }}
</div>
