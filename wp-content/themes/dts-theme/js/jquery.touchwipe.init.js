jQuery(document).ready(function($) {

	$(window).touchwipe({
		wipeLeft: function() {
			// Open
			if ( !document.querySelector( '.fbx-stage img' ) ) {
				$.sidr('open', 'sidr-guide-index');
			}
		},
		wipeRight: function() {
			// Close
			$.sidr('close', 'sidr-guide-index');
		},
		preventDefaultEvents: false,
		min_move_x: 42
	});

});