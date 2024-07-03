'use strict'

import debug from '../utils/debug'
import serialize from '../form/serialize'
// import url from '../utils/url'

window.$ = window.jQuery

class request {

    constructor( modal, params ) {

        this.$ = modal
        this.params = params
        this.$append = $('.rz-modal-append', this.$)

        modal.addClass('rz-modal-ready')

    }

    init() {}
    close() {}

    ajaxing() {

        if( this.$.hasClass('rz-ajaxing') ) {
            return
        }
		this.$.addClass('rz-ajaxing')
        this.$append.html('')

    }

}

/*
 * listing edit
 *
 */
export class listing_edit extends request {

    init() {

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: window.rz_vars.admin_ajax,
            data: {
                action: 'rz_listing_edit',
                listing_id: this.params
            },
            beforeSend: () => {
                this.ajaxing()
            },
            success: ( response ) => {

                this.$append.html( response.html )
                this.$.removeClass('rz-ajaxing')

                window.Routiz.form.fields() // init fields

                $(`[data-action='listing-save']`, this.$).on('click', () => { this.save() } )

            }
        })

    }

    save() {

        if( this.$.hasClass('rz-modal-ajaxing') ) {
            return;
        }

        let data = serialize( $( 'form', this.$ ) )
        data['action'] = 'rz_listing_update'

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: window.rz_vars.admin_ajax,
            data: data,
            beforeSend: () => {

                this.$.addClass('rz-modal-ajaxing')
                $('.rz-error-holder', this.$).remove()

            },
            complete: () => {
                // ..
            },
            success: ( response ) => {

                if( response.success ) {

                    window.location.reload()

                }else{

                    let $first_error = null

                    this.$.removeClass('rz-modal-ajaxing')

                    for( var field_name in response.errors ) {

                        let $error = $('[data-id="' + field_name.replace(/^rz_/, '') + '"]', this.$).first()

                        $error.addClass('rz-form-group-error')
                            .append('<p class="rz-error-holder"><span class="rz-error">' + response.errors[ field_name ] + '</span></p>')

                        if( ! $first_error ) {
                            $first_error = $error
                        }

                    }

                    // scroll to first error on screen
                    if( $first_error.length ) {

                        let top = $first_error.position().top + $('.rz-modal-container', this.$).scrollTop() - 20

                        $('.rz-modal-container', this.$).get(0).scrollTo({
                            top: top,
                            left: null,
                            behavior: 'smooth'
                        })

                    }

                }

            }
        })

    }

    close() {}

}

/*
 * promote
 *
 */
export class promote extends request {

    init() {

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: window.rz_vars.admin_ajax,
            data: {
                action: 'rz_get_promotions',
                listing_id: this.params
            },
            beforeSend: () => {
                this.ajaxing()
            },
            success: ( response ) => {

                this.$append.html( response.html )
                this.$.removeClass('rz-ajaxing')

                $(document).on('click', '[data-action="promote-listing"]', () => this.promote_listing())

            }
        })

    }

    promote_listing() {

        if( this.$.hasClass('rz-modal-ajaxing') ) {
            return;
        }

		$.ajax({
            type: 'post',
            dataType: 'json',
            url: window.rz_vars.admin_ajax,
            data: {
                action: 'rz_promote_listing',
				security: $('#routiz_promote_listing').val(),
				listing_id: $('#promote-listing-id').val(),
				package_id: $('[name="package_id"]:checked', this.$).val()
            },
            beforeSend: () => {

                this.$.addClass('rz-modal-ajaxing')
                $('.rz-error-holder', this.$).remove()

            },
            complete: () => {



            },
			success: ( response ) => {

				// success
				if( response.success ) {
					if( response.cart_url ) {
						window.location.href = response.cart_url
					}
				}
				// error
				else{

					this.$.removeClass('rz-modal-ajaxing')

					$('.rz-modal-container', this.$)
						.append('<div class="rz-error-holder rz-text-center"><p class="rz-error">' + response.error + '</p></div>');
				}

			}
        })

	}

    close() {}

}

