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
});