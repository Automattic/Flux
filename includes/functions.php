<?php

/**
 * Are we looking at a flux query?
 *
 * @since Flux (0.1)
 * @global WP_Query $wp_query
 * @return bool
 */
function is_flux_query() {
	global $wp_query;

	return (bool) isset( $wp_query->flux );
}

/**
 * Get the 
 * @since Flux (0.1)
 * @return string
 */
function flux_get_rewrite_id() {
	return apply_filters( 'flux_get_id', 'flux_id' );
}