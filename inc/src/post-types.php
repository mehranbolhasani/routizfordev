<?php

namespace Routiz\Inc\Src;

use \Routiz\Inc\Src\Listing\Listing;
use \Routiz\Inc\Src\Validation;
use \Routiz\Inc\Src\Listing\iCal;

class Post_Types {

    use \Routiz\Inc\Src\Traits\Singleton;

    function __construct() {

        add_action( 'init', [ $this, 'register_post_types' ] );
        add_action( 'init', [ $this, 'register_post_status' ], 12 );
        add_action( 'admin_init', [ $this, 'approve_listing' ] );
        add_action( 'admin_init', [ $this, 'approve_claim' ] );
        add_action( 'routiz_check_for_expired_listings', [ $this, 'check_for_expired_listings' ] );
        add_action( 'routiz_check_for_expired_promotions', [ $this, 'check_for_expired_promotions' ] );
        add_action( 'routiz_reset_visits', [ $this, 'reset_visits' ] );
        add_action( 'routiz_check_for_pending_payment_bookings', [ $this, 'pending_payment_bookings' ] );
        add_action( 'routiz_scheduled_ical_sync', [ $this, 'routiz_scheduled_ical_sync' ] );
        // add_action( 'routiz_check_for_expired_bookings', [ $this, 'check_for_expired_bookings' ] );

        add_action( 'routiz/ical/sync', [ $this, 'ical_sync' ] );

        // on claim complete
        add_action( 'transition_post_status', [ $this, 'claim_complete' ], 10, 3 );
        add_action( 'transition_post_status', [ $this, 'entry_status_change' ], 10, 3 );
        add_action( 'save_post', [ $this, 'listing_status_change' ] );

        // on listing insert
        add_action( 'wp_insert_post', [ $this, 'insert_listing' ], 10, 3 );

        foreach([ 'post', 'post-new' ] as $hook ) {
			add_action( "admin_footer-{$hook}.php", [ $this, 'extend_submit_post_status' ] );
		}

    }

    function insert_listing( $post_id, $post, $update ) {

        if ( wp_is_post_revision( $post_id ) ) {
            return;
        }

        if ( $post->post_type !== 'rz_listing' ) {
            return;
        }

        // make sure the listing owns priority meta
        if( ! metadata_exists( 'post', $post_id, 'rz_priority' ) ) {
            add_post_meta( $post_id, 'rz_priority', 0 );
        }

    }

