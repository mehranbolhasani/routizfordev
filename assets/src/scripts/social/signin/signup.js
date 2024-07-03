'use strict'

import debug from '../../utils/debug'

window.$ = window.jQuery

export default class Signup {

    constructor() {

        debug.site.log('Social: Signup')

        $(document).ready(() => this.init())

	}

	init() {

        this.$ = $(`.rz-signin-section[data-id='create-account']`)
        this.$button = $(`[data-action='sign-up']`)

        $(document).on('routiz/signin/create-account', () => this.request())

        this.$.on( 'submit', e => {
            e.preventDefault()
            this.request()
        })

	}

    request() {

        let $modal = $('.rz-modal-signin')
        let signup_type = $modal.attr('data-signup')
        var response = grecaptcha.getResponse();
        if(response.length == 0) 
          { 
            var captcha=0;
          }
          else
          {
            var captcha=1;
          }

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: window.rz_vars.admin_ajax,
            data: {
                action: 'rz_signup',
                username: $('[name="username"]', this.$).val(),
                first_name: $('[name="first_name"]', this.$).val(),
                last_name: $('[name="last_name"]', this.$).val(),
                email: $('[name="email"]', this.$).val(),
                phone: $('[name="phone"]', this.$).val(),
                password: $('[name="password"]', this.$).val(),
                repeat_password: $('[name="repeat_password"]', this.$).val(),
                terms: $('[name="terms"]', this.$).prop('checked') ? 1 : 0,
                rcaptcha: captcha ? 1 : 0,
                role: $('[name="role"]', this.$).val(),
                security: window.rz_vars.nonce,
            },
			beforeSend: () => {

                $('.rz-modal-button').addClass('rz-ajaxing')
                $('.rz-signin-errors', this.$).html('').removeClass('rz-active')

                if( signup_type == 'email' ) {
                    $('.rz-signin-success', this.$).removeClass('rz-active')
                }

			},
			complete: () => {

            },
			success: ( response ) => {

				if( response.success ) {

                    if( signup_type == 'email' ) {

                        $('.rz-signin-success', this.$).addClass('rz-active')
                        $(`[name='first_name'], [name='last_name'], [name='email'], [name='password'], [name='repeat_password']`, this.$).val('')

                        $('.rz-modal-container', $modal).scrollTop(999)
                        $('.rz-modal-button').removeClass('rz-ajaxing')

                    }else{
                        window.location.reload()
                    }

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
