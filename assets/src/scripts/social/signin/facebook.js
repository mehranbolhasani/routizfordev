'use strict'

import debug from '../../utils/debug'

window.$ = window.jQuery

export default class Facebook {

    constructor() {

        debug.site.log('Social: Facebook')

        $(document).ready(() => this.init())

	}

	init() {

        this.fb()

        this.$button = $(`[data-action='sign-in-facebook']`)

	}

    fb() {

        window.fbAsyncInit = () => {

            FB.init({
                appId : window.rz_vars.sdk.facebook.app_id,
                cookie : true,
                xfbml : true,
                version : 'v7.0'
            })

            FB.getLoginStatus(( response ) => {

                this.login_status = response
                this.$button.on('click', () => this.request())

            })

        }

    }

    request() {

        // authorized
        if( this.login_status.status == 'connected' ) {

            FB.api('/me?fields=email,name,first_name,last_name,picture', ( response ) => {

                response.action = 'rz_signin_facebook'
                response.security = window.rz_vars.nonce
                response.role = $('[name="role"]').val()

                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: window.rz_vars.admin_ajax,
                    data: response,
        			beforeSend: () => {
        				this.$button.addClass('rz-ajaxing')
        			},
        			complete: () => {

                    },
        			success: ( response ) => {

        				if( response.success ) {

                            window.location.reload()

                        }else{

                            this.$button.removeClass('rz-ajaxing')

                        }

        			}
                })

            })

        }
        // not authorized
        else if( this.login_status.status == 'not_authorized' ) {
            this.login()
        }
        // logged out
        else{
            this.login()
        }

    }

    login() {

        FB.login(( response ) => {

            this.login_status = response

            if( response.status == 'connected' ) {
                this.request()
            }

        }, {
            scope: 'email',
            return_scopes: true
        })

    }

}