/*
 * favorites
 *
 */
export class favorites extends request {

    init() {

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: window.rz_vars.admin_ajax,
            data: {
                action: 'rz_get_favorites',
                listing_id: this.params
            },
            beforeSend: () => {
                this.ajaxing()
            },
            success: ( response ) => {

                this.$append.html( response.html )
                this.$.removeClass('rz-ajaxing')

            }
        })

    }

    close() {}

}

/*
 * conversation
 *
 */
export class conversation extends request {

    init() {

        this.params.action = 'rz_get_conversation'

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: window.rz_vars.admin_ajax,
            data: this.params,
            beforeSend: () => {
                this.ajaxing()
            },
            success: ( response ) => {

                this.count = response.count

                this.$append.html( response.html )

                let $messages = $('.rz-messages', this.$)
                let $container = $('.rz-modal-container', this.$)

                setTimeout(() => {
                    $container.scrollTop( $container.prop('scrollHeight') )
                }, 1 )

                let $input = $('#rz_message')
                $input.on('focus', () => {
                    $input.addClass('rz--focus')
                    $container.scrollTop( $container.prop('scrollHeight') )
                })

                $('[data-action="send-message"]', this.$).on('click', e => this.send_message(e))

                this.$.removeClass('rz-ajaxing')

            }
        })

    }

    send_message(e) {

        if( this.$append.hasClass('rz-ajaxing') ) {
            return;
        }

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: window.rz_vars.admin_ajax,
            data: {
                action: 'rz_send_message',
				security: $('#routiz_message').val(),
				listing_id: $('#rz_message_listing_id').val(),
				conversation_id: $('#rz_message_conversation_id').val(),
				message: $( '[name="rz_message"]', this.$ ).val()
            },
            beforeSend: () => {

                this.$append.addClass('rz-ajaxing')
                $('.rz-error-holder', this.$).remove()

            },
            success: ( response ) => {

                // success
				if( response.success ) {

                    $('.rz-messaged').removeClass('rz-none')
                    $('.rz-message-submit').addClass('rz-none')
                    $( '[name="rz_message"]', this.$ ).val('')

                    $('.rz-messages').replaceWith( $( response.html ).find('.rz-messages') )
                    this.message_ready()

				}
                // error
                else{

                    $('.rz-messages').append('<p class="rz-error-holder"><span class="rz-error">' + response.error + '</span></p>')
                    this.$append.removeClass('rz-ajaxing')

                }

            }
        })
    }

    message_ready() {

        setTimeout(() => {
            let $container = $('.rz-modal-container', this.$)
            $container.scrollTop( $container.prop('scrollHeight') )
            this.$append.removeClass('rz-ajaxing')
        }, 10 )

    }

    close() {

        this.$append.empty()
            .removeClass('rz-ajaxing')

        clearInterval( this.tracker )
        this.tracker = null

    }

}

/*
 * action type application
 *
 */
export class action_application extends request {

    init() {

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: window.rz_vars.admin_ajax,
            data: {
                action: 'rz_action_application_form',
                listing_id: window.rz_vars.post_id
            },
            beforeSend: () => {
                this.ajaxing()
            },
            success: ( response ) => {

                this.$append.html( response.html )

                $('[data-action="send-application"]', this.$).on('click', e => this.send_application(e))

                this.$.removeClass('rz-ajaxing')

                window.Routiz.form.fields()

            }
        })

    }

    send_application(e) {

        if( this.$.hasClass('rz-modal-ajaxing') ) {
            return;
        }

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: window.rz_vars.admin_ajax,
            data: {
                action: 'rz_action_application_send',
                listing_id: window.rz_vars.post_id,
                input: serialize( $( 'form', this.$ ) )
            },
            beforeSend: () => {

                this.$.addClass('rz-modal-ajaxing')
                $('.rz-error-holder', this.$).remove()

            },
            complete: () => {

                this.$.removeClass('rz-modal-ajaxing')

            },
            success: ( response ) => {

                if( response.success ) {

                    $('.rz--icon', this.$).removeClass('rz-none')
                    $('.rz-form, .rz-modal-footer', this.$).addClass('rz-none')

                }else{

                    for( var field_name in response.errors ) {

                        $('[data-id="' + field_name.replace(/^rz_/, '') + '"]', this.$)
                            .addClass('rz-form-group-error')
                            .append('<p class="rz-error-holder"><span class="rz-error">' + response.errors[ field_name ] + '</span></p>');
                    }

                }

            }
        })
    }

    close() {
        this.$append.empty()
        this.$.removeClass('rz-modal-ajaxing')
    }

}

