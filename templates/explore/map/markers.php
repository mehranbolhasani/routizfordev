<?php

defined('ABSPATH') || exit;

global $rz_explore;

echo '<div class="rz-dynamic-markers rz-none">';
    // if( $rz_explore->type ) {
        while( $rz_explore->query()->posts->have_posts() ) { $rz_explore->query()->posts->the_post();

            $listing_type_id = Rz()->get_meta( 'rz_listing_type' );

            $address = '';
            $attr = [];

            $attr['data-id'] = get_the_ID();

            $location = Rz()->get_meta( 'rz_location', null, false );
            if( isset( $location[0] ) ) { $address = esc_attr( $location[0] ); }
            if( isset( $location[1] ) ) { $attr['data-lat'] = esc_attr( $location[1] ); }
            if( isset( $location[2] ) ) { $attr['data-lng'] = esc_attr( $location[2] ); }

            if( $location ) {

                $class = [];
                $type = Rz()->get_meta( 'rz_marker_type', $listing_type_id );
                $enable_favorite = Rz()->get_meta( 'rz_display_listing_favorite', $listing_type_id );
                $is_favorite = ( is_object( $rz_explore ) and $rz_explore->user->id and $rz_explore->user->is_favorite( get_the_ID() ) );

                if( $enable_favorite and $is_favorite ) {
                    $class[] = 'rz--is-fav';
                }

                echo '<div ' . Rz()->attrs( $attr ) . '>';

                    if( $type == 'field' ) {

                        $field = Rz()->get_meta( 'rz_marker_field', $listing_type_id );
                        $format = Rz()->get_meta( 'rz_marker_field_format', $listing_type_id );
                        $value = Rz()->get_meta( $field );
                        if( empty( $value ) ) {
                            $value = esc_html__( 'No data', 'routiz' );
                        }else{
                            $value = $format ? sprintf( $format, $value ) : $value;
                        }

                        echo '<div class="rz-marker rz-marker-field ' . implode( ' ', $class ) . '" data-id="' . get_the_ID() . '">';
                        echo apply_filters( 'rz_marker_field_output', esc_attr( $value ) );
                        if( $enable_favorite ) {
                            echo '<span class="rz--fav"><i class="fas fa-heart"></i></span>';
                        }
                        echo '</div>';

                    }
                    elseif( $type == 'icon' ) {

                        $icon = Rz()->get_meta( 'rz_icon', $listing_type_id );

                        if( $icon ) {
                            echo '<div class="rz-marker rz-marker-icon ' . implode( ' ', $class ) . '" data-id="' . get_the_ID() . '">';
                            echo '<i class="' . esc_attr( $icon ) . '"></i>';
                            if( $enable_favorite ) {
                                echo '<span class="rz--fav"><i class="fas fa-heart"></i></span>';
                            }
                            echo '</div>';
                        }

                    }
                    elseif( $type == 'image' ) {

                        $image_attrs = Rz()->jsoning( 'rz_marker_image', $listing_type_id );
                        $image_width = Rz()->get_meta( 'rz_marker_image_width', $listing_type_id );

                        if( isset( $image_attrs[0] ) and isset( $image_attrs[0]->id ) ) {

                            $image = Rz()->get_image( $image_attrs[0]->id );

                            if( $image ) {
                                echo '<div class="rz-marker rz-marker-image ' . implode( ' ', $class ) . '" data-id="' . get_the_ID() . '">';
                                echo '<img src="' . esc_url( $image ) . '" alt="" width="' . (int) $image_width . '">';
                                if( $enable_favorite ) {
                                    echo '<span class="rz--fav"><i class="fas fa-heart"></i></span>';
                                }
                                echo '</div>';
                            }



                        }

                    }

                echo '</div>';
            }

        }
    // }
echo '</div>';
wp_reset_postdata();
