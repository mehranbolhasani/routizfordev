<?php

namespace Routiz\Inc\Src\Listing\Modules\Content;

use \Routiz\Inc\Src\Listing\Modules\Module;

class Content extends Module {

    public function controller() {

        switch( $this->props->id ) {
            case 'post_content': $content = get_post_field( 'post_content' ); break;
            case 'post_title': $content = get_post_field( 'post_title' ); break;
            default: $content = Rz()->get_meta( $this->props->id );
        }

        return array_merge( (array) $this->props, [
            'id' => $this->props->id,
            'name' => $this->props->name,
            'content' => $content,
        ]);

    }

}
