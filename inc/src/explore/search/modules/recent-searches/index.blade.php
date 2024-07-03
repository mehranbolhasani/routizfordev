@if( $has_recent_searches )
    <label>{{ esc_html( $name ) }}</label>
    <div class="rz-recent-searches">
        <ul>
            @foreach( $recent_searches as $recent_search )
                @php
                    $recent_search_url = add_query_arg( 'search_term', $recent_search, $explore_page );
                @endphp
                <li>
                    <a href="{{ $recent_search_url }}" class="rz-ellipsis rz-no-transition rz-action-dynamic-explore">
                        <i class="fas fa-clock"></i>
                        <span><?php echo $recent_search; ?></span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endif
