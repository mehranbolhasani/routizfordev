<a href="{{ $uri }}" class="rz-button {{ $html->class }}" @if( $html->id ) id="{{ $html->id }}" @endif>
    <span>{{ $name }}</span>
    @if( $html->preloader )
        {{ Rz()->the_template('routiz/globals/preloader') }}
    @endif
</a>
