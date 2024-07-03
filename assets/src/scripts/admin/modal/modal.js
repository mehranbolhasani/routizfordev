'use strict'

import debug from '../../utils/debug'
import * as Admin_Requests from './requests'

window.$ = window.jQuery
window.Routiz = window.Routiz || {}

class Admin_Modal {

    constructor() {

        this.requests = {}

        $(document).ready(() => this.ready())

    }

	ready() {

        debug.site.log('Modals Ready')

        this.$b = $('body')
        this.$overlay = $('.rz-overlay')

        $(document).on('click', '[data-modal]', e => this.request(e))
        $(document).on('click', '.rz-close, .rz-overlay', () => this.close())

	}

    request(e) {

        e.preventDefault()

        let $click = $( e.currentTarget )
        let id = $click.data('modal')
        let params = $click.data('params')

        if( id ) {
            this.open( id, params )
        }

    }

    open( id, params ) {

        let $e = $(`.rz-modal[data-id='${id}']`)

        if( $e.length ) {

            let request_id = id.split('-').join('_')

            // built-in
            if( typeof Admin_Requests[ request_id ] == 'function' ) {
                this.instance = new Admin_Requests[ request_id ]( $e, params )
                this.instance.init()
            }
            // external
            else if( typeof window.Routiz.modal.requests[ request_id ] == 'function' ) {
                new window.Routiz.modal.requests[ request_id ]( $e, params ).init()
            }

            $e.addClass('rz-visible')
            $('body').addClass('rz-modal-visible')

        }else{
            console.log(`Modal '${id}' doesn't exists.`);
        }

    }

    close() {

        $('.rz-modal.rz-visible').each(( index, element ) => {

            let $e = $( element )
            let id = $e.data('id')

            if( id ) {
                let request_id = id.split('-').join('_');
                if( typeof Admin_Requests[ request_id ] == 'function' ) {
    				this.instance.close()
    			}
            }

            $e.removeClass('rz-visible')

        })

        this.$b.removeClass('rz-modal-visible')

    }

}

window.Routiz.modal = new Admin_Modal()
