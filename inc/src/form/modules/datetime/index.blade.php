@include('label.index')

<div class="rz-datetime">

    <div class="rz-grid">
        <div class="rz-col-12 rz--col-month rz-mb-1">
            <p class="rz-weight-700 rz-mb-1">{{ $strings->month }}</p>
            <div class="rz-select rz-select-single">
                <select name="{{ $id }}[month]">
                    <option value="">{{ $strings->select_month }}</option>
                    @foreach( $months as $month_key => $month_name )
                        <option value="{{ $month_key }}" @if( isset( $value['month'] ) and $value['month'] == $month_key ) selected="selected" @endif>{{ sprintf( '%02d', $month_key ) }} - {{ $month_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="rz-col-12 rz--col-day rz-mb-1">
            <p class="rz-weight-700 rz-mb-1">{{ $strings->day }}</p>
            <input

                type="text"
                name="{{ $id }}[day]"
                value="{{ isset( $value['day'] ) ? $value['day'] : '' }}"
                placeholder="{{ date_i18n('d') }}">
        </div>
        <div class="rz-col-12 rz--col-year rz-mb-1">
            <p class="rz-weight-700 rz-mb-1">{{ $strings->year }}</p>
            <input

                type="text"
                name="{{ $id }}[year]"
                value="{{ isset( $value['year'] ) ? $value['year'] : '' }}"
                placeholder="{{ date_i18n('Y') }}">
        </div>
    </div>

    <div class="rz-grid">
        <div class="rz-col-6 rz--col-hour rz-mb-1">
            <p class="rz-weight-700 rz-mb-1">{{ $strings->hour }}</p>
            <input

                type="text"
                name="{{ $id }}[hour]"
                value="{{ isset( $value['hour'] ) ? $value['hour'] : '' }}"
                placeholder="{{ date_i18n('H') }}">
        </div>
        <div class="rz-col-6 rz--col-minute rz-mb-1">
            <p class="rz-weight-700 rz-mb-1">{{ $strings->minute }}</p>
            <input

                type="text"
                name="{{ $id }}[minute]"
                value="{{ isset( $value['minute'] ) ? $value['minute'] : '' }}"
                placeholder="{{ date_i18n('i') }}">
        </div>
    </div>



</div>
