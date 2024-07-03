'use strict'

import debug from '../utils/debug'
import slugalize from './slugalize'
import serialize from './serialize'
import label from './label'
import map_styles from '../explore/map/providers/google/styles/styles'
import url from '../utils/url'

import Queue from '../utils/queue'

window.$ = window.jQuery

class field {

    constructor( field ) {

        this.$ = field
        this.init()

        field.addClass('rz-field-ready')

        window.rz_vars.is_admin ? this.init_admin() : this.init_front()

        // this.collect()
        this.collect_label()

    }

    init() {}
    init_admin() {}
    init_front() {}

    collect_label() {

        let $e = this.$.closest('.rz-filter-tab')
        if( $e.length ) {
            label( $e )
        }

    }

    // collect field data
    collect() {

        this.collect_label()

        $(document).trigger('rz-form:changed')
        this.$.trigger('rz-field:changed')

    }

    ajaxing() {

        if( this.$.hasClass('rz-ajaxing') ) {
            return
        }
		this.$.addClass('rz-ajaxing')

    }

}

/*
 * text
 *
 */
export class text extends field {

    init() {

        let time = null

        $('input', this.$).on('input', () => {
            clearTimeout( time )
            time = setTimeout(() => {
                this.collect()
            }, 350 )
		});

    }

}

/*
 * editor
 *
 */
export class editor extends field {

    init() {

        let time = null

        $('textarea', this.$).on('input', () => {
            clearTimeout( time )
            time = setTimeout(() => {
                this.collect()
            }, 350 )
		})

    }

}

/*
 * Faq
 *
 */
export class faq extends field {

    init() {

        let time = null

        $('textarea', this.$).on('input', () => {
            clearTimeout( time )
            time = setTimeout(() => {
                this.collect()
            }, 350 )
        })

    }

}



/*
 * hidden
 *
 */
export class hidden extends field {

    init() {

        $('input', this.$).on('input', () => {
			this.collect()
		})

    }

}

/*
 * textarea
 *
 */
export class textarea extends field {

    init() {

        let time = null

        $('textarea', this.$).on('input', () => {
            clearTimeout( time )
            time = setTimeout(() => {
                this.collect()
            }, 350 )
		})

    }

}

/*
 * checkbox
 *
 */
export class checkbox extends field {

    init() {

        $('[type="checkbox"]', this.$).on('change', (e) => {
			$('[type="hidden"]', this.$).val( e.target.checked ? '1' : '' )
                this.collect()
		});

    }

}

/*
 * toggle
 *
 */
export class toggle extends field {

    init() {

        $('[type="checkbox"]', this.$).on('change', (e) => {
			$('[type="hidden"]', this.$).val( e.target.checked ? '1' : '' )
            this.collect()
		});

    }

}

/*
 * checklist
 *
 */
export class checklist extends field {

    init() {

		$('[type="checkbox"]', this.$).on('change', () => {
			$('[type="hidden"]', this.$).prop('disabled', $('[type="checkbox"]', this.$).serialize() )
            this.collect()
		});

    }

}

/*
 * options
 *
 */
export class options extends field {

    init() {

        this.$input = $('textarea', this.$)
        this.$items = $('.rz-opts-items', this.$)

        $('.rz-button-opts-add', this.$).on('click', () => this.add())

		$('.rz-button-opts-save', this.$).on('click', () => this.save())

    }

    add() {

        this.$.addClass('rz-expand')
        this.$input.val( this.$input.val().split('%%').join('\n') )

    }

    save() {

        this.$items.html('')

        let lines = this.$input.val().split('\n').join('%%').split('%%')

        for( let k = 0; k < lines.length; k++ ) {

            let option = lines[ k ].replace(/([ \t]+)?:([ \t]+)?/g, ':').split(':')

            if( option[0].trim() === '' ) { continue }
            if( ! option[1] ) { option[1] = option[0] }

            this.$items.append('<li><input type="text" value="' + option[1] + '" disabled></li>')

        }

        this.$input.val( lines.join('%%') )

        this.$.removeClass('rz-expand')

        this.collect()

    }

}

/*
 * number
 *
 */
export class number extends field {

    init() {

        let type = this.$.data('input-type');

        if( type == 'range' ) {
            this.range();
        }else if( type == 'stepper' ) {
            this.stepper();
        }else{
            // number
        }

        let time = null

        $('input', this.$).on('input', () => {
            clearTimeout( time )
            time = setTimeout(() => {
                this.collect()
            }, 350 )
		});

    }

    range() {

        let input = $('input', this.$);

        input.on('input', (e) => {

            $('.rz-number-range-text', this.$).html( input.data('format').replace( /%s/g, e.target.value ) );

        });

    }

    stepper() {

        let input = $('[type="number"]', this.$);
        let step = input.data('step') !== 'undefined' ? input.data('step') : 1;

        this.$.on('click', '.rz-stepper-button', (e) => {

            $(e.currentTarget).data('action') == 'increase' ? input.get(0).stepUp( step ) : input.get(0).stepDown( step )
            $('.rz-stepper-text', this.$).html( input.data('format').replace( /%s/g, input.val() ) )
            input.trigger('input')

        });

    }

}

/*
 * range
 *
 */
export class range extends field {

    init() {

        let type = this.$.data('input-type')

        this.init_number()

    }

    init_number() {
        $('[type="number"]', this.$).on('input', e => this.number_input(e))
    }

    number_input(e) {

        let $input = $( e.currentTarget )
        let $field = $input.closest('.rz-range-field')
        let $hidden = $( 'input[name]', $field )

        $hidden.val( e.target.value )
        this.number_hiddens()
        this.collect()

    }

    number_hiddens() {

        let has_values = false

        $( 'input[name]', this.$ )
            .each(( index, element ) => {
                if( element.value !== '' ) {
                    has_values = true
                }
            })
            .prop( 'disabled', ! has_values )

    }

}

/*
 * repeater
 *
 */
export class repeater extends field {

    init() {

        let self = this;

        this.$repeater = $('>.rz-repeater', this.$);
        this.$select = $('>.rz-repeater-select', this.$repeater);
        this.$items = $('>.rz-repeater-items', this.$repeater);

        this.items();
		this.sortable();

		$('.rz-button', this.$select).on('click', () => this.additem());

    }

	items() {

        $('>.rz-repeater-item', this.$items).not('.rz-item-ready').each((index, element) => {
			new repeater_item( $(element) );
        });

    }

	sortable() {

        let _this = this

		$( '>.rz-repeater-items', this.$repeater ).sortable({
			distance: 5,
			placeholder: 'rz-item-dummy',
			handle: '.rz-item-label',
			start: function( e, ui ) {
                ui.placeholder.attr('data-id', ui.item.data('id'))
				ui.placeholder.height( ui.item.height() )
			},
			update: function( e, ui ) {
				_this.collect()
			}
		});

	}

	additem() {

		this.ajaxing()

		let schema = JSON.parse( $('>.rz-repeater-schema', this.$repeater).val() )
		let template = $('select', this.$select).val()

        let data = {
            action: 'rz_repeater',
            post_id: window.rz_vars.post_id,
            schema: JSON.stringify( schema[ template ] ),
            template: template,
            storage: this.$.attr('data-storage')
        }

		$.ajax({
			type: 'POST',
			url: window.rz_vars.admin_ajax,
			data: data,
			success: ( response ) => {

				this.$.removeClass('rz-ajaxing')

				if( response.success ) {

					let item_element = $( response.html )
					$('>.rz-repeater-items', this.$repeater).append( item_element )

                    $(document).trigger('rz-form:init')

                    new repeater_item( item_element ).expand()

                    this.collect()

				}

			},
		});

	}

}

