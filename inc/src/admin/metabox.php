<?php

namespace Routiz\Inc\Src\Admin;

use \Routiz\Inc\Src\Form\Component as Form;
use \Routiz\Inc\Src\Request\Request;
use \Routiz\Inc\Src\Listing\Listing;
use \Routiz\Inc\Src\Listing_Type\Listing_Type;

class Metabox {

    use \Routiz\Inc\Src\Traits\Singleton;

    function __construct() {

        // metabox
        add_action( 'add_meta_boxes' , [ $this, 'register_meta_boxes' ] );
        // metabox comments
        add_action( 'add_meta_boxes_comment' , [ $this, 'register_meta_boxes_comment' ] );
        // metabox user
        // add_action( 'edit_user_profile', [ $this, 'register_meta_boxes_user' ] );
        add_action( 'personal_options', [ $this, 'register_meta_boxes_user' ] );

        // save metabox
        add_action( 'save_post', [ $this, 'update_listing_type' ], 10, 3 );
        add_action( 'save_post', [ $this, 'update_listing' ], 10, 3 );
        add_action( 'save_post', [ $this, 'update_search_form' ], 10, 3 );
        add_action( 'save_post', [ $this, 'update_entry' ], 10, 3 );
        add_action( 'save_post', [ $this, 'update_page' ], 10, 3 );
        add_action( 'save_post', [ $this, 'update_post' ], 10, 3 );
        add_action( 'save_post', [ $this, 'update_report' ], 10, 3 );

        // save comment metabox
        add_action( 'edit_comment', [ $this, 'update_comment' ], 1, 2 );
        add_action( 'comment_post', [ $this, 'update_comment' ], 1, 2 );

        // save user metabox
        add_action( 'personal_options_update', [ $this, 'update_user' ] );
        add_action( 'edit_user_profile_update', [ $this, 'update_user' ] );

        // preserve tab on save
        add_filter( 'redirect_post_location', [ $this, 'update_listing_type_anchor' ] );

        // panel init
        add_action( 'load-post.php', [ $this, 'init_panel' ] );
        add_action( 'load-post-new.php', [ $this, 'init_panel' ] );
        add_action( 'load-comment.php', [ $this, 'init_panel' ] );
        add_action( 'load-comment-new.php', [ $this, 'init_panel' ] );

    }

    function init_panel() {
        Panel::instance();
    }

    function register_meta_boxes() {

        // listing types
        add_meta_box(
            'rz-listing-type-options',
            _x( 'Listing Type Options', 'Listing type options in wp-admin', 'routiz' ),
            [ $this, 'meta_boxes_listing_type' ],
            'rz_listing_type'
        );

        // listings
        add_meta_box(
            'rz-listing-options',
            _x( 'Listing Options', 'Listing options in wp-admin', 'routiz' ),
            [ $this, 'meta_boxes_listing' ],
            'rz_listing'
        );

        // listings side
        add_meta_box(
            'rz-listing-side-options',
            _x( 'Listing Options', 'Listing sidebar options in wp-admin', 'routiz' ),
            [ $this, 'meta_boxes_listing_side' ],
            'rz_listing',
            'side',
            'default'
        );

        // search forms
        add_meta_box(
            'rz-search-form-options',
            _x( 'Search Form', 'Search form options in wp-admin', 'routiz' ),
            [ $this, 'meta_boxes_search_form' ],
            'rz_search_form'
        );

        // plans
        add_meta_box(
            'rz-plan-options',
            _x( 'Plan', 'Plan options in wp-admin', 'routiz' ),
            [ $this, 'meta_boxes_plan' ],
            'rz_plan'
        );

        // promotion
        add_meta_box(
            'rz-promotion-options',
            _x( 'Promotion', 'Plan options in wp-admin', 'routiz' ),
            [ $this, 'meta_boxes_promotion' ],
            'rz_promotion'
        );

        // conversation
        add_meta_box(
            'rz-conversation-options',
            _x( 'Conversation', 'Conversation options in wp-admin', 'routiz' ),
            [ $this, 'meta_boxes_conversation' ],
            'rz_conversation'
        );

        // entry
        add_meta_box(
            'rz-entry-options',
            _x( 'Entry', 'Entries options in wp-admin', 'routiz' ),
            [ $this, 'meta_boxes_entry' ],
            'rz_entry'
        );

        // claim
        add_meta_box(
            'rz-claim-options',
            _x( 'Claim', 'Claim options in wp-admin', 'routiz' ),
            [ $this, 'meta_boxes_claim' ],
            'rz_claim'
        );

        // page
        // add_meta_box(
        //     'rz-page-modules',
        //     _x( 'Page Modules', 'Pages module options in wp-admin', 'routiz' ),
        //     [ $this, 'meta_boxes_page' ],
        //     'page'
        // );

        // report
        add_meta_box(
            'rz-report-options',
            _x( 'Report', 'Report options in wp-admin', 'routiz' ),
            [ $this, 'meta_boxes_report' ],
            'rz_report'
        );

    }

