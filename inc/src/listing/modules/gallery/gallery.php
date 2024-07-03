<?php

namespace Routiz\Inc\Src\Listing\Modules\Gallery;

use \Routiz\Inc\Src\Listing\Modules\Module;

class Gallery extends Module {

    public function controller() {

        global $rz_listing;

        if( $this->props->display == 'adaptive' ) {
            $gallery = $rz_listing->get_gallery([
                'ulz_gallery_large',
                'ulz_gallery_preview'
            ], $this->props->id );
        }else{
            $gallery = [];
            $files = Rz()->jsoning( $this->props->id, $rz_listing->id );
            foreach( $files as $file ) {
                $file_src = wp_get_attachment_url( $file->id );
                $gallery[] = [
                    'id' => $file->id,
                    'src' => $file_src,
                    'name' => basename( $file_src ),
                ];
            }
        }

        return array_merge( (array) $this->props, [
            'name' => $this->props->name,
            'gallery' => $gallery,
            'gallery_num' => count( $gallery ),
            'strings' => (object) [
                'more_images' => esc_html__( '+%s', 'routiz' )
            ]
        ]);

    }

}