/*
 * repeater item
 *
 */
export class repeater_item {

    constructor( item ) {

        this.$ = item;
        this.$row = $('>.rz-item-row', this.$);

        $('.rz-item-remove', this.$row).on('click', (e) => this.remove(e));
        $('.rz-item-clone', this.$row).on('click', (e) => this.clone(e));
        $('.rz-item-hide', this.$row).on('click', (e) => this.hide(e));
		$('.rz-item-label', this.$row).on('click', (e) => this.expand(e));
		$( '>.rz-repeater-content > [data-id="' + this.$.data('heading') + '"] input', this.$ ).on('input', (e) => this.heading(e));

        item.addClass('rz-item-ready');

    }

    collect() {
        $(document).trigger('rz-form:changed');
    }

	remove(e) {
		$(e.currentTarget).closest('.rz-repeater-item').remove();
		this.collect()
	}

	clone(e) {

        let $e = $(e.currentTarget)
        let $item = $e.closest('.rz-repeater-item')
        let $clone = $item.clone()

        $clone.find('.rz-field-ready').removeClass('rz-field-ready')
        $clone.insertAfter( $item )

        $('.rz-field', $clone).each((index, e) => {

            let $field = $(e)
            let type = $field.attr('data-type')

            // re-populate auto-keys
            if( type == 'auto-key' ) {
                new auto_key( $field ).repopulate()
            }

        })

        $(document).trigger('rz-form:init')
        new repeater_item( $clone ).unexpand()
		this.collect()

	}

    hide(e) {

        let $e = $(e.currentTarget)
        let $item = $e.closest('.rz-repeater-item')
        let $check = $item.find(`[name='_item_hidden']:first`)
        let is_checked = $check.val().length

        $check.val( is_checked ? '' : '1' ).trigger('input')
        $item[ ! is_checked ? 'addClass' : 'removeClass' ]('rz-item-hidden')

        // $item.toggleClass('rz-item-hidden')

    }

	unexpand() {

		this.$.removeClass('rz-expand')

	}

	expand() {

        if( this.$.hasClass('rz-item-empty') ) {
            return;
        }

		this.$.parent().children().not( this.$ ).removeClass('rz-expand')
		this.$.toggleClass('rz-expand')

        // trigger window resize
        window.dispatchEvent(
            new Event('resize')
        )

	}

	heading(e) {

		$('>.rz-item-row .rz-item-title', this.$).html( $( e.currentTarget ).val().replace(/(<([^>]+)>)/gi, "") )

	}

}

/*
 * select2
 *
 */
export class select2 extends field {

    init() {

        $('select', this.$)
            .select2({
                // placeholder: window.rz_vars.localize.select,
                // allowClear: true
                // containerCssClass: 'rz-select2-container',
                // dropdownCssClass: 'rz-select2-dropdown',
                width: '100%',
                dropdownAutoWidth: true,
                placeholder : {
                    id : '',
                    text : window.rz_vars.localize.select
                },
            })
            .on('change', (e) => {
                if( $( e.currentTarget ).attr('multiple') ) {
                    $('>[type="hidden"]', this.$).prop('disabled', $( e.currentTarget ).serialize() )
                }
                this.collect()
			})

    }
}

/*
 * buttons
 *
 */
export class buttons extends field {

    init() {

        $('.rz-buttons .rz-btn', this.$).on('click', (e) => {
			$('input', e.currentTarget).prop( 'checked', $( 'input', e.currentTarget ).is(':checked') ? false : true ).trigger('change')
		});

        $('[type="radio"]', this.$).on('change', () => {
			$('>[type="hidden"]', this.$).prop('disabled', $('[type="radio"]', this.$).serialize() )
            this.collect()
		});

    }
}

/*
 * radio
 *
 */
export class radio extends field {

    init() {

        $('[type="radio"]', this.$).on('change', () => {
			this.collect()
		});

    }
}

/*
 * radio image
 *
 */
export class radio_image extends field {

    init() {

        $('[type="radio"]', this.$).on('change', () => {
			this.collect()
		});

    }
}

/*
 * select
 *
 */
export class select extends field {

    init() {

        $('select', this.$).on('change', () => {
            this.collect()
        })

        // fix for empty inputs in post
		$('select[multiple]', this.$).on('change', (e) => {
			$( '>[type="hidden"]', this.$ ).prop('disabled', $( 'select', this.$ ).serialize() );
		})

    }

}

/*
 * icon
 *
 */
export class icon extends field {

    init() {

        this.loaded = false

        this.$input = $('.rz-icon-input', this.$)
        this.$select = $('select', this.$)

        $('.rz-font-search', this.$).on('input', e => this.search(e))
        this.$select.on('change', () => this.select())
        $('.rz-toggler', this.$).on('click', e => this.toggle(e))
        $('.rz-remove', this.$).on('click', e => this.remove(e))

        $('.rz-icon', this.$).removeClass('rz-icon-expand')
        $('.rz-icon-action', this.$).removeClass('rz-block')

    }

    toggle() {

        $('.rz-icon', this.$).toggleClass('rz-icon-expand')
        $('.rz-icon-action', this.$).toggleClass('rz-block')

        if( ! this.loaded ) {
            this.select()
            this.loaded = true
        }

    }

    select() {

        this.ajaxing()

        $.ajax({
            type: 'POST',
            url: window.rz_vars.admin_ajax,
            data: {
                action: 'rz_icon',
                set: this.$select.val(),
                active: this.$input.val()
            },
            success: ( response ) => {

                let toggler = $('.rz-current-icon', this.$)

                $('.rz-font-search', this.$).val('')

                $('.rz-icon-list ul', this.$)
                    .html( response.icons )
                    .find('li')
                    .on('click', e => this.icon_click(e) )

                // scroll to active icon
                let active = $('li.rz-active', this.$)
                let position = 0

                if( active.length ) {
                    position = active.position().top + $('.rz-icon-list ul', this.$).scrollTop() - 130
                }

                $('.rz-icon-list ul', this.$).scrollTop( position )

                this.$.removeClass('rz-ajaxing')

            },
        })

    }

    icon_click(e) {

        let icon = $( e.currentTarget )
        let attr = icon.attr('title')

        $('.rz-icon-list li', this.$).removeClass('rz-active')
        icon.addClass('rz-active')

        this.$input.val( attr )
        $('.rz-preview > i', this.$).attr( 'class', attr )

        this.$.find('.rz-icon').removeClass('rz--empty')

        this.collect()

    }

    search(e) {

        let icons = $('.rz-icon-list ul li', this.$)

        if( e.target.value ) {
            icons.addClass('rz-none')
            $( 'i[class*="' + e.target.value.toLowerCase() + '"]', icons ).closest('li').removeClass('rz-none')
        }else{
            icons.removeClass('rz-none')
        }

    }

    remove() {

        $('.rz-font-search', this.$).val('')
        // this.search.val('').trigger('input')

        $('.rz-icon-list ul li', this.$).removeClass('rz-active')

        this.$input.val('')
        $('.rz-preview > i', this.$).removeAttr('class')

        this.$.find('.rz-icon').addClass('rz--empty')

        this.collect()

    }

}

