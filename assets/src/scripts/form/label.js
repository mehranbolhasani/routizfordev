'use strict'

import serialize from './serialize'

window.$ = window.jQuery

export default function label( $label ) {

    let results = {}
    let $title = $label.children('.rz-tab-title')

    $.each( $( 'input, select, textarea', $label ).serializeArray(), function() {

        let $e = $( `[name="${this.name}"]`, $label )
        let $group = $e.closest('.rz-field')
        let type = $group.attr('data-type')
        let selected, value

        let heading = $group.attr('data-heading')

        if( this.value.length ) {

            if( typeof heading == 'undefined' ) {
                heading = ''
            }

            switch( type ) {

                case 'calendar':

                    value = ''
                    let calendar_start = $('.rz-calendar-date-start', $group).val()
                    let calendar_end = $('.rz-calendar-date-end', $group).val()

                    if( calendar_start ) {
                        value += calendar_start
                    }

                    if( calendar_end ) {
                        value += ` - ${calendar_end}`
                    }

                    results[ `__${heading}` ] = value

                    break;

                case 'checkbox':

                    results[ heading ] = `__${this.value}`

                    break;

                case 'select':
                case 'select2':

                    selected = $(`select option[value='${this.value}']`, $group).html()

                    results[ heading ] = selected

                    break;

                case 'radio':

                    selected = $(`input[type="radio"][value='${this.value}']`, $group).closest('.rz-radio').find('span').html()

                    results[ heading ] = selected

                    break;

                case 'buttons':

                    selected = $(`input[type="radio"][value='${this.value}']`, $group).closest('.rz-btn').find('span').html()

                    results[ heading ] = selected

                    break;

                case 'checklist':

                    selected = []

                    $group.find('input[type="checkbox"]:checked').each(( index, element ) => {
                        selected.push( $( element ).closest('.rz-checkbox').find('em').html() )
                    })

                    results[ heading ] = selected.join(', ')

                    break;

                case 'range':

                    value = ''
                    let range_from = $('.rz-range-field[data-type="from"] input[type="hidden"]', $group).val()
                    let range_to = $('.rz-range-field[data-type="to"] input[type="hidden"]', $group).val()

                    if( range_from ) {
                        value += range_from
                    }

                    if( range_to ) {
                        value += ` - ${range_to}`
                    }

                    results[ heading ] = value

                    break;

                case 'autocomplete':
                case 'guests':

                    break;

                case 'number':

                    if( this.value > 0 ) {
                        results[ heading ] = this.value
                    }

                    break;

                case 'geo':

                    if( this.name == 'rz_geo' ) {
                        results[ heading ] = this.value
                    }

                    break;

                default:

                    if( typeof heading !== 'undefined' ) {
                        results[ heading ] = this.value
                    }

            }

        }

    })

    let text = ''

    for( const [ key, val ] of Object.entries( results ) ) {
        if( val.length ) {
            if( val.substring( 0, 2 ) == '__' ) {
                text += `${key}, `
            }else if( key.substring( 0, 2 ) == '__' ) {
                text += `${val}, `
            }else{
                // if( key.length ) {
                //     text += `${key}: ${val}, `
                // }else{
                //     text += `${val}, `
                // }
                text += `${val}, `
            }
        }
    }

    if( text.length ) {
        $title.removeClass('rz-is-placeholder')
    }else{
        $title.addClass('rz-is-placeholder')
    }


    $title.find('span').html( text.length ? text.slice( 0, -2 ) : $title.attr('data-label') )

}
