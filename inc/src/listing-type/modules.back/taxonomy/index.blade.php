<div class="rz-section-heading">
    <h3 class="rz-section-title">{{ $name }}</h3>
    @if( $description )
        <p class="rz-section-description">{{ $description }}</p>
    @endif
</div>

@if( $items )
    <div class="rz-taxonomy-boxes">
        <ul class="rz-flex rz-flex-wrap">
            @foreach( $items as $item )
                <li>
                    <a href="{{ Rz()->get_explore_page_url([ 'type' => $listing_type->get('rz_slug'), Rz()->unprefix( $id ) => $item->slug ]) }}" class="rz-box">
                        @if( $item->image )
                            <div class="rz-image" style="background-image:url('{{ $item->image }}');"></div>
                        @endif
                        <div class="rz-label">
                            {{ $item->name }}
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endif