    function register_post_types() {

        $is_multisite = is_multisite();
        $capability_create = $is_multisite ? 'do_not_allow' : false;

        // post types
		$singular = esc_html__( 'Listing Type', 'routiz' );
		$plural = esc_html__( 'Listing Types', 'routiz' );

		$rewrite = [
			'slug' => 'listing-type',
			'with_front' => false,
			'feeds' => false,
			'pages' => false
		];

		register_post_type( 'rz_listing_type',
			apply_filters( 'routiz/post_type/listing_type', [
				'labels' => [
					'name' 					=> $plural,
					'singular_name' 		=> $singular,
					'menu_name'             => $plural,
					'all_items'             => sprintf( esc_html__( 'All %s', 'routiz' ), $plural ),
					'add_new' 				=> esc_html__( 'Add New', 'routiz' ),
					'add_new_item' 			=> sprintf( esc_html__( 'Add %s', 'routiz' ), $singular ),
					'edit' 					=> esc_html__( 'Edit', 'routiz' ),
					'edit_item' 			=> sprintf( esc_html__( 'Edit %s', 'routiz' ), $singular ),
					'new_item' 				=> sprintf( esc_html__( 'New %s', 'routiz' ), $singular ),
					'view' 					=> sprintf( esc_html__( 'View %s', 'routiz' ), $singular ),
					'view_item' 			=> sprintf( esc_html__( 'View %s', 'routiz' ), $singular ),
					'search_items' 			=> sprintf( esc_html__( 'Search %s', 'routiz' ), $plural ),
					'not_found' 			=> sprintf( esc_html__( 'No %s found', 'routiz' ), $plural ),
					'not_found_in_trash' 	=> sprintf( esc_html__( 'No %s found in trash', 'routiz' ), $plural ),
					'parent' 				=> sprintf( esc_html__( 'Parent %s', 'routiz' ), $singular ),
				],
                'taxonomies'            => [],
				'menu_icon'             => 'dashicons-admin-settings',
				// 'public' 				=> $has_archive,
				'public' 				=> false,
				'show_ui' 				=> true,
				// 'publicly_queryable' 	=> $has_archive,
				'publicly_queryable' 	=> false,
				'exclude_from_search' 	=> true,
				'hierarchical' 			=> false,
				'rewrite' 				=> $rewrite,
				'query_var' 			=> true,
				'supports' 				=> [ 'title', 'thumbnail' ],
				'has_archive' 			=> false,
				'show_in_nav_menus' 	=> true,
			])
		);

        $singular = esc_html__( 'Listing', 'routiz' );
		$plural = esc_html__( 'Listings', 'routiz' );

        $listing_slug = esc_attr( get_option('rz_listing_slug') );

		$rewrite = [
			'slug' => $listing_slug ? $listing_slug : 'listing',
			'with_front' => false,
			'feeds' => false,
			'pages' => false
		];

        $pending_approval = new \WP_Query([
            'post_type' => 'rz_listing',
            'post_status' => 'pending',
        ]);

		register_post_type( 'rz_listing',
			apply_filters( 'routiz/post_type/listing', [
				'labels' => [
					'name' 					=> $plural,
					'singular_name' 		=> $singular,
					'menu_name'             => $plural,
					'all_items'             => sprintf( esc_html__( 'All %s%s', 'routiz' ), $plural, $pending_approval->found_posts ? ' <span class="awaiting-mod">' . (int) $pending_approval->found_posts . '</span>' : '' ),
					'add_new' 				=> esc_html__( 'Add New', 'routiz' ),
					'add_new_item' 			=> sprintf( esc_html__( 'Add %s', 'routiz' ), $singular ),
					'edit' 					=> esc_html__( 'Edit', 'routiz' ),
					'edit_item' 			=> sprintf( esc_html__( 'Edit %s', 'routiz' ), $singular ),
					'new_item' 				=> sprintf( esc_html__( 'New %s', 'routiz' ), $singular ),
					'view' 					=> sprintf( esc_html__( 'View %s', 'routiz' ), $singular ),
					'view_item' 			=> sprintf( esc_html__( 'View %s', 'routiz' ), $singular ),
					'search_items' 			=> sprintf( esc_html__( 'Search %s', 'routiz' ), $plural ),
					'not_found' 			=> sprintf( esc_html__( 'No %s found', 'routiz' ), $plural ),
					'not_found_in_trash' 	=> sprintf( esc_html__( 'No %s found in trash', 'routiz' ), $plural ),
					'parent' 				=> sprintf( esc_html__( 'Parent %s', 'routiz' ), $singular ),
				],
                'taxonomies'            => [],
				'menu_icon'             => 'dashicons-location',
				'public' 				=> true,
				'show_ui' 				=> true,
				'publicly_queryable' 	=> true,
				'exclude_from_search' 	=> true,
				'hierarchical' 			=> false,
				'rewrite' 				=> $rewrite,
				'query_var' 			=> true,
				'supports' 				=> [ 'title', 'thumbnail', 'author', 'comments' ],
				'has_archive' 			=> true,
				'show_in_nav_menus' 	=> true,
			])
		);

        $singular = esc_html__( 'Search Form', 'routiz' );
		$plural = esc_html__( 'Search Forms', 'routiz' );

		$rewrite = [
			'slug' => 'search-forms',
			'with_front' => false,
			'feeds' => false,
			'pages' => false
		];

		register_post_type( 'rz_search_form',
			apply_filters( 'routiz/post_type/search_form', [
				'labels' => [
					'name' 					=> $plural,
					'singular_name' 		=> $singular,
					'menu_name'             => $plural,
					'all_items'             => sprintf( esc_html__( '%s', 'routiz' ), $plural ),
					'add_new' 				=> esc_html__( 'Add New', 'routiz' ),
					'add_new_item' 			=> sprintf( esc_html__( 'Add %s', 'routiz' ), $singular ),
					'edit' 					=> esc_html__( 'Edit', 'routiz' ),
					'edit_item' 			=> sprintf( esc_html__( 'Edit %s', 'routiz' ), $singular ),
					'new_item' 				=> sprintf( esc_html__( 'New %s', 'routiz' ), $singular ),
					'view' 					=> sprintf( esc_html__( 'View %s', 'routiz' ), $singular ),
					'view_item' 			=> sprintf( esc_html__( 'View %s', 'routiz' ), $singular ),
					'search_items' 			=> sprintf( esc_html__( 'Search %s', 'routiz' ), $plural ),
					'not_found' 			=> sprintf( esc_html__( 'No %s found', 'routiz' ), $plural ),
					'not_found_in_trash' 	=> sprintf( esc_html__( 'No %s found in trash', 'routiz' ), $plural ),
					'parent' 				=> sprintf( esc_html__( 'Parent %s', 'routiz' ), $singular ),
				],
                'taxonomies'            => [],
                'show_in_menu'          => 'edit.php?post_type=rz_listing_type',
                'capability_type'       => 'page',
				'public' 				=> false,
				'show_ui' 				=> true,
				'publicly_queryable' 	=> false,
				'exclude_from_search' 	=> true,
				'hierarchical' 			=> false,
				'rewrite' 				=> $rewrite,
				'query_var' 			=> true,
				'supports' 				=> [ 'title' ],
				'has_archive' 			=> false,
				'show_in_nav_menus' 	=> false,
			])
		);

        $singular = esc_html__( 'Entry', 'routiz' );
		$plural = esc_html__( 'Entries', 'routiz' );

		$rewrite = [
			'slug' => 'entry',
			'with_front' => false,
			'feeds' => false,
			'pages' => false
		];

		register_post_type( 'rz_entry',
			apply_filters( 'routiz/post_type/entry', [
				'labels' => [
					'name' 					=> $plural,
					'singular_name' 		=> $singular,
					'menu_name'             => $plural,
					'all_items'             => sprintf( esc_html__( '%s', 'routiz' ), $plural ),
					'add_new' 				=> esc_html__( 'Add New', 'routiz' ),
					'add_new_item' 			=> sprintf( esc_html__( 'Add %s', 'routiz' ), $singular ),
					'edit' 					=> esc_html__( 'Edit', 'routiz' ),
					'edit_item' 			=> sprintf( esc_html__( 'Edit %s', 'routiz' ), $singular ),
					'new_item' 				=> sprintf( esc_html__( 'New %s', 'routiz' ), $singular ),
					'view' 					=> sprintf( esc_html__( 'View %s', 'routiz' ), $singular ),
					'view_item' 			=> sprintf( esc_html__( 'View %s', 'routiz' ), $singular ),
					'search_items' 			=> sprintf( esc_html__( 'Search %s', 'routiz' ), $plural ),
					'not_found' 			=> sprintf( esc_html__( 'No %s found', 'routiz' ), $plural ),
					'not_found_in_trash' 	=> sprintf( esc_html__( 'No %s found in trash', 'routiz' ), $plural ),
					'parent' 				=> sprintf( esc_html__( 'Parent %s', 'routiz' ), $singular ),
				],
                'taxonomies'            => [],
                'show_in_menu'          => 'edit.php?post_type=rz_listing_type',
				'public' 				=> false,
				'show_ui' 				=> true,
				'publicly_queryable' 	=> false,
				'exclude_from_search' 	=> true,
				'hierarchical' 			=> false,
				'rewrite' 				=> $rewrite,
				'query_var' 			=> true,
				'supports' 				=> [ 'title', 'author' ],
				'has_archive' 			=> false,
				'show_in_nav_menus' 	=> true,

                'capability_type'       => 'post',
                'capabilities' => [
                    'create_posts' => $capability_create,
                ],
                'map_meta_cap'          => true,
			])
		);

        $singular = esc_html__( 'Report', 'routiz' );
		$plural = esc_html__( 'Reports', 'routiz' );

		$rewrite = [
			'slug' => 'report',
			'with_front' => false,
			'feeds' => false,
			'pages' => false
		];

		register_post_type( 'rz_report',
			apply_filters( 'routiz/post_type/report', [
				'labels' => [
					'name' 					=> $plural,
					'singular_name' 		=> $singular,
					'menu_name'             => $plural,
					'all_items'             => sprintf( esc_html__( '%s', 'routiz' ), $plural ),
					'add_new' 				=> esc_html__( 'Add New', 'routiz' ),
					'add_new_item' 			=> sprintf( esc_html__( 'Add %s', 'routiz' ), $singular ),
					'edit' 					=> esc_html__( 'Edit', 'routiz' ),
					'edit_item' 			=> sprintf( esc_html__( 'Edit %s', 'routiz' ), $singular ),
					'new_item' 				=> sprintf( esc_html__( 'New %s', 'routiz' ), $singular ),
					'view' 					=> sprintf( esc_html__( 'View %s', 'routiz' ), $singular ),
					'view_item' 			=> sprintf( esc_html__( 'View %s', 'routiz' ), $singular ),
					'search_items' 			=> sprintf( esc_html__( 'Search %s', 'routiz' ), $plural ),
					'not_found' 			=> sprintf( esc_html__( 'No %s found', 'routiz' ), $plural ),
					'not_found_in_trash' 	=> sprintf( esc_html__( 'No %s found in trash', 'routiz' ), $plural ),
					'parent' 				=> sprintf( esc_html__( 'Parent %s', 'routiz' ), $singular ),
				],
                'taxonomies'            => [],
                'show_in_menu'          => 'edit.php?post_type=rz_listing_type',
				'public' 				=> false,
				'show_ui' 				=> true,
				'publicly_queryable' 	=> false,
				'exclude_from_search' 	=> true,
				'hierarchical' 			=> false,
				'rewrite' 				=> $rewrite,
				'query_var' 			=> true,
				'supports' 				=> [ 'title' ],
				'has_archive' 			=> false,
				'show_in_nav_menus' 	=> true,

                'capability_type'       => 'post',
                'capabilities' => [
                    'create_posts' => $capability_create,
                ],
                'map_meta_cap'          => true,
			])
		);

        $singular = esc_html__( 'Conversation', 'routiz' );
		$plural = esc_html__( 'Conversations', 'routiz' );

		$rewrite = [
			'slug' => 'conversation',
			'with_front' => false,
			'feeds' => false,
			'pages' => false
		];

		register_post_type( 'rz_conversation',
			apply_filters( 'routiz/post_type/conversation', [
				'labels' => [
					'name' 					=> $plural,
					'singular_name' 		=> $singular,
					'menu_name'             => $plural,
					'all_items'             => sprintf( esc_html__( '%s', 'routiz' ), $plural ),
					'add_new' 				=> esc_html__( 'Add New', 'routiz' ),
					'add_new_item' 			=> sprintf( esc_html__( 'Add %s', 'routiz' ), $singular ),
					'edit' 					=> esc_html__( 'Edit', 'routiz' ),
					'edit_item' 			=> sprintf( esc_html__( 'Edit %s', 'routiz' ), $singular ),
					'new_item' 				=> sprintf( esc_html__( 'New %s', 'routiz' ), $singular ),
					'view' 					=> sprintf( esc_html__( 'View %s', 'routiz' ), $singular ),
					'view_item' 			=> sprintf( esc_html__( 'View %s', 'routiz' ), $singular ),
					'search_items' 			=> sprintf( esc_html__( 'Search %s', 'routiz' ), $plural ),
					'not_found' 			=> sprintf( esc_html__( 'No %s found', 'routiz' ), $plural ),
					'not_found_in_trash' 	=> sprintf( esc_html__( 'No %s found in trash', 'routiz' ), $plural ),
					'parent' 				=> sprintf( esc_html__( 'Parent %s', 'routiz' ), $singular ),
				],
                'taxonomies'            => [],
                'show_in_menu'          => 'edit.php?post_type=rz_listing_type',
				'public' 				=> false,
				'show_ui' 				=> true,
				'publicly_queryable' 	=> false,
				'exclude_from_search' 	=> true,
				'hierarchical' 			=> false,
				'rewrite' 				=> $rewrite,
				'query_var' 			=> true,
				'supports' 				=> [ 'title', 'author' ],
				'has_archive' 			=> false,
				'show_in_nav_menus' 	=> true,
                'capability_type'       => 'post',
                'capabilities' => [
                    'create_posts' => $capability_create,
                ],
                'map_meta_cap'          => true,
			])
		);

        $singular = esc_html__( 'Claim', 'routiz' );
		$plural = esc_html__( 'Claims', 'routiz' );

		$rewrite = [
			'slug' => 'claim',
			'with_front' => false,
			'feeds' => false,
			'pages' => false
		];

		register_post_type( 'rz_claim',
			apply_filters( 'routiz/post_type/claim', [
				'labels' => [
					'name' 					=> $plural,
					'singular_name' 		=> $singular,
					'menu_name'             => $plural,
					'all_items'             => sprintf( esc_html__( '%s', 'routiz' ), $plural ),
					'add_new' 				=> esc_html__( 'Add New', 'routiz' ),
					'add_new_item' 			=> sprintf( esc_html__( 'Add %s', 'routiz' ), $singular ),
					'edit' 					=> esc_html__( 'Edit', 'routiz' ),
					'edit_item' 			=> sprintf( esc_html__( 'Edit %s', 'routiz' ), $singular ),
					'new_item' 				=> sprintf( esc_html__( 'New %s', 'routiz' ), $singular ),
					'view' 					=> sprintf( esc_html__( 'View %s', 'routiz' ), $singular ),
					'view_item' 			=> sprintf( esc_html__( 'View %s', 'routiz' ), $singular ),
					'search_items' 			=> sprintf( esc_html__( 'Search %s', 'routiz' ), $plural ),
					'not_found' 			=> sprintf( esc_html__( 'No %s found', 'routiz' ), $plural ),
					'not_found_in_trash' 	=> sprintf( esc_html__( 'No %s found in trash', 'routiz' ), $plural ),
					'parent' 				=> sprintf( esc_html__( 'Parent %s', 'routiz' ), $singular ),
				],
                'taxonomies'            => [],
                'show_in_menu'          => 'edit.php?post_type=rz_listing_type',
				'public' 				=> false,
				'show_ui' 				=> true,
				'publicly_queryable' 	=> false,
				'exclude_from_search' 	=> true,
				'hierarchical' 			=> false,
				'rewrite' 				=> $rewrite,
				'query_var' 			=> true,
				'supports' 				=> [ 'title', 'author' ],
				'has_archive' 			=> false,
				'show_in_nav_menus' 	=> true,
                'capability_type'       => 'post',
                'capabilities' => [
                    'create_posts' => $capability_create,
                ],
                'map_meta_cap'          => true,
			])
		);

    }