    function register_meta_boxes_comment( $comment ) {

        // check user capabilities
		if ( ! current_user_can( 'edit_comment', $comment->comment_ID ) ) {
			return;
		}

		// only in listing comments
		if ( get_post_type( $comment->comment_post_ID ) !== 'rz_listing' ) {
			return;
		}

		// only top level comments
		if ( 0 !== intval( $comment->comment_parent ) ) {
			return;
		}

        add_meta_box(
            'rz-comments-options',
            __( 'Listing Review', 'routiz' ),
            [ $this, 'meta_boxes_comments' ],
            'comment',
            'normal'
        );

    }

    function register_meta_boxes_user( $user ) {

        global $rz_user;
        $rz_user = $user;

        Rz()->the_template('admin/metabox/user');

    }

    static function meta_boxes_listing_type( $post ) {
        Rz()->the_template('admin/metabox/listing-type');
    }

    static function meta_boxes_listing( $post ) {
        Rz()->the_template('admin/metabox/listing');
    }

    static function meta_boxes_listing_side( $post ) {
        Rz()->the_template('admin/metabox/listing-side');
    }

    static function meta_boxes_search_form( $post ) {
        Rz()->the_template('admin/metabox/search-form');
    }

    static function meta_boxes_plan( $post ) {
        Rz()->the_template('admin/metabox/plan');
    }

    static function meta_boxes_promotion( $post ) {
        Rz()->the_template('admin/metabox/promotion');
    }

    static function meta_boxes_conversation( $post ) {
        Rz()->the_template('admin/metabox/conversation');
    }

    static function meta_boxes_entry( $post ) {
        Rz()->the_template('admin/metabox/entry');
    }

    static function meta_boxes_claim( $post ) {
        Rz()->the_template('admin/metabox/claim');
    }

    static function meta_boxes_report( $post ) {
        Rz()->the_template('admin/metabox/report');
    }

    // static function meta_boxes_page( $post ) {
    //     Rz()->the_template('admin/metabox/page-modules');
    // }

    static function meta_boxes_comments( $comment ) {
        Rz()->the_template('admin/metabox/comment');
    }

    static function update_listing_type( $post_id ) {

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if( get_post_type( $post_id ) == 'rz_listing_type' and isset( $_POST ) and ! empty( $_POST ) ) {

            foreach( $_POST as $id => $value ) {
                if( substr( $id, 0, 3 ) == 'rz_' ) {

                    delete_post_meta( $post_id, $id );

                    // array
                    if( is_array( $value ) ) {
                        foreach( $value as $val ) {
                            add_post_meta( $post_id, $id, $val );
                        }
                    }
                    // single
                    else{
                        delete_post_meta( $post_id, $id );
                        update_post_meta( $post_id, $id, $value );
                    }
                }
            }
        }

    }

    static function update_search_form( $post_id ) {

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if( get_post_type( $post_id ) == 'rz_search_form' and isset( $_POST ) and ! empty( $_POST ) ) {

            foreach( $_POST as $id => $value ) {
                if( substr( $id, 0, 3 ) == 'rz_' ) {

                    delete_post_meta( $post_id, $id );

                    // array
                    if( is_array( $value ) ) {
                        foreach( $value as $val ) {
                            add_post_meta( $post_id, $id, $val );
                        }
                    }
                    // single
                    else{
                        delete_post_meta( $post_id, $id );
                        update_post_meta( $post_id, $id, $value );
                    }
                }
            }
        }
    }

    static function update_page( $post_id ) {

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if( get_post_type( $post_id ) == 'page' and isset( $_POST ) and ! empty( $_POST ) ) {

            foreach( $_POST as $id => $value ) {
                if( substr( $id, 0, 3 ) == 'rz_' ) {

                    delete_post_meta( $post_id, $id );

                    // array
                    if( is_array( $value ) ) {
                        foreach( $value as $val ) {
                            add_post_meta( $post_id, $id, $val );
                        }
                    }
                    // single
                    else{
                        delete_post_meta( $post_id, $id );
                        update_post_meta( $post_id, $id, $value );
                    }
                }
            }
        }
    }

