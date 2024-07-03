'use strict'

import debug from '../utils/debug'
import * as mods from './mods'
import * as actions from './actions'

window.$ = window.jQuery
window.Routiz = window.Routiz || {}

class Listing {

    constructor() {

        debug.listing.log('Constructor')

        $(document).ready(() => this.init())

	}

    init() {

        this.xhr = null
        this.reviews_page = 1;

        this.fields()
        // this.guests()
        this.comments()

        $(document).on('rz-listing:init', () => this.modules())
            .trigger('rz-listing:init')

        $(document).on('rz-listing:action', () => this.action())
            .trigger('rz-listing:action')

        $('#rz-send-report').on('click', () => this.send_report())

        $(document).on('click', '[data-action="load-more-reviews"]', e => this.load_more_reviews(e))

    }

    load_more_reviews(e) {

        let $e = $(e.currentTarget)
        let $comments = $('.rz-reviews-comments')

        if( $comments.hasClass('rz-ajaxing') ) {
            return;
        }

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: window.rz_vars.admin_ajax,
            data: {
                action: 'rz_action_load_more_reviews',
                listing_id: window.rz_vars.post_id,
                onpage: ++this.reviews_page,
            },
			beforeSend: () => {
                $comments.addClass('rz-ajaxing')
			},
			complete: () => {
                $comments.removeClass('rz-ajaxing')
            },
			success: ( response ) => {

				if( response.success ) {

                    if( response.html ) {

                        let $html = $( response.html ).contents()

                        $('.rz-comments', $comments).append( $html )

                        if( response.max_num_pages <= this.reviews_page ) {
                            $e.addClass('rz-disabled').removeAttr('data-action')
                        }

                    }


				}

			}
        })

    }

    comments() {

        $(document).on('click', '.rz-comment-more', e => this.get_comment(e))

    }

    get_comment(e) {

        if( $('.rz-comment-more.rz-ajaxing').length ) {
            return;
        }

        let $e = $(e.currentTarget)
        let comment_id = $e.data('id')

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: window.rz_vars.admin_ajax,
            data: {
                action: 'rz_action_get_comment',
                comment_id: comment_id,
            },
			beforeSend: () => {
                $e.addClass('rz-ajaxing')
			},
			complete: () => {
                $e.removeClass('rz-ajaxing')
            },
			success: ( response ) => {

				if( response.success ) {

                    $(`.rz-comment-text[data-id='${comment_id}']`).html( response.comment_text )
                    $e.remove()

				}

			}
        })

    }

    // TODO: move this to action
    booking_calculate_price() {

        let $action = $('.rz-mod-action[data-type="booking"]')

        if( ! $action.length ) {
            return;
        }

        if( this.xhr !== null ) {
            this.xhr.abort()
        }

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

        this.xhr = $.ajax({
            type: 'post',
            dataType: 'json',
            url: window.rz_vars.admin_ajax,
            data: {
                action: 'rz_action_booking_pricing',
                listing_id: window.rz_vars.post_id,
                guests: $(`.rz-guests [name='guests']`, $action).val(),
                children: $(`[name='guest_children']`, $action).val(),
                adults: $(`.rz-guests [name='guests']`, $action).val()-$(`[name='guest_children']`, $action).val(),
                addons: addons,
                checkin: $('.rz-calendar-ts', $action).val(),
                checkout: $('.rz-calendar-ts-end', $action).val(),
            },
			beforeSend: () => {
                $('.rz-action-pricing', $action).html('')
				$action.addClass('rz-ajaxing')
				$('.rz-error-holder', $action).remove()
			},
			complete: () => {
                $action.removeClass('rz-ajaxing')
            },
			success: ( response ) => {

                this.xhr = null

				// success
				if( response.success ) {
                    $('.rz-action-pricing', $action).html( response.html )
				}

			}
        })

    }

    fields() {

        window.Routiz.form.fields()

    }

    modules() {

        $('.rz-mod-listing').not('.rz-ready').each(( index, element ) => {

            let $e = $( element )
            let type = $e.data('type')

			if( typeof mods[ type ] == 'function' ) {
				new mods[ type ]( $e )
			}

        })

    }

    action() {

        debug.listing.log('Action init')

        $('.rz-mod-action').each((i, e) => {

            let $e = $(e)
            let type = $e.data('type').replace( /-/g, '_' )

    		if( typeof actions[ type ] == 'function' ) {
    			new actions[ type ]( $e )
    		}

        })

    }

    send_report() {

        let $report = $('.rz-modal-report')
        let $content = $('.rz-modal-content', $report)

        if( $content.hasClass('rz-ajaxing') ) {
            return
        }
		$content.addClass('rz-ajaxing')

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: window.rz_vars.admin_ajax,
            data: {
                action: 'rz_send_report',
				security: $('#routiz_report').val(),
				listing_id: $('#rz_listing_id').val(),
				report_reason: $( '[name="rz_report_reason"]:checked', this.$ ).val()
            },
			beforeSend: () => {
				$content.addClass('rz-ajaxing')
				$('.rz-error-holder', $report).remove()
			},
			complete: () => {
                $content.removeClass('rz-ajaxing')
            },
			success: ( response ) => {

				// success
				if( response.success ) {

                    $('.rz-reported').removeClass('rz-none')
                    $('.rz-report-submit, .rz-modal-footer', $report).addClass('rz-none')

				}else{

                    for( var field_name in response.errors ) {
                        $(`.rz-form-group[data-id="${field_name}"]`, $report)
							.addClass('rz-form-group-error')
                            .append('<p class="rz-error-holder"><span class="rz-error">' + response.errors[ field_name ] + '</span></p>')
                    }

                }

			}
        })

    }

}

window.Routiz.listing = new Listing()
