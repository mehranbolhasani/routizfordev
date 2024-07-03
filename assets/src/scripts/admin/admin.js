'use strict'

import debug from '../utils/debug'
import Admin_Modal from './modal/modal'
import Queue from '../utils/queue'

window.$ = window.jQuery
window.Routiz = window.Routiz || {};

class Admin {

    constructor() {

        debug.admin.log('Contructor')

		$(document).ready(() => this.ready())

	}

	ready() {

        debug.admin.log('Dom ready')

        this.init()

	}

    init() {

		this.bind()
		this.importer()
		this.icon_set()

    }

	bind() {

		$(document).on('click', 'a[href="#"]', (e) => {
			e.preventDefault()
		})

        $(`[data-action='action-confirmation']`).on('click', e => {

            if( ! confirm( $(e.currentTarget).attr('data-confirmation-msg') ) ) {
                e.preventDefault()
            }
        })

	}

    importer() {

        let $import = $('.rz-import')

        $(`[data-action='import-demos']`).on('click', e => {

            if( $import.hasClass('rz-ajaxing') || $('body').hasClass('rz-demo-importing') ) {
                return;
            }

            $.ajax({
                type: 'post',
                dataType: 'json',
                url: window.rz_vars.admin_ajax,
                data: {
                    action: 'rz_demo_import',
                    perform: 'prepare',
    				demo: $(`input[name='demo']:checked`).val(),
                },
    			beforeSend: () => {
                    $import.addClass('rz-ajaxing')
    				$('.rz--error', $import).remove()
                    $('.rz-importer-progress').addClass('rz-ajaxing')
                    $('.rz-importer-progress .rz--bar').css( 'width', 0 )
    			},
    			complete: () => {
                    // $import.removeClass('rz-ajaxing')

                },
    			success: ( response ) => {

    				// success
    				if( response.success ) {

                        let call_index = 1

        				$('body').css('overflow-y', 'hidden')
                            .addClass('rz-demo-importing')

                        let queue = new Queue({
                            timeout: 3000,
                            ajaxSettings: {
                                type: 'post',
                                dataType: 'json',
                                url: window.rz_vars.admin_ajax,
                            },
                            onStop: () => {

                                $('body').css('overflow-y', 'scroll')
                                    .removeClass('rz-demo-importing')

                                window.location.reload()

                            }
                        })

                        let i = 0
                        for( const call of response.queue ) {

                            queue.add({
                                beforeSend: () => {
                                    // ..
                                },
                                data: {
                                    action: 'rz_demo_import',
                                    perform: call.perform,
                                    demo: $(`input[name='demo']:checked`).val(),
                                    index: i++
                                },
                                beforeSend: () => {
                                    this.progress( response.queue.length, call_index++ )
                                    $('.rz-importer-progress .rz--title').html( call.message )

                                },
                                success: res => {
                                    this.progress( response.queue.length, call_index )
                                }
                            })

                        }

    				}
    				// error
    				else{

                        $('.rz-importer-progress').removeClass('rz-ajaxing')
                        $import.removeClass('rz-ajaxing')

    					$('.rz-demos ul').after(`<div class="rz--error">
                            <div class="rz--icon">
                                <i class="fas fa-frown-open"></i>
                            </div>
                            <div class="rz--content">
                                ${response.error}
                            </div>
                        </div>`)

    				}

    			}
            })

        })

    }

    progress( index, length ) {
        $('.rz-importer-progress .rz--bar').css( 'width', ( 100 / index ) * length + '%' )
    }

    icon_set() {

        let $e = $('.rz-upload-inline'),
            $label = $('.rz--label', $e),
            $file = $('.rz--file', $e)

        $('.rz-button', $e).on('click', (e) => {
			$label.click()
		})

        $file.on('change', e => {
            $(`[name='icon-package-name']`, $e).val( e.target.files[0].name )
        })

		$(`[data-action='re-upload-set']`).on('click', (e) => {
			$('.rz-reload-set').remove()
            $('.rz-upload-inline').removeClass('rz-none')
		})

	}

}

window.Routiz.admin = new Admin()
