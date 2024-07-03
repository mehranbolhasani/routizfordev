'use strict'

import debug from '../../../../utils/debug'

export default class Marker extends google.maps.OverlayView {

    constructor( params ) {

        super()

        debug.map.log('Marker init')

        this.id = params.id
        this.position = params.position
        this.html = params.content

        this.setMap( params.map )

    }

    draw() {

        if ( ! this.div ) {

            this.div = document.createElement('div')
            this.div.style.position = 'absolute'

            if ( this.html ) {
                this.div.innerHTML = this.html
            }

            google.maps.event.addDomListener( this.div, 'click', event => {
                google.maps.event.trigger( this, 'click' )
            })

            const panes = this.getPanes()
            panes.overlayImage.appendChild( this.div )

        }

        const point = this.getProjection().fromLatLngToDivPixel( this.position )

        if ( point ) {
            this.div.style.left = `${point.x}px`
            this.div.style.top = `${point.y}px`
        }

    }

    onRemove() {
        if ( this.div ) {
            this.div.remove()
        }
    }

}