/*
 * key
 *
 */
export class key extends field {

    init() {

        this.hidden = $('[type="hidden"]', this.$)
		this.select = $('select', this.$)
		this.buttons = $('.rz-button', this.$)
		this.text = $('[type="text"]', this.$)

        this.buttons.on('click', () => this.on_click())
        this.text.on('focusout', (e) => this.on_focusout(e))
		this.select.on('change', (e) => this.on_change(e))

    }

    on_click() {

        this.$.toggleClass('rz-is-defined')
        let input = this.$.hasClass('rz-is-defined') ? this.select : this.text

        this.hidden.val( input.val() )

    }

    on_focusout(e) {

        let input = $( e.currentTarget )
        let slugalized = slugalize( input.val() )

        input.val( slugalized )
        this.hidden.val( slugalized )

        this.collect()

    }

    on_change(e) {

        let input = $( e.currentTarget )
        this.hidden.val( input.val() )

        this.collect()

    }

}

/*
 * auto key
 *
 */
export class auto_key extends field {

    init() {

        this.$input = $('input', this.$)

        this.$input.on('input', () => {
            this.populate()
			this.collect()
		})

        this.populate()

    }

    repopulate() {
        this.$input.val( this.generate( 30 ) )
    }

    populate() {

        if( ! this.$input.val() ) {
            this.$input.val( this.generate( 30 ) )
        }

    }

    generate( max_length ) {

        let result = []
        const characters = 'abcdefghijklmnopqrstuvwxyz0123456789'

        for( var i = 0; i < max_length; i++ ) {
            result.push( characters.charAt( Math.floor( Math.random() * characters.length ) ) )
        }

        return result.join('')

    }

}

/*
 * calendar
 *
 */
export class calendar extends field {

    init() {

        this.range = this.$.data('range')
        this.readonly = this.$.data('readonly')
        this.clicks = 0
        this.ts = 0
        this.ts_end = 0
        this.date = ''
        this.date_end = ''

        if( ! this.readonly ) {

            $('.rz-calendar .rz-days li', this.$).on('click rz-calendar:pick', (e) => this.day_click(e, true))
            $('.rz-calendar .rz-days li', this.$).on('mouseenter mouseleave', (e) => this.day_hover(e))
            $('.rz-calendar-clear', this.$).on('click', () => this.clear( true ))

            this.$.on('rz-calendar:change', () => this.change())
            this.$.on('rz-calendar:clear', () => this.clear())

        }

        $('.rz-calendar-nav a', this.$).on('click', (e) => this.nav(e))

        this.selected()

    }

    selected() {

        if( ! this.$.hasClass('rz-calendar-end') ) {

            let ts_start = $('.rz-calendar-ts', this.$).val()
            let ts_end = $('.rz-calendar-ts-end', this.$).val()

            if( ts_start.length && ts_end.length ) {
                $(`.rz-days li[data-timestamp="${ts_start}"]`).trigger('click')
                $(`.rz-days li[data-timestamp="${ts_end}"]`).trigger('click')
            }

        }

    }

    change() {

        $('.rz-calendar-ts', this.$).val( this.ts )
        $('.rz-calendar-ts-end', this.$).val( this.ts_end )

        $('.rz-calendar-date-start', this.$).val( this.date )
        $('.rz-calendar-date-end', this.$).val( this.date_end )

        this.$.trigger('rz-calendar:changed')

        if( ! this.range || this.ts_end ) {
            this.$.trigger('rz-calendar:satisfied', {
                ts: this.ts,
                ts_end: this.ts_end,
                date: this.date,
                date_end: this.date_end,
            })
        }

        this.collect()

    }

    day_hover(e) {

        if ( this.clicks == 1 ) {
            let $e = $( e.currentTarget )
            if( ! ( $e.hasClass('rz--not-available') || $e.hasClass('rz--temp-disabled') ) || $e.hasClass('rz--temp-active') ) {
                this.set_between_range( this.ts, $e.data('timestamp') )
            }
        }

    }

    set_between_range( from, to ) {

        $('.rz-calendar-month .rz-days li', this.$)
            .removeClass('rz--in-between')
            .filter(function() {
                let current = $(this).data('timestamp')
                return current > from && current < to
            })
            .addClass('rz--in-between')

    }

    day_click( e, is_click ) {

        let day = $( e.currentTarget )

        if(
            (
                day.hasClass('rz--not-available') ||
                day.hasClass('rz--temp-disabled') ||
                day.hasClass('rz--day-disabled') ||
                day.hasClass('rz--from-day')
            ) &&
            ! day.hasClass('rz--temp-active')
        ) {
            return
        }

        is_click = is_click || false

        let ts_picked = day.data('timestamp')
        let date_picked = day.data('date')

        $('.rz-calendar-actions', this.$).addClass('rz-visible')

        // single
        if( ! this.range ) {

            this.clear_days()
            // $('.rz-calendar-ts', this.$).val( date_picked )
            day.addClass('rz--from-day rz--to-day rz--selected')

            this.ts = ts_picked
            this.date = date_picked

            this.$.trigger('rz-calendar:change').trigger('rz-calendar:change')

            this.collect()

        }
        // range
        else{

            this.clicks += 1

            // start
            if( this.clicks == 1 ) {

                this.ts = ts_picked
                this.date = date_picked

                this.day_leave()
                this.disable_previous_dates()
                this.disable_next_dates_after_unavailable()
                this.enable_departing()

                this.$.addClass('rz-calendar-start')
                day.addClass('rz--from-day rz--selected')

                if( is_click ) {
                    this.$.trigger('rz-calendar:change')
                }

            }

            // end
            else if( this.clicks == 2 ) {

                this.ts_end = ts_picked
                this.date_end = date_picked

                this.disable_day_leave()
                this.disable_next_dates()
                this.set_between_range( this.ts, this.ts_end )

                this.$.removeClass('rz-calendar-start').addClass('rz-calendar-end')
                day.addClass('rz--to-day rz--selected')

                if( is_click ) {
                    this.$.trigger('rz-calendar:change')
                }

            }

            this.collect()

        }

    }

    day_leave() {
        $('.rz-calendar-month .rz-days li', this.$).on('mouseleave', () => {
            $('.rz-calendar-month .rz-days li', this.$).removeClass('rz--in-between')
        })
    }

    disable_day_leave() {
        $('.rz-calendar-month .rz-days li', this.$).off('mouseleave')
    }

    disable_next_dates() {

        let _this = this

        $('.rz-calendar-month .rz-days li[data-timestamp]', this.$)
            .filter(function() {
                let ts_current = $(this).data('timestamp')
                return ts_current > _this.ts_end
            })
            .addClass('rz--temp-disabled')
            .removeClass('rz--temp-active')

    }

    disable_next_dates_after_unavailable() {

        let _this = this
        let has_unavailable = false

        $('.rz-calendar-month ul li', this.$).filter(function() {

            let day = $(this)
            let current = day.data('timestamp')

            // fix for single day unavailable, having start and end
            if( current > _this.ts && day.hasClass('rz--unavailable-start') ) {
                has_unavailable = true
                return
            }

            if( day.hasClass('rz--unavailable-ends') && current > _this.ts ) {
                has_unavailable = true
            }

            return ( current < _this.ts ) || has_unavailable

        })
        .addClass('rz--temp-disabled')

    }

