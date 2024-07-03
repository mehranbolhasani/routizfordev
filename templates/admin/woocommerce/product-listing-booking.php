<?php global $post; ?>

<div class="options_group show_if_listing_booking">

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
			jQuery('.pricing').addClass( 'show_if_listing_booking' );
			jQuery('#product-type').change();
		});
	</script>

</div>