<?php

namespace Routiz\Inc\Src\Submission;

use \Routiz\Inc\Src\Listing_Type\Listing_Type;
use \Routiz\Inc\Src\Explore\Explore;
use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Traits\Singleton;

class Submission {

    public $request;
    public $component;

    public $id;
    public $listing_type;

    public $tabs;

    function __construct( $id = null ) {

        global $rz_explore;

        $this->request = Request::instance();
        $this->component = new Component();

        // type passed
        if( $id ) {
            $this->id = $id;
        }
        // type from request
        elseif( ! $this->request->is_empty('type') ) {
            $this->id = $this->request->get('type');
        }
        // get single type
        elseif( $rz_explore->total_types == 1 ) {
            $this->id = $rz_explore->type->id;
        }

        $this->listing_type = new Listing_Type( $this->id );

        // not a valid listing type
        // redirect somewhere safe
        if( $rz_explore->request->has('type') && ! $this->listing_type->id ) {
            $submission_page = Rz()->get_submission_page_url();
            die( wp_redirect( $submission_page ? $submission_page : get_home_url() ) );
        }

        $this->tabs = $this->component->tabs(
            Rz()->jsoning( 'rz_fields', $this->listing_type->id )
        );

    }

    public function is_missing_type() {

        global $rz_explore;
        return $rz_explore->total_types > 1 and $rz_explore->request->is_empty('type');

    }

    public function get_listing_types() {

        return new \WP_Query([
            'post_status' => 'publish',
            'post_type' => 'rz_listing_type',
            'posts_per_page' => 10
        ]);

    }

}