    disable_previous_dates() {

        let _this = this

        $('.rz-calendar-month .rz-days li', this.$).filter(function() {
            let day = $(this)
            let ts_current = day.data('timestamp')
            return ( ts_current < _this.ts )
        })
        .addClass('rz--temp-disabled')

    }

    enable_departing() {

        let _this = this

        let next_unavailable = $('.rz-calendar-month ul li.rz--unavailable-start', this.$)
            .filter(function( index ) {
                let current = $(this).data('timestamp')
                return current > _this.ts
            }
        ).eq(0)

        next_unavailable.addClass('rz--temp-active')

    }

    clear_days() {

        this.$.removeClass('rz-calendar-start rz-calendar-end')
        $('.rz-calendar-month ul li', this.$).removeClass('rz--to-day rz--from-day rz--selected rz--in-between rz--temp-disabled rz--temp-active')

    }

    clear( is_click ) {

        is_click = is_click || false

        this.clear_days()

        this.clicks = 0

        $('.rz-calendar-actions', this.$).removeClass('rz-visible')

        this.ts = this.ts_end = this.date = this.date_end = ''
        this.$.trigger('rz-calendar:change')
        if( is_click ) {
            this.$.trigger('rz-calendar:cleared')
        }

        this.collect()

    }

    nav(e) {

        let element = $( e.currentTarget )

        let months = $('.rz-calendar-month', this.$).length
        let is_next = element.data('action') == 'next'

        let indexes = []
        $('.rz-calendar-month.rz-active', this.$).each(( index, element ) => {
            indexes.push( $( '.rz-calendar-month', this.$ ).index( element ) )
        })

        // first month
        if( ! is_next && indexes[0] == 0 ) {
            return
        }

        // last month
        if( is_next && indexes[ indexes.length - 1 ] + 1 >= months ) {
            return
        }

        let nav = $('.rz-calendar-nav', this.$)
        $('a', nav).removeClass('rz-disabled')

        // nav first
        if( ! is_next && indexes[0] == 1 ) {
            $('a[data-action="prev"]', nav).addClass('rz-disabled')
        }

        // nav last
        if( is_next && indexes[ indexes.length - 1 ] + 2 >= months ) {
            $('a[data-action="next"]', nav).addClass('rz-disabled')
        }

        $( '.rz-calendar-month', this.$ ).removeClass('rz-active')
        let new_index
        for ( let i = 0; i < indexes.length; i++ ) {

            new_index = indexes[i] + ( is_next ? +1 : -1 )
            $( '.rz-calendar-month', this.$ ).eq( new_index ).addClass('rz-active')

        }

    }

}

/*
 * map
 *
 */
export class map extends field {

    init() {

        this.map = $('.rz-map', this.$)
        this.lat = $('.rz-map-lat', this.$)
        this.lng = $('.rz-map-lng', this.$)
        this.address = $('.rz-map-address', this.$)

        this.country = $('.rz-map-country', this.$)
        this.city = $('.rz-map-city', this.$)
        this.city_alt = $('.rz-map-city-alt', this.$)

        this.set()

    }

    set() {

        if( window.rz_vars.sdk.map_provider == 'google' ) {

    		// location
    		let myLatLng = {
    			lat: parseFloat( this.lat.val() ),
    			lng: parseFloat( this.lng.val() )
    		};

    		// init map
    		let map = new google.maps.Map( this.map.get(0), {
    			center: myLatLng,
    			zoom: 15,
    			mapTypeControl: false,
    			streetViewControl: false,
    			scrollwheel: false,
    			styles: map_styles()
    		});

    		// set marker
    		let marker = new google.maps.Marker({
    			position: myLatLng,
    			map: map,
    			title: 'Marker',
    			draggable:true,
    		});

    		let geocoder = new google.maps.Geocoder()

    		// marker drag listener
    		google.maps.event.addListener( marker, 'dragend', ( marker ) => {

                let geo_args = {
                    'latLng': marker.latLng
                }

    			geocoder.geocode( geo_args, ( results, status ) => {

    				if( status == google.maps.GeocoderStatus.OK ) {
    					if ( results[0] ) {

                            let geo = this.extract_geo( results )

    						this.set_values(
    							results[0].formatted_address,
    							marker.latLng.lat(),
    							marker.latLng.lng(),
                                geo
    						);

    					}
    				}else{

                        alert('Geocode was not successful for the following reason: ' + status)

                    }
    			})


    		});

            let geo_args = []

            if( window.rz_vars.map.geolocation_restrictions.length ) {
                geo_args.componentRestrictions = {
                    country: window.rz_vars.map.geolocation_restrictions,
                }
            }

    		// create the search box and link it to the UI element.
    		let searchBox = new google.maps.places.Autocomplete( this.address.get(0), geo_args );

    		// bias the SearchBox results towards current map's viewport.
    		map.addListener( 'bounds_changed', () => {
    			searchBox.setBounds( map.getBounds() )
    		})

    		// Listen for the event fired when the user selects a prediction and retrieve
    		// more details for that place.
    		searchBox.addListener( 'place_changed', () => {

    			let place = searchBox.getPlace();

    			if ( ! place.geometry || ! place.geometry.location ) {
    				return;
    			}

    			// For each place, get the icon, name and location.
    			var bounds = new google.maps.LatLngBounds();

    			if( typeof place !== 'undefined' ) {

    				if ( ! place.geometry ) {
    					console.log('Returned place contains no geometry');
    					return;
    				}

    				if ( place.geometry.viewport ) { // Only geocodes have viewport.
    					bounds.union( place.geometry.viewport );
    				} else {
    					bounds.extend( place.geometry.location );
    				}

    				marker.setPosition( place.geometry.location );
    				map.fitBounds( bounds );

                    let geo_args = {
                        'latLng': place.geometry.location
                    }

                    // get
                    geocoder.geocode( geo_args, ( results, status ) => {

        				if ( status == google.maps.GeocoderStatus.OK ) {
        					if ( results[0] ) {

                                let geo = this.extract_geo( results )

                                this.set_values(
                					place.formatted_address,
                					place.geometry.location.lat(),
                					place.geometry.location.lng(),
                                    geo
                				);

        					}
                        }else{

                            alert('Geocode was not successful for the following reason: ' + status)

                        }
        			})

    			}

    		});

        }else if( window.rz_vars.sdk.map_provider == 'mapbox' ) {

            let _lat = parseFloat( this.lat.val() )
            let _lng = parseFloat( this.lng.val() )

            if( isNaN( _lat ) || isNaN( _lng ) ) {
                return
            }

            // location
    		let latLng = {
    			lat: _lat,
    			lng: _lng
    		};

    		// init map
            let map = new mapboxgl.Map({
                container: this.map.get(0),
                style: window.rz_vars.sdk.mapbox_style_url ? window.rz_vars.sdk.mapbox_style_url : 'mapbox://styles/mapbox/streets-v11',
                center: latLng,
                zoom: 14,
                scrollZoom: false,
            })

            map.on('load', () => {
                map.resize()
            })

            map.addControl( new mapboxgl.NavigationControl() )

            // set marker
            const marker = new mapboxgl.Marker({
                draggable: true
            })
                .setLngLat( latLng )
                .addTo( map )

            marker.on('dragend', () => {

                const lngLat = marker.getLngLat();

                this.set_values(
                    null,
                    lngLat.lat,
                    lngLat.lng
                );

            })

            let xhr_geocode = null

            $('.rz-map-address', this.$).on('input', e => {

                $('.rz-dropdown-list li', this.$).remove()
                this.dropdown_close()

                if( xhr_geocode !== null ) {
                    clearTimeout( xhr_geocode )
                }

                xhr_geocode = setTimeout(() => {
                    $.ajax({
                        type: 'GET',
                        url: `https://api.mapbox.com/geocoding/v5/mapbox.places/${e.target.value}.json?types=address&access_token=${window.rz_vars.sdk.mapbox}`,
                        success: response => {
                            if( typeof response.features !== 'undefined' && response.features.length ) {
                                for( const element of response.features ) {
                                    $('.rz-dropdown-list', this.$).find('ul').append(`<li data-lat="${element.geometry.coordinates[1]}" data-lng="${element.geometry.coordinates[0]}"><a href="#"><i class="material-icon-location_onplaceroom"></i><span>${element.place_name}</span></a></li>`)
                                }
                                this.dropdown_show()
                            }
                        }
                    })
                }, 300 )

            })

            $('.rz-map-address', this.$).on('focus', e => {
                if( $('.rz-dropdown-list li', this.$).length ) {
                    this.dropdown_show()
                }
            })

            $('.rz-map-address', this.$).on('focusout', e => {
                setTimeout(() => {
                    this.dropdown_close()
                }, 250)
            })

            this.$.on('click', '.rz-dropdown-list li', e => {

                let $e = $(e.currentTarget)

                $('.rz-map-address', this.$).val( $e.find('span').html() )
                $('.rz-map-lat', this.$).val( parseFloat( $e.attr('data-lat') ) )
                $('.rz-map-lng', this.$).val( parseFloat( $e.attr('data-lng') ) )

                map.setCenter([
                    $e.attr('data-lng'),
                    $e.attr('data-lat'),
                ])

                marker.setLngLat([
                    $e.attr('data-lng'),
                    $e.attr('data-lat'),
                ])

            })

        }

    }

