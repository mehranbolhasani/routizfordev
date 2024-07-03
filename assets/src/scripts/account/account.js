'use strict'

import debug from '../utils/debug'
import ApexCharts from 'apexcharts'
import chart_options from './chart-options'

window.$ = window.jQuery
window.Routiz = window.Routiz || {}

class Account {

    constructor() {

        debug.account.log('Constructor')

        $(document).ready(() => this.init())

	}

    init() {

        debug.account.log('Dom ready')

        this.bind()
        this.dashboard_chart()
        this.table_click()

    }

    bind() {

        $('.rz--toggle-active').on('click', (e) => {
            $(e.currentTarget).closest('.rz-box').removeClass('rz-active')
        })

        $(`[data-action='account-listing-delete']`).on('click', e => {

            if( ! confirm( $(e.currentTarget).attr('data-confirmation') ) ) {
                e.preventDefault()
            }
        })

    }

    dashboard_chart() {

        let $e = $('.rz-chart[data-id="dashboard"]')

        if( $e.length ) {

            $.ajax({
                type: 'post',
                dataType: 'json',
                url: window.rz_vars.admin_ajax,
                data: {
                    action: 'rz_account_get_chart_views',
                },
    			beforeSend: () => {
    				$e.addClass('rz-ajaxing')
    			},
    			complete: () => {
                    $e.removeClass('rz-ajaxing')
                },
    			success: ( response ) => {

                    chart_options.series.push({
                        name: window.rz_vars.localize.listing_views,
                        data: response.views
                    })
                    chart_options.xaxis.categories = response.dates
                    chart_options.chart.height = 260

                    let chart = new ApexCharts( $e.get(0), chart_options )
                    chart.render()

    			}
            })

        }

    }

    table_click() {

        $('.rz-boxes-table tr').on('click', e => {
            let $click = $(e.currentTarget).find('.rz--click')
            if( $click.length && typeof $(e.target).attr('href') == 'undefined' && ! $(e.target).closest('a').length ) {

                e.preventDefault()
                $click.trigger('click')
            }
        })

    }

}

window.Routiz.account = new Account()
