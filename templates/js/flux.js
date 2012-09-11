var flux_capacitor_name = "#flux-capacitor";
var menu_y = null;

jQuery( document ).ready( function() {

	menu_Y = parseInt( jQuery( flux_capacitor_name ).css( 'top' ).substring( 0, jQuery( flux_capacitor_name ).css( 'top' ).indexOf( 'px' ) ) )

	jQuery( window ).scroll( function () { 

		var offset = menu_y + jQuery( document ).scrollTop() + 'px';

		jQuery( flux_capacitor_name ).animate( {top:offset}, {duration:0, queue:false} );
	});
}); 
