'use strict'

import debug from '../../../../../utils/debug'

export default class Box {

    constructor( div ) {

        debug.map.log('Infobox: box: init')

        this.$ = $(div)
        this.$slider = $('.rz-listing-gallery', this.$)
        this.$main = $('.rz-image', this.$)

        this.slider()

    }

    slider() {

        if( this.$slider.length ) {

            // slide
            this.$slider.off('select.flickity.slide').on('select.flickity.slide', ( e, index ) => {

                if( typeof this.flkty !== 'undefined' ) {

                    let $selected = $( this.flkty.selectedElement )
                    let image = $selected.attr('data-image')

                    if( typeof image !== 'undefined' ) {
                        $selected.removeAttr('data-image')
                        $('<img scr="' + image + '">').imagesLoaded(() => {
                            $selected.css({
                                'background-image': 'url(\'' + image + '\')',
                                'opacity': 1
                            })
                        });
                    }

                }
            })

            // ready
            this.$slider.off('ready.flickity.ready').on('ready.flickity.ready', () => {
                this.$slider.addClass('rz-ready')
                this.$main.css('visibility', 'hidden')
            });

            // slider
            setTimeout(() => {

                // init
                this.$slider.flickity({
                    contain: true,
                    prevNextButtons: false,
                    pageDots: false,
                })

                // nav
                $('.rz-slider-nav', this.$).off('click.slider-nav').on('click.slider-nav', (e) => {
                    this.$slider.flickity( $( e.currentTarget ).hasClass('rz-nav-next') ? 'next' : 'previous' )
                })

                // instance
                this.flkty = this.$slider.data('flickity')

            }, 10 )

        }

    }

    kill() {

        this.$slider.flickity('destroy')
        debug.map.log('Infobox: box: kill')

    }

}
