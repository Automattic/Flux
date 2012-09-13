jQuery( document ).ready( function() {

	var flux = jQuery( '#flux-capacitor' );

	if ( !! flux.offset() ) {

		var stickyTop = flux.offset().top;
		var topGap    = 28;

		jQuery( window ).scroll( function() {

			var windowTop = jQuery( window ).scrollTop();

			if ( stickyTop - topGap < windowTop ) {
				flux.css( { position: 'fixed', top: topGap } );
			} else {
				flux.css( 'position', 'static' );
			}
		});
	}

	jQuery( '.flux-year' ).click( function() {
		var date = jQuery( this ).text() + '-12-31';
		var infinite_ajax_orig = infiniteScroll.settings.ajaxurl; 
		infiniteScroll.settings.ajaxurl += '&date=' + date; 

		jQuery( '#flux-content' ).html( '' ); 

		infiniteScroll.settings.ajaxurl = infinite_ajax_orig; 
		return false
	} );
});
