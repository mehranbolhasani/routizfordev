export default () => {

    if( window.rz_vars.map.style == 'custom' ) {
        let style_custom = window.rz_vars.map.style_custom
        if( typeof style_custom == 'object' ) {
            return style_custom
        }
    }else{
        try{
            return require( `./${window.rz_vars.map.style || 'default'}` ).default
        }catch( ex ) {
            console.log( 'Error: map style does not exist!' )
        }
    }

    return []

}
