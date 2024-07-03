<?php

namespace Routiz\Inc\Src\Admin\Columns;

use \Routiz\Inc\Src\Traits\Singleton;

class Report {

    use Singleton;

    function __construct() {

        add_filter( 'manage_edit-rz_report_columns', [ $this, 'columns' ] );
        add_action( 'manage_rz_report_posts_custom_column', [ $this, 'custom_columns' ], 10, 2 );
        add_filter( 'manage_edit-rz_report_sortable_columns', [ $this, 'sortable_columns' ] );

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
        // $columns['rz_owner_image'] = '&nbsp;';
        // $columns['rz_owner'] = esc_html__( 'Owner', 'routiz' );
        $columns['rz_reporter_image'] = '&nbsp;';
        $columns['rz_reporter'] = esc_html__( 'Reporter', 'routiz' );
        $columns['rz_image'] = '&nbsp;';
        $columns['rz_listing'] = esc_html__( 'Listing', 'routiz' );
        $columns['rz_posted'] = esc_html__( 'Posted', 'routiz' );
        $columns['rz_actions'] = esc_html__( 'Actions', 'routiz' );

        return $columns;

    }

    public function custom_columns( $column, $post_id ) {

        global $post;

        switch ( $column ) {

            case 'rz_image':

                echo '<div class="rz-column-image">';
                echo Rz()->dummy('far fa-bell');
                echo '</div>';

                break;

            /*case 'rz_owner_image':

                echo '<div class="rz-column-image">';
                echo Rz()->dummy('fas fa-user-tie');
                echo '</div>';

                break;

            case 'rz_owner':

                echo '<a href="' . get_edit_user_link( $post->post_author ) . '">' . get_the_author_meta( 'display_name', $post->post_author ) . '</a>';

                break;*/

            case 'rz_reporter_image':

                echo '<div class="rz-column-image">';
                echo Rz()->dummy('fas fa-user');
                echo '</div>';

                break;

            case 'rz_reporter':

                $reporter = get_userdata( $post->post_author );

                echo '<a href="' . get_edit_user_link( $post->post_author ) . '">' . $reporter->display_name . '</a>';

                break;

            case 'rz_image':

                $listing_id = Rz()->get_meta( 'rz_listing_id', $post->ID );

                $listing = new \Routiz\Inc\Src\Listing\Listing( $listing_id );
                $image = $listing->get_first_from_gallery( 'thumbnail' );

                echo '<div class="rz-column-image">';
                if( $image ) {
                    echo '<img src="' . esc_url( $image ) . '" alt="">';
                }else{
                    echo Rz()->dummy();
                }
                echo '</div>';

                break;

            case 'rz_listing':

                $listing_id = Rz()->get_meta( 'rz_listing_id', $post->ID );

                echo '<a href="' . get_edit_post_link( $listing_id ) . '">' . get_the_title( $listing_id ) . '</a>';

                break;

            case 'rz_title':

                echo '<div class="rz-edit-title">';
                    echo '<a href="' . esc_url( admin_url( 'post.php?post=' . $post_id . '&action=edit' ) ) . '" class="tips" data-tip="' . sprintf( esc_html__( 'ID: %d', 'routiz' ), intval( $post_id ) ) . '">' . get_the_title() . '</a>';
                echo '</div>';

                break;

            case 'rz_posted':

                echo '<div class="rz-posted">';
                echo '<strong>' . esc_html( date_i18n( get_option( 'date_format' ), strtotime( $post->post_date ) ) ) . '</strong><span>';
                echo ( empty( $post->post_author ) ? esc_html__( 'by a guest', 'routiz' ) : sprintf( esc_html__( 'by %s', 'routiz' ), '<a href="' . esc_url( add_query_arg( 'author', $post->post_author ) ) . '">' . esc_html( get_the_author() ) . '</a>' ) ) . '</span>';
                echo '</div>';
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

}
