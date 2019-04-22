jQuery(document).on( 'click', '.pw-base-woocommerce-notice .notice-dismiss', function() {

	jQuery.ajax({
	    url: ajaxurl,
	    data: {
	        action: 'pw_base_dismiss_woocommerce_notice'
	    }
	});

});
