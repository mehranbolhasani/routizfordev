'use strict'

import debug from '../utils/debug'
import url from '../utils/url'
import serialize from '../form/serialize'

export default class Dynamic {

	constructor() {

        debug.dynamic.log('Constructor')

		this.$b = $('body')
		this.is_back = false
		this.is_explore = !! $('.rz-dynamic-listings').length

        $(document).on('click', 'a.rz-action-dynamic-explore, li.rz-action-dynamic-explore > a', e => this.request(e) )
        $(document).on('rz-explore:request', () => this.request() )

		this.popstate()

    }

	popstate() {

		window.addEventListener('popstate', e => {
			this.is_back = true
			this.request()
		});

	}

    request(e) {

		if( this.is_explore ) {

            if(e) {
				e.preventDefault()
			}

			this.explore( url.query( e ? $( e.currentTarget ).attr('href') : window.location.href ) )

		}

	}

	explore( params, _url ) {

        debug.dynamic.log('Explore start')

		_url = _url || null

		// this.is_type_present = params.type || null

		if( $('#rz-explore').hasClass('rz-ajaxing') ) {
            return
        }

		let is_explore_generated = url.generate( params, _url, false, this.is_back ) // set url with new params
		if( ! is_explore_generated ) {
			return
		}

		this.is_back = false

		// close infobox
		if(
			window.Routiz.explore.map &&
			window.Routiz.explore.map.infobox &&
			window.Routiz.explore.map.infobox.is_open
		) {
			window.Routiz.explore.map.infobox.close()
		}

		// close mobile expanded filter
        if( this.$b.hasClass('rz--expand-search-filters') ) {
            this.$b.removeClass('rz--expand-search-filters')
        }

		// primary
		if( $('.rz-search-form').length ) {
			params.search_form_id = $('.rz-search-form').attr('data-form-id')
		}

        params.action = 'rz_dynamic_explore'

		$.ajax({
			type: 'get',
			dataType: 'json',
			url: window.rz_vars.admin_ajax,
			data: params,
			beforeSend: () => {
                $('#rz-explore').addClass('rz-ajaxing')
            },
			success: ( response ) => {

				// if there is no map, create one
				if( ! window.Routiz.explore.map ) {
					window.Routiz.explore.init_map()
				}

				/*
				 * welcome
				 *
				 */
				if( window.Routiz.explore.map ) {
					window.Routiz.explore.map.show()
				}

				/*
				 * listings
				 *
				 */
				this.$listings = response.listings.content
				// this.$listings.imagesLoaded(() => {

					$('.rz-dynamic-listings').empty().replaceWith( this.$listings )

					this.$listings = null

					if( response.listings.has ) {
						window.Routiz.explore.init_listings()
					}

					/*
					 * filters
					 *
					 */

					// search form
					$('.rz-dynamic-filter').replaceWith( response.filters.content )

					// primary search form
					if( response.search_form ) {
						$(`.rz-search-form[data-form-id='${response.search_form.form_id}']`).html( response.search_form.content )
					}

					/*
					 * map
					 *
					 */
					$('.rz-dynamic-infoboxes').replaceWith( response.infoboxes.content )
					if( response.infoboxes.has ) {
						window.Routiz.explore.map.init_infobox()
					}

					$('.rz-dynamic-markers').replaceWith( response.markers.content )

					// init fields
					window.Routiz.form.fields()

					/*
					 * finish
					 *
					 */
					$(document).trigger('rz-dynamic:changed')

					// remove preloaders
					setTimeout(() => {

						$('#rz-explore').removeClass('rz-ajaxing')
						// $advanced.removeClass('rz-ajaxing')

						$('.rz-search-form').removeClass('rz-ajaxing')

						window.Routiz.explore.map.init_markers()

					}, 20 )

					// set explore type
					this.$b.removeClass(( index, css ) => {
						return ( css.match(/(^|\s)rz-explore-type--\S+/g) || [] ).join(' ')
					}).addClass(`rz-explore-type--${response.type}`)

					// set explore geo
					$('body')[ url.get('geo') ? 'addClass' : 'removeClass' ]('rz--explore-geo')

					// trigger
					$(document).trigger('rz-dynamic:done')

					// $('body')[ this.is_type ? 'addClass' : 'removeClass' ]('rz-is-explore-type')

				// })

			}
		});

	}

}
