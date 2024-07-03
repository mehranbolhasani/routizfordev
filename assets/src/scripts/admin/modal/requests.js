'use strict'

window.$ = window.jQuery

class Admin_Request {

    constructor( modal, params ) {

        this.$ = modal
        this.params = params
        this.$append = $('.rz-modal-append', this.$)

        modal.addClass('rz-modal-ready')

    }

    init() {}
    close() {}

    ajaxing() {

        if( this.$.hasClass('rz-ajaxing') ) {
            return
        }
		this.$.addClass('rz-ajaxing')
        this.$append.html('')

    }

}

/*
 * listing edit
 *
 */
export class listing_edit extends Admin_Request {

    init() {



    }

    close() {}

}