    dropdown_show() {
        $('.rz-dropdown', this.$).addClass('rz--visible')
    }

    dropdown_close() {
        $('.rz-dropdown', this.$).removeClass('rz--visible')
    }

    extract_geo( results ) {

        let country = null,
            city = null,
            city_alt = null

        for( let i = 0; i < results.length; i++ ) {

            if( results[i].types.includes('locality') ) {
                city = results[i].formatted_address
                continue;
            }

            if( results[i].types.includes('administrative_area_level_1') ) {
                city_alt = results[i].formatted_address
                continue;
            }

            if( results[i].types.includes('country') ) {
                country = results[i].formatted_address
                continue;
            }

        }

        return {
            country: country,
            city: city,
            city_alt: city_alt
        };

    }

    set_values( _address, _lat, _lng, geo ) {

        geo = geo || null

        if( _address ) {
            this.address.val( _address )
        }

		let lat;
		lat = this.lat;
		lat = lat.add( lat.prev() );
		lat.val( _lat );

		let lng;
		lng = this.lng;
		lng = lng.add( lng.prev() );
		lng.val( _lng );

        if( geo ) {
            this.country.val( geo.country )
            this.city.val( geo.city )
            this.city_alt.val( geo.city_alt )
        }

		this.collect()

	}

}

/*
 * upload
 *
 */
export class upload extends field {

    init_admin() {

        this.is_multiple = this.$.data('multiple');
        this.upload_type = this.$.data('upload-type');
        this.$input = $('.rz-upload-input', this.$);
        this.$preview = $('.rz-image-preview', this.$);

        if( this.is_multiple ) {
            this.reorder();
        }

        this.$.on('click', '.rz-image-remove', (e) => this.remove(e));

        $('.rz-upload-button', this.$).on('click', (e) => {

            e.preventDefault();

            const rz_media_uploader = wp.media.frames.file_frame = wp.media({ // extend the wp.media object
                library: { type: 'image' },
                multiple: this.is_multiple,
            });

            rz_media_uploader.on('select', () => { // when a file is selected, grab the url and set it as the text field's value

                let attachment_output = '';

                // single
                if( ! this.is_multiple ) {

                    let attachment = rz_media_uploader.state().get('selection').first().toJSON();

                    let attachment_url = null;
                    if( typeof attachment.sizes == 'undefined' ) {
                        // no image size support
                    }else if( typeof attachment.sizes.rz_thumbnail !== 'undefined' ) {
                        attachment_url = attachment.sizes.rz_thumbnail.url
                    }else if( typeof attachment.sizes.thumbnail !== 'undefined' ) {
                        attachment_url = attachment.sizes.thumbnail.url
                    }else{
                        attachment_url = attachment.sizes.full.url
                    }

                    let attachment_output = attachment.url;

                    // set input value
                    this.$input
                        .val( JSON.stringify([{
                            id: attachment.id,
                        }]))
                        .trigger('input');

                    // preview
                    this.preview( attachment_url, attachment.id );

                    this.collect()

                }
                // multiple
                else{

                    let _ids = [];

                    try {
                        let o = JSON.parse( this.$input.val() );
                        if ( o && typeof o === 'object' ) {
                            if( this.$input.val() !== '' ) {
                                _ids = JSON.parse( this.$input.val() );
                            }
                        }
                    } catch (e) {}

                    let selection = rz_media_uploader.state().get( 'selection' );
                    selection.map(( attachment ) => {

                        attachment = attachment.toJSON();

                        let attachment_url = null;
                        if( typeof attachment.sizes == 'undefined' ) {
                            // no image size support
                        }else if( typeof attachment.sizes.rz_thumbnail !== 'undefined' ) {
                            attachment_url = attachment.sizes.rz_thumbnail.url
                        }else if( typeof attachment.sizes.thumbnail !== 'undefined' ) {
                            attachment_url = attachment.sizes.thumbnail.url
                        }else{
                            attachment_url = attachment.sizes.full.url
                        }

                        _ids.push({
                            id: attachment.id.toString(),
                        });

                        // preview
                        this.preview( attachment_url, attachment.id );

                    });

                    // set input value
                    this.$input.val( JSON.stringify( _ids ) ).trigger('input');

                    this.collect()

                }
            });

            // open the uploader dialog
            rz_media_uploader.open();

        });

    }

