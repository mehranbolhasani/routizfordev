class QueueObject {

    constructor( options, complete, context ) {

        this.options = options
        this.complete = complete
        this.context = context
        this.promise = { done: [], then: [], always: [], fail: [] }

    }

    _promise( n, h ) {

        if( this.promise[n] ) {
            this.promise[n].push( h )
        }

        return this

    }

    done( handler ) {
        return this._promise( 'done', handler )
    }

    then( handler ) {
        return this._promise( 'then', handler )
    }

    always( handler ) {
        return this._promise( 'always', handler )
    }

    fail( handler ) {
        return this._promise( 'fail', handler )
    }

}

class Queue {

    constructor( o ) {

        this.opt = $.extend({
            timeout: null,
            onStart: null,
            onStop: null,
            onError: null,
            onTimeout: null,
            onQueueChange: null,
            queueChangeDelay: 0,
            ajaxSettings: {
                contentType: 'application/x-www-form-urlencoded charset=UTF-8',
                type: 'post'
            }
        }, o )

        this.__queue = []
        this.current_req = null
        this.timeout_ref = null
        this.started = false

    }

    triggerStartEvent() {

        if(!this.started) {
            this.started = true
            if(this.opt.onTimeout && this.opt.timeout && $.isFunction(this.opt.onTimeout)) {
                if(this.timeout_ref) {
                    clearTimeout(this.timeout_ref)
                }
                this.timeout_ref = setTimeout($.proxy(() => {
                    this.opt.onTimeout.call(this, this.current_req.options)
                }, this), this.opt.timeout)
            }
            if(this.opt.onStart && $.isFunction(this.opt.onStart)) {
                this.opt.onStart(this, this.current_req.options)
            }
        }

    }

    triggerStopEvent() {

        if(this.started && this.__queue.length <= 0) {
            this.started = false
            if(this.timeout_ref) {
                clearTimeout(this.timeout_ref)
            }
            if(this.opt.onStop && $.isFunction(this.opt.onStop)) {
                this.opt.onStop(this, this.current_req.options)
            }
        }

    }

    triggerQueueChange() {

        if(this.opt.onQueueChange) {
            this.opt.onQueueChange.call(_this, this.__queue.length)
        }

        if(this.__queue.length >= 1 && !this.current_req) {
            this.current_req = this.__queue.shift()
            if(this.current_req.options.isCallback) {
                this.current_req.options.complete()
            }else{
                this.triggerStartEvent()
                var request = $.ajax(this.current_req.options)
                for(var i in this.current_req.promise) {
                    for(var x in this.current_req.promise[i]) {
                        request[i].call(this, this.current_req.promise[i][x])
                    }
                }
            }
        }
    }

    clear() {
        this.__queue = []
    }

    add( obj, thisArg ) {

        var _o = {}, origComplete = null

        if(obj instanceof Function) {
            _o = { isCallback: true }
            origComplete = obj
        }else{
            _o = $.extend( {}, this.opt.ajaxSettings, obj || {} )
            origComplete = _o.complete
        }

        _o.complete = ( request, status ) => {
            if(status == 'error' && this.opt.onError && $.isFunction(this.opt.onError)) {
                this.opt.onError.call( this.current_req.context || this, request, status )
            }
            if(this.current_req) {

                if( this.current_req.complete ) {
                    this.current_req.complete.call( this.current_req.context || this, request, status )
                }

                this.triggerStopEvent()
                this.current_req = null
                this.triggerQueueChange()
            }
        }

        var obj = new QueueObject(_o, origComplete, thisArg)
        this.__queue.push(obj)

        setTimeout(() => {
            this.triggerQueueChange()
        }, this.opt.queueChangeDelay )

        return obj

    }
}

export default Queue
