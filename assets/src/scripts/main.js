'use strict'

import Mobile from './mobile'
import Modal from './modal/modal'
import Signin from './social/signin/init'
import url from './utils/url'
import serialize from './form/serialize'

window.$ = window.jQuery
window.Routiz = window.Routiz || {}

class Routiz {

	constructor() {

		$(document).ready(() => this.ready())

	}

	ready() {

		this.init()

	}

	init() {

		this.bind()
		this.filters()
		this.search_form()

	}

	bind() {

		$(document).on('click', 'a[href="#"]', e => { e.preventDefault(); })
		$(document).on('click', '.rz-toggler', e => this.toggle(e))
		$(document).on('click', '.rz-notification-icon', e => this.notifications(e))

	}

	search_form() {

		$(document).on('submit', '.rz-search-form form', e => {

			e.preventDefault()

			$(e.currentTarget).closest('.rz-search-form').addClass('rz-ajaxing')

			let params = serialize( $( 'input, textarea, select', $( e.currentTarget ) ), true, true )

			window.Routiz.explore.dynamic.explore( params, window.rz_vars.pages.explore )

			return

		})

	}

	notifications(e) {

		let $e = $( e.currentTarget ).closest('.rz-notifications')

		$e.toggleClass('rz-open')

		if( $e.hasClass('rz-open') ) {

			$(document).on('mousedown.rz-outside:notifications', e => {
				if ( ! $e.is( e.target ) && $e.has( e.target ).length === 0 ) {
					$e.removeClass('rz-open')
					$(document).off('mousedown.rz-outside:notifications')
				}
			})

		}else{

			$(document).off('mousedown.rz-outside:notifications')

		}

	}

	toggle(e) {

		let $toggler = $( e.currentTarget )
		let $area = $(`[data-toggler="${$toggler.attr('data-for')}"]`)

		$toggler.toggleClass('rz-open')

		if( $area.length ) {

			$area.toggleClass('rz-area-closed')

			// open
			if( ! $area.hasClass('rz-area-closed') ) {
				TweenLite.to(
					$area,
					.4,
					{ height: $area.children().outerHeight(), ease: Power3.easeOut, onComplete: () => {
						$area.css('height', 'auto')
					}}
				);
			}
			// close
			else{
				TweenLite.to(
					$area,
					.4,
					{ height: 0, ease: Power3.easeOut }
				)
			}

		}

	}

	filters() {

		this.tabs_init()

		$(document).on('rz-filter-tabs:init, rz-dynamic:changed', () => this.tabs_init() )
		$(document).on('click', '.rz-tab-footer a', e => this.tabs_close(e) )

	}

	tabs_close(e) {
		$( e.currentTarget ).closest('.rz-filter-tab').removeClass('rz-expand')
	}

	tabs_init() {

		$('.rz-filter-tab').each(( index, element ) => {

			let $tab = $( element )

			$('.rz-tab-title', $tab).on('click', e => {

				let $e = $( e.currentTarget )
				let $tab = $e.parent()

				$('.rz-filter-tab .rz-tab-title').not( $e ).parent().removeClass('rz-expand')

				$tab.toggleClass('rz-expand')

				// close on click outside
				if( $tab.hasClass('rz-expand') ) {
					$(document).on('mousedown.outside', e => {

						if ( ! $tab.is( e.target ) && $tab.has( e.target ).length === 0 ) {

							$tab.removeClass('rz-expand')
							$(document).off('mousedown.outside')

						}

					})
				}

			})

			$tab.addClass('rz-ready')

		})

	}

}

new Routiz()
