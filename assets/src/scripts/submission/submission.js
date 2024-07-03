'use strict'

import debug from '../utils/debug'
import serialize from '../form/serialize'
import * as steps from './steps'

window.$ = window.jQuery
window.Routiz = window.Routiz || {};

class Submission {

    constructor() {

        debug.submission.log('Constructor')

        $(document).ready(() => this.init())

	}

	init() {

        this.index = 0

        this.$ = $('.rz-submission')
        this.$steps = $('.rz-submission-steps')
        this.$step = $('.rz-submission-step')
        this.$wizard = $('.rz-wizard')
        // this.$nav = $('.rz-submission-nav')

        this.navigate()

        $(document).on('click', `[data-action='submission-continue']`, () => {
            this.call()
        })

        $(document).on('click', `[data-action='submission-back']`, e => {
            this.back(e)
        })

        // $(document).on('click', `[data-action='submission-submit-type']`, e => {
        //     this.submit_type(e)
        // })

        $(document).on('click', '.rz-submission-types .rz--type', e => {
            $('.rz-submission-types .rz--type').removeClass('rz-active')
            $(e.currentTarget).addClass('rz-active')
        })

	}

    submit_type() {

        let type = $('[name="type"]:checked').val()

        if( typeof type == 'undefined' ) {
            $('.rz-submission-error').addClass('rz-block')
            window.scrollTo({
                top: $('.rz-submission-error').position().top - 70,
                left: null,
                behavior: 'smooth'
            })
        }else{
            this.$.addClass('rz-ajaxing')
            $('[name="rz-submit-type"]').submit()
        }

    }

    navigate() {

        let $current_li = this.$wizard.find('li').eq( this.index )
        let $next_step = this.$step.eq( this.index )

        // progress
        let total = this.$wizard.find('li').length
        let current = this.index + 1
        let length = 100 / total * current

        this.$step.removeClass('rz-active')
        $next_step.addClass('rz-active')

        $current_li.addClass('rz-active rz-current')
        $current_li.prevAll().removeClass('rz-current')
        $current_li.nextAll().removeClass('rz-active rz-current')

        $('.rz--steps-total').html( total )
        $('.rz--steps-current').html( current )
        $('.rz--progress').css( 'width', `${length}%` )

        return $next_step.attr('data-id')

    }

    back(e) {

        if( this.$.hasClass('rz-ajaxing') ) {
            return
        }

        if( this.index <= 0 ) {
            return
        }

        if( $( e.currentTarget ).hasClass('rz-disabled') ) {
            return
        }

        // first
        if( this.index == 1 ) {
            $(`[data-action='submission-back']`).addClass('rz-disabled')
        }

        this.index -= 1
        this.navigate()

    }

