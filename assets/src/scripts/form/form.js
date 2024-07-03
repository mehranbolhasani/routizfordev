'use strict'

import debug from '../utils/debug'
import escape from './escape'
import serialize from './serialize'
import dependency from './dependency'
import * as fields from './fields'

window.$ = window.jQuery
window.Routiz = window.Routiz || {};

class Form {

	constructor() {

		debug.form.log('Constructor')

		this.events()
	}

	events() {

		$(document).on('rz-form:changed', () => this.collect())
		$(document).on('rz-form:init', () => this.fields())

	}

	fields() {

		debug.form.log('Fields init')

        $('.rz-field').not('.rz-field-ready').each(( index, element ) => {

            let $e = $( element )
            let type = $e.data('type')

			if( typeof type !== 'undefined' ) {
				type = type.replace(/-/ig, '_')
			}

			if( typeof fields[ type ] == 'function' ) {
				new fields[ type ]( $e )
			}

			new dependency( $e )

        })

    }

	collect() {

		debug.form.log('Collect data')

		$('.rz-repeater-collect').each((index, element) => {

			let $repeater = $(element)
			let $input = $('>.rz-repeater-value', $repeater)

			$input
				.val( JSON.stringify( this.repeater_items( $repeater ) ) )
				.trigger('input')

			$input.get(0).dispatchEvent( new Event('input') )

		})

	}

	repeater_items( repeater ) {

		let values = []

		$('>.rz-repeater-items >.rz-repeater-item', repeater).each(( index, element ) => {

			let item = $( element )

			values.push({
				template: {
					id: item.attr('data-id'),
					name: item.attr('data-name'),
					heading: item.attr('data-heading'),
					heading_text: escape( item.find('[name="' + item.attr('data-heading') + '"]:first').val() ),
				},
				fields: this.repeater_item_fields( item )
			})

		})

		return values

	}

	repeater_item_fields( item ) {

		let values = {}

		$('>.rz-repeater-content >.rz-form-group', item).each(( index, element ) => {

			let field = $( element )
			let id = field.attr('data-id')
			let type = field.attr('data-type')

			if( typeof type !== 'undefined' ) {
				type = type.replace(/-/ig, '_')
			}

			if( type == 'repeater' ) {
				values[ id ] = this.repeater_items( $('>.rz-repeater', field) )
			}else{
				Object.assign( values, serialize( $('select, textarea, input', field), false, true ) )
			}

		})

		return values

	}

}

window.Routiz.form = new Form()
