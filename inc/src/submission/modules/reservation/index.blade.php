<h4 class="rz--title">{{ $title }}</h4>

<form>
    <div class="rz-grid">

        @if( $action_fields->allow_instant )

            {{ $form->render([
                'type' => 'checkbox',
                'id' => 'rz_instant',
                'name' => $strings->allow_instant,
            ]) }}

        @endif

        @if( $action_fields->allow_guests )

            {{ $form->render([
                'type' => 'number',
                'id' => 'rz_guests',
                'name' => $strings->max_guests,
                'description' => $strings->max_guests_desc,
                'input_type' => 'stepper',
                'style' => 'v2',
                'value' => 1,
                'min' => 1,
                'step' => 1,
                'col' => $action_fields->allow_guest_pricing ? 6 : 12,
                'col' => $action_fields->allow_child_pricing ? 6 : 12,
                'col' => $action_fields->allow_child_based_pricing ? 6 : 12,
            ]) }}

        @endif

        @if( $action_fields->allow_guests and $action_fields->allow_guest_pricing )

            {{ $form->render([
                'type' => 'number',
                'id' => 'rz_guest_price',
                'name' => $strings->guest_price,
                'description' => $strings->guest_price_desc,
                // 'input_type' => 'stepper',
                // 'style' => 'v2',
                'min' => 0,
                'step' => 0.01,
                'col' => 6,
            ]) }}

        @endif

        @if($action_fields->allow_guests and $action_fields->allow_child_pricing )

            {{ $form->render([
                'type' => 'number',
                'id' => 'rz_child_price',
                'name' => $strings->child_price,
                'description' => $strings->child_price_desc,
                // 'input_type' => 'stepper',
                // 'style' => 'v2',
                'min' => 0,
                'step' => 0.01,
                'col' => 6,
            ]) }}

        @endif

        @if($action_fields->allow_guests and $action_fields->allow_child_based_pricing )

            {{ $form->render([
                'type' => 'number',
                'id' => 'rz_child_sep_price',
                'name' => $strings->child_sep_price,
                'description' => $strings->child_price_sep,
                // 'input_type' => 'stepper',
                // 'style' => 'v2',
                'min' => 0,
                'step' => 0.01,
                'col' => 6,
            ]) }}

        @endif

        @if( $action_fields->allow_min_max )

            {{ $form->render([
                'type' => 'number',
                'id' => 'rz_reservation_length_min',
                'name' => $strings->reservation_length_min,
                'description' => $strings->length_empty,
                'input_type' => 'stepper',
                'style' => 'v2',
                'min' => 0,
                'step' => 1,
                'col' => 6
            ]) }}

            {{ $form->render([
                'type' => 'number',
                'id' => 'rz_reservation_length_max',
                'name' => $strings->reservation_length_max,
                'description' => $strings->length_empty,
                'input_type' => 'stepper',
                'style' => 'v2',
                'min' => 0,
                'step' => 1,
                'col' => 6
            ]) }}

        @endif

        @if( $actions->has('booking_hourly') )

            {{ $form->render([
                'type' => 'select',
                'id' => 'rz_reservation_start',
                'name' => $strings->booking_start,
                'options' => $time,
                'col' => 6
            ]) }}

            {{ $form->render([
                'type' => 'select',
                'id' => 'rz_reservation_end',
                'name' => $strings->booking_end,
                'options' => $time,
                'col' => 6
            ]) }}

            {{ $form->render([
                'type' => 'checkbox',
                'id' => 'rz_hour_book_multi_disable',
                'name' => $strings->hourly_multi_disable,
                'col' => 6
            ]) }}

        @endif

        @if( $actions->has('booking_appointments') )

            @php
                global $rz_form;
                $rz_form = $form;
            @endphp

            {{ Rz()->the_template('routiz/metabox/listing/reservation-availability') }}

        @endif

    </div>
</form>