    public function register_post_status() {

        register_post_status( 'pending_payment', [
			'label' => _x( 'Pending Payment', 'post status', 'routiz' ),
			'public' => true,
			'exclude_from_search' => false,
			'show_in_admin_all_list' => true,
			'show_in_admin_status_list' => true,
			'label_count' => _n_noop( 'Pending Payment <span class="count">(%s)</span>', 'Pending Payment <span class="count">(%s)</span>', 'routiz' ),
		]);

        register_post_status( 'expired', [
			'label' => _x( 'Expired', 'post status', 'routiz' ),
			'public' => true,
			// 'protected' => true,
			'exclude_from_search' => false,
			'show_in_admin_all_list' => true,
			'show_in_admin_status_list' => true,
			'label_count' => _n_noop( 'Expired <span class="count">(%s)</span>', 'Expired <span class="count">(%s)</span>', 'routiz' ),
		]);

        register_post_status( 'declined', [
			'label' => _x( 'Declined', 'post status', 'routiz' ),
			'public' => true,
			// 'protected' => true,
			'exclude_from_search' => false,
			'show_in_admin_all_list' => true,
			'show_in_admin_status_list' => true,
			'label_count' => _n_noop( 'Declined <span class="count">(%s)</span>', 'Declined <span class="count">(%s)</span>', 'routiz' ),
		]);

        register_post_status( 'cancelled', [
			'label' => _x( 'Cancelled', 'post status', 'routiz' ),
			'public' => true,
			// 'protected' => true,
			'exclude_from_search' => false,
			'show_in_admin_all_list' => true,
			'show_in_admin_status_list' => true,
			'label_count' => _n_noop( 'Cancelled <span class="count">(%s)</span>', 'Cancelled <span class="count">(%s)</span>', 'routiz' ),
		]);

		register_post_status( 'pending_listing', [
            'label' => _x( 'pending listing', 'post status', 'routiz' ),
            'public' => true,
            'exclude_from_search' => false,
            'show_in_admin_all_list' => true,
            'show_in_admin_status_list' => true,
            'label_count' => _n_noop( 'Pending listing <span class="count">(%s)</span>', 'Pending Listing <span class="count">(%s)</span>', 'routiz' ),
        ]);


    }