/*
 * action claim
 *
 */
export class action_claim extends request {

    init() {

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: window.rz_vars.admin_ajax,
            data: {
                action: 'rz_action_claim',
                listing_id: window.rz_vars.post_id
            },
            beforeSend: () => {
                this.ajaxing()
            },
            success: ( response ) => {

                this.$append.html( response.html )

                $('[data-action="send-claim"]', this.$).on('click', e => this.send_claim(e))

                this.$.removeClass('rz-ajaxing')

                window.Routiz.form.fields()

            }
        })

    }

    send_claim(e) {

        if( this.$.hasClass('rz-modal-ajaxing') ) {
            return;
        }

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: window.rz_vars.admin_ajax,
            data: {
                action: 'rz_action_claim_send',
                listing_id: window.rz_vars.post_id,
                claim_comment: $(`[name='claim_comment']`, this.$).val(),
            },
            beforeSend: () => {

                this.$.addClass('rz-modal-ajaxing')
                $('.rz-error-holder', this.$).remove()

            },
            complete: () => {
                // ..
            },
            success: ( response ) => {

                if( response.success ) {

                    if( response.redirect_url ) {
                        window.location.href = response.redirect_url
                    }else{
                        this.$.removeClass('rz-modal-ajaxing')
                        $('.rz--icon', this.$).removeClass('rz-none')
                        $('.rz-form, .rz-modal-footer', this.$).addClass('rz-none')
                    }

                }else{

                    this.$.removeClass('rz-modal-ajaxing')
                    $('.rz-modal-container', this.$)
						.append('<div class="rz-error-holder rz-text-center"><p class="rz-error">' + response.error + '</p></div>');

                }

            }
        })
    }

    close() {
        this.$append.empty()
        this.$.removeClass('rz-modal-ajaxing')
    }

}

/*
 * request payout
 *
 */
export class request_payout extends request {

    init() {

        window.Routiz.form.fields()

        $('[data-action="request-payout"]').on('click', () => this.call() )

    }

    close() {

        $('[data-action="request-payout"]').off()

    }

    call() {

        if( this.$.hasClass('rz-modal-ajaxing') ) {
            return;
        }

        let data = serialize( $( 'form', this.$ ) )
        data['action'] = 'rz_action_request_payout'

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: window.rz_vars.admin_ajax,
            data: data,
            beforeSend: () => {

                this.$.addClass('rz-modal-ajaxing')
                $('.rz-error-holder', this.$).remove()

            },
            complete: () => {

                this.$.removeClass('rz-modal-ajaxing')

            },
            success: ( response ) => {

                if( response.success ) {

                    $('.rz--icon', this.$).removeClass('rz-none')
                    $('.rz-form, .rz-modal-footer').addClass('rz-none')

                    setTimeout(() => {
                        window.location.reload()
                    }, 1000 )

                }else{

                    for( var field_name in response.errors ) {

                        $('[data-id="' + field_name.replace(/^rz_/, '') + '"]', this.$)
                            .addClass('rz-form-group-error')
                            .append('<p class="rz-error-holder"><span class="rz-error">' + response.errors[ field_name ] + '</span></p>');
                    }

                }

            }
        })

    }

}

/*
 * add review
 *
 */
export class add_review extends request {

