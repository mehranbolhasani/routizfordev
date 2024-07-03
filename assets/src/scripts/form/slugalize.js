export default function slugalize( str ) {

    str = str.replace(/^\s+|\s+$/g, '') // trim
    str = str.toLowerCase()

    // remove accents, swap ñ for n, etc
    var from = 'ãàáäâẽèéëêìíïîõòóöôùúüûñç·/,:;'
    var to   = 'aaaaaeeeeeiiiiooooouuuunc-----'
    for( var i = 0, l = from.length; i < l; i++ ) {
        str = str.replace( new RegExp( from.charAt(i), 'g' ), to.charAt(i) )
    }

    str = str.replace( /[^a-z0-9 -_]/g, '' ) // remove invalid chars
        .replace( /\s+/g, '-' ) // collapse whitespace and replace with -
        .replace( /-+/g, '-' ) // collapse dashes

    return str

}