    public function approve_listing() {

        if (
            isset( $_GET['approve_listing'] ) &&
            wp_verify_nonce( $_REQUEST['_wpnonce'], 'approve_listing' ) &&
            current_user_can( 'publish_post', $_GET['approve_listing'] )
        ) {

			$post_id = (int) $_GET['approve_listing'];
			$data = [
				'ID' => $post_id,
				'post_status' => 'publish',
			];

            // notify
			routiz()->notify->distribute( 'listing-approved', [
				'user_id' => get_post_field( 'post_author', $post_id ),
				'meta' => [
                    'listing_id' => $post_id,
                ],
			]);

            wp_update_post( $data );
            wp_redirect( admin_url( 'edit.php?post_type=rz_listing' ) );
            exit;

		}

	}

    public function approve_claim() {

        if (
            isset( $_GET['approve_claim'] ) &&
            wp_verify_nonce( $_REQUEST['_wpnonce'], 'approve_claim' ) &&
            current_user_can( 'publish_post', $_GET['approve_claim'] )
        ) {

			$post_id = (int) $_GET['approve_claim'];
			$data = [
				'ID' => $post_id,
				'post_status' => 'publish',
			];

            wp_update_post( $data );
            wp_redirect( admin_url( 'edit.php?post_type=rz_claim' ) );
            exit;

		}

	}

