<?php global $post; ?>

<div class="options_group show_if_listing_promotion">

	<?php

    	woocommerce_wp_text_input([
    		'id' => '_rz_promotion_duration',
    		'label' => __( 'Duration', 'routiz' ),
    		'description' => __( 'The number of days of the listing to be promoted.', 'routiz' ),
			'placeholder' => '30',
    		'value' => get_post_meta( $post->ID, '_rz_promotion_duration', true ),
    		'desc_tip' => true,
    		'type' => 'number',
    		'custom_attributes' => [
        		'min' => '',
        		'step' => '1',
    		],
    	]);

    	woocommerce_wp_text_input([
    		'id' => '_rz_promotion_priority',
    		'label' => __( 'Priority', 'routiz' ),
    		'description' => __( 'Higher value means higher priority in search results.', 'routiz' ),
			'placeholder' => 2,
    		'value' => get_post_meta( $post->ID, '_rz_promotion_priority', true ),
    		'desc_tip' => true,
    		'type' => 'number',
    		'custom_attributes' => [
        		'min' => '',
        		'step' => '1',
    		],
    	]);

    ?>

	<script type="text/javascript">
		jQuery(function(){
			jQuery('#product-type').change( function() {
				jQuery('#woocommerce-product-data').removeClass(function(i, classNames) {
					var classNames = classNames.match(/is\_[a-zA-Z\_]+/g);
					if ( ! classNames ) {
						return '';
					}
					return classNames.join(' ');
				});
				jQuery('#woocommerce-product-data').addClass( 'is_' + jQuery(this).val() );
			} );
			jQuery('.pricing').addClass( 'show_if_listing_promotion' );
			jQuery('#product-type').change();
		});
	</script>

</div>