    init() {

        this.reload = false

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: window.rz_vars.admin_ajax,
            data: {
                action: 'rz_get_review',
                listing_id: this.params
            },
            beforeSend: () => {
                this.ajaxing()
            },
            success: ( response ) => {

                this.$append.html( response.html )
                this.$.removeClass('rz-ajaxing')
                window.Routiz.form.fields()

                $('#rz-submit-review').on('click', e => this.submit(e))
        		$('.rz-rating-stars i', this.$).on('click', e => this.rate(e))

            }
        })

    }

	rate(e) {

		let $star = $( e.currentTarget )
		let $review = $star.parent()
		let $stars = $( 'i', $review )

		$stars.removeClass('rz-active')
        $star.addClass('rz-active')

		$review.parent().find('input').val( 5 - $stars.index( $star ) )

	}

	submit(e) {

		e.preventDefault()

        if( this.$.hasClass('rz-modal-ajaxing') ) {
            return;
        }

		$.ajax({
			type: 'post',
			dataType: 'json',
			url: window.rz_vars.admin_ajax,
			data: {
    			action: 'rz_submit_review',
    			review: serialize( $( 'input, textarea, select', this.$ ), false, true )
    		},
			beforeSend: () => {

                this.$.addClass('rz-modal-ajaxing')
                $('.rz-form-group-error', this.$).removeClass('rz-form-group-error')
        		$('.rz-error-holder', this.$).remove()

            },
            complete: () => {

                this.$.removeClass('rz-modal-ajaxing')

            },
			success: ( response ) => {

				if( response.success ) {

                    $('.rz-reviews-form', this.$).addClass('rz-none')
                    $('.rz-success', this.$).removeClass('rz-none')

                    $('.rz-modal-footer', this.$).hide()

                    this.reload = true
                    // setTimeout(() => {
                    //     window.location.reload()
                    // }, 1000 )

				}else{

					this.$.removeClass('rz-ajaxing')

					for( var field_name in response.errors ) {
                        $('.rz-form-group[data-id="' + field_name + '"]', this.$)
							.addClass('rz-form-group-error')
                            .append('<p class="rz-error-holder"><span class="rz-error">' + response.errors[ field_name ] + '</span></p>')
                    }

				}

			}
		});

	}

    close() {
        this.$append.html('')

        if( this.reload ) {
            window.location.reload()
        }
    }

}

/*
 * review reply
 *
 */
export class review_reply extends request {

    init() {

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: window.rz_vars.admin_ajax,
            data: {
                action: 'rz_get_review_reply',
                comment_id: this.params
            },
            beforeSend: () => {
                this.ajaxing()
            },
            success: ( response ) => {

                this.$append.html( response.html )
                this.$.removeClass('rz-ajaxing')
                window.Routiz.form.fields()

                $('#rz-submit-review-reply').on('click', e => this.submit(e))

            }
        })

    }

	submit(e) {

		e.preventDefault()

        if( this.$.hasClass('rz-modal-ajaxing') ) {
            return;
        }

		$.ajax({
			type: 'post',
			dataType: 'json',
			url: window.rz_vars.admin_ajax,
			data: {
    			action: 'rz_submit_review_reply',
    			reply: serialize( $( 'input, textarea, select', this.$ ), false, true )
    		},
			beforeSend: () => {

                this.$.addClass('rz-modal-ajaxing')
                $('.rz-form-group-error', this.$).removeClass('rz-form-group-error')
        		$('.rz-error-holder', this.$).remove()

            },
            complete: () => {

                this.$.removeClass('rz-modal-ajaxing')

            },
			success: ( response ) => {

				if( response.success ) {

                    $('.rz-reviews-form', this.$).addClass('rz-none')
                    $('.rz-success', this.$).removeClass('rz-none')

                    setTimeout(() => {
                        window.location.reload()
                    }, 1000 )

				}else{

                    this.$.removeClass('rz-ajaxing')

					for( var field_name in response.errors ) {
                        $('.rz-form-group[data-id="' + field_name + '"]', this.$)
							.addClass('rz-form-group-error')
                            .append('<p class="rz-error-holder"><span class="rz-error">' + response.errors[ field_name ] + '</span></p>')
                    }

				}

			}
		});

	}

    close() {
        this.$append.html('')
    }

}