    public function extend_submit_post_status() {

        global $post, $post_type;

		if ( $post_type !== 'rz_listing' ) {
			return;
		}

		$options = $display = '';
		foreach( Rz()->get_listing_statuses() as $status => $name ) {
			$selected = selected( $post->post_status, $status, false );
			if ( $selected ) {
				$display = $name;
			}
			$options .= "<option{$selected} value='{$status}'>" . esc_html( $name ) . '</option>';
		}

		?>
    		<script type="text/javascript">
    			jQuery( document ).ready( function($) {
    				<?php if ( ! empty( $display ) ) : ?>
    					jQuery( '#post-status-display' ).html( decodeURIComponent( '<?php echo rawurlencode( (string) wp_specialchars_decode( $display ) ); ?>' ) );
    				<?php endif; ?>
    				var select = jQuery( '#post-status-select' ).find( 'select' );
    				jQuery( select ).html( decodeURIComponent( '<?php echo rawurlencode( (string) wp_specialchars_decode( $options ) ); ?>' ) );
    			} );
    		</script>
		<?php

	}

	public function check_for_expired_listings() {

        $listing_ids = get_posts([
			'post_type' => 'rz_listing',
			'post_status' => 'publish',
			'fields' => 'ids',
			'posts_per_page' => -1,
			'meta_query' => [
				'relation' => 'AND',
				[
					'key' => 'rz_listing_expires',
					'value' => 0,
					'compare' => '>',
				],
				[
					'key' => 'rz_listing_expires',
					'value' => date( 'Y-m-d H:i', current_time( 'timestamp' ) ),
					'compare' => '<',
				],
			],
		]);

		if( $listing_ids ) {
			foreach( $listing_ids as $listing_id ) {

                wp_update_post([
                    'ID' => $listing_id,
                    'post_status' => 'expired',
                ]);

                delete_post_meta( $listing_id, 'rz_listing_expires' );

                /*
                 * send notification
                 *
                 */
                routiz()->notify->distribute( 'listing-expired', [
                    'user_id' => get_post_field( 'post_author', $listing_id ),
                    'meta' => [
                        'listing_id' => $listing_id
                    ],
                ]);

			}
		}

	}

