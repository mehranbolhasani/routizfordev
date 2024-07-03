<?php

namespace Routiz\Inc\Src\Admin\Columns;

use \Routiz\Inc\Src\Traits\Singleton;

class Listing {

    use Singleton;

    function __construct() {

        add_filter( 'manage_edit-rz_listing_columns', [ $this, 'columns' ] );
        add_action( 'manage_rz_listing_posts_custom_column', [ $this, 'custom_columns' ], 10, 2 );
        add_filter( 'manage_edit-rz_listing_sortable_columns', [ $this, 'sortable_columns' ] );
        add_filter( 'request', [ $this, 'sort_columns' ] );

    }

    public function columns( $columns ) {

        if ( ! is_array( $columns ) ) {
            $columns = [];
        }

        unset(
            $columns['title'],
            $columns['date'],
            $columns['author'],
            $columns['comments']
        );

        $columns['rz_image'] = '&nbsp;';
        $columns['rz_title'] = esc_html__( 'Title', 'routiz' );
        $columns['rz_status'] = esc_html__( 'Status', 'routiz' );
        $columns['rz_listing_category'] = esc_html__( 'Categories', 'routiz' );
        $columns['rz_listing_region'] = esc_html__( 'Region', 'routiz' );
        $columns['rz_listing_tag'] = esc_html__( 'Tags', 'routiz' );
        $columns['rz_priority'] = esc_html__( 'Priority', 'routiz' );
        $columns['rz_review'] = esc_html__( 'Review', 'routiz' );
        $columns['rz_posted'] = esc_html__( 'Posted', 'routiz' );
        $columns['rz_expires'] = esc_html__( 'Expires', 'routiz' );
        $columns['rz_actions'] = esc_html__( 'Actions', 'routiz' );

        return $columns;

    }

