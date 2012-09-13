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
	return apply_filters( 'flux_get_rewrite_id', 'flux_id' );
}

/**
 * Get the years and months this blog has posts for
 */
function flux_get_blog_history() {
	global $wpdb;

	$query = "SELECT YEAR(post_date) AS `year`, MONTH(post_date) AS `month` FROM $wpdb->posts GROUP BY YEAR(post_date), MONTH(post_date) ORDER BY post_date DESC";
	$raw_history = $wpdb->get_results( $query );
	return $raw_history;
}

/** Template Classes **********************************************************/

/**
 * Add Flux specific body classes where needed
 *
 * @since Flux (0.1)
 *
 * @param array $wp_classes
 * @param array $custom_classes
 * @return array
 */
function flux_add_body_class( $wp_classes = array(), $custom_classes = false ) {

	// The special flux body classes
	$flux_classes = array();

	// Add Flux class if we are within a timeline page
	if ( is_flux_query() )
		$flux_classes[] = 'flux';

	// Merge WP classes with Flux classes and remove duplicates
	$classes = array_unique( array_merge( (array) $flux_classes, (array) $wp_classes ) );
	
	return apply_filters( 'flux_add_body_class', $classes, $flux_classes, $wp_classes, $custom_classes );
}

/**
 * Add Flux specific post classes where needed
 *
 * @since Flux (0.1)
 *
 * @param array $wp_classes Previously created classes
 * @param array $classes 
 * @return array
 */
function flux_add_post_class( $wp_classes = array(), $class = '', $post_id = 0 ) {

	// The special flux body classes
	$flux_classes = array();

	// Add Flux class if we are within a timeline page
	if ( is_flux_query() )
		$flux_classes[] = 'flux';

	// Merge WP classes with Flux classes and remove duplicates
	$classes = array_unique( array_merge( (array) $flux_classes, (array) $wp_classes ) );

	return apply_filters( 'flux_add_post_class', $classes, $flux_classes, $wp_classes, $class, $post_id );
}

/**
 * Make sure infinite scroll added content to the Flux section
 *
 * @since Flux (0.1)
 *
 * @param array $settings Infinite scroll Javascript settings
 * @return array
 */
function flux_infinite_scroll_js_settings( $settings ) {
	$settings['id'] = 'flux-content';
	return $settings;
}
