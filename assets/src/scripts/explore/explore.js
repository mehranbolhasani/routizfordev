'use strict'

import debug from '../utils/debug'
import serialize from '../form/serialize'
import url from '../utils/url'
import Box from './box'
import Dynamic from './dynamic'

const Map = require(`./map/providers/${window.rz_vars.sdk.map_provider}/map`).default

window.$ = window.jQuery
window.Routiz = window.Routiz || {}

class Explore {

    constructor() {

        debug.explore.log('Constructor')

        $(document).ready(() => this.init())

	}

    init() {

        debug.explore.log('Dom ready')

        this.init_dynamic()
        this.init_map()
        this.init_listings()
        this.init_fields()

        $(document).on('click', '.rz-action-filter', () => this.filtering())
        $(document).on('click', '[data-action="add-favorite"]', e => this.add_favorite(e))
        $(document).on('click', '.rz-favorites-list .rz-remove', e => this.remove_favorite(e))

    }

    init_map() {
        this.map = new Map
        $(window).trigger('rz-explore:map')
    }

    init_dynamic() {
        this.dynamic = new Dynamic()
    }

    init_listings() {

        if( $('.rz-listings').length ) {

            $('.rz-listing').each(( index, e ) => {
                new Box( e )
            })
        }

    }

    init_fields() {
        window.Routiz.form.fields()
    }

    filtering() {

        if( this.dynamic.is_explore ) {

            let filters = serialize( $( 'input, textarea, select', $('.rz-search-filter') ), true, true )
            let more = serialize( $( 'input, textarea, select', $('.rz-modal-more-filters') ), true, true )
            let all = { ...filters, ...more }

            if( $('.rz-dynamic-listings').length ) {

                this.dynamic.explore( all )

            }else{

                url.generate( all, window.rz_vars.pages.explore )
                url.reload()

            }
        }

    }

    add_favorite(e) {

        e.preventDefault()

        let $e = $( e.currentTarget )

        if( $e.attr('href') !== '#' ) {
            return;
        }

        if( $e.hasClass('rz-ajaxing') ) {
            return;
        }

        let id = $e.data('id')

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: window.rz_vars.admin_ajax,
            data: {
                action: 'rz_add_favorite',
				id: id,
            },
			beforeSend: () => {

                $e.addClass('rz-ajaxing')

			},
			complete: () => {

                $e.removeClass('rz-ajaxing')

            },
			success: ( response ) => {

                $(`[data-action='add-favorite'][data-id='${id}']`).toggleClass('rz-active')
                $(`.rz-marker[data-id='${id}']`).toggleClass('rz--is-fav')

			}
        })

    }

    remove_favorite(e) {

        let $trash = $( e.currentTarget )
        let id = $trash.data('id')
        let $list = $trash.closest('ul')
        let $listing = $(`.rz-listing[data-id="${id}"]`)

        $trash.closest('li').remove()

        // empty
        if( ! $list.find('li').length ) {
            $('.rz-modal[data-id="favorites"] .rz--empty').removeClass('rz-none')
        }
    }

}

window.Routiz.explore = new Explore()