/*
 * more filters
 *
 */
export class more_filters extends request {

    init() {

        $('.rz-modal-button', this.$).on('click', () => {
            window.Routiz.modal.close()
        })

    }

    close() {
        // this.$append.html('')
    }

}

/*
 * signin
 *
 */
export class signin extends request {

    init() {
        $('.rz-modal-footer', this.$).on('click', () => {
            $(document).trigger('routiz/signin/action')
        })
    }

    close() {
        // ..
    }

}

/*
 * account edit entry
 *
 */
export class account_entry extends request {

    init() {

        this.params = Object.assign({
            id: null,
            type: '',
        }, this.params )

        this.call()

    }

    close() {

        this.$append.html('')

        if( this.params.type !== '' ) {
            window.location.reload()
        }

    }

    call() {

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: window.rz_vars.admin_ajax,
            data: {
                action: 'rz_account_entry',
                id: this.params.id,
                type: this.params.type,
            },
            beforeSend: () => {
                this.ajaxing()
            },
            success: ( response ) => {

                if( response.redirect_url ) {

                    window.location.href = response.redirect_url

                }else{

                    this.$append.html( response.html )
                    this.$.removeClass('rz-ajaxing')

                    $(`[data-action='booking-entry-action']`, this.$).on('click', e => this.entry_action(e))

                }

            }
        })

    }

    entry_action( e ) {

        let $e = $(e.currentTarget)
        let type = $e.attr('data-type')
        if( type ) {
            this.params.type = type
        }

        this.call()

    }

}

/*
 * listing edit booking calendar
 *
 */
export class listing_edit_booking_calendar extends request {

    init() {

        this.month_index = 0

        this.$.on('click', '.rz--days .rz--available, .rz--days .rz--day-unavailable', e => this.toggle_day(e))
        this.$.on('click', `[data-action='calendar-toggle-all']`, e => this.toggle_all(e))
        this.$.on('click', '.rz--nav a', e => this.nav(e))

        this.load_month()

        this.ajaxing()

    }

    close() {
        this.$append.html('')

        this.$.off('click', '.rz--days .rz--available, .rz--days .rz--day-unavailable')
        this.$.off('click', `[data-action='calendar-toggle-all']`)
        this.$.off('click', '.rz--nav a')
    }

    load_month() {

        this.$.find('.rz-calendar-inline').addClass('rz-loading')

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: window.rz_vars.admin_ajax,
            data: {
                action: 'rz_listing_edit_booking_calendar',
                id: this.params,
                month: this.month_index
            },
            beforeSend: () => {
                // this.ajaxing()
            },
            success: ( response ) => {

                this.$.find('.rz-calendar-inline').removeClass('rz-loading')
                this.$append.html( response.html )
                this.$.removeClass('rz-ajaxing')

            }
        })

    }

    nav(e) {

        let $e = $( e.currentTarget )

        if( $e.hasClass('rz-disabled') ) {
            return;
        }

        let is_next = $e.data('action') == 'next'

        this.month_index += is_next ? +1 : -1

        if( this.month_index < 0 ) {
            return;
        }

        this.load_month()

    }

    toggle_day(e) {

        if( $('.rz--days > li.rz-ajaxing', this.$).length ) {
            return;
        }

        let $e = $(e.currentTarget)

        $e.append('<div class="rz-preloader"><i class="fas fa-sync fa-spin"></i></div>')
            .addClass('rz-ajaxing')

        this.toggle( [ $e.attr('data-timestamp') ] )

    }

    toggle_all() {

        let dates = [];
        let $e = $('.rz--days .rz--available', this.$).length ? $('.rz--days .rz--available', this.$) : $('.rz--days .rz--day-unavailable', this.$)

        $e.each(( index, day ) => {
            dates.push( $(day).attr('data-timestamp') )
        })
            .append('<div class="rz-preloader"><i class="fas fa-sync fa-spin"></i></div>')
                .addClass('rz-ajaxing')

        this.toggle( dates )

    }

    toggle( dates ) {

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: window.rz_vars.admin_ajax,
            data: {
                action: 'rz_listing_calendar_toggle_day',
                id: this.params,
                dates: dates
            },
            beforeSend: () => {
                // ..
            },
            success: ( response ) => {

                this.$append.html( response.html )
                this.$.removeClass('rz-ajaxing')

                $('.rz--days .rz-ajaxing', this.$).removeClass('rz-ajaxing')
                .toggleClass('rz--day-unavailable')
                .toggleClass('rz--not-available')
                .toggleClass('rz--available')
                    .find('.rz-preloader').remove()

            }
        })

    }

}

