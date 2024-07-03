<?php

namespace Routiz\Inc\Src\Form\Modules\Upload;

use \Routiz\Inc\Src\Form\Modules\Module;

class Upload extends Module {

    public $thumbnail_size = 'rz_thumbnail';

    public function before_construct() {
        $this->defaults += [
            'multiple_upload' => false,
            'upload_type' => 'image',
            'is_admin' => is_admin() and ! wp_doing_ajax(),
            'button' => [
                'label' => esc_html__('Upload', 'routiz')
            ],
            'display_info' => true
        ];
    }

    public function after_build() {
        $this->props->single = true;
        $this->attrs['data-multiple'] = $this->props->multiple_upload ? 'true' : 'false';
        $this->attrs['data-upload-type'] = $this->props->upload_type;
    }

    public function finish() {}

    public function controller() {
        $preview = [];

        // single
        if ($this->props->multiple_upload) {
            $json_value = $this->props->value; // Assuming value is already a JSON string
            if ($json_value !== null) {
                $decoded_value = json_decode($json_value);
                if ($decoded_value !== null) {
                    if (is_array($decoded_value)) {
                        foreach ($decoded_value as $image_id) {
                            $image = $this->get_image($image_id);
                            if ($image) {
                                $preview[] = $image;
                            }
                        }
                    }
                }
            }
        } 
        // multiple
        else {
            $image_attrs = Rz()->json_decode($this->props->value);
            if ($image_attrs !== null && isset($image_attrs[0])) {
                $image = $this->get_image($image_attrs[0]);
                if ($image) {
                    $preview[] = $image;
                }
            }
        }

        return array_merge((array)$this->props, [
            'preview' => $preview,
            'strings' => (object)[
                'max_file_size' => esc_html__('Maximum upload file size: %s MB.', 'routiz'),
                'drag_reorder' => esc_html__('Drag to reorder.', 'routiz'),
                'featured_note' => esc_html__('First image selected for listing will be featured image of this listing.', 'routiz'),
            ]
        ]);
    }


    public function get_image( $image ) {
        if( isset( $image->id ) ) {

            $thumb = null;
            if( $this->props->upload_type == 'image' ) {
                $image_data = wp_get_attachment_image_src( $image->id, $this->thumbnail_size );
                if( isset( $image_data[0] ) ) {
                    $thumb = $image_data[0];
                }
            }

            return (object) [
                'id' => $image->id,
                'thumb' => $thumb,
                'url' => wp_get_attachment_url( $image->id ),
				'name' => basename( get_attached_file( $image->id ) ),
            ];

        }

    }

    public function before_save( $post_id, $value ) {

        if( is_array( $value ) ) {
            $value = json_encode( $value );
        }

        return $value;

    }

}