    static function update_post( $post_id ) {

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if( get_post_type( $post_id ) == 'post' and isset( $_POST ) and ! empty( $_POST ) ) {

            foreach( $_POST as $id => $value ) {
                if( substr( $id, 0, 3 ) == 'rz_' ) {

                    delete_post_meta( $post_id, $id );

                    // array
                    if( is_array( $value ) ) {
                        foreach( $value as $val ) {
                            add_post_meta( $post_id, $id, $val );
                        }
                    }
                    // single
                    else{
                        delete_post_meta( $post_id, $id );
                        update_post_meta( $post_id, $id, $value );
                    }
                }
            }
        }
    }

    static function update_report( $post_id ) {

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if( get_post_type( $post_id ) == 'rz_report' and isset( $_POST ) and ! empty( $_POST ) ) {

            foreach( $_POST as $id => $value ) {
                if( substr( $id, 0, 3 ) == 'rz_' ) {

                    delete_post_meta( $post_id, $id );

                    // array
                    if( is_array( $value ) ) {
                        foreach( $value as $val ) {
                            add_post_meta( $post_id, $id, $val );
                        }
                    }
                    // single
                    else{
                        delete_post_meta( $post_id, $id );
                        update_post_meta( $post_id, $id, $value );
                    }
                }
            }
        }
    }

    static function is_valid_date( $time, $format = 'Y-m-d' ) {

        $date_format = date('Y-m-d');
        $d = \DateTime::createFromFormat( "Y-m-d {$format}", "{$date_format} {$time}" );
        return $d && $d->format( $format ) == $time;

    }

    static function update_listing( $post_id, $post, $update ) {

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( ! $update ) {
            return;
        }

        if( get_post_type( $post_id ) == 'rz_listing' and isset( $_POST ) and ! empty( $_POST ) ) {

            $form = new Form( Form::Storage_Request );
            $request = Request::instance();
            $listing_type = new Listing_Type( Rz()->get_meta( 'rz_listing_type', $post_id ) );

            /*
             * update priority
             *
             */
            if( $request->has('rz_priority_selection') ) {

                update_post_meta( $post_id, 'rz_priority_selection', $request->get('rz_priority_selection') );
                update_post_meta( $post_id, 'rz_priority_custom', $request->get('rz_priority_custom') );

                $priority = 0;

                switch( $request->get('rz_priority_selection') ) {
                    case 'normal':
                        $priority = 0; break;
                    case 'featured':
                        $priority = 1; break;
                    case 'promoted':
                        $priority = 2; break;
                    case 'custom':
                        $priority = (int) $request->get('rz_priority_custom'); break;
                }

                update_post_meta( $post_id, 'rz_priority', $priority );

            }

            /*
             * update expiration
             *
             */
            if( ! $request->is_empty('rz_enable_listing_expiration') ) {

                $exp = $request->get('rz_listing_expiration');

                if( is_array( $exp ) ) {

                    $exp = (object) $exp;

                    if(
                        isset( $exp->month ) and
                        isset( $exp->day ) and
                        isset( $exp->year ) and
                        isset( $exp->hour ) and
                        isset( $exp->minute )
                    ) {

                        if( self::is_valid_date( sprintf( '%s-%s-%s %s:%s', $exp->year, sprintf('%02d', $exp->month ), sprintf('%02d', $exp->day), sprintf('%02d', $exp->hour), sprintf('%02d', $exp->minute) ), 'Y-m-d H:i' ) ) {

                            $timezone = new \DateTimeZone( wp_timezone_string() );
                            $datetime = new \DateTime( sprintf( '%s-%s-%s %s:%s', $exp->year,sprintf('%02d', $exp->month ), sprintf('%02d', $exp->day), sprintf('%02d', $exp->hour), sprintf('%02d', $exp->minute) ), $timezone );
                            $datetime->setTimezone( new \DateTimeZone('GMT') );

                            update_post_meta( $post_id, 'rz_listing_expires', $datetime->format('Y-m-d H:i') );

                        }
                    }
                }

            }
            // revert expiration
            elseif( $request->get('rz_remove_listing_expiration') ) {
                delete_post_meta( $post_id, 'rz_listing_expires' );
            }

            /*
             * static fields
             *
             */
            $static_fields = [
                'rz_instant',
                'rz_price',
                'rz_price_weekend',
                'rz_security_deposit',
                'rz_long_term_week',
                'rz_long_term_month',
                'rz_price_seasonal',
                'rz_extra_pricing',
                'rz_addons',
                'rz_guests',
                'rz_children',
                'rz_guest_price',
                'rz_child_price',
                'rz_child_sep_price',
                'rz_reservation_length_min',
                'rz_reservation_length_max',
    			'rz_reservation_start',
    			'rz_reservation_end',
                'rz_hour_book_multi_disable',
    			'rz_time_availability',
            ];

            foreach( $static_fields as $static_field ) {
                if( $request->has( $static_field ) ) {
                    update_post_meta( $post_id, $static_field, $request->get( $static_field ) );
                }
            }

            /*
             * update other side options
             *
             */
            if( $request->has('rz_is_claimed') ) {
                update_post_meta( $post_id, 'rz_is_claimed', $request->get('rz_is_claimed') );
            }

            /*
             * update fields
             *
             */
            $items = Rz()->jsoning( 'rz_fields', Rz()->get_meta('rz_listing_type') );

            if( ! $items ) { $items = []; }

            $items = array_merge( $items, [
                (object) [
                    'template' => (object) [
                        'id' => 'select'
                    ],
                    'fields' => (object) [
                        'key' => 'rz_listing_type'
                    ],
                ]
            ]);

            foreach( $items as $item ) {

                // prefix custom field ids
                if( isset( $item->fields->key ) ) {
                    $item->fields->key = Rz()->prefix( $item->fields->key );
                }

                $field = $form->create( $item );
                $id = $field->props->id;

                if( isset( $_POST[ $id ] ) ) {

                    $value = $field->props->value;
                    $value = $field->before_save( $post_id, $value );

                    delete_post_meta( $post_id, $id );

                    // array
                    if( is_array( $value ) ) {
                        foreach( $value as $val ) {
                            add_post_meta( $post_id, $id, $val );
                        }
                    }
                    // single
                    else{
                        delete_post_meta( $post_id, $id );
                        update_post_meta( $post_id, $id, $value );
                    }

                    $field->after_save( $post_id, $value );

                }
            }
        }
    }