/*
 * listing edit booking iCalendar
 *
 */
export class listing_edit_booking_ical extends request {

    init() {

        this.params = Object.assign({
            id: null,
            type: '',
        }, this.params )

        this.call()

    }

    close() {
        this.$append.html('')
    }

    call() {

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: window.rz_vars.admin_ajax,
            data: {
                action: 'rz_listing_edit_booking_ical',
                id: this.params.id,
                type: this.params.type,
                input: serialize( $( 'form', this.$ ) )
            },
            beforeSend: () => {
                this.ajaxing()
            },
            success: ( response ) => {

                this.$append.html( response.html )
                this.$.removeClass('rz-ajaxing')
                window.Routiz.form.fields()

                $(`[data-action='ical-save']`, this.$).on('click', e => this.ical_action(e))

            }
        })

    }

    ical_action(e) {

        let $e = $(e.currentTarget)
        let type = $e.attr('data-type')
        if( type ) {
            this.params.type = type
        }

        this.call()

    }

}

/*
 * appointments
 *
 */
export class appointments extends request {

    init() {

        this.page = 0

        $(document).on('click.routiz-appointments-paginate', `[data-action='appointments-paginate']`, () => {
            this.call()
        })

        this.call()


    }

    close() {
        this.$.html()
        this.page = 0
        $(document).off('click.routiz-appointments-paginate')
    }

    call() {

        if( this.$.hasClass('rz-modal-ajaxing') ) {
            return;
        }

        this.page += 1;

        let addons = $(`[data-id='addons'] input`)
            .map(( index, e ) => {
                let $e = $(e)
                if( $e.is(':checked') ) {
                    let val = $e.val()
                    if( val.length ) {
                        return val;
                    }
                }
            }).get()

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: window.rz_vars.admin_ajax,
            data: {
                action: 'rz_appointments_get_more_dates',
                listing_id: window.rz_vars.post_id,
                page: this.page,
                checkin: $(`[data-type='booking_appointments'] .rz-calendar-ts`).val(),
                guests: $(`.rz-guests [name='guests']`).val(),
                children: $(`[name='guest_children']`).val(),
                adults: $(`.rz-guests [name='guests']`).val()-$(`[name='guest_children']`).val(),
                addons: addons,
            },
            beforeSend: () => {

                if( this.page == 1 ) {
                    this.ajaxing()
                }else{
                    this.$.addClass('rz-modal-ajaxing')
                }


            },
            success: ( response ) => {

                if( this.page == 1 ) {

                    this.$append.html( response.html )
                    this.$.removeClass('rz-ajaxing')

                }else{

                    if( response.html ) {

                        $('.rz-appointment-table', this.$).append( $('.rz-appointment-table > *', response.html) )

                        // remove duplicated days
                        $('.rz-appointment-table .rz--day', this.$).each((index, e) => {
                            let $e = $(e)
                            let unix = $e.attr('data-unix')
                            if( $(`[data-unix='${unix}']`, this.$).length > 1 ) {
                                $(`[data-unix='${unix}']:last`, this.$).remove()
                            }
                        })

                    }else{

                        $('.rz-modal-footer', this.$).remove()

                    }

                    this.$.removeClass('rz-modal-ajaxing')

                }

            }
        })

    }

}
