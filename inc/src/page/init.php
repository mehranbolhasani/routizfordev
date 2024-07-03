<?php

namespace Routiz\Inc\Src\Page;

use \Routiz\Inc\Extensions\Component\Init as Main_Init;
use \Routiz\Inc\Src\Traits\Singleton;

class Init extends Main_Init {

    use Singleton;

    function __construct() {

        add_filter( 'the_content', [ $this, 'page_content' ] );

    }

    public function page_content( $content ) {

        if( is_page() and is_main_query() and Rz()->get_meta('rz_enable_page_modules') and function_exists('Brk') and ! Brk()->is_elementor() ) {
            return Rz()->get_template('routiz/page/content');
        }

        return $content;

    }

}
