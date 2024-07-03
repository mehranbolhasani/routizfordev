'use strict'

import debug from '../../utils/debug'

window.$ = window.jQuery

export default class Google {

    constructor() {

        debug.site.log('Social: Google')

        // $(document).ready(() => this.init())
        this.init()

	}

	init() {

        this.$button = $(`[data-action='sign-in-google']`)

	}

    gapis_init() {

        gapi.load('auth2', () => {

            this.auth2 = gapi.auth2.init({
                client_id: window.rz_vars.sdk.google.client_id,
                cookiepolicy: 'single_host_origin',
                scope: 'profile email'
            })

            this.$button.each((index, e) => {
                this.attach_signin(e)
            })

        })

    }

    attach_signin(e) {
        this.auth2.attachClickHandler( e, {},
            googleUser => {
                let profile = googleUser.getBasicProfile()

                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: window.rz_vars.admin_ajax,
                    data: {
                        action: 'rz_signin_google',
                        id: profile.getId(),
                        name: profile.getName(),
                        first_name: profile.getGivenName(),
                        last_name: profile.getFamilyName(),
                        email: profile.getEmail(),
                        picture: profile.getImageUrl(),
                        security: window.rz_vars.nonce,
                        role: $('[name="role"]').val()
                    },
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

            },
            ( error ) => {
                console.log( 'Sign-in error', error )
            }
        )
    }

    request() {

    }

}
