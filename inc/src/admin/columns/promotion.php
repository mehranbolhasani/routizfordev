<?php

namespace Routiz\Inc\Src\Admin\Columns;

use \Routiz\Inc\Src\Traits\Singleton;

class Promotion {

    use Singleton;

    function __construct() {

        add_filter( 'manage_edit-rz_promotion_columns', [ $this, 'columns' ] );
        add_action( 'manage_rz_promotion_posts_custom_column', [ $this, 'custom_columns' ], 10, 2 );
        add_filter( 'manage_edit-rz_promotion_sortable_columns', [ $this, 'sortable_columns' ] );

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
        $columns['rz_owner_image'] = '&nbsp;';
        $columns['rz_owner'] = esc_html__( 'Owner', 'routiz' );
        $columns['rz_listing'] = esc_html__( 'Listing', 'routiz' );
        $columns['rz_duration'] = esc_html__( 'Promoted Until', 'routiz' );
        $columns['rz_priority'] = esc_html__( 'Priority', 'routiz' );
        $columns['rz_product'] = esc_html__( 'Product', 'routiz' );
        $columns['rz_order'] = esc_html__( 'Order', 'routiz' );
        $columns['rz_posted'] = esc_html__( 'Posted', 'routiz' );
        $columns['rz_actions'] = esc_html__( 'Actions', 'routiz' );

        return $columns;

    }

    public function custom_columns( $column, $post_id ) {

        global $post;

        switch ( $column ) {

            case 'rz_image':

                echo '<div class="rz-column-image">';
                echo Rz()->dummy('fas fa-toolbox');
                echo '</div>';

                break;

            case 'rz_owner_image':

                echo '<div class="rz-column-image">';
                echo Rz()->dummy('fas fa-user-tie');
                echo '</div>';

                break;

            case 'rz_owner':

                echo '<a href="' . get_edit_user_link( $post->post_author ) . '">' . get_the_author_meta( 'display_name', $post->post_author ) . '</a>';

                break;

            case 'rz_listing':

                $listing_id = Rz()->get_meta( 'rz_attached', $post->ID );
                echo '<a href="' . get_edit_post_link( $listing_id ) . '">' . get_the_title( $listing_id ) . '</a>';

                break;

            case 'rz_usage':

                $limit = Rz()->get_meta('rz_limit');
                echo sprintf( esc_html__( '%s posted of %s', 'routiz' ), Rz()->get_meta('rz_count'), $limit == 0 ? esc_html__( 'unlimited', 'routiz' ) : $limit );
                break;

            case 'rz_duration':

                $expires = Rz()->get_meta( 'rz_expires' );
                echo $expires ? date_i18n( get_option('date_format') . ' ' . get_option('time_format'), $expires ) : '&ndash;';

                /*$duration = Rz()->get_meta( 'rz_duration' );
                if( $duration > 0 ) {
                    echo sprintf( esc_html__( '%s days', 'routiz' ), $duration );
                }else{
                    esc_html_e( 'Never expires', 'routiz' );
                }*/

                break;

            case 'rz_priority':

                $priority = Rz()->get_meta( 'rz_priority' );
                echo $priority ? $priority : 0;

                break;

            case 'rz_product':

                echo '<a href="' . esc_url( get_edit_post_link( Rz()->get_meta( 'rz_product_id' ) ) ) . '">' . get_the_title( Rz()->get_meta( 'rz_product_id' ) ) . '</a>';

                break;

            case 'rz_order':

                $order_id = Rz()->get_meta( 'rz_order_id' );
                if( $order_id ) {
                    echo '<a href="' . esc_url( get_edit_post_link( Rz()->get_meta( 'rz_order_id' ) ) ) . '">#' . esc_html( $order_id ) . '</a>';
                }else{
                    echo '&ndash;';
                }

                break;

            case 'rz_title':

                $plan_title = get_the_title();
                // $plan_type = 'Some';

                echo '<div class="rz-edit-title">';
                    echo '<a href="' . esc_url( admin_url( 'post.php?post=' . $post_id . '&action=edit' ) ) . '" class="tips" data-tip="' . sprintf( esc_html__( 'ID: %d', 'routiz' ), intval( $post_id ) ) . '">' . esc_html( $plan_title ) . ' - ' . get_the_title( Rz()->get_meta( 'rz_product_id' ) ) . '</a>';
                    // echo '<div class="rz-edit-type"><span>' . esc_attr( $plan_type ) . '</span></div>';
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
