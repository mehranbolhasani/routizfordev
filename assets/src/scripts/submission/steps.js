'use strict'

import debug from '../utils/debug'

window.$ = window.jQuery

class step {

    constructor( element, response ) {

        this.$ = element
        this.response = response

    }

    init() {}
    error() {}

}

/*
 * fields
 *
 */
export class fields extends step {

    init() {

        window.dispatchEvent( new Event('resize') )

    }

}

/*
 * success
 *
 */
export class success extends step {

    init() {

        $('.rz-submission').addClass('rz--success')

        $(`[data-action='submission-continue']`)
            .attr( 'href', this.response.listing.button_url )
            .removeAttr('data-action')
            .find('.rz--text')
                .html( this.response.listing.button_text )

        $(`[data-action='submission-back']`)
            .addClass('rz-disabled')
            .removeAttr('data-action')

    }

}

/*
 * select plan
 *
 */
export class select_plan extends step {

    init() {

    }

    error() {

        $('.rz-select-plan-error', this.$).addClass('rz-block')
            .find('span').html( this.response.errors.rz_plan )

        $(window).scrollTop( $('.rz-select-plan-error', this.$).position().top )

    }

}
