'use strict'

import debug from '../utils/debug'
import map_styles from '../explore/map/providers/google/styles/styles'

window.$ = window.jQuery

class action {

    constructor( action ) {

        this.$ = action
        this.init()

        action.addClass('rz-ready')

    }

    ajaxing() {

        if( this.$.hasClass('rz-ajaxing') ) {
            return
        }
		this.$.addClass('rz-ajaxing')

    }

    init() {}

}

/*
 * daily booking
 *
 */
export class booking extends action {

    init() {

		$('.rz-add-booking', this.$).on('click', () => this.add())
        $('.rz-calendar-booking').on('rz-calendar:satisfied', () => this.booking_changed())
        $('.rz-calendar-booking').on('rz-calendar:cleared', () => this.booking_changed())
        $(`.rz-guests [name='guests']`).on('input', () => this.booking_changed())
        $(`[data-id='addons']`).on('input', () => this.booking_changed())

	}

    booking_changed() {
        window.Routiz.listing.booking_calculate_price()
    }

    add() {

        if( this.$.hasClass('rz-ajaxing') ) {
            return;
        }

        let data = {
            action: 'rz_add_booking',
            security: $('#security-action', this.$).val(),
            listing_id: window.rz_vars.post_id,
            checkin: $('.rz-calendar-ts', this.$).val(),
            checkout: $('.rz-calendar-ts-end', this.$).val(),
        }

        let $guests = $(`input[name='guests']`, this.$)
        if( $guests.length ) {
            data['guests'] = parseInt( $guests.val() )
        }

        let child = $(`input[name='guest_children']`, this.$)
        if( $child.length ) {
            data['children'] = parseInt( $child.val() )
        }

        let addons = $(`[data-id='addons'] input`).map(( index, e ) => {
            let $e = $(e)
            if( $e.is(':checked') ) {
                let val = $e.val()
                if( val.length ) {
                    return val;
                }
            }

        }).get()

        if( addons ) {
            data['addons'] = addons
        }

        $.ajax({
			type: 'post',
			dataType: 'json',
			url: window.rz_vars.admin_ajax,
			data: data,
            beforeSend: () => {

                $('.rz-action-success', this.$).removeClass('rz-block')

                $('.rz-error-holder', this.$).remove()
                this.ajaxing()

            },
            complete: () => {},
			success: ( response ) => {

                // success
                if( response.success ) {
                    if( response.redirect_url ) {
                        window.location.href = response.redirect_url
                    }else if( response.message ) {

                        this.$.removeClass('rz-ajaxing')

                        // display success message
                        $('.rz-action-success', this.$).addClass('rz-block')
                            .html( response.message )

                        // days selected
                        let $days = $('.rz--from-day, .rz--in-between', this.$)

                        // remove calendar selection
                        $('.rz-calendar-clear', this.$).trigger('click')

                        // disable the dates
                        $days.addClass('rz--temp-disabled')

                    }
                }
                // error
                else{

                    this.$.removeClass('rz-ajaxing')

                    // display error
                    $('.rz-action-footer', this.$).append()
                        .append('<p class="rz-error-holder"><span class="rz-error">' + response.message + '</span></p>')

                }

			}
		})
    }
}

/*
 * hourly booking
 *
 */
export class booking_hourly extends action {

    init() {

        this.xhr = null

        $('.rz-calendar-picker', this.$).on('rz-calendar:change', () => this.booking_changed())
        $(`[name='guests'], [data-id='addons']`, this.$).on('input', () => this.booking_changed())
        $(`select`, this.$).on('change', () => this.booking_changed())

        $('.rz-add-booking-hour', this.$).on('click', () => this.add())

	}

    booking_changed() {
        this.calculate_price()
    }