    static function update_entry( $post_id, $post, $update ) {

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( ! $update ) {
            return;
        }

        if( get_post_type( $post_id ) == 'rz_entry' and isset( $_POST ) and ! empty( $_POST ) ) {

            $listing_id = Rz()->get_meta('rz_listing', $post_id );

            if( ! $listing_id ) {
                return;
            }

            $request = Request::instance();
            $form = new Form( Form::Storage_Request );
            $listing = new Listing( $listing_id );

    		$items = Rz()->jsoning( 'rz_action_type_application_fields', $listing->type->id );

            foreach( $items as $item ) {

				$field = $form->create(
					Rz()->prefix_item( $item )
				);

                $id = $field->props->id;

                if( $request->has( $id ) ) {

                    $value = $field->props->value;
                    $value = $field->before_save( $post_id, $value );

                    // array
                    if( is_array( $value ) ) {
                        delete_post_meta( $post_id, $id );
                        foreach( $value as $val ) {
                            add_post_meta( $post_id, $id, $val );
                        }
                    }
                    // single
                    else{
                        update_post_meta( $post_id, $id, $value );
                    }

                    $field->after_save( $post_id, $value );

                }

    		}
        }
    }

    function update_listing_type_anchor( $location ) {

        if( $_POST['post_type'] == 'rz_listing_type' ) {
            if( isset( $_POST['routiz_current_tab'] ) ) {
                return $location . '#' . esc_attr( $_POST['routiz_current_tab'] );
            }
        }

        return $location;

    }

    function update_comment( $comment_id, $data ) {

        if ( ! current_user_can( 'edit_comment', $comment_id ) || ( isset( $data['comment_parent'] ) and $data['comment_parent'] ) ) {
			return $comment_id;
		}

        if ( ! isset( $_POST['routiz_review_comment_nonce'] ) || ! wp_verify_nonce( $_POST['routiz_review_comment_nonce'], 'routiz_comment' ) ) {
			return $comment_id;
		}

        $request = Request::instance();
        $listing = new Listing( $data['comment_post_ID'] );

        if( ! $listing->id ) {
            return;
        }

        if( isset( $_POST ) and ! empty( $_POST ) ) {

            $review_ratings = Rz()->jsoning( 'rz_review_ratings', $listing->type->id );

            // ratings
            $comment_ratings = [];
            foreach( $review_ratings as $review_rating ) {
                $comment_ratings[ $review_rating->fields->key ] = $request->get( sprintf( 'rz_rating_%s', $review_rating->fields->key ) );
            }

            update_comment_meta( $comment_id, 'rz_ratings', $comment_ratings );

            // gallery
            if( ! $request->is_empty('rz_gallery') ) {
                $gallery = $request->get('rz_gallery');
                // TODO: improve this with after_save
                update_comment_meta( $comment_id, 'rz_gallery', is_array( $gallery ) ? json_encode( $gallery ) : $gallery );
            }
        }

    }

    public function update_user( $user_id ) {

        if ( ! current_user_can( 'edit_user', $user_id ) ) {
            return;
        }

        update_user_meta( $user_id, 'rz_verified', sanitize_text_field( $_POST['rz_verified'] ) );
        update_user_meta( $user_id, 'rz_role', sanitize_text_field( $_POST['rz_role'] ) );

    }

}
