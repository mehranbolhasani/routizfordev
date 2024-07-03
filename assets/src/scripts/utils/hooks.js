'use strict';

class Hooks {

    constructor() {

        this.actions = {};

    }

    add_action( hook, callback, args ) {

		let action_object = {
			callback: callback,
			args: args
		};

		this.actions[ hook ] = action_object;

	}

	do_action( hook, element ) {

		if( typeof this.actions[ hook ] === 'function' ) {
			this.actions[ hook ].call( null, ...this.actions[ hook ].args );
		}else{
			this.actions[ hook ].callback.callback( ...this.actions[ hook ].args );
		}

	}

}

export default Hooks