    calculate_price() {

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
                action: 'rz_action_booking_hour_pricing',
                security: $('#security-action', this.$).val(),
                listing_id: window.rz_vars.post_id,
                guests: $(`.rz-guests [name='guests']`, this.$).val(),
                children: $(`[name='guest_children']`, $action).val(),
                adults: $(`.rz-guests [name='guests']`, $action).val()-$(`[name='guest_children']`, $action).val(),
                addons: addons,
                date: $(`[data-id='booking'] .rz-calendar-ts`, this.$).val(),
                start: $(`[name='booking_time']`, this.$).val(),
                end: $(`[name='booking_time_end']`, this.$).val(),
            },
			beforeSend: () => {
                this.ajaxing()
                $('.rz-action-pricing', this.$).html('')
				$('.rz-error-holder', this.$).remove()
			},
			complete: () => {
                this.$.removeClass('rz-ajaxing')
            },
			success: ( response ) => {

                this.xhr = null

				// success
				if( response.success ) {
                    $('.rz-action-pricing', this.$).html( response.html )
				}

			}
        })

    }

    add() {

        if( this.$.hasClass('rz-ajaxing') ) {
            return;
        }

        let data = {
            action: 'rz_add_booking_hour',
            security: $('#security-action').val(),
            listing_id: $('[name="listing_id"]').val(),
            date: $(`[data-id='booking'] .rz-calendar-ts`).val(),
            start: $(`[name='booking_time']`).val(),
            end: $(`[name='booking_time_end']`).val(),
        }

        let $guests = $(`input[name='guests']`, this.$)
        if( $guests.length ) {
            data['guests'] = parseInt( $guests.val() )
        }
        
        let $child = $(`input[name='guest_children']`, this.$)
        if( $child.length ) {
            data['children'] = parseInt( $child.val() )
        }

        let addons = $(`[data-id='addons'] input`).map(( index, e ) => {
            let $e = $(e)
            if( $e.is(':checked') ) {
                let val = $e.val()
                if( val.length ) {
                    return val;
                }
            }

        }).get()

        if( addons ) {
            data['addons'] = addons
        }

        $.ajax({
			type: 'post',
			dataType: 'json',
			url: window.rz_vars.admin_ajax,
			data: data,
            beforeSend: () => {

                $('.rz-action-success', this.$).removeClass('rz-block')

                $('.rz-error-holder', this.$).remove()
                this.ajaxing()

            },
            complete: () => {

                // ..

            },
			success: ( response ) => {

                // success
                if( response.success ) {
                    if( response.redirect_url ) {
                        window.location.href = response.redirect_url
                    }else if( response.message ) {

                        this.$.removeClass('rz-ajaxing')

                        // display success message
                        $('.rz-action-success', this.$).addClass('rz-block')
                            .html( response.message )

                        // days selected
                        let $days = $('.rz--from-day, .rz--in-between', this.$)

                        // remove calendar selection
                        $('.rz-calendar-clear', this.$).trigger('click')

                        // remove select value
                        $('option:selected', this.$).prop('selected', false)

                        // disable the dates
                        $days.addClass('rz--temp-disabled')

                    }
                }
                // error
                else{

                    this.$.removeClass('rz-ajaxing')

                    // display error
                    $('.rz-action-footer', this.$).append()
                        .append('<p class="rz-error-holder"><span class="rz-error">' + response.message + '</span></p>')

                }

			}
		})
    }
}

/*
 * appointments
 *
 */
export class booking_appointments extends action {

    init() {

        this.xhr = null

        $('.rz-calendar-booking', this.$).on('rz-calendar:satisfied', () => this.changed())
        $(`[name='guests'], [name='children'], [data-id='addons']`, this.$).on('input', () => this.changed())

        $(document).on('click', `[data-action='action-add-appointment']`, e => this.add(e))

	}

