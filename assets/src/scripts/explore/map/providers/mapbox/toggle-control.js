'use strict'

export default class ToggleControl extends mapboxgl.GeolocateControl {
    _onSuccess( position ) {
        this.map.setCenter([position.coords.longitude, position.coords.latitude])
        this.map.setZoom(17)
    }

    onAdd( map, cs ) {
        this.map = map;
        this.container = document.createElement('div')
        this.container.className = `mapboxgl-ctrl`
        const button = this._createButton()
        return this.container
    }

    _createButton() {
        const $el = $('.rz-geo-get')
        if( $el.length ) {
            const el = $('.rz-geo-get').get(0)
            el.addEventListener('click', () => {
                this.trigger()
            })
            this._setup = true
            return el
        }
    }
}
