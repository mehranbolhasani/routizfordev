@include('label.index')

<div class="rz-calendar-date rz-no-select">

    <div class="rz-grid rz-none">

        <div class="rz-col">
            <input type="text" class="rz-calendar-date-start rz-mt-4" value="{{ $date->date_start }}" autocomplete="nope">
            <input type="text" name="{{ $id }}[]" class="rz-calendar-ts" value="{{ $date->ts_start }}" autocomplete="nope">
        </div>

        <div class="rz-col">
            <input type="text" class="rz-calendar-date-end rz-mt-4" value="{{ $date->date_end }}" autocomplete="nope">
            <input type="text" name="{{ $id }}[]" class="rz-calendar-ts-end" value="{{ $date->ts_end }}" autocomplete="nope">
        </div>

    </div>

    @php
        $calendar_class = [];
        if( $large ) {
            $calendar_class[] = 'rz-calendar-large';
        }
    @endphp

    <div class="rz-calendar {{ implode( ' ', $calendar_class ) }}">

        <div class="rz-calendar-nav rz-flex rz-w-100 rz-justify-space">
            <div class="">
                <a href="#" class="rz-disabled" data-action="prev">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i>
                </a>
            </div>
            <div class="">
                <a href="#" class="" data-action="next">
                    <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        @foreach( $months as $month )
            <div class="rz-calendar-month @if( $month->id <= ( $large == true ? 2 : 1 ) ) rz-active @endif" data-month="{{ $month->id }}">
                <div class="rz-month">
                    <h4>{{ date_i18n( 'F', mktime( 0, 0, 0, $month->month, 10 ) ) }} {{ esc_attr( $month->year ) }}</h4>
                </div>
                <ul class="rz-weekdays">
                    @foreach( $week_days as $week_day )
                        <li data-name="{{ $week_day->name }}">{{ $week_day->initial }}</li>
                    @endforeach
                </ul>
                <ul class="rz-days">
                    @foreach( $month->days as $d_index => $day )
                        <li class="{{ implode( ' ', $day->class ) }}"
                            @if( isset( $day->timestamp ) ) data-timestamp="{{ $day->timestamp }}" @endif
                            @if( isset( $day->date ) ) data-date="{{ $day->date }}" @endif>
                            @if( $day->day )
                                <span><i>{{ (int) $day->day }}</i></span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>

    <div class="rz-calendar-actions @if( ! $range ) rz-none @endif">
        @if( ! $readonly )
            <a href="#" class="rz-calendar-clear">
                {{ $strings->clear_dates }}
            </a>
        @endif
    </div>

</div>
