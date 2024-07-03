'use strict'

window.$ = window.jQuery
window.Routiz = window.Routiz || {}

class Routiz_Mobile {

	constructor() {

		$(document).ready(() => this.ready())

	}

	ready() {

		this.$b = $('body')

		this.init()

	}

	init() {

		this.bind()

	}

	bind() {

		$(document).on('click', `[data-action='toggle-search-filters']`, e => this.toggle_search_filters(e))
		// $(document).on('click', `.brk-mobile-nav .menu-item-has-children`, e => this.click_has_children(e))

	}

	toggle_search_filters( e ) {

		let $e = $( e.currentTarget )
		this.$b.toggleClass('rz--expand-search-filters')

	}

}

export default new Routiz_Mobile()