    changed() {

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
                action: 'rz_action_get_appointments',
                listing_id: window.rz_vars.post_id,
                guests: $(`.rz-guests [name='guests']`, this.$).val(),
                children: $(`[name='guest_children']`, this.$).val(),
                adults: $(`.rz-guests [name='guests']`, this.$).val()-$(`[name='guest_children']`, this.$).val(),
                checkin: $('.rz-calendar-ts', this.$).val(),
                addons: addons,
            },
			beforeSend: () => {
                // $('.rz-appointment-table', this.$).html('')
				this.$.addClass('rz-ajaxing')
                $('.rz-action-success, .rz-action-error', this.$).removeClass('rz-block')
			},
			complete: () => {
                this.$.removeClass('rz-ajaxing')
            },
			success: ( response ) => {

                this.xhr = null

				// success
				if( response.success ) {
                    $('.rz-appointment-table', this.$).html( response.html )
				}

			}
        })

    }

    add(e) {

        let $e = $(e.currentTarget)
        let id = $e.attr('data-id')
        let checkin = $e.attr('data-checkin')

        // close the modal, if any
        window.Routiz.modal.close()

        // console.log(1);
        //
        // return;

        // if( this.$.hasClass('rz-ajaxing') ) {
        //     return;
        // }

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

        let data = {
            action: 'rz_add_appointment',
            security: $('#security-action', this.$).val(),
            listing_id: window.rz_vars.post_id,
            guests: $(`.rz-guests [name='guests']`, this.$).val(),
            children: $(`[name='guest_children']`, this.$).val(),
            adults: $(`.rz-guests [name='guests']`, this.$).val()-$(`[name='guest_children']`, this.$).val(),
            period_id: id,
            checkin: checkin,
            addons: addons,
        }

        $.ajax({
			type: 'post',
			dataType: 'json',
			url: window.rz_vars.admin_ajax,
			data: data,
            beforeSend: () => {

                $('.rz-action-success, .rz-action-error', this.$).removeClass('rz-block')
                this.ajaxing()

            },
            complete: () => {



            },
			success: ( response ) => {

                // success
                if( response.success ) {

                    if( response.redirect_url ) {

                        window.location.href = response.redirect_url

                    }else if( response.message ) {

                        this.$.removeClass('rz-ajaxing')

                        // display success message
                        $('.rz-action-success', this.$).addClass('rz-block')
                            .html( response.message )



                        // // days selected
                        // let $days = $('.rz--from-day, .rz--in-between', this.$)
                        //
                        // // remove calendar selection
                        // $('.rz-calendar-clear', this.$).trigger('click')
                        //
                        // // disable the dates
                        // $days.addClass('rz--temp-disabled')

                    }

                }
                // error
                else{

                    this.$.removeClass('rz-ajaxing')

                    // $('.rz-action-footer', this.$).append()
                    //     .append('<p class="rz-error-holder"><span class="rz-error">' + response.message + '</span></p>')

                    // display error
                    $('.rz-action-error', this.$).addClass('rz-block')
                        .html( response.message )

                }

			}
		})
    }
}

/*
 * location
 *
 */
export class location extends action {

    init() {

        this.$map = $('.rz-action-map')

        if( ! this.$map.length ) {
            return;
        }

        if( window.rz_vars.sdk.map_provider == 'google' ) {

            this.location = {
                lat: parseFloat( this.$map.attr('data-lat') ),
                lng: parseFloat( this.$map.attr('data-lng') )
            }

            this.map = new google.maps.Map( this.$map.get(0), {
                zoom: 16,
                center: this.location,
                mapTypeControl: false,
                fullscreenControl: false,
                streetViewControl: false,
                scrollwheel: false,
                zoomControl: false,
                clickableIcons: false,
                // disableDoubleClickZoom: true,
                styles: map_styles()
            })

            const Marker = require('../explore/map/providers/google/marker').default

            let marker = new Marker({
                id: 1,
                position: new google.maps.LatLng( this.location.lat, this.location.lng ),
                content: $('.rz--marker').html(),
                map: this.map
            })

            $(`[data-action='explore-map-zoom-in']`, this.$).on('click', () => {
                var currentZoomLevel = this.map.getZoom()
                if( currentZoomLevel !== 21 ) {
                    this.map.setZoom( currentZoomLevel + 1 )
                }
            })

            $(`[data-action='explore-map-zoom-out']`, this.$).on('click', () => {
                var currentZoomLevel = this.map.getZoom()
                if( currentZoomLevel !== 0 ) {
                    this.map.setZoom( currentZoomLevel - 1 )
                }
            })

        }else if( window.rz_vars.sdk.map_provider == 'mapbox' ) {

            // init map
            this.map = new mapboxgl.Map({
                container: this.$map.get(0),
                style: window.rz_vars.sdk.mapbox_style_url ? window.rz_vars.sdk.mapbox_style_url : 'mapbox://styles/mapbox/streets-v11',
                center: [
                    parseFloat( this.$map.attr('data-lng') ),
                    parseFloat( this.$map.attr('data-lat') ),
                ],
                zoom: 14,
                scrollZoom: false,
            })

            let marker_element = $('.rz--marker', this.$)

            const marker = new mapboxgl.Marker( marker_element.get(0) )
                .setLngLat([
                    parseFloat( this.$map.attr('data-lng') ),
                    parseFloat( this.$map.attr('data-lat') ),
                ])
                .addTo( this.map )

            this.map.on('load', () => {

                marker_element.removeClass('rz-none')

                $(`[data-action='explore-map-zoom-in']`, this.$).on('click', () => {
                    var currentZoomLevel = this.map.getZoom()
                    if( currentZoomLevel !== 21 ) {
                        this.map.zoomTo( currentZoomLevel + 1 )
                    }
                })

                $(`[data-action='explore-map-zoom-out']`, this.$).on('click', () => {
                    var currentZoomLevel = this.map.getZoom()
                    if( currentZoomLevel !== 0 ) {
                        this.map.zoomTo( currentZoomLevel - 1 )
                    }
                })

            })

        }

	}

}

