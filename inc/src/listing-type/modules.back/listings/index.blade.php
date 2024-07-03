<div class="rz-section-heading">
    <h3 class="rz-section-title">{{ $name }}</h3>
    @if( $description )
        <p class="rz-section-description">{{ $description }}</p>
    @endif
</div>

@if( $listings )
    <ul class="rz-listings" data-cols="3">
        @while ( $listings->have_posts() ) {{ $listings->the_post() }}
            <li class="rz-listing-item <?php Rz()->listing_class(); ?>">
                {{ Rz()->the_template('routiz/explore/listing/listing') }}
            </li>
        @endwhile {{ wp_reset_postdata() }}
    </ul>
@else
    <p>{{ $strings->no_results }}</p>
@endif

@if( $more_posts > 0 )
    <div class="rz-mt-3">
        <a href="{{ $more_url }}" class="rz-button">
            {{ $strings->view_more }}
        </a>
    </div>
@endif