	public function pending_payment_bookings() {

        $now = current_time('U');
        $expiration_time = 24; // hours
        if( floatval( $expiration_time_option = get_option('rz_days_booking_pending_payment') ) ) {
            $expiration_time = $expiration_time_option;
        }

        $entries = get_posts([
			'post_type' => 'rz_entry',
			'post_status' => [ 'pending_payment' ],
			'fields' => 'ids',
			'posts_per_page' => -1,
		]);

		if( $entries ) {
			foreach( $entries as $entry_id ) {

                $modified = get_the_modified_date( 'U', $entry_id );
                $time_passed = $now - $modified;

                // has expired
                if( $time_passed > $expiration_time * 3600 ) {

                    $success = wp_update_post([
                        'ID' => $entry_id,
                        'post_status' => 'declined'
                    ]);

                    /*
                     * send notification
                     *
                     */
                    if( $success ) {
                        if( $request_user_id = Rz()->get_meta( 'rz_request_user_id', $entry_id ) ) {
                            routiz()->notify->distribute( 'entry-declined', [
                                'user_id' => $request_user_id,
                                'meta' => [
                                    'entry_id' => $entry_id,
                                    'listing_id' => (int) Rz()->get_meta( 'rz_listing', $entry_id ),
                                ],
                            ]);
                        }
                    }
                }
			}
		}
	}