/*
 * open hours
 *
 */
export class open_hours extends action {

    init() {

        $('.rz--toggle', this.$).on('click', () => {

            let $list = $('.rz--list', this.$)

            this.$.find('.rz-open-hours').toggleClass('rz--expand')

            if( this.$.find('.rz-open-hours').hasClass('rz--expand') ) {

                TweenLite.fromTo(
                    $list,
                    .3,
                    { height: 0 },
                    { height: $list.find('> ul').outerHeight(), ease: Power3.easeOut, onComplete: () => {
                        $list.css( 'height', 'auto' )
                    }}
                )

            }else{

                TweenLite.fromTo(
                    $list,
                    .3,
                    { height: 'auto', ease: Power3.easeOut },
                    { height: 0, ease: Power3.easeOut }
                )

            }

        })

	}

}

/*
 * purchase
 *
 */
export class purchase extends action {

    init() {

        this.xhr = null

        $(`[data-id='addons']`).on('input', () => this.changed())
        $(document).on('click', `[data-action='purchase']`, () => this.purchase())

        this.changed()

	}

    changed() {

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
                action: 'rz_action_purchase_pricing',
                security: $('#security-action', this.$).val(),
                listing_id: window.rz_vars.post_id,
                addons: addons,
            },
			beforeSend: () => {
                this.ajaxing()
                $('.rz-action-pricing', this.$).html('')
				$('.rz-error-holder', this.$).remove()
			},
			complete: () => {
                this.$.removeClass('rz-ajaxing')
            },
			success: ( response ) => {

                this.xhr = null

				// success
				if( response.success ) {

                    $('.rz-action-pricing', this.$).html( response.html )

                    // if( parseFloat( response.pricing.processing ) <= 0 ) {
                    //     $(`[data-action='purchase']`).removeAttr('data-action')
                    //         .addClass('rz-disabled')
                    // }

				}

			}
        })

    }

    purchase() {

        if( this.$.hasClass('rz-ajaxing') ) {
            return;
        }

        let data = {
            action: 'rz_purchase',
            security: $('#security-action', this.$).val(),
            listing_id: window.rz_vars.post_id,
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

        if( addons ) {
            data['addons'] = addons
        }

        $.ajax({
			type: 'post',
			dataType: 'json',
			url: window.rz_vars.admin_ajax,
			data: data,
            beforeSend: () => {

                $('.rz-action-success', this.$).removeClass('rz-block')

                $('.rz-error-holder', this.$).remove()
                this.ajaxing()

            },
            complete: () => {},
			success: ( response ) => {

                // success
                if( response.success ) {
                    if( response.redirect_url ) {
                        window.location.href = response.redirect_url
                    }else if( response.message ) {

                        this.$.removeClass('rz-ajaxing')

                        // display success message
                        $('.rz-action-success', this.$).addClass('rz-block')
                            .html( response.message )

                        // days selected
                        let $days = $('.rz--from-day, .rz--in-between', this.$)

                        // remove calendar selection
                        $('.rz-calendar-clear', this.$).trigger('click')

                        // disable the dates
                        $days.addClass('rz--temp-disabled')

                    }
                }
                // error
                else{

                    this.$.removeClass('rz-ajaxing')

                    // display error
                    $('.rz-action-footer', this.$).append()
                        .append('<p class="rz-error-holder"><span class="rz-error">' + response.message + '</span></p>')

                }

			}
		})

    }

}
