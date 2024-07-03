'use strict'

import debug from '../../../../utils/debug'
import url from '../../../../utils/url'
import Box from './infobox/box'
// import ToggleControl from './toggle-control'

export default class Map {

    constructor() {

        this.init_get_geolocation()

        if( typeof window.mapboxgl == 'undefined' ) {
            return;
        }

        if( $('#rz-explore-map').length ) {

            this.$ = $('#rz-explore-map')

            this.init_map_global()
            this.init_map()
            this.init_zoom_controls()
            this.init_infobox()
            this.init_markers()
            this.markers_box_hover()

        }

	}

    init_map_global() {

        const Barcelona = {
            lat: 41.38506389999999,
            lng: 2.1734034999999494
        }

        const global_lat = parseFloat( window.rz_vars.map.lat )
        const global_lng = parseFloat( window.rz_vars.map.lng )

        this.default = ( global_lat && global_lng ) ? { lat: global_lat, lng: global_lng } : Barcelona

    }

    init_map() {

        debug.map.log('Init')

        this.$.addClass('rz-map-ready')

        this.map = new mapboxgl.Map({
            container: 'rz-explore-map',
            style: window.rz_vars.sdk.mapbox_style_url ? window.rz_vars.sdk.mapbox_style_url : 'mapbox://styles/mapbox/streets-v11',
            center: this.default,
            zoom: 14,
            scrollZoom: false,
        })

        this.map.on('load', e => {
            this.map.on('zoomend', e => {
                this.map_idle(e)
            })
            this.map.on('dragend', e => {
                this.map_idle(e)
            })
        })

    }

    init_zoom_controls() {

        $(`[data-action='explore-map-zoom-in']`).on('click', () => {
            var currentZoomLevel = this.map.getZoom()
            if( currentZoomLevel !== 21 ) {
                this.map.zoomTo( currentZoomLevel + 1 )
            }
        })

        $(`[data-action='explore-map-zoom-out']`).on('click', () => {
            var currentZoomLevel = this.map.getZoom()
            if( currentZoomLevel !== 0 ) {
                this.map.zoomTo( currentZoomLevel - 1 )
            }
        })

    }

    init_infobox() {

    }

    init_markers() {

        for ( var i in this.markers ) { // remove any markers
            this.markers[i].remove()
        }

        this.markers = []
        this.infobox_box = null
        this.bounds = new mapboxgl.LngLatBounds()

        $('.rz-dynamic-markers > div').each(( index, element ) => {

            let $e = $(element)

            let template = $e.html()
            let id = parseInt( $e.attr('data-id') )
            let lat = parseFloat( $e.attr('data-lat') )
            let lng = parseFloat( $e.attr('data-lng') )

            if( ! isNaN( lat ) && ! isNaN( lng ) ) {

                const popup = new mapboxgl.Popup({
                    anchor: 'bottom',
                    closeButton: false,
                })
                    .setHTML( '<div class="rz-infobox-outer">' + $('.rz-dynamic-infoboxes li[data-id="' + id + '"]').html() + '</div>' )
                    .addTo( this.map )

                const marker = new mapboxgl.Marker( $e.get(0) )
                    .setLngLat({
                        lat: lat,
                        lng: lng
                    })
                    .setPopup( popup )
                .addTo( this.map )

                popup.on('open', () => {
                    this.infobox_box = new Box( popup.getElement() )
                });

                popup.on('close', () => {
                    if( this.infobox_box ) {
                        this.infobox_box.kill()
                        this.infobox_box = null
                    }
                });

                this.markers.push( marker )
                this.bounds.extend([
                    lng, lat
                ])

            }

        })

        let geo = url.get('geo')
        let geo_n = url.get('geo_n')
        let geo_e = url.get('geo_e')
        let geo_s = url.get('geo_s')
        let geo_w = url.get('geo_w')

        // has geo
        if( this.has_geo_query() ) {

            const geoViewport = require('@mapbox/geo-viewport')

            let size = [1002,1002]
            let map_container = $(this.map.getContainer())
            if( map_container.length ) {
                size = [map_container.outerWidth(), map_container.outerHeight()]
            }

            let viewport = geoViewport.viewport([
                geo_w, // w
                geo_s, // s
                geo_e, // e
                geo_n, // n
            ], size)

            // no markers
            if( this.bounds.isEmpty() ) {

                if( typeof this.map !== 'undefined' ) {

                    this.map.setCenter( viewport.center )
                    this.map.setZoom( viewport.zoom )

                }

                this.last_geo = geo

            }
            // has markers
            else{

                if( this.last_geo !== geo ) {

                    this.last_geo = geo

                    if( ! isNaN( viewport.zoom ) ) {
                        this.map.setCenter( viewport.center )
                        this.map.setZoom( viewport.zoom - 1 )
                    }

                }

            }

            $('.rz-geo-sync').removeClass('rz--ask')

        }
        // no geo
        else{
            if( ! this.bounds.isEmpty() ) {

                this.map.fitBounds( this.bounds, {
                    padding: 100,
                    duration: 0
                })

            }
        }

    }

