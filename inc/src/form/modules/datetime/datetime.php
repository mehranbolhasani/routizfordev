<?php

namespace Routiz\Inc\Src\Form\Modules\Datetime;

use \Routiz\Inc\Src\Form\Modules\Module;

class Datetime extends Module {

    public function finish() {

        if( ! empty( $this->props->value ) ) {

            $datetime = new \DateTime();
            $datetime->setTimezone( new \DateTimeZone('GMT') );
            $datetime->setTimestamp( $this->props->value );
            $datetime->setTimezone( new \DateTimeZone( wp_timezone_string() ) );

            $this->props->value = [
                'year' => $datetime->format('Y'),
                'month' => $datetime->format('m'),
                'day' => $datetime->format('d'),
                'hour' => $datetime->format('H'),
                'minute' => $datetime->format('i')
            ];
        }

    }

    public function controller() {

        $months = [
            1 => esc_html__('Jan', 'routiz'),
            2 => esc_html__('Feb', 'routiz'),
            3 => esc_html__('Mar', 'routiz'),
            4 => esc_html__('Apr', 'routiz'),
            5 => esc_html__('May', 'routiz'),
            6 => esc_html__('Jun', 'routiz'),
            7 => esc_html__('Jul', 'routiz'),
            8 => esc_html__('Aug', 'routiz'),
            9 => esc_html__('Sep', 'routiz'),
            10 => esc_html__('Oct', 'routiz'),
            11 => esc_html__('Nov', 'routiz'),
            12 => esc_html__('Dec', 'routiz'),
        ];

        return array_merge( (array) $this->props, [
            'months' => $months,
            'strings' => (object) [
                'select_month' => esc_html__('Select Month', 'routiz'),
                'month' => esc_html__('Month', 'routiz'),
                'day' => esc_html__('Day', 'routiz'),
                'year' => esc_html__('Year', 'routiz'),
                'hour' => esc_html__('Hour', 'routiz'),
                'minute' => esc_html__('Minute', 'routiz'),
            ],
        ]);

    }

}
