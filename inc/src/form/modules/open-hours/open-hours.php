<?php

namespace Routiz\Inc\Src\Form\Modules\Open_Hours;

use \Routiz\Inc\Src\Form\Modules\Module;

class Open_Hours extends Module {

    public function value() {

        parent::value();

        $default = [
            'mon' => [
                'start' => null,
                'end' => null,
            ],
            'tue' => [
                'start' => null,
                'end' => null,
            ],
            'wed' => [
                'start' => null,
                'end' => null,
            ],
            'thu' => [
                'start' => null,
                'end' => null,
            ],
            'fri' => [
                'start' => null,
                'end' => null,
            ],
            'sat' => [
                'start' => null,
                'end' => null,
            ],
            'sun' => [
                'start' => null,
                'end' => null,
            ],
        ];

        // handle for serialized value
        if( is_serialized( $this->props->value ) ) {
            $this->props->value = maybe_unserialize( $this->props->value );

            $new = [];
            foreach( $this->props->value as $k => $v ) {
                $new[ $k ] = (object) $v;
            }

            $this->props->value = $new;
        }

        $value = is_array( $this->props->value ) ? $this->props->value : (array) json_decode( $this->props->value );
        $this->props->value = array_merge( $default, is_array( $value ) ? $value : [] );

    }

    /*
     * generate hours
     *
     */
    public function get_hours() {

        $hours = [];
        $today = strtotime( 'today', time() );
        $date = new \DateTime('01:00:00');
        $date_end = new \DateTime('24:00:00');
        $time_format = get_option('time_format');

        while( $date <= $date_end ) {
            $hours[ $date->getTimestamp() - $today ] = $date->format( $time_format );
            $date->add( new \DateInterval( apply_filters( 'routiz/form/field/open-hours/interval', 'PT30M' ) ) );
        }

        return $hours;

    }

    public function controller() {

        return array_merge( (array) $this->props, [
            'component' => $this->component,
            'placeholder' => date( get_option('time_format'), strtotime('8:00') ) . ' - ' . date( get_option('time_format'), strtotime('18:00') ),
            'hours' => $this->get_hours(),
            'week_days' => [
                'mon' => esc_html__('Monday', 'routiz'),
                'tue' => esc_html__('Tuesday', 'routiz'),
                'wed' => esc_html__('Wednesday', 'routiz'),
                'thu' => esc_html__('Thursday', 'routiz'),
                'fri' => esc_html__('Friday', 'routiz'),
                'sat' => esc_html__('Saturday', 'routiz'),
                'sun' => esc_html__('Sunday', 'routiz'),
            ],
            'strings' => (object) [
                'start' => esc_html__('Start', 'routiz'),
                'end' => esc_html__('End', 'routiz'),
                'select' => esc_html__('Select', 'routiz'),
                'range' => esc_html__('Time range', 'routiz'),
                'open' => esc_html__('All day open', 'routiz'),
                'closed' => esc_html__('All day closed', 'routiz'),
            ]
        ]);

    }

    public function before_save( $post_id, $value ) {

        return is_array( $value ) ? json_encode( $value ) : $value;

    }

}
