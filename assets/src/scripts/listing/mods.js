'use strict'

import debug from '../utils/debug'
import url from '../utils/url'
import serialize from '../form/serialize'
import map_styles from '../explore/map/providers/google/styles/styles'

window.$ = window.jQuery

class mod {

    constructor( module ) {

        this.$ = module
        this.init()

        module.addClass('rz-ready')

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
 * location
 *
 */
export class location extends mod {

    init() {

        let $map = $('.rz-map', this.$)

        if( ! $map.length ) {
            return;
        }

        if( window.rz_vars.sdk.map_provider == 'google' ) {

            let marker_html = $('.rz-mod-listing-marker-content', this.$).html()

            let marker_position = {
                lat: parseFloat( $map.data('lat') ),
                lng: parseFloat( $map.data('lng') )
            }

            let map = new google.maps.Map( $map.get(0), {
                zoom: 15,
                center: marker_position,
                mapTypeControl: false,
                fullscreenControl: false,
                streetViewControl: false,
                scrollwheel: false,
                zoomControl: false,
                clickableIcons: false,
                styles: map_styles()
            })

            if( ! this.$.children().hasClass('rz--has-address') ) {
                new google.maps.Circle({
                    strokeColor: '#111',
                    strokeOpacity: 0,
                    strokeWeight: 0,
                    fillColor: '#111',
                    fillOpacity: 0.125,
                    map: map,
                    center: marker_position,
                    radius: 300
                })
            }

            const Marker = require('../explore/map/providers/google/marker')

            var marker = new Marker.default({
                id: null,
                position: new google.maps.LatLng( marker_position ),
                content: marker_html,
                map: map,
            })

            $(`[data-action='explore-map-zoom-in']`, this.$).on('click', () => {
                var currentZoomLevel = map.getZoom()
                if( currentZoomLevel !== 21 ) {
                    map.setZoom( currentZoomLevel + 1 )
                }
            })

            $(`[data-action='explore-map-zoom-out']`, this.$).on('click', () => {
                var currentZoomLevel = map.getZoom()
                if( currentZoomLevel !== 0 ) {
                    map.setZoom( currentZoomLevel - 1 )
                }
            })

        }else if( window.rz_vars.sdk.map_provider == 'mapbox' ) {

            // init map
            let map = new mapboxgl.Map({
                container: $map.get(0),
                style: window.rz_vars.sdk.mapbox_style_url ? window.rz_vars.sdk.mapbox_style_url : 'mapbox://styles/mapbox/streets-v11',
                center: [
                    $map.data('lng'),
                    $map.data('lat'),
                ],
                zoom: 14,
                scrollZoom: false,
            })

            let marker_element = $('.rz-mod-listing-marker-content', this.$)

            const marker = new mapboxgl.Marker( marker_element.get(0) )
                .setLngLat([
                    $map.data('lng'),
                    $map.data('lat'),
                ])
                .addTo( map )

                map.on('load', () => {

                    // circle
                    if( ! this.$.children().hasClass('rz--has-address') ) {
                        let createGeoJSONCircle = function( center, radiusInKm, points ) {
                            if(!points) points = 64;

                            let coords = {
                                latitude: center[1],
                                longitude: center[0]
                            };

                            let km = radiusInKm;

                            let ret = [];
                            let distanceX = km/(111.320*Math.cos(coords.latitude*Math.PI/180));
                            let distanceY = km/110.574;

                            let theta, x, y;
                            for(let i=0; i<points; i++) {
                                theta = (i/points)*(2*Math.PI);
                                x = distanceX*Math.cos(theta);
                                y = distanceY*Math.sin(theta);

                                ret.push([coords.longitude+x, coords.latitude+y]);
                            }
                            ret.push(ret[0]);

                            return {
                                "type": "geojson",
                                "data": {
                                    "type": "FeatureCollection",
                                    "features": [{
                                        "type": "Feature",
                                        "geometry": {
                                            "type": "Polygon",
                                            "coordinates": [ret]
                                        }
                                    }]
                                }
                            };
                        };

                    map.addSource("polygon", createGeoJSONCircle([
                        $map.data('lng'),
                        $map.data('lat'),
                    ], 0.25));

                    map.addLayer({
                        "id": "polygon",
                        "type": "fill",
                        "source": "polygon",
                        "layout": {},
                        "paint": {
                            "fill-color": "#000",
                            "fill-opacity": 0.125
                        }
                    });
                }
                // end circle

                marker_element.removeClass('rz-none')

                $(`[data-action='explore-map-zoom-in']`, this.$).on('click', () => {
                    var currentZoomLevel = map.getZoom()
                    if( currentZoomLevel !== 21 ) {
                        map.zoomTo( currentZoomLevel + 1 )
                    }
                })

                $(`[data-action='explore-map-zoom-out']`, this.$).on('click', () => {
                    var currentZoomLevel = map.getZoom()
                    if( currentZoomLevel !== 0 ) {
                        map.zoomTo( currentZoomLevel - 1 )
                    }
                })

            })

        }

    }

}

/*
 * menu
 *
 */
export class menu extends mod {

    init() {

        $('.rz--tabs li', this.$).on('click', e => {

            let $e = $(e.currentTarget)
            let index = $('.rz--tabs li', this.$).index( $e )

			$('.rz--tabs li', this.$).removeClass('rz-active')
            $e.addClass('rz-active')

            $('.rz--section', this.$).removeClass('rz-active')
                .eq( index ).addClass('rz-active')

		});

    }

}
