jQuery(document).ready(function($) {


	if ( !$( '#guide-index-side' ).length > 0 ) {
		return;
	}
	// Initiate sidr
	$('#primary-nav-link').sidr({
		name: 'sidr-guide-index',
		source: '#guide-index-side',
		displace: 0,
		side: 'right'
	});

	// Click "out" -- anywhere on body, other than menu (inside '#sidr-primary-nav')
	$(document).on('click', function(event) {
		if ( $( 'body' ).hasClass( 'sidr-open' ) ) {
			if ( $(event.target).closest('#sidr-guide-index').length == 0 ) {
				$.sidr('close', 'sidr-guide-index');
			}
		}
	});

	// Close sidr when menu item clicked.  Needed for same-page anchors
  //   $('.sidr li a').click( function () {
  //   	$.sidr('close', 'sidr-guide-index');
  //   });

});