	public function check_for_expired_promotions() {

        $listing_ids = get_posts([
			'post_type' => 'rz_listing',
			'post_status' => 'publish',
			'fields' => 'ids',
			'posts_per_page' => -1,
			'meta_query' => [
				'relation' => 'AND',
				[
					'key' => 'rz_promotion_expires',
					'value' => 0,
					'compare' => '>',
				],
				[
					'key' => 'rz_promotion_expires',
					'value' => current_time( 'timestamp' ),
					'compare' => '<',
				],
			],
		]);

		if( $listing_ids ) {
			foreach( $listing_ids as $listing_id ) {

                $old_priority = (int) get_post_meta( $listing_id, 'rz_old_priority', true );

                delete_post_meta( $listing_id, 'rz_promotion_expires' );
                delete_post_meta( $listing_id, 'rz_old_priority' );
                delete_post_meta( $listing_id, 'rz_priority_custom' );

                update_post_meta( $listing_id, 'rz_priority', $old_priority );
                switch( $old_priority ) {
                    case 0: update_post_meta( $listing_id, 'rz_priority_selection', 'normal' ); break;
                    case 1: update_post_meta( $listing_id, 'rz_priority_selection', 'featured' ); break;
                    case 2: update_post_meta( $listing_id, 'rz_priority_selection', 'promoted' ); break;
                    default:
                        update_post_meta( $listing_id, 'rz_priority_selection', 'custom' );
                        update_post_meta( $listing_id, 'rz_priority_custom', $old_priority );
                }

                /*
                 * send notification
                 *
                 */
                routiz()->notify->distribute( 'promotion-expired', [
                    'user_id' => get_post_field( 'post_author', $listing_id ),
                    'meta' => [
                        'listing_id' => $listing_id
                    ],
                ]);

			}
		}
	}

    public function reset_visits() {

        global $wpdb;

        $table = $wpdb->prefix . 'routiz_visits';
        $delete = $wpdb->query("TRUNCATE TABLE $table");

    }

    // change claim listing author on status complete
    public function claim_complete( $new_status, $old_status, $post ) {

        if( ! current_user_can('manage_options') ) {
            return;
        }

        if( $post->post_type == 'rz_claim' and $post->post_status == 'publish' ) {

            // update listing owner
            if( $listing_id = Brk()->get_meta( 'rz_listing', $post->ID ) ) {

                wp_update_post([
                    'ID' => $listing_id,
                    'post_author' => $post->post_author,
                ]);

                // mark as claimed
                update_post_meta( $listing_id, 'rz_is_claimed', true );

                /*
                 * send notification
                 *
                 */
                routiz()->notify->distribute( 'claim-approved', [
                    'user_id' => $post->post_author,
                    'meta' => [
                        'listing_id' => $listing_id
                    ],
                ]);

            }

        }

    }

