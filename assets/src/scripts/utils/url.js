'use strict';

class url {

    constructor() {
        // ..
    }

    generate( params, url, push_state, is_back ) {

        url = url || window.location.protocol + '//' + window.location.host + window.location.pathname
        push_state = push_state || true
        is_back = is_back || false

        let new_url = new URL( url )

        // is explore page
        if( new_url.href.includes( window.rz_vars.pages.explore ) ) {

            for( let param in params ) {
                if( params[ param ] == '' || params[ param ] == '0' ) continue

                if( Array.isArray( params[ param ] ) ) {
                    params[ param ].forEach(( prm ) => {
                        new_url.searchParams.append( `${param}[]`, prm )
                    })
                }else{
                    new_url.searchParams.append( param, params[ param ] )
                }
            }

            if ( push_state && ! is_back && window.history.replaceState ) {
                window.history.pushState( {}, null, new_url.href )
                if( ! window.Routiz.explore.dynamic.is_explore ) {
                    this.reload()
                }
            }

            return true

        }
        // going back to no explore page
        else{
            this.reload()
        }

        return

    }

    query( query_string ) {

        // query_string = query_string || window.location.origin
        query_string = this.absolute_url( query_string ) || window.location.href

        var search_params = new URL( query_string ).searchParams

        let result = {}
        for( let pair of search_params.entries() ) {
            result[ pair[0] ] = pair[1]
        }

        return result

    }

    absolute_url( url ) {

        let a

        if( ! a ) a = document.createElement('a')
		a.href = url

		return a.href

    }

    get( params ) {

        url = new URL( window.location.href )
        return url.searchParams.get( params )

    }

    reload() {
        window.location.reload()
    }

}

export default new url()