    init_front() {

        this.is_multiple = this.$.data('multiple')
        this.upload_type = this.$.data('upload-type')
        this.$input = $('.rz-upload-input', this.$)
        this.$preview = $('.rz-image-preview', this.$)

        let gallery = []

        if( this.is_multiple ) {
            this.reorder()
        }

        this.$.on('click', '.rz-image-remove', (e) => this.remove(e))

        $('.rz-upload-file', this.$).on('change', (e) => {

            this.ajaxing()

            let file = e.target.files
            let files_queued = file.length
            let files_uploaded = 0
            let has_gallery_id = false
            let gallery_id = 0

            if( files_queued <= 0 ) {
                return;
            }

            if( this.is_multiple ) {
                $('.rz-image-prv-wrapper', this.$preview).each(function() {

                    gallery_id = parseInt( $(this).attr('data-id') )

                    for( let k = 0; k < gallery.length; k++ ) {
                        if( gallery[k].id == gallery_id ) {
                            has_gallery_id = true
                        }
                    }

                    if( ! has_gallery_id ) {
                        gallery.push({
                            id: gallery_id
                        })
                    }

                })
            }

            $.each( file, ( key, value ) => {

                var data = new FormData()
                data.append( 'action', 'rz_upload' )
                data.append( 'upload_type', this.upload_type )
                data.append( 'rz_file_upload', value )
                data.append( 'rz_action', 'upload' )

                $.ajax({
                    type: 'POST',
                    url: window.rz_vars.admin_ajax,
                    data: data,
                    cache: false,
                    dataType: 'json',
                    processData: false, // don't process the files
                    contentType: false, // set content type to false as jquery will tell the server its a query string request
                    beforeSend: () => {
                        $('.rz-error-output', this.$).empty()
                    },
                    complete: () => {
                        $('.rz-upload-file', this.$).val('')
                    },
                    success: ( response ) => {

                        files_uploaded++

                        if( files_uploaded == files_queued ) {
                            this.$.removeClass('rz-ajaxing')
                        }

                        if( response.success == true ) {

                            // multiple
                            if( this.is_multiple ) {

                                gallery.push({
                                    id: response.id,
                                })

                                this.$input
                                    .val( JSON.stringify( gallery ) )
                                    .trigger('input')

                            }
                            // single
                            else{

                                this.$preview.empty()
                                this.$input
                                    .val( JSON.stringify([{
                                        id: response.id,
                                    }]))
                                    .trigger('input')

                            }

                            var template = '<div class="rz-image-prv-wrapper" data-id="' + response.id + '" data-thumb="' + response.thumb + '">' +
                                '<div class="rz-image-prv">' +
                                    '<div class="rz-image-prv-outer">' +
                                        '<div class="rz-image-prv-inner rz-flex rz-flex-column rz-justify-center">' +
                                            ( this.upload_type == 'image' ? '<img class="rz-no-drag" src="' + response.thumb + '" alt="">' : '<i class="fas fa-file-alt"></i>' ) +
                                            '<span class="rz-image-remove rz-transition">' +
                                                '<i class="fas fa-times"></i>' +
                                            '</span>' +
                                        '</div>' +
                                    '</div>' +
                                '</div>' +
                                '<p class="rz-file-name">' +
                                    '<a class="rz-ellipsis" href="' + response.url + '" target="_blank">' + response.name + '</a>' +
                                '</p>' +
                            '</div>'

                            this.$preview.append( template )

                            this.collect()

                        }else{

                            $('.rz-error-output', this.$).append('<div class="rz-error-holder"><p class="rz-error">' + response.error + '</p></div>')

                        }
                    }
                })
            })
        })
    }

    reorder() {

        if( this.$.data('disabled') == 'yes' ) {
            return;
        }

        //if( $.fn.sortable ) {
            $('.rz-image-preview', this.$).sortable({
                distance: 5,
                placeholder: 'rz-image-prv-dummy',
                tolerance: 'pointer',
                start: ( e, ui ) => {
                    ui.placeholder.height( ui.item.width() );
                },
                update: ( e, ui ) => {

                    var gallery = [];
                    var input = ui.item.closest('[data-multiple]').find('.rz-upload-input');

                    ui.item.closest('.rz-image-preview').find('.rz-image-prv-wrapper').each(function() {
                        gallery.push({
                            id: $(this).attr('data-id'),
                            // thumb: $(this).attr('data-thumb'),
                        });
                    });

                    // set input value
                    input.val( JSON.stringify( gallery ) ).trigger('input');

                    this.collect()

                }
            });
        //}
    }

    preview( image_url, image_id, multiple ) {

        let preview_template = `<div class="rz-image-prv-wrapper" data-id="${image_id}">
            <div class="rz-image-prv">
                <div class="rz-image-prv-outer">
                    <div class="rz-image-prv-inner rz-flex rz-flex-column rz-justify-center">` +
                        ( ( this.upload_type == 'image' && image_url !== null ) ? `<img class="rz-no-drag" src="${image_url}" alt="">` : '<i class="fas fa-file-alt"></i>' ) +
                        `<span class="rz-image-remove rz-transition">
                            <i class="fas fa-times"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>`;

        if( ! this.is_multiple ) {
            this.$preview.empty();
        }

        this.$preview.append( preview_template );

    }

    remove(e) {

        // single
        if( ! this.is_multiple ) {

            // set input value
            this.$input.val('').trigger('input');
            this.$preview.empty();

            if( ! window.rz_vars.is_admin ) {
                let phisical_input = $('.rz-upload-file', this.$)
                if( phisical_input.length ) {
                    phisical_input.val('')
                }
            }

        }
        // multiple
        else{

            $( e.currentTarget ).closest('.rz-image-prv-wrapper').remove();

            var ids = [];

            $('.rz-image-prv-wrapper', this.$).each(function() {
                ids.push({
                    id: $(this).data('id'),
                    // thumb: $(this).data('thumb'),
                });
            });

            // set input value
            this.$input.val( JSON.stringify( ids ) ).trigger('input');

        }

        this.collect()

    }

}

/*
 * term
 *
 */
export class term extends field {

    init() {

        this.taxonomy = this.$.attr('data-taxonomy')
        this.$taxonomy = $('.rz-terms-taxonomy select', this.$)
        this.$terms = $('.rz-terms-terms select', this.$)
        this.$input = $('.rz-terms-input', this.$)

        // form stack
        this.form = this.$.parent('.rz-repeater-content')
		if( ! this.form.length ) {
			this.form = this.$.closest('.rz-form')
		}

        this.$taxonomy.on('change', () => this.taxonomy_change())
        this.$terms.on('change', () => this.terms_change())

    }

    taxonomy_change() {

		$.ajax({
			type: 'POST',
			url: window.rz_vars.admin_ajax,
			data: {
                action: 'rz_term',
                taxonomy: this.$taxonomy.val()
            },
			success: response => {

				if( response.success ) {

                    this.$terms.find('option' + ( ! this.$terms.prop('multiple') ? ':gt(0)' : '' ) ).remove()

                    for ( let id in response.terms ) {

                        let option = new Option( response.terms[ id ].name, response.terms[ id ].term_id, false, false )
                        this.$terms.append( option )

                    }

                    this.$terms.closest('.rz-terms-terms')[ response.terms.length ? 'addClass' : 'removeClass' ]('rz-active')

                    this.collect()

				}

			},
		});

    }

    terms_change() {

        this.collect()

    }

    collect() {

        if( this.$taxonomy.val() && this.$terms.val() ) {
            this.$input.val( JSON.stringify({
                taxonomy: this.$taxonomy.val(),
                term: this.$terms.val()
            }));
        }else{
            this.$input.val( null )
        }

        super.collect();

    }

}

/*
 * order list
 *
 */
export class order_list extends field {

    init() {

        $('ul', this.$).sortable({
            distance: 5,
            cancel: '.rz-list-empty',
            placeholder: 'rz-placeholder',
            connectWith: $('ul', this.$),
            tolerance: 'pointer',
            update: ( e, ui ) => {

                if( ui.sender ) {

                    let sender_state = ui.sender.data('state');

                    $( 'input', ui.item ).prop('disabled', sender_state == 'active' )

                    setTimeout(() => {

                        $('>[type="hidden"]', this.$).prop('disabled', $('[data-state="active"] li', this.$).length )

                        this.collect()

                    }, 20 )

                }

            }
        });

    }

}

/*
 * guests
 *
 */
export class guests extends field {