    call() {

        if( this.$.hasClass('rz-ajaxing') ) {
            return;
        }

        if( $('.rz-submission-types').length ) {
            this.submit_type()
            return;
        }

        let $current = this.$step.eq( this.index )
        let id = $current.data('id')

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: window.rz_vars.admin_ajax,
            data: {
                action: 'rz_submission_next',
                group: $current.attr('data-group'),
                id: $current.attr('data-id'),
                input: serialize( $( 'form', this.$ ) )
            },
            beforeSend: () => {

                this.$.addClass('rz-ajaxing')

                $('.rz-form-group-error', $current).removeClass('rz-form-group-error')
                $('.rz-error-holder', $current).remove()

                $('.rz-select-plan-error', this.$).removeClass('rz-block')

            },
            complete: () => {
                this.$.removeClass('rz-ajaxing')
            },
            success: ( response ) => {

                // success
                if( response.success ) {

                    window.scrollTo({
                        top: 0,
                        left: null,
                        behavior: 'smooth'
                    })

                    this.index += 1

                    $(`[data-action='submission-back']`).removeClass('rz-disabled')

                    id = this.navigate()

                    /**/

                }
                // error
                else{

                    let $first_error = null

                    for( var field_name in response.errors ) {

                        let $error = $('[data-id="' + field_name.replace(/^rz_/, '') + '"]', $current).first()

                        $error.addClass('rz-form-group-error')
                            .append('<p class="rz-error-holder"><span class="rz-error">' + response.errors[ field_name ] + '</span></p>')

                        if( ! $first_error ) {
                            $first_error = $error
                        }
                    }

                    // scroll to first error on screen
                    if( $first_error.length ) {

                        let top = $first_error.position().top - $('.brk--top').outerHeight() - 20

                        window.scrollTo({
                            top: top,
                            left: null,
                            behavior: 'smooth'
                        })

                    }

                }

                let step_id = id.split('-').join('_');

                if( typeof steps[ step_id ] == 'function' ) {

                    let fallback = new steps[ step_id ]( this.$step.filter(( index, element ) => {
                        return element.getAttribute('data-id') == id
                    }), response )

                    response.success ? fallback.init() : fallback.error()

                }

            }

        })

    }

    /*
    components() {

        let Routiz_Submission_Step = Vue.component('rz-submission-step', {
            props: {
                index: Number,
        	},
        });

        let Routiz_Submission = Vue.component('rz-submission', {
            props: {
        		props: Object,
        	},
            data () {
        		return {

                    index: this.props.index,
                    wizard: this.props.wizard,
                    titles: this.props.titles,
                    steps: [ 'rz-submission-step' ],

        		}
        	},
            methods: {
                next: function() {

                    let _this = this;
                    let submission = $( this.$refs.submission );

                    if( submission.hasClass('rz-submission-ajaxing') ) { return; }
                    submission.addClass('rz-submission-ajaxing');

                    $('.rz-form-group-error', submission).removeClass('rz-form-group-error');
            		$('.rz-error-holder', submission).remove();

                    let input = serialize( $('.rz-submission-step form', submission) )

                    $.ajax({
                        type: 'post',
                        dataType: 'json',
                        url: window.rz_vars.admin_ajax,
                        data: {
                            action: 'rz_submission_next',
                            step_index: _this.index,
                            input: input,
                            wizard: _this.index == 0 ? _this.props.wizard : _this.wizard // on step listing types, reset wizard
                        },
                        success: function( response ) {

                            submission.removeClass('rz-submission-ajaxing');

                            if( response.validation.success ) {

                                // if there is any wizard update, then update it
                                if( response.validation.wizard ) {
                                    _this.wizard = response.validation.wizard;
                                }

                                _this.title = response.title;
                                _this.index++;

                                // add the new component step
                                let Routiz_Submission_Step_Dynamic = Vue.component('rz-submission-step-dynamic', {
                                    extends: Routiz_Submission_Step,
                                    template: response.content,
                                    mounted: function () {

                                        let step = _this.wizard[ _this.index ].replace( /--tab-[0-9]+/g, '' ).replace( /-/g, '_' )

                            			if( typeof steps[ step ] == 'function' ) {

                                            debug.submission.log(`init step: ${step}`)

                            				new steps[ step ]( $( this.$refs.main ), response )
                            			}

                                    }
                                });

                                // titles
                                if( ! _this.titles.includes( response.title ) ) {
                                    _this.titles.push( response.title );
                                }

                                // steps
                                _this.steps.push( Routiz_Submission_Step_Dynamic );

                            }
                            // error
                            else{

                                for ( var field_name in response.validation.errors ) {
                                    $('.rz-form-group[data-id="' + field_name.replace(/^rz_/, '') + '"]', submission)
            							.addClass('rz-form-group-error')
                                        .append('<p class="rz-error-holder"><span class="rz-error">' + response.validation.errors[ field_name ] + '</span></p>');
                                }

                            }

                        }
                    });

                },

                back: function() {

                    debug.submission.log('step back')

                    let submission = $( this.$refs.submission );

                    if( submission.hasClass('rz-submission-ajaxing') ) { return; }

                    $('.rz-form-group-error', submission).removeClass('rz-form-group-error');
            		$('.rz-error-holder', submission).remove();

                    this.index--;
                    this.steps = this.ob_to_array( this.steps ).slice( 0, -1 );
                    this.titles = this.ob_to_array( this.titles ).slice( 0, -1 );

                },

                ob_to_array: function( ob ) {
                    let arr = [];
                    for( let i = 0; i < ob.length; i++ ) {
                       arr.push( ob[i] );
                    }
                    return arr;
                }

            }
        });

    }*/

}

window.Routiz.submission = new Submission()
