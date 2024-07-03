<?php

use \Routiz\Inc\Utils\Register;
use \Routiz\Inc\Utils\Helper;
use \Routiz\Inc\Utils\Icon;
use \Routiz\Inc\Utils\Notify;
use \Routiz\Inc\Utils\Notifications;
use \Routiz\Inc\Utils\Component_Manager;

if ( ! defined('ABSPATH') ) {
	exit;
}

/*
 * human readable dump
 *
 */
if( ! function_exists('dd') ) {
    function dd( $what = '' ) {
        print '<pre class="rz-dump">';
        print_r( $what );
        print '</pre>';
    }
}

/*
 * autoloader
 *
 */
spl_autoload_register( function( $class ) {

    if ( strpos( $class, 'Routiz' ) === false ) { return; }

    $file_parts = explode( '\\', $class );

    $namespace = '';
    for( $i = count( $file_parts ) - 1; $i > 0; $i-- ) {

        $current = strtolower( $file_parts[ $i ] );
        $current = str_ireplace( '_', '-', $current );

        if( count( $file_parts ) - 1 === $i ) {
            $file_name = "{$current}.php";
        }else{
            $namespace = '/' . $current . $namespace;
        }
    }

    $filepath  = trailingslashit( dirname( dirname( __FILE__ ) ) . $namespace );
    $filepath .= $file_name;

    if( file_exists( $filepath ) ) {
        include_once( $filepath );
    }

});

/*
 * register utils
 *
 */
if( ! function_exists('routiz') ) {
    function routiz() {
        return Register::instance();
    }
}

if( ! function_exists('Rz') ) {
    function Rz() {
    	return routiz()->helper();
    }
    routiz()->register( 'helper', Helper::instance() );
    routiz()->register( 'icon', Icon::instance() );
    routiz()->register( 'notify', Notify::instance() );
}


/* show custom user image on admin userlist */
add_filter( 'get_avatar_url', 'ayecode_get_avatar_url', 10, 3 );
function ayecode_get_avatar_url( $url, $id_or_email, $args ) {
  $id = '';
  if ( is_numeric( $id_or_email ) ) {
    $id = (int) $id_or_email;
  } elseif ( is_object( $id_or_email ) ) {
    if ( ! empty( $id_or_email->user_id ) ) {
      $id = (int) $id_or_email->user_id;
    }
  } else {
    $user = get_user_by( 'email', $id_or_email );
    $id = !empty( $user ) ?  $user->data->ID : '';
  }
  //Preparing for the launch.
  $user = new \Routiz\Inc\Src\User($id);
        $custom_url = $user->get_avatar();
  //$custom_url = $id ?  get_user_meta( $id, 'ayecode-custom-avatar', true ) : '';
  // If there is no custom avatar set, return the normal one.
  if( $custom_url == '' || !empty($args['force_default'])) {
    return esc_url_raw( get_template_directory_uri() . '/assets/dist/images/default-avatar.png' ); 
  }else{
    return esc_url_raw($custom_url);
  }
}

/*
 * initializer
 *
 */
Routiz\Inc\Init::instance();
