jQuery( document ).ready( function($) {

	var flux = jQuery( '#flux-navigation' );

	if ( !! flux.offset() ) {

		var stickyTop = flux.offset().top;
		var topGap    = 78;

		jQuery( window ).scroll( function() {

			var windowTop = jQuery( window ).scrollTop();

			$('.post.flux').removeClass('top').each(function() {
				var transition_zone = $(this).offset().top + $(this).height();
				if ( transition_zone > windowTop ) {
					$(this).addClass('top');
					var timestamp = $(this).attr('class').match( /flux-timestamp-([\d]+)/ );
					if ( ! $.isArray( timestamp ) )
						return false;
					var d = new Date( timestamp[1] * 1000 );
					if ( d.getFullYear() != $('.flux-year.flux-year-active').html() ) {
						$('.flux-year').removeClass('flux-year-active');
						$('.flux-year-'+d.getFullYear()).addClass('flux-year-active');
						$('.flux-month-selector').hide();
						$('#flux-month-selector-'+d.getFullYear()).show();
					}
					var month_num = d.getMonth() + 1;
					if ( month_num != $('.flux-month.flux-month-active').html() ) {
						$('.flux-month').removeClass('flux-month-active');
						$('.flux-month-'+month_num).addClass('flux-month-active');
					}
					return false; // stops the iteration after the first one on screen
				}
			});

			if ( windowTop > stickyTop - topGap ) {
					flux.css( { position: 'fixed', top: topGap } );
			} else {
				flux.css( { position: 'absolute', top: 0 } );
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
