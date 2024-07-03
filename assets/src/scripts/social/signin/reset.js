'use strict'

import debug from '../../utils/debug'

window.$ = window.jQuery

export default class Reset {

    constructor() {

        debug.site.log('Reset password')

        $(document).ready(() => this.init())

	}

	init() {

        this.$ = $(`.rz-signin-section[data-id='reset-password']`)
        this.$button = $(`[data-action='reset-password']`)

        $(document).on('routiz/signin/reset-password', () => this.request())

        this.$.on( 'submit', e => {
            e.preventDefault()
            this.request()
        })

	}

    request() {

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: window.rz_vars.admin_ajax,
            data: {
                action: 'rz_reset_password',
                user_email: $('[name="email"]', this.$).val(),
                security: window.rz_vars.nonce,
            },
			beforeSend: () => {
				$('.rz-modal-button').addClass('rz-ajaxing')
				// $('.rz-form-group-error', this.$).removeClass('rz-form-group-error')
                $('.rz-signin-errors', this.$).html('').removeClass('rz-active')
			},
			complete: () => {
                $('.rz-modal-button').removeClass('rz-ajaxing')
            },
			success: ( response ) => {

				if( response.success ) {

                    $('.rz-signin-success', this.$).addClass('rz-active')
                    $(`[name='email']`, this.$).val('')

                }else{

                    let error_strings = '<ul><li>' + response.error_strings.join('</li><li>') + '</li></ul>'
                    $('.rz-signin-errors', this.$).html( error_strings ).addClass('rz-active')

                }

			}
        })

    }

}