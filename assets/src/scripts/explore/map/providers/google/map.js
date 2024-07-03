'use strict'

import debug from '../../../../utils/debug'
import url from '../../../../utils/url'
import map_styles from './styles/styles'

export default class Map {

    constructor() {

        if( typeof window.google == 'undefined' ) {
            return;
        }

        if( $('#rz-explore-map').length ) {

            this.$ = $('#rz-explore-map')
            this.markers = []

            this.init_map_global()
            this.init_map()
            this.init_zoom_controls()
            this.init_infobox()
            this.init_markers()
            this.init_bounds()
            this.markers_box_hover()

        }

	}

    init_infobox() {

        if( typeof window.google == 'undefined' ) {
            return;
        }

        const Infobox = require('./infobox/infobox.js')

        this.infobox = new Infobox.default({
            map: this.map
        })

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

        const params = {
            zoom: 14,
            center: this.default,
            mapTypeControl: false,
            fullscreenControl: false,
            streetViewControl: false,
            scrollwheel: false,
            zoomControl: false,
            clickableIcons: false,
            disableDoubleClickZoom: true,
            styles: map_styles()
        }

        this.map = new google.maps.Map( this.$.get(0), params )
        this.last_geo = url.get('geo')

        // close infobox if click on map ( without dragging )
        let click_loc = false

        $('.rz-explore-map')
            .on('mousedown', (e) => {
                click_loc = this.map.getCenter()
            })
            .on('mouseup', (e) => {
                if( click_loc == this.map.getCenter() ) {

                    var target = $( e.target )
                    if(
                        ! target.closest('.rz-infobox-outer').length &&
                        ! target.closest('.rz-marker').length &&
                        ! target.closest('.gm-bundled-control').length
                    ) {
                        this.infobox.close()
                    }

                }
            })

        this.map.addListener('idle', () => {
            $(document).trigger('routiz/explore/map/idle')
        });

    }

    set_map_style() {

        this.map.setOptions({
            styles: map_styles()
        })

    }

    init_zoom_controls() {

        $(`[data-action='explore-map-zoom-in']`).on('click', () => {
            var currentZoomLevel = this.map.getZoom()
            if( currentZoomLevel !== 21 ) {
                this.map.setZoom( currentZoomLevel + 1 )
            }
        })

        $(`[data-action='explore-map-zoom-out']`).on('click', () => {
            var currentZoomLevel = this.map.getZoom()
            if( currentZoomLevel !== 0 ) {
                this.map.setZoom( currentZoomLevel - 1 )
            }
        })

    }

    init_markers() {

        for ( var i in this.markers ) { // remove any markers
            this.markers[i].setMap( null )
        }
        this.markers = []
        this.bounds = new google.maps.LatLngBounds()

        const Marker = require('./marker.js')

        $('.rz-dynamic-markers > div').each(( index, element ) => {

            let $e = $( element )

            let template = $e.html()
            let id = parseInt( $e.attr('data-id') )
            let lat = parseFloat( $e.attr('data-lat') )
            let lng = parseFloat( $e.attr('data-lng') )

            let position = new google.maps.LatLng( lat, lng )

            if( ! isNaN( lat ) && ! isNaN( lng ) ) {

                var marker = new Marker.default({
                    id: id,
                    position: position,
                    content: template,
                    map: this.map
                })

                this.markers.push( marker )
                this.bounds.extend( position )

    			google.maps.event.addListener( marker, 'click', () => { // infobox

    				if ( this.infobox.is_open && this.infobox.id == id ) { // already open, bail
    					return
    				}

    				this.infobox.set_id( marker.id );
    				this.infobox.set_content( $('.rz-dynamic-infoboxes li[data-id="' + marker.id + '"]').html() );
    				this.infobox.open( marker );

    			})

            }

        })

        let geo = url.get('geo')
        let geo_n = url.get('geo_n')
        let geo_e = url.get('geo_e')
        let geo_s = url.get('geo_s')
        let geo_w = url.get('geo_w')

        // geo
        if( this.has_geo_query() ) {

            let geo_bounds = new google.maps.LatLngBounds(
                new google.maps.LatLng( geo_s, geo_w ),
                new google.maps.LatLng( geo_n, geo_e )
            )

            // no markers
            if( this.bounds.isEmpty() ) {

                if(
                    ! isNaN( geo_bounds.getCenter().lat() ) &&
                    ! isNaN( geo_bounds.getCenter().lng() ) &&
                    typeof this.map !== 'undefined'
                ) {

                    this.map.setCenter( geo_bounds.getCenter() )

                    this.reset_events()
                    this.map.fitBounds( geo_bounds )
                    this.map.setZoom( this.map.getZoom() + 1 )

                    google.maps.event.addListenerOnce( this.map, 'idle', () => {
                        this.set_events()
                    })

                }

                this.last_geo = geo

            }
            // has markers
            else{

                if( this.last_geo !== geo ) {

                    this.reset_events()
                    this.map.fitBounds( geo_bounds )
                    this.last_geo = geo

                    google.maps.event.addListenerOnce( this.map, 'idle', () => {
                        this.set_events()
                    })

                }

            }

        }
        // no geo
        else{
            if( ! this.bounds.isEmpty() ) {

                this.map.setOptions({ maxZoom: 16 }) // fix for single marker zoom

                setTimeout(() => {
                    this.map.setOptions({ maxZoom: 20 })
                }, 100 )

                this.map.fitBounds( this.bounds )

            }
        }

    }

    has_geo_query() {

        let geo = url.get('geo')
        let geo_n = url.get('geo_n')
        let geo_e = url.get('geo_e')
        let geo_s = url.get('geo_s')
        let geo_w = url.get('geo_w')

        return geo && geo_n && geo_e && geo_s && geo_w;

    }

    set_events() {

        this.reset_events()

        this.event_dragend = this.map.addListener('dragend', () => {

            if( this.has_geo_query() ) {
                $(document).trigger('routiz/explore/map/geo')
            }
        })

        this.event_zoom_changed = this.map.addListener('zoom_changed', () => {

            if( this.has_geo_query() ) {
                $(document).trigger('routiz/explore/map/geo')
            }
        })

    }

    reset_events() {

        if( typeof this.event_dragend !== 'undefined' ) {
            google.maps.event.removeListener( this.event_dragend )
        }

        if( typeof this.event_zoom_changed !== 'undefined' ) {
            google.maps.event.removeListener( this.event_zoom_changed )
        }

    }

    init_bounds() {

        /*
         * geo
         *
         */
        let geo = url.get('geo')
        let geo_n = url.get('geo_n')
        let geo_e = url.get('geo_e')
        let geo_s = url.get('geo_s')
        let geo_w = url.get('geo_w')

        // set center on geo location
        if( this.has_geo_query() ) {

            let geo_bounds = new google.maps.LatLngBounds(
                new google.maps.LatLng( geo_s, geo_w ),
                new google.maps.LatLng( geo_n, geo_e )
            )

            this.set_events()

            this.map.fitBounds( geo_bounds )

        }
        // set bounds on markers
        else if( ! this.bounds.isEmpty() ) {

            this.map.fitBounds( this.bounds )

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

}
