'use strict'

window.$ = window.jQuery

export default function escape( str ) {

    if( ! str ) {
        return '';
    }

    let entities = [
        { regex: />/g, entity: '&gt;' },
        { regex: /</g, entity: '&lt;' },
        { regex: /"/g, entity: '&quot;' },
        { regex: /\\/g, entity: '&bsol;' },
    ]

    for( let v in entities ) {
        str = str.replace( entities[v].regex, entities[v].entity )
    }

    return str

}