    init() {

        let $e = $('.rz-guests', this.$)
        let num_guest = parseInt( $e.data('num-guests') )
        let text_singular = $e.data('text-singular')
        let text_plural = $e.data('text-plural')
        let text_placeholder = $e.data('text-placeholder')

        if( ! $e.length ) {
            return;
        }

        $('[data-action="guests-close"]', this.$).on('click', e => {

            $e.removeClass('rz-open')
            $(document).off('mousedown.rz-outside:guests')

        })

        $('.rz--label', $e).on('click', e => {

            $e.toggleClass('rz-open')

            if( $e.hasClass('rz-open') ) {

                $(document).on('mousedown.rz-outside:guests', e => {
    				if ( ! $e.is( e.target ) && $e.has( e.target ).length === 0 ) {
    					$e.removeClass('rz-open')
    					$(document).off('mousedown.rz-outside:guests')
    				}
    			})

            }else{

                $(document).off('mousedown.rz-outside:guests')

            }

        })

        let $guest_inputs = $('[name="guest_adults"], [name="guest_children"]', this.$)
        $('[data-id="guest_adults"], [data-id="guest_children"]', $e).find('[data-action="increase"]').on('click', (e) => {

            e.preventDefault()

            let current_guests = 0;

            $guest_inputs.each(( index, element ) => {
                current_guests += parseInt( $(element).val() )
            });

            if( current_guests == num_guest ) {
                e.stopImmediatePropagation();
            }

        })

        $guest_inputs.on('input', () => {

            let current_guests = 0;

            $guest_inputs.each(( index, element ) => {
                current_guests += parseInt( $(element).val() )
            });

            // display placeholder
            if( current_guests == 0 ) {
                $e.addClass('rz-is-placeholder')
                $('.rz--label span', this.$).html( text_placeholder )
            }
            // display guests
            else{
                $e.removeClass('rz-is-placeholder')
                $('.rz--label span', this.$).html( current_guests <= 1 ? text_singular : text_plural.replace( '%s', current_guests ) )
            }

            $(`[name='guests']`, this.$).val( current_guests ).trigger('input')

            this.collect()

        })

    }

}

/*
 * autocomplete
 *
 */
export class autocomplete extends field {

    init() {

        this.xhr = null
        this.first_load = true

        this.outside()
        this.focus()

        $('.rz-quick-label', this.$).on('input', e => this.autocomplete(e))
        this.$.on('click', '.rz-selectable', e => this.selectable(e))
        $('.rz-icon-clear', this.$).on('click', e => this.clear(e))

    }

    focus() {

        let $input = $('.rz-quick-label', this.$)

        $input.on('focus', () => {
            if( $input.val().length > 0 ) {
                $('.rz-quick', this.$).addClass('rz-is-typed')
                if( this.first_load ) {
                    $input.trigger('input')
                }
            }
            this.first_load = false
        })

    }

    outside() {

		$(document).on('mousedown.rz-outside:quick', e => {
			if ( ! this.$.is( e.target ) && this.$.has( e.target ).length === 0 ) {
				$('.rz-quick', this.$).removeClass('rz-is-typed')
			}
		})

    }

    clear() {

        $('.rz-quick-input input', this.$).val('')
        $('.rz-quick', this.$)
            .removeClass('rz-is-typed')
            .removeClass('rz-is-selected')

        this.remove_dummy()

    }

    autocomplete(e) {

        setTimeout(() => {
            this.get_autocomplete( e, e.target.value )
        }, 5 )

    }

    get_autocomplete( e, search_term ) {

        let $e = $(e.currentTarget)

        $('.rz-quick', this.$)[ search_term.length > 0 ? 'addClass' : 'removeClass' ]('rz-is-typed')

        if( this.xhr !== null ) {
            this.xhr.abort()
        }

        $( '.rz-quick-input', this.$ ).addClass('rz-quick-ajaxing')

        this.xhr = $.ajax({
            type: 'post',
            dataType: 'json',
            url: window.rz_vars.admin_ajax,
            data: {
                action: 'rz_autocomplete',
                taxonomy: this.$.attr('data-taxonomy'),
                icon: this.$.attr('data-icon'),
                search: search_term,
            },
            beforeSend: ( response ) => {
                $('.rz-autocomplete ul', this.$).html('')
                $('.rz-autocomplete', this.$).addClass('rz-is-empty')
            },
            success: ( response ) => {

                if( typeof response.content !== 'undefined' ) {

                    $( '.rz-quick-input', this.$ ).removeClass('rz-quick-ajaxing')

                    let $content = $( response.content )

                    $content.imagesLoaded(() => {
                        $('.rz-autocomplete ul', this.$).html( $content )
                        $('.rz-autocomplete', this.$)[ response.found > 0 ? 'removeClass' : 'addClass' ]('rz-is-empty')
                    })

                }

            }
        })

    }

    remove_dummy() {
        $('.rz-quick-value', this.$).val('')
    }

    selectable(e) {

        let $e = $(e.currentTarget)

        this.remove_dummy()

        $('.rz-quick-label', this.$)
            .val( $e.find('.rz-auto-content').text().trim() )

        $('.rz-quick-value', this.$).val( $e.attr('data-value') )
        $('.rz-quick', this.$)
            .removeClass('rz-is-typed')
            .addClass('rz-is-selected')

    }

}

/*
 * color
 *
 *
export class color extends field {

    init() {

        $('.rz-colorpicker', this.$).wpColorPicker()

    }

}

*/

/*
 * geo
 *
 */
export class geo extends field {

    init() {

        this.set_places()

        if( window.rz_vars.sdk.map_provider == 'google' ) {

            this.set_geo()
            this.sync()

            $(`[name='rz_geo']`, this.$).on('change', e => {
                if( $(e.currentTarget).val() == '' ) {
                    $(`[name='rz_geo_n']`, this.$).val('')
                    $(`[name='rz_geo_e']`, this.$).val('')
                    $(`[name='rz_geo_s']`, this.$).val('')
                    $(`[name='rz_geo_w']`, this.$).val('')
                }
            })

        }else if( window.rz_vars.sdk.map_provider == 'mapbox' ) {

            $(document).on('routiz/explore/map/geo', () => this.idle())

            $(document).on('click', '.rz-ask-button', e => {
                $('.rz-geo-sync').removeClass('rz--ask')
                window.Routiz.explore.filtering()
            })

        }

    }

