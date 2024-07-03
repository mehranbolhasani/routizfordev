'use strict'

import debug from '../../utils/debug'
import Signup from './signup'
import Standard from './standard'
import Reset from './reset'
import Facebook from './facebook'
import Google from './google'

window.$ = window.jQuery
window.Routiz = window.Routiz || {};

class Signin {

    constructor() {

        debug.site.log('Social: Facebook')

        $(document).ready(() => this.init())

	}

	init() {

        this.action = 'sign-in'

        new Signup()
        new Standard()
        new Reset()

        let $fb_button = $(`[data-action='sign-in-facebook']`)
        if( $fb_button.length ) {
            this.facebook = new Facebook()
        }

        let $gg_button = $(`[data-action='sign-in-google']`)
        if( $gg_button.length ) {
            this.google = new Google()
        }

        $(document).on('click', '.rz-signin-tabs li, .rz-lost-pass-link', e => {

			let $e = $(e.currentTarget)
			let id = $e.attr('data-for')
			let label = $e.attr('data-label')

            this.action = id

            if( $('.rz-standard-role').length ) {
                $('.rz-modal-signin .rz-modal-footer')[ id == 'create-account' ? 'addClass' : 'removeClass' ]('rz-none')
                $('.rz-signin-container').addClass('rz-none')
                $('.rz-standard-role').removeClass('rz-none')
            }

            $('.rz-modal-signin .rz-modal-button span').html( label )

			$('.rz-signin-tabs li').removeClass('rz-active')
			$e.addClass('rz-active')

			$('.rz-signin-section').removeClass('rz-active')
			$(`.rz-signin-section[data-id='${id}']`).addClass('rz-active')



		})

        $('.rz-modal-signin form').on('submit', e => {
            e.preventDefault()
            this.do_action()
        })

        $(document).on('routiz/signin/action', () => this.do_action())

        // role
        let $role = $('.rz-standard-role a')
        $role.on('click', e => {

            let $e = $(e.currentTarget)

            $role.removeClass('rz--active')
            $e.addClass('rz--active')

            $('.rz-standard-role input').val( $e.attr('data-role') )

            $('.rz-signin-container').removeClass('rz-none')
            $('.rz-standard-role').addClass('rz-none')

            $('.rz-modal-signin .rz-modal-footer').removeClass('rz-none')

        })

	}

    do_action() {

        if( $('.rz-modal-signin .rz-modal-button').hasClass('rz-ajaxing') ) {
            return;
        }

        switch( this.action ) {
            case 'sign-in':
                $(document).trigger('routiz/signin/standard')
                break;
            case 'create-account':
                $(document).trigger('routiz/signin/create-account')
                break;
            case 'reset-password':
                $(document).trigger('routiz/signin/reset-password')
                break;
        }

    }

}

window.Routiz.signin = new Signin()

// gapis fallback
window.rz_gapis_init = function() {
    window.Routiz.signin.google.gapis_init()
}
