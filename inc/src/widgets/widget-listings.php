<?php

class Widget_Listings extends WP_Widget {

    function __construct() {

        parent::__construct(
            'routiz-widget-listings',
            'Routiz Listings'
        );

        add_action( 'widgets_init', function() {
            register_widget( 'Widget_Listings' );
        });

    }

    public $args = [
        'before_title'  => '<h4 class="rz--title">',
        'after_title'   => '</h4>',
        'before_widget' => '<div class="rz-widget-wrap">',
        'after_widget'  => '</div>'
    ];

    public function widget( $args, $instance ) {

        echo $args['before_widget'];

        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        global $wpdb, $rz_widget_listing_post_ids;

        if( ! is_array( $rz_widget_listing_post_ids ) ) {
            $rz_widget_listing_post_ids = [];
        }

        /*
         * get listing posts
         *
         */

        if( $instance['rz_sorting'] == 'top-rated' ) {

            $listings = $wpdb->get_results(
                    $wpdb->prepare("
                        SELECT *
                        FROM {$wpdb->posts}
                        WHERE post_type = 'rz_listing'
                        AND post_status = 'publish'
                        AND ID IN (
                            SELECT DISTINCT comment_post_ID
                            FROM {$wpdb->comments}
                        )
                        ORDER BY ID DESC
                        LIMIT %d
                    ", (int) $instance['rz_num_listings'])
                );

        }elseif( $instance['rz_sorting'] == 'favorites' ) {

            $listings = $wpdb->get_results(
                $wpdb->prepare("
                    SELECT *, SUM( count ) as total_views
                    FROM {$wpdb->posts}
                        LEFT JOIN {$wpdb->prefix}routiz_views
                        ON {$wpdb->posts}.ID = {$wpdb->prefix}routiz_views.listing_id
                    WHERE {$wpdb->posts}.post_type = 'rz_listing'
                    AND {$wpdb->prefix}routiz_views.datetime > DATE_SUB( NOW(), INTERVAL 7 DAY )
                    GROUP BY {$wpdb->posts}.ID
                    ORDER BY total_views DESC
                    LIMIT %d
                ", (int) $instance['rz_num_listings'] )
            );

        }else{

            $meta_query = [];
            if( $instance['rz_listing_type'] ) {
                $meta_query = [
                    'relation' => 'AND',
                    [
                        'key' => 'rz_listing_type',
                        'value' => (int) $instance['rz_listing_type'],
                        'compare' => '=',
                    ]
                ];
            }

            $query_args = [
                'post_type' => 'rz_listing',
                'post_status' => 'publish',
                'posts_per_page' => (int) $instance['rz_num_listings'],
                'meta_query' => $meta_query,
                'post__not_in' => $rz_widget_listing_post_ids
            ];

            $query_args['meta_query'] = $meta_query;
            $listings = get_posts( $query_args );

        }

        ?>

        <div class="rz-widget-listings">
            <?php if( $listings ): ?>
                <ul>
                    <?php foreach( $listings as $post ): ?>
                        <?php $rz_widget_listing_post_ids[] = $post->ID; ?>
                        <?php $listing = new \Routiz\Inc\Src\Listing\Listing( $post->ID ); ?>
                        <li>
                            <a href="<?php echo get_permalink( $post->ID ); ?>">
                                <?php $image = $listing->get_first_from_gallery( 'thumbnail' ); ?>
                                <?php if( $image ): ?>
                                    <div class="rz--image">
                                        <img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_html( get_the_title( $post->ID ) ); ?>">
                                    </div>
                                <?php endif; ?>
                                <div class="rz--content">

                                    <p class="rz--name"><?php echo get_the_title( $post->ID ); ?></p>

                                    <?php
                                        if( $instance['rz_sorting'] == 'top-rated' ) {

                                            if( $listing->reviews->average ): ?>
                                                <div class="rz--review">
                                                    <i class="fas fa-star"></i>
                                                    <span><?php echo number_format( $listing->reviews->average, 2 ); ?></span>
                                                </div>
                                            <?php endif;

                                        }else{

                                            $geo_city = $listing->get('rz_location__geo_city');

                                            if( empty( $geo_city ) ) {
                                                $geo_city = $listing->get('rz_location__geo_city_alt');
                                            }

                                            if( $geo_city ): ?>
                                                <div class="rz--geo">
                                                    <?php echo esc_html( $geo_city ); ?>
                                                </div>
                                            <?php endif;

                                        }
                                    ?>
                                </div>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p><?php esc_html_e( 'No results were found.', 'routiz' ) ?></p>
            <?php endif; ?>
        </div>

        <?php

        echo $args['after_widget'];

    }

    public function form( $instance ) {

        $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
        $listing_type = ! empty( $instance['rz_listing_type'] ) ? $instance['rz_listing_type'] : 0;
        $sorting = ! empty( $instance['rz_sorting'] ) ? $instance['rz_sorting'] : 'latest';
        $num_listings = ! empty( $instance['rz_num_listings'] ) ? $instance['rz_num_listings'] : 3;

        $types = get_posts([
            'post_type' => 'rz_listing_type',
            'post_status' => 'publish',
            'numberposts' => -1,
        ]);

        ?>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_html__( 'Title:', 'routiz' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'rz_listing_type' ) ); ?>"><?php echo esc_html__( 'Select listing type ( Optional ):', 'routiz' ); ?></label>
                <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'rz_listing_type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'rz_listing_type' ) ); ?>">
                    <option value=""><?php esc_html_e('Select', 'routiz'); ?></option>
                    <?php if( $types ): ?>
                        <?php foreach( $types as $type ): ?>
                            <option value="<?php echo (int) $type->ID; ?>" <?php if( $type->ID == $listing_type ) { echo 'selected="selected"'; } ?>>
                                <?php echo esc_html( Rz()->get_meta('rz_name_plural', $type->ID) ); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'rz_sorting' ) ); ?>"><?php echo esc_html__( 'Sort by:', 'routiz' ); ?></label>
                <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'rz_sorting' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'rz_sorting' ) ); ?>">
                    <option value="latest" <?php if( $sorting == 'latest' ) { echo 'selected="selected"'; } ?>><?php esc_html_e('Latest', 'routiz'); ?></option>
                    <option value="top-rated" <?php if( $sorting == 'top-rated' ) { echo 'selected="selected"'; } ?>><?php esc_html_e('Top rated', 'routiz'); ?></option>
                    <option value="favorites" <?php if( $sorting == 'favorites' ) { echo 'selected="selected"'; } ?>><?php esc_html_e('Favorites', 'routiz'); ?></option>
                </select>
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'rz_num_listings' ) ); ?>"><?php echo esc_html__( 'Number of listings:', 'routiz' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'rz_num_listings' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'rz_num_listings' ) ); ?>" type="number" min="1" max="10" value="<?php echo (int) $num_listings; ?>">
            </p>
        <?php

    }

    public function update( $new_instance, $old_instance ) {

        $instance = [];

        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['rz_listing_type'] = ( ! empty( $new_instance['rz_listing_type'] ) ) ? $new_instance['rz_listing_type'] : '';
        $instance['rz_sorting'] = ( ! empty( $new_instance['rz_sorting'] ) ) ? $new_instance['rz_sorting'] : 'latest';
        $instance['rz_num_listings'] = ( ! empty( $new_instance['rz_num_listings'] ) ) ? strip_tags( $new_instance['rz_num_listings'] ) : '';

        return $instance;

    }

}

new Widget_Listings();
