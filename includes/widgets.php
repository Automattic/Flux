<?php

function flux_sidebar_widgets( $sidebar_widgets ) {
	// The primary sidebar for Twenty Twelve
	if ( isset( $sidebar_widgets['sidebar-1'] ) && !is_admin() ) {
		wp_register_sidebar_widget( 'flux-navigation', '', 'flux_add_sidebar_navigation' );
		array_unshift( $sidebar_widgets['sidebar-1'], 'flux-navigation' );
	}
	return $sidebar_widgets;
}