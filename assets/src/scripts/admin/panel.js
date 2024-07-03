'use strict'

import debug from '../utils/debug'
window.Routiz = window.Routiz || {};

class Panel {

    constructor() {

        debug.panel.log('Constructor')

        Vue.config.devtools = window.rz_vars.debug

        if( ! $('#rz-panel').length ) {
            window.Routiz.form.fields()
            return
        }

        this.components()
        this.app()
		this.gradient()
		this.development()
		this.split()
		this.webhooks()

    }

    app() {

        let _this = this

        new Vue({

            el: '#rz-panel',

            data() {
                return {
                    ready: false,

                    tab: null,
                    tabMain: null,
                    tabSub: null,
                }
            },

            created() {

                if( window.location.hash ) {
                    this.tabClick( window.location.hash.substr(1), true )
                }

            },

            mounted() {

                if( this.tab == null ) {
                    this.tab = this.$el.getAttribute('data-tab-start')
                }

                this.tabClick( this.tab, true )
                this.parse()

                this.ready = true

            },

            methods: {

                parse: function() {

                    debug.panel.log('Mounted')

                    window.Routiz.form.fields()

                },

                tabClick: function ( id, anchor ) {

                    if( id.includes('/') ) {

                        let splitted = id.split('/')
                        this.tab = splitted.join('/')
                        this.tabMain = splitted[0]
                        this.tabSub = splitted[1]

                    }else{

                        this.tab = id
                        this.tabMain = id
                        this.tabSub = null

                    }

                    if( typeof anchor == 'undefined' ) {
                        window.location.hash = id
                    }

                },

            }

        });

    }

    components() {

        Vue.component('rz-preview-listing', {

            props: {
        		props: Object,
        	},

        	data () {
        		return {

                    type: this.props.type,
        			cover: this.props.cover,
        			hide_listing_details: this.props.hide_listing_details,
        			favorite: this.props.favorite,
        			review: this.props.review,
        			title: this.props.title,
        			tagline: this.props.tagline,
        			// top_labels: this.props.top_labels,
        			bottom_labels: this.props.bottom_labels,
        			content: this.props.content,

        		}
        	},

            methods: {
                format_heading( format, text ) {
                    if( ! format ) {
                        return text
                    }
                    return format.replace( /%s/g, '<strong>' + text + '</strong>' )
                }
            }

        });

        Vue.component('rz-preview-marker', {

            props: {
        		props: Object,
        	},

        	data () {
        		return {

                    type: this.props.type,
                    icon: this.props.icon,
                    image: this.props.image,
                    image_width: this.props.image_width,
                    field: this.props.field,
                    field_format: this.props.field_format,

        		}
        	},

            methods: {
                format_heading( format, text ) {
                    return format.replace( /%s/g, '<strong>' + text + '</strong>' )
                },
                get_image_thumb() {
                    try {
                        let image = JSON.parse( this.image )
                        return image.thumb
                    } catch (e) {
                        return ''
                    }

                },
            }

        });

    }

	gradient() {

        $(document).on('mousemove', '.rz-button, .rz-bg', e => {
			let rect = e.currentTarget.getBoundingClientRect(),
				x = e.clientX - rect.left,
				y = e.clientY - rect.top

			e.currentTarget.style.setProperty('--x', `${x}px`)
			e.currentTarget.style.setProperty('--y', `${y}px`)
		})

	}

    development() {

        $(`[data-action='dev-export-options']`).on('click', e => {

            let $dev = $('.rz-development')

            if( $dev.hasClass('rz-ajaxing') ) {
                return;
            }

            $.ajax({
                type: 'post',
                dataType: 'json',
                url: window.rz_vars.admin_ajax,
                data: {
                    action: 'rz_dev_export_options',
                    perform: 'prepare',
    				demo: $(`input[name='demo']:checked`).val(),
                },
    			beforeSend: () => {
                    $dev.addClass('rz-ajaxing')
    			},
    			complete: () => {
                    $dev.removeClass('rz-ajaxing')
                },
    			success: ( response ) => {

                    $('#rz-input-options-export').removeClass('rz-none').find('textarea').val( response.output )

    			}
            })

        })

    }

    split() {

        let $splits = $('.rz-settings-split')

        if( $splits.length ) {

            $splits.each(( index, element ) => {

                let $split = $(element)

                let $li = $('.rz--sidebar li', $split)

                $li.on('click', e => {

                    let $e = $(e.currentTarget)
                    let id = $e.attr('data-for')

                    $li.removeClass('rz--active')
                    $e.addClass('rz--active')

                    $(`.rz--section`, $split).removeClass('rz--active')
                    $(`.rz--section[data-id='${id}']`, $split).addClass('rz--active')

                })

            })



        }

    }

    webhooks() {

        $(document).on('click', '[data-action="trigger-webhook"]', e => {

            let $e = $(e.currentTarget)

            if( $e.hasClass('rz-ajaxing') ) {
                return;
            }

            let webhook_id = $e.attr('data-for')
            let webhook_url = $(`[name="rz_${webhook_id}"]`).val()

            if( ! webhook_url.length ) {
                alert('Please enter a valid webhook url!')
                return;
            }

            $.ajax({
                type: 'post',
                dataType: 'json',
                url: window.rz_vars.admin_ajax,
                data: {
                    action: 'rz_trigger_webhook',
    				webhook_id: webhook_id,
    				webhook_url: webhook_url,
                },
    			beforeSend: () => {
                    $e.addClass('rz-ajaxing')
    			},
    			complete: () => {
                    $e.removeClass('rz-ajaxing')
                },
    			success: ( response ) => {

                    $('#rz-input-options-export').removeClass('rz-none').find('textarea').val( response.output )

    			}
            })

        })

    }

}

window.Routiz.panel = new Panel()