    set_places() {

        if( window.rz_vars.sdk.map_provider == 'google' ) {

            let geo_args = []

            if( window.rz_vars.map.geolocation_restrictions.length ) {
                geo_args.componentRestrictions = {
                    country: window.rz_vars.map.geolocation_restrictions,
                }
            }

            // create the search box and link it to the UI element.
            let searchBox = new google.maps.places.Autocomplete( $(`[name='rz_geo']`, this.$).get(0), geo_args )

            searchBox.addListener( 'place_changed', () => {

                let place = searchBox.getPlace()

                if ( ! place.geometry || ! place.geometry.location ) {
    				return;
    			}

                if ( place.geometry ) {

                    $(`[name='rz_geo_n']`, this.$).val( place.geometry.viewport.getNorthEast().lat )
                    $(`[name='rz_geo_e']`, this.$).val( place.geometry.viewport.getNorthEast().lng )
                    $(`[name='rz_geo_s']`, this.$).val( place.geometry.viewport.getSouthWest().lat )
                    $(`[name='rz_geo_w']`, this.$).val( place.geometry.viewport.getSouthWest().lng )

                }else{

                    console.log('Returned place contains no geometry')

                }

            })

        }else if( window.rz_vars.sdk.map_provider == 'mapbox' ) {

            let xhr_geocode = null

            $('.rz-map-address', this.$).on('input', e => {

                $('.rz-dropdown-list li', this.$).remove()
                this.dropdown_close()

                if( xhr_geocode !== null ) {
                    clearTimeout( xhr_geocode )
                }

                xhr_geocode = setTimeout(() => {
                    $.ajax({
                        type: 'GET',
                        url: `https://api.mapbox.com/geocoding/v5/mapbox.places/${e.target.value}.json?types=address&access_token=${window.rz_vars.sdk.mapbox}`,
                        success: response => {
                            if( typeof response.features !== 'undefined' && response.features.length ) {
                                let $li, $list = $('.rz-dropdown-list', this.$).find('ul')
                                for( const element of response.features ) {
                                    $li = $(`<li><a href="#"><i class="material-icon-location_onplaceroom"></i><span>${element.place_name}</span></a></li>`)
                                    $li.attr('data-lat', element.geometry.coordinates[1])
                                    $li.attr('data-lng', element.geometry.coordinates[0])
                                    $li.appendTo( $list )
                                }
                                this.dropdown_show()
                            }
                        }
                    })
                }, 300 )

            })

            $('.rz-map-address', this.$).on('focus', e => {
                if( $('.rz-dropdown-list li', this.$).length ) {
                    this.dropdown_show()
                }
            })

            $('.rz-map-address', this.$).on('focusout', e => {
                setTimeout(() => {
                    this.dropdown_close()
                }, 50)
            })

            this.$.on('click', 'li', e => {

                let $e = $(e.currentTarget)

                $('.rz-map-address', this.$).val( $e.find('span').html() )

                const geoViewport = require('@mapbox/geo-viewport')

                let size = [1003,1003]
                if( typeof window.Routiz.explore.map.map !== 'undefined' ) {
                    let map_container = $(window.Routiz.explore.map.map.getContainer())
                    if( map_container.length ) {
                        size = [map_container.outerWidth(), map_container.outerHeight()]
                    }
                }


                let results = geoViewport.bounds(
                    [ parseFloat( $e.attr('data-lng') ), parseFloat( $e.attr('data-lat') ) ],
                    14,
                    size
                )

                $(`[name='rz_geo_n']`, this.$).val( results[3] )
                $(`[name='rz_geo_e']`, this.$).val( results[2] )
                $(`[name='rz_geo_s']`, this.$).val( results[1] )
                $(`[name='rz_geo_w']`, this.$).val( results[0] )

            })

        }

    }

    dropdown_show() {
        $('.rz-dropdown', this.$).addClass('rz--visible')
    }

    dropdown_close() {
        setTimeout(() => {
            $('.rz-dropdown', this.$).removeClass('rz--visible')
        }, 100 )
    }

    set_geo() {

        $('.rz-geo-get', this.$).on('click', () => {

            if( window.rz_vars.sdk.map_provider == 'google' ) {

                if( navigator.geolocation ) {
                    navigator.geolocation.getCurrentPosition( position => {

                        if( window.Routiz.explore && window.Routiz.explore.map ) {

                            if( typeof this.geocoder == 'undefined' ) {
                                this.geocoder = new google.maps.Geocoder()
                            }

                            let geo_args = {
                                'location': new google.maps.LatLng( position.coords.latitude, position.coords.longitude )
                            }

                            this.geocoder.geocode( geo_args, ( results, status ) => {
                                if( status == google.maps.GeocoderStatus.OK ) {

                                    $(`[name='rz_geo']`, this.$).val( results[0].formatted_address )

                                    if ( results[0].geometry ) {

                                        $(`[name='rz_geo_n']`, this.$).val( results[0].geometry.viewport.getNorthEast().lat )
                                        $(`[name='rz_geo_e']`, this.$).val( results[0].geometry.viewport.getNorthEast().lng )
                                        $(`[name='rz_geo_s']`, this.$).val( results[0].geometry.viewport.getSouthWest().lat )
                                        $(`[name='rz_geo_w']`, this.$).val( results[0].geometry.viewport.getSouthWest().lng )

                                    }else{

                                        console.log('Returned place contains no geometry')

                                    }

                                }else{

                                    alert(`Geocode was not successful for the following reason: ${status}`)

                                }
                            })

                        }

                    }, () => {

                        alert(`Error: The Geolocation service failed`)

                    })

                }else{

                    alert(`Error: Your browser doesn't support geolocation`)

                }

            }else if( window.rz_vars.sdk.map_provider == 'mapbox' ) {



            }

        })

    }

    sync() {

        let $sync = $('.rz-geo-sync input')
        if( $sync.length ) {
            $sync.off('change').on('change', e => {
                if( $sync.is(':checked') ) {
                    this.idle()
                }
            })
        }

        setTimeout(() => {
            $(document).off('routiz/explore/map/geo')
            $(document).on('routiz/explore/map/geo', () => this.idle())
        }, 500 )

    }

    idle() {

        if( window.rz_vars.sdk.map_provider == 'google' ) {

            let $sync = $('.rz-geo-sync input')
            if( $sync.length && $sync.is(':checked') ) {

                let bounds = window.Routiz.explore.map.map.getBounds()
                let center = window.Routiz.explore.map.map.getCenter()
                let ne = bounds.getNorthEast()
                let sw = bounds.getSouthWest()

                $(`[name='rz_geo_n']`, this.$).val( ne.lat() )
                $(`[name='rz_geo_e']`, this.$).val( ne.lng() )
                $(`[name='rz_geo_s']`, this.$).val( sw.lat() )
                $(`[name='rz_geo_w']`, this.$).val( sw.lng() )

                window.Routiz.explore.filtering()

            }

        }else if( window.rz_vars.sdk.map_provider == 'mapbox' ) {

            let $sync = $('.rz-geo-sync input')
            if( $sync.length && $sync.is(':checked') ) {

                const geoViewport = require('@mapbox/geo-viewport')

                let center = window.Routiz.explore.map.map.getCenter()

                let size = [1004,1004]
                let map_container = $(window.Routiz.explore.map.map.getContainer())
                if( map_container.length ) {
                    size = [map_container.outerWidth(), map_container.outerHeight()]
                }

                let results = geoViewport.bounds(
                    [ center.lng, center.lat ],
                    window.Routiz.explore.map.map.getZoom() + 1,
                    size
                )

                $(`[name='rz_geo_n']`, this.$).val( results[3] )
                $(`[name='rz_geo_e']`, this.$).val( results[2] )
                $(`[name='rz_geo_s']`, this.$).val( results[1] )
                $(`[name='rz_geo_w']`, this.$).val( results[0] )

                $('.rz-geo-sync').addClass('rz--ask')

            }

        }

    }

}


/*
 * open hours
 *
 */
export class open_hours extends field {

    init() {

        let time = null

        this.change()

        $('select', this.$).on('change', () => {
            this.change()
            this.dependency()
        })

        this.dependency()

    }

    change() {

        $('.rz--value', this.$).val( JSON.stringify( serialize( $('.rz-week-days', this.$), true, false, true ) ) )

    }

    dependency() {

        $('.rz--day', this.$).each(( index, e ) => {

            let $e = $(e)
            let $select = $e.find('select')
            let $row = $e.find('.rz--row')

            if( $select.val() == 'range' ) {
                $row.show()
            }else{
                $row.hide()
            }

        })

    }

}
