@if( is_array( $open_hours ) and ! empty( $open_hours ) )
    @php
        $is_empty = true;
        foreach( $open_hours as $day_key => $hours ) {
            if( ! empty( $hours ) ) {
                $is_empty = false;
                break;
            }
        }
    @endphp
    @if( ! $is_empty )
        <div class="rz-action-open-hours">
            <div class="rz-open-hours {{ $is_expanded ? 'rz--expand' : '' }}">
                <div class="rz--current">
                    <div class="rz--now {{ $is_open ? 'rz--open' : '' }}">
                        <span>{{ $is_open ? $strings->open_now : $strings->closed }}</span>
                    </div>
                    <div class="rz--now-hours">

                        @if( $type == 'open' )
                            {{-- .. --}}
                        @elseif( $type == 'closed' )
                            {{-- .. --}}
                        @else
                            @if( $current_start and $current_end )
                                <span class="rz--now-hours">{{ date( $time_format, $current_start ) }} - {{ date( $time_format, $current_end ) }}</span>
                            @endif
                        @endif

                        <a href="#" class="rz--toggle">
                            <i class="fas fa-plus"></i>
                        </a>
                    </div>
                </div>
                <div class="rz--list">
                    <ul>
                        @foreach( $open_hours as $day_key => $hr )
                            @if(
                                $hr->type == 'open' or
                                $hr->type == 'closed' or
                                (
                                    $hr->type == 'range' and
                                    ! empty( $hr->unix_start ) and
                                    ! empty( $hr->unix_end )
                                )
                            )
                                <li>
                                    <span class="rz--day">{{ $week_days[ $day_key ] }}</span>
                                    <span class="rz--hour">

                                        @if( $hr->type == 'open' )
                                            {{ $strings->all_day_open }}
                                        @elseif( $hr->type == 'closed' )
                                            {{ $strings->all_day_closed }}
                                        @else
                                            {{ date( $time_format, $hr->unix_start ) }} - {{ date( $time_format, $hr->unix_end ) }}
                                        @endif

                                    </span>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                <p class="rz--info"><u>{{ $strings->local_time }}</u></p>
            </div>
        </div>
    @endif
@endif