    public function entry_status_change( $new_status, $old_status, $post ) {

        if( $post->post_type == 'rz_entry' ) {

            $entry_type = Rz()->get_meta( 'rz_entry_type', $post->ID );
            $listing = new Listing( Rz()->get_meta( 'rz_listing', $post->ID ) );

            if( $entry_type == 'booking' ) {

                if( $new_status == $old_status ) {
                    return;
                }

                if( ! $listing->id ) {
                    return;
                }

                $checkin_date = Rz()->get_meta( 'rz_checkin_date', $post->ID );
                $checkout_date = Rz()->get_meta( 'rz_checkout_date', $post->ID );
                $dates = $listing->booking->get_dates_between_range( $checkin_date, $checkout_date );

                if( ! $dates ) {
                    return;
                }

                switch( true ) {

                    case $new_status == 'publish':
                        $listing->booking->add_dates( 'booked', $dates );
                        $listing->booking->release_dates( 'pending', $dates );
                        $listing->booking->set_unavailable( $dates );
                        break;

                    case $new_status == 'pending':
                        $listing->booking->add_dates( 'pending', $dates );
                        $listing->booking->release_dates( 'booked', $dates );
                        $listing->booking->set_unavailable( $dates );
                        break;

                    case $new_status == 'pending_payment':
                        $listing->booking->add_dates( 'pending', $dates );
                        $listing->booking->release_dates( 'booked', $dates );
                        $listing->booking->set_unavailable( $dates );
                        break;

                    case $new_status == 'declined':
                        $listing->booking->release_dates( 'pending', $dates );
                        $listing->booking->release_dates( 'booked', $dates );
                        $listing->booking->set_available( $dates );
                        break;

                    case $new_status == 'trash':
                        $listing->booking->release_dates( 'pending', $dates );
                        $listing->booking->release_dates( 'booked', $dates );
                        $listing->booking->set_available( $dates );
                        break;
                }

            }elseif( $entry_type == 'booking_appointments' ) {

                $appt_id = get_post_meta( $post->ID, 'rz_appt_id', true );
                $appt_booked = (int) get_post_meta( $listing->id, $appt_id, true );
                $appt_guests = (int) get_post_meta( $post->ID, 'rz_guests', true );

                if( $new_status == 'publish' and $old_status !== 'publish' ) {
                    update_post_meta( $listing->id, $appt_id, $appt_booked + $appt_guests );
                }elseif( $new_status !== 'publish' and $old_status == 'publish' ) {
                    update_post_meta( $listing->id, $appt_id, $appt_booked - $appt_guests );
                }

            }
        }

    }

    public function listing_status_change( $post_id ) {

        if( get_post_type( $post_id ) == 'rz_listing' ) {

            $listing = new \Routiz\Inc\Src\Listing\Listing( $post_id );
            $image = $gallery_attrs = Rz()->jsoning( 'rz_gallery', $listing->id );

            if( ! $listing->type->get('rz_gallery_as_featured') ) {
                return;
            }

            if( ! $listing->id ) {
                return;
            }

            if( isset( $image[0] ) and isset( $image[0]->id ) ) {
                set_post_thumbnail( $listing->id, $image[0]->id );
            }

        }

    }

    public function ical_sync( $listing_id ) {

        $ical = new iCal( $listing_id );
        $ical->sync();

    }

    public function routiz_scheduled_ical_sync() {

        if( apply_filters( 'routiz/scheduled/ical/sync', true ) ) {

            $args = array(
                'post_type' => 'rz_listing',
                'post_status' => 'publish',
                'posts_per_page' => -1
            );

            $post_query = new \WP_Query($args);

            if( $post_query->have_posts() ) {
                while( $post_query->have_posts() ) { $post_query->the_post();

                    $listing = new Listing( get_the_ID() );

                    if( $listing->id ) {

                        $action = $listing->type->get_action();

                        // only listing with booking action type
                        if( $action->has('booking') ) {

                            $ical = new iCal( $listing->id );
                            $ical->sync();

                        }
                    }

                }
            }

            wp_reset_postdata();

        }
    }

}
