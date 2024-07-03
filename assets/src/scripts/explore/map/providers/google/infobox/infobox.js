'use strict'

import debug from '../../../../../utils/debug'
import Box from './box'

export default class Infobox extends google.maps.OverlayView {

    constructor( params ) {

        super()

        debug.map.log('Infobox: constructor')

        this.is_open = false
        this.map = params.map
        this.setMap( params.map )
        this.build_dom()

    }

    set_id( id ) {

        this.id = id

    }

    set_content( content ) {

        this.div.innerHTML = content

    }

    open( marker ) {

        this.is_open = true
        this.position = marker.position
        this.div.style.display = 'block'
        this.draw()

        this.box = new Box( this.div )

        setTimeout(() => {
            this.pan_to_view();
        }, 50 );

    }

    close() {

        this.div.style.display = 'none'
        this.is_open = false

        if( this.box ) {
            this.box.kill()
        }

    }

    build_dom() {

        if ( ! this.div ) {

            this.div = document.createElement('div')
            this.div.style.position = 'absolute'
            this.div.className = 'rz-infobox-outer'

            google.maps.event.addDomListener( this.div, 'click', event => {
                google.maps.event.trigger( this, 'click' )
            })

        }

    }

    onAdd() {

        this.build_dom()

        let panes = this.getPanes()
        if ( panes ) {
            panes.floatPane.appendChild( this.div )
        }

        google.maps.event.trigger( this, 'domready' )

    }

    draw() {

        if( this.div && this.position && this.is_open ) {

            var projection = this.getProjection()

            var pos = projection.fromLatLngToDivPixel( this.position )
            var width = this.div.offsetWidth
            var height = this.div.offsetHeight

            if ( ! width ) {
                return
            }

            let arrow_height = 40
            var top = pos.y - height - arrow_height
            var left = pos.x - width * .5

            this.div.style.top = top + 'px'
            this.div.style.left = left + 'px'

        }

    }

    pan_to_view() {

        var projection = this.getProjection()

        if ( ! projection ) {
            return
        }

        if ( ! this.div ) {
            return
        }

        let bounds_limit = 30,
            height = this.div.offsetHeight + bounds_limit,
            width = this.div.offsetWidth,
            map_div = this.map.getDiv(),
            map_width = map_div.offsetWidth,
            needs_pan = false,
            bounds = new google.maps.LatLngBounds(),
            loc = new google.maps.LatLng( this.position.lat(), this.position.lng() ),
            loc_pixels = projection.fromLatLngToContainerPixel( loc )

        if( loc_pixels.y - height < bounds_limit ) {
            needs_pan = true;
            loc_pixels.y -= height;
        }

        if( loc_pixels.x - width < bounds_limit ) {
            needs_pan = true;
            loc_pixels.x -= ( width * .5 ) - 11;
        }

        if( map_width < loc_pixels.x + ( width * .5 ) ) {
            needs_pan = true;
            loc_pixels.x += ( width * .5 ) - 11;
        }

        if( needs_pan ) {

            bounds.extend( projection.fromContainerPixelToLatLng( loc_pixels ) );
            this.map.panToBounds( bounds, bounds_limit );

        }

    }

}