    public function custom_columns( $column, $post_id ) {

        global $post;

        $listing = new \Routiz\Inc\Src\Listing\Listing( $post_id );

        switch ( $column ) {

            case 'rz_image':

                $image = $listing->get_first_from_gallery( 'thumbnail' );

                echo '<div class="rz-column-image">';
                if( $image ) {
                    echo '<img src="' . esc_url( $image ) . '" alt="">';
                }else{
                    echo Rz()->dummy();
                }
                echo '</div>';

                break;

            case 'rz_title':

                $listing_type_id = get_post_meta( $post_id, 'rz_listing_type', true );

                if( $listing_type_id ) {
                    echo '<div class="rz-edit-title">';
                        echo '<a href="' . esc_url( admin_url( 'post.php?post=' . $post_id . '&action=edit' ) ) . '" class="tips" data-tip="' . sprintf( esc_html__( 'ID: %d', 'routiz' ), intval( $post_id ) ) . '">' . esc_html( $post->post_title ) . '</a>';
                        echo '<div class="rz-edit-type"><a href="' . get_edit_post_link( $listing_type_id ) . '">' . esc_attr( get_post_meta( $listing_type_id, 'rz_name_plural', true ) ) . '</a></div>';
                    echo '</div>';
                }

                break;

            case 'rz_listing_region':

                $term_ids = get_post_meta( $post_id, 'rz_listing_region' );
                $html = [];
                foreach( $term_ids as $term_id ) {
                    $term = get_term_by( 'id', $term_id, 'rz_listing_region' );
                    if( $term ) {
                        $html[] = '<a href="' . get_edit_term_link( $term ) . '">' . $term->name . '</a>';
                    }
                }
                echo implode( ', ', $html );

                break;

            case 'rz_listing_category':

                $term_ids = array_filter( get_post_meta( $post_id, 'rz_listing_category' ) );

                $html = [];
                foreach( $term_ids as $term_id ) {
                    $term = get_term_by( 'id', $term_id, 'rz_listing_category' );
                    if( $term ) {
                        $html[] = '<a href="' . get_edit_term_link( $term ) . '">' . $term->name . '</a>';
                    }
                }
                echo implode( ', ', $html );

                break;

            case 'rz_listing_tag':

                $term_ids = get_post_meta( $post_id, 'rz_listing_tag' );
                $html = [];
                foreach( $term_ids as $term_id ) {
                    $term = get_term_by( 'id', $term_id, 'rz_listing_tag' );
                    if( $term ) {
                        $html[] = '<a href="' . get_edit_term_link( $term ) . '">' . $term->name . '</a>';
                    }
                }
                echo implode( ', ', $html );

                break;

            case 'rz_priority':

                $priority = get_post_meta( $post_id, 'rz_priority', true );

                echo $priority ? $priority : 0;

                break;

            case 'rz_review':

                if( $listing->reviews->average ) {
                    echo '<span class="rz-column-review-average">';
                    echo '<strong><i class="fas fa-star"></i>' . number_format( $listing->reviews->average, 2 ) . '</strong>';
                    echo '</span>';
                }
                break;

            case 'rz_posted':

                echo '<div class="rz-posted">';
                echo '<strong>' . esc_html( date_i18n( get_option( 'date_format' ), strtotime( $post->post_date ) ) ) . '</strong><span>';
                echo ( empty( $post->post_author ) ? esc_html__( 'by a guest', 'routiz' ) : sprintf( esc_html__( 'by %s', 'routiz' ), '<a href="' . esc_url( add_query_arg( 'author', $post->post_author ) ) . '">' . esc_html( get_the_author() ) . '</a>' ) ) . '</span>';
                echo '</div>';
                break;

            case 'rz_expires':

                $expires = Rz()->get_meta('rz_listing_expires');

                if ( $expires ) {
                    $datetime = new \DateTime();
                    $datetime->setTimezone( new \DateTimeZone('GMT') );
                    $datetime->setTimestamp( strtotime( $expires ) );
                    $datetime->setTimezone( new \DateTimeZone( wp_timezone_string() ) );
                    echo '<strong>' . $datetime->format( get_option( 'date_format' ) ) . '</strong>';
                } else {
                    echo '&ndash;';
                }
                break;

            case 'rz_status':

                echo '<div class="rz-post-status rz-status-' . $post->post_status . '"><span>' . Rz()->get_status( $post ) . '</span></div>';
                break;

            case 'rz_actions':

                echo '<div class="rz-actions">';
                $admin_actions = apply_filters( 'admin_edit_listing_actions', [], $post );

                if ( in_array( $post->post_status, [ 'pending', 'pending_payment' ], true ) && current_user_can( 'publish_post', $post_id ) ) {
                    $admin_actions['approve'] = [
                        'action' => 'approve',
                        'name'   => __( 'Approve', 'routiz' ),
                        'url'    => wp_nonce_url( add_query_arg( 'approve_listing', $post_id ), 'approve_listing' ),
                        'icon'    => 'fas fa-check',
                    ];
                }
                if ( 'trash' !== $post->post_status ) {
                    if ( current_user_can( 'read_post', $post_id ) ) {
                        $admin_actions['view'] = [
                            'action' => 'view',
                            'name'   => __( 'View', 'routiz' ),
                            'url'    => get_permalink( $post_id ),
                            'icon'    => 'fas fa-eye',
                        ];
                    }
                    if ( current_user_can( 'edit_post', $post_id ) ) {
                        $admin_actions['edit'] = [
                            'action' => 'edit',
                            'name'   => __( 'Edit', 'routiz' ),
                            'url'    => get_edit_post_link( $post_id ),
                            'icon'    => 'fas fa-pen',
                        ];
                    }
                    if ( current_user_can( 'delete_post', $post_id ) ) {
                        $admin_actions['delete'] = [
                            'action' => 'delete',
                            'name'   => __( 'Delete', 'routiz' ),
                            'url'    => get_delete_post_link( $post_id ),
                            'icon'    => 'fas fa-trash-alt',
                        ];
                    }
                }

                $admin_actions = apply_filters( 'rz_admin_actions', $admin_actions, $post );

                foreach ( $admin_actions as $action ) {
                    if ( is_array( $action ) ) {
                        printf( '<a class="button button-icon tips icon-%1$s" href="%2$s" data-tip="%3$s"><i class="%4$s"></i></a>', esc_attr( $action['action'] ), esc_url( $action['url'] ), esc_attr( $action['name'] ), esc_html( $action['icon'] ) );
                    }else{
                        echo wp_kses_post( str_replace( 'class="', 'class="button ', $action ) );
                    }
                }

                echo '</div>';

                break;

        }
    }

    public function sortable_columns( $columns ) {

        $custom = [
            'rz_posted'                     => 'date',
            'rz_title'                      => 'title',
            'taxonomy-rz_listing_category'  => 'title',
            'taxonomy-rz_listing_region'    => 'title',
            'taxonomy-rz_listing_tag'       => 'title',
            'rz_location'                   => 'rz_location',
            'rz_expires'                    => 'rz_expires',
        ];

        return wp_parse_args( $custom, $columns );

    }

    public function sort_columns( $vars ) {

        if ( isset( $vars['orderby'] ) ) {
            if ( 'rz_expires' === $vars['orderby'] ) {
                $vars = array_merge(
                    $vars,
                    [
                        'meta_key' => 'rz_listing_expires',
                        'orderby'  => 'meta_value',
                    ]
                );
            }elseif( 'rz_location' === $vars['orderby'] ) {
                $vars = array_merge(
                    $vars,
                    [
                        'meta_key' => '_rz_location',
                        'orderby'  => 'meta_value',
                    ]
                );
            }
        }

        return $vars;

    }

}
