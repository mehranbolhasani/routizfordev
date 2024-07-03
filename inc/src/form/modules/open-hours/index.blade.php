@include('label.index')

<input class="rz--value" type="hidden" name="{{ $id }}" value="{{ is_array( $value ) ? json_encode( $value ) : $value }}">

<div class="rz-week-days">

    @foreach( $week_days as $day_id => $day_text )
        <div class="rz--day">
            <div class="rz--label">
                <span class="rz-block rz-mb-2">{{ $day_text }}</span>
            </div>
            <div class="rz-mb-2" style="max-width: 700px;">
                <div class="rz-select rz-select-single">
                    <select name="{{ $day_id }}[type]">
                        <option value="range" {{ ( isset( $value[ $day_id ] ) and isset( $value[ $day_id ]->type ) and $value[ $day_id ]->type == 'range' ) ? ' selected="selected"' : '' }}>{{ $strings->range }}</option>
                        <option value="open" {{ ( isset( $value[ $day_id ] ) and isset( $value[ $day_id ]->type ) and $value[ $day_id ]->type == 'open' ) ? ' selected="selected"' : '' }}>{{ $strings->open }}</option>
                        <option value="closed" {{ ( isset( $value[ $day_id ] ) and isset( $value[ $day_id ]->type ) and $value[ $day_id ]->type == 'closed' ) ? ' selected="selected"' : '' }}>{{ $strings->closed }}</option>
                    </select>
                </div>
            </div>
            <div class="rz--row">

                <div class="rz--field">
                    <div class="rz-range-number" style="max-width: 700px;">

                        <div class="rz-range-field" data-type="from">
                            <div class="rz-field-suffix">
                                <span class="rz-suffix rz-flex rz-flex-column rz-justify-center">
                                    {{ $strings->start }}
                                </span>
                                <div class="rz-select rz-select-single">
                                    <select name="{{ $day_id }}[start]">
                                        <option value="">{{ $strings->select }}</option>
                                        @foreach( $hours as $hour_value => $hour_label )
                                            <option value="{{ $hour_value }}" {{ ( isset( $value[ $day_id ] ) and isset( $value[ $day_id ]->start ) and $value[ $day_id ]->start == $hour_value ) ? 'selected' : '' }}>{{ $hour_label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="rz-range-separator rz-flex rz-flex-column rz-justify-center">
                            -
                        </div>

                        <div class="rz-range-field" data-type="to">
                            <div class="rz-field-suffix">
                                <span class="rz-suffix rz-flex rz-flex-column rz-justify-center">
                                    {{ $strings->end }}
                                </span>
                                <div class="rz-select rz-select-single">
                                    <select name="{{ $day_id }}[end]">
                                        <option value="">{{ $strings->select }}</option>
                                        @foreach( $hours as $hour_value => $hour_label )
                                            <option value="{{ $hour_value }}" {{ ( isset( $value[ $day_id ] ) and isset( $value[ $day_id ]->end ) and $value[ $day_id ]->end == $hour_value ) ? 'selected' : '' }}>{{ $hour_label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endforeach

</div>
