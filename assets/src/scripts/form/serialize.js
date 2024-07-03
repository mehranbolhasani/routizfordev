'use strict'

import escape from './escape'

window.$ = window.jQuery

function is_json( string ) {

    if( string == '[]' ) {
        return
    }

    try {
        let json = JSON.parse( string )
        return ( typeof json === 'object' );
    }catch(e) {
        return;
    }

}

function serialize_elements( elements, remove_empty, unprefix ) {

    let result = {}

    $.each( elements, function( index, element ) {

        // let name = this.name.replace(/_rz_fieldset_[0-9]+$/g, '')
        let name = this.name
        if( unprefix ) {
            name = name.replace(/^rz_/g, '')
        }

        if( name.indexOf('[]') !== -1 ) {

            name = name.replace('[]', '')
            if( typeof result[ name ] == 'undefined' ) { result[ name ] = [] }

            if( ! ( remove_empty && ( ! this.value || this.value.length === 0 ) ) ) {
                result[ name ].push( escape( this.value ) ) // set value
            }

        }else if( name.indexOf('[') !== -1 ) {

            name = name.replace('[]', '')
            let match = name.match( /^(.*)\[(.*)\]$/i )
            if( match[2] ) {
                let group = match[1]
                let name = match[2]
                if( typeof result[ group ] == 'undefined' ) { result[ group ] = {} }
                if( ! ( remove_empty && ( ! this.value || this.value.length === 0 ) ) ) {
                    result[ group ][ name ] = escape( this.value )  // set value
                }
            }

        }else{

            if( ! ( remove_empty && ( ! this.value || this.value.length === 0 || this.value == '0' ) ) ) {

                if( is_json( this.value ) ) {

                    // repeater
                    if( $(`[name='${this.name}']`).hasClass('rz-repeater-value') ) {
                        result[ name ] = this.value // set value
                    }else{
                        result[ name ] = JSON.parse( this.value ) // set value
                    }

                }else{
                    result[ name ] = escape( this.value ) // set value
                }

            }

        }

    })

    return result

}

export default function serialize( form, remove_empty, unprefix, is_dom ) {

    remove_empty = remove_empty || false
    unprefix = unprefix || false
    is_dom = is_dom || false

    let elements

    if( is_dom ) {
        elements = $( 'input, select, textarea', form ).serializeArray()
    }else{
        elements = form.serializeArray()
    }

    return serialize_elements( elements, remove_empty, unprefix )

}
