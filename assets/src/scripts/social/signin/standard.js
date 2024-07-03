'use strict'

import debug from '../../utils/debug'

window.$ = window.jQuery

export default class Standard {

    constructor() {

        debug.site.log('Social: Standard')

        $(document).ready(() => this.init())

	}

	init() {

        this.$ = $(`.rz-signin-section[data-id='sign-in']`)
        this.$button = $(`[data-action='sign-in-standard']`)

        $(document).on('routiz/signin/standard', () => this.request())

        this.$.on( 'submit', e => {
            e.preventDefault()
            this.request()
        })

	}

    request() {

        let $modal = $('.rz-modal-signin')

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: window.rz_vars.admin_ajax,
            data: {
                action: 'rz_signin_standard',
                user_email: $('[name="user_email"]', this.$).val(),
                user_password: $('[name="user_password"]', this.$).val(),
                security: window.rz_vars.nonce,
            },
			beforeSend: () => {
                $('.rz-modal-button').addClass('rz-ajaxing')
                $('.rz-signin-errors', this.$).html('').removeClass('rz-active')
			},
			complete: () => {

            },
			success: ( response ) => {

				if( response.success ) {

                    window.location.reload()

                }else{

                    $('.rz-modal-container', $modal).scrollTop(999)
                    $('.rz-modal-button').removeClass('rz-ajaxing')

                    $('.rz-signin-errors', this.$).html( `<ul><li>${response.error_string}</li></ul>` )
                        .addClass('rz-active')

                }

			}
        })

    }

}