    has_geo_query() {

        let geo = url.get('geo')
        let geo_n = url.get('geo_n')
        let geo_e = url.get('geo_e')
        let geo_s = url.get('geo_s')
        let geo_w = url.get('geo_w')

        if(
            ! isNaN( parseFloat( geo_n ) ) &&
            ! isNaN( parseFloat( geo_e ) ) &&
            ! isNaN( parseFloat( geo_s ) ) &&
            ! isNaN( parseFloat( geo_w ) )
        ) {
            return true
        }

        return

    }

    map_idle() {

        if( this.has_geo_query() ) {
            $(document).trigger('routiz/explore/map/geo')
        }

    }

    markers_box_hover() {

        $(document).on('mouseenter mouseleave', '.rz-listing', e => {
            $('.rz-marker[data-id="' + e.currentTarget.getAttribute('data-id') + '"]')[ e.type == 'mouseenter' ? 'addClass' : 'removeClass' ]('rz-marker-over')
        })

    }

    hide() {
        if( this.$ ) {
            this.$.hide()
        }
    }

    show() {
        if( this.$ ) {
            this.$.show()
        }
    }

    init_get_geolocation() {
        const $e = $('.rz-geo-get')
        if( $e.length ) {
            $(document).on('click', '.rz-geo-get', e => {
                if($(e.currentTarget).hasClass('_done')) {
                    return;
                }
                if( navigator.geolocation ) {
                    navigator.geolocation.getCurrentPosition( this.show_position )
                }else{
                    alert(`Geolocation is not supported by this browser.`)
                }
            })
        }
    }

    show_position( position ) {

        let xhr_geocode = null

        if( xhr_geocode !== null ) {
            clearTimeout( xhr_geocode )
        }

        xhr_geocode = setTimeout(() => {
            $.ajax({
                type: 'GET',
                url: `https://api.mapbox.com/geocoding/v5/mapbox.places/${position.coords.longitude},${position.coords.latitude}.json?access_token=${window.rz_vars.sdk.mapbox}`,
                success: response => {
                    if( typeof response.features !== 'undefined' && response.features.length ) {
                        for( const element of response.features ) {

                            // address
                            $(`.rz-geo-field input[type='text']`).val( element.place_name )

                            // coordinates
                            const geoViewport = require('@mapbox/geo-viewport')

                            let size = [1001,1001]
                            let zoom = 14

                            if( typeof window.Routiz.explore.map.map !== 'undefined' ) {
                                let map_container = $(window.Routiz.explore.map.map.getContainer())
                                size = [map_container.outerWidth(), map_container.outerHeight()]
                            }
                            console.log(111);


                            // if( typeof window.Routiz.explore.map.map !== 'undefined' && map_container.length ) {
                            //     size = [map_container.outerWidth(), map_container.outerHeight()]
                            //     // zoom = window.Routiz.explore.map.map.getZoom()
                            // }

                            let results = geoViewport.bounds(
                                [ position.coords.longitude, position.coords.latitude ],
                                14,
                                size
                            )

                            $(`.rz-geo input[name='rz_geo_n']`).val( results[3] )
                            $(`.rz-geo input[name='rz_geo_e']`).val( results[2] )
                            $(`.rz-geo input[name='rz_geo_s']`).val( results[1] )
                            $(`.rz-geo input[name='rz_geo_w']`).val( results[0] )

                            break;
                        }
                    }
                }
            })
        }, 300 )

        $('.rz-geo-get').addClass('_done')

    }
}
