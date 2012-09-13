jQuery( document ).ready( function($) {

	var flux = jQuery( '#flux-capacitor' );

	if ( !! flux.offset() ) {

		var stickyTop = flux.offset().top;
		var topGap    = 28;

		jQuery( window ).scroll( function() {

			var windowTop = jQuery( window ).scrollTop();

			$('.post.flux').removeClass('top').each(function() {
				var transition_zone = $(this).offset().top + $(this).height();
				console.log( $(this).offset() );
				console.log( transition_zone + ' ' + windowTop );
				if ( transition_zone > windowTop ) {
					$(this).addClass('top');
					var timestamp = $(this).attr('class').match( /flux-timestamp-([\d]+)/ );
					if ( ! $.isArray( timestamp ) )
						return false;
					var d = new Date( timestamp[1] * 1000 );
					if ( d.getFullYear() != $('.flux-year.flux-year-active').html() ) {
						$('.flux-year').removeClass('flux-year-active');
						$('.flux-year-'+d.getFullYear()).addClass('flux-year-active');
						// @todo make sure the proper month range is loaded
					}
					return false; // stops the iteration after the first one on screen
				}
			});

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
		infiniteScroll.scroller.page = 1;

		jQuery( '#flux-content' ).html( '' ); 
		infiniteScroll.settings.ajaxurl = infinite_ajax_orig; 

		return false
	} );

	jQuery( '.flux-month' ).click( function() {
		var this_month = jQuery( this ).text();
		if ( this_month.match( /^\d$/ ) ) {
			this_month = '0' + this_month;
		}

		var date = jQuery( '.flux-year-active' ).text();
		date += '-' + this_month + '-01';

		var infinite_ajax_orig = infiniteScroll.settings.ajaxurl;
		infiniteScroll.settings.ajaxurl += '&date=' + date;
		infiniteScroll.scroller.page = 1; 

		jQuery( '#flux-content' ).html( '' );
		infiniteScroll.scroller.refresh();
		infiniteScroll.settings.ajaxurl = infinite_ajax_orig;
		
		return false;
	} );
});
