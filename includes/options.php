<?php

/**
 * Flux Options
 *
 * @package Flux
 * @subpackage Options
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Get the default site options and their values.
 * 
 * These option
 *
 * @since Flux (0.1)
 * @return array Filtered option names and values
 */
function flux_get_default_options() {

	// Default options
	return apply_filters( 'flux_get_default_options', array(

		/** DB Version ********************************************************/

		'_flux_db_version'            => flux()->db_version,

		/** Visible ***********************************************************/

		// Content types
		'_flux_show_pages'            => true,   // Show posts created
		'_flux_show_posts'            => true,   // Show pages created
		'_flux_show_comments'         => true,   // Show comments created

		// Post formats
		'_flux_show_post_standard'    => true,   // Show pages created
		'_flux_show_post_aside'       => true,   // Show pages created
		'_flux_show_post_image'       => true,   // Show pages created
		'_flux_show_post_link'        => true,   // Show pages created
		'_flux_show_post_quote'       => true,   // Show pages created
		'_flux_show_post_video'       => true,   // Show pages created

		/** Boundaries ********************************************************/

		'_flux_boundaries_low'        => 0,      // Lowest possible boundary
		'_flux_boundaries_high'       => 10,     // Highest possible boundary

		'_flux_comment_comments_low'  => 0,      // Show comments left on other sites
		'_flux_comment_comments_high' => 10,     // Show comments left on other sites

		'_flux_post_comments_low'     => 0,      // Lowest comments to make visible
		'_flux_post_comments_high'    => 10,     // Highest likes boundary

		'_flux_post_likes_low'        => 0,      // Lowest likes to make visible
		'_flux_post_likes_high'       => 10,     // Highest likes boundary

		'_flux_post_categories_low'   => 0,      // Lowest categories to make visible
		'_flux_post_catagories_high'  => 10,     // Highest categories boundary

		'_flux_post_tags_low'         => 0,      // Lowest tags to make visible
		'_flux_post_tags_high'        => 10,     // Highest tags boundary

		/** Per Page **********************************************************/

		'_flux_posts_per_page'        => 15,         // Posts per page

		/** Archive Slugs *****************************************************/

		'_flux_slug'                  => 'timeline', // Timeline archive slug
	) );
}

/**
 * Add default options
 *
 * Hooked to flux_activate, it is only called once when Flux is activated.
 * This is non-destructive, so existing settings will not be overridden.
 *
 * @since Flux (0.1)
 * @uses flux_get_default_options() To get default options
 * @uses add_option() Adds default options
 * @uses do_action() Calls 'flux_add_options'
 */
function flux_add_options() {

	// Add default options
	foreach ( flux_get_default_options() as $key => $value )
		add_option( $key, $value );

	// Allow previously activated plugins to append their own options.
	do_action( 'flux_add_options' );
}

/**
 * Delete default options
 *
 * Hooked to flux_uninstall, it is only called once when Flux is uninstalled.
 * This is destructive, so existing settings will be destroyed.
 *
 * @since Flux (0.1)
 * @uses flux_get_default_options() To get default options
 * @uses delete_option() Removes default options
 * @uses do_action() Calls 'flux_delete_options'
 */
function flux_delete_options() {

	// Add default options
	foreach ( array_keys( flux_get_default_options() ) as $key )
		delete_option( $key );

	// Allow previously activated plugins to append their own options.
	do_action( 'flux_delete_options' );
}

/**
 * Add filters to each Flux option and allow them to be overloaded
 *
 * @since Flux (0.1)
 * @uses flux_get_default_options() To get default options
 * @uses add_filter() To add filters to 'pre_option_{$key}'
 * @uses do_action() Calls 'flux_add_option_filters'
 */
function flux_setup_option_filters() {

	// Add filters to each Flux option
	foreach ( array_keys( flux_get_default_options() ) as $key )
		add_filter( 'pre_option_' . $key, 'flux_pre_get_option' );

	// Allow previously activated plugins to append their own options.
	do_action( 'flux_setup_option_filters' );
}

/**
 * Filter default options and allow them to be overloaded
 *
 * @since Flux (0.1)
 * @param bool $value Optional. Default value false
 * @return mixed false if not overloaded, mixed if set
 */
function flux_pre_get_option( $value = '' ) {

	// Remove the filter prefix
	$option = str_replace( 'pre_option_', '', current_filter() );

	// Check the options global for preset value
	if ( isset( flux()->options[$option] ) )
		$value = flux()->options[$option];

	// Always return a value, even if false
	return $value;
}

/** Active? *******************************************************************/

/**
 * Checks if pages should appear in timeline.
 *
 * @since Flux (0.1)
 * @param $default bool Optional.Default value true
 * @uses get_option() To get the favorites option
 * @return bool Is favorites enabled or not
 */
function flux_show_pages( $default = 1 ) {
	return (bool) apply_filters( 'flux_show_pages', (bool) get_option( '_flux_show_pages', $default ) );
}

/**
 * Checks if posts should appear it timeline.
 *
 * @since Flux (0.1)
 * @param $default bool Optional.Default value true
 * @uses get_option() To get the favorites option
 * @return bool Is favorites enabled or not
 */
function flux_show_posts( $default = 1 ) {
	return (bool) apply_filters( 'flux_show_posts', (bool) get_option( '_flux_show_posts', $default ) );
}

/** Slugs *********************************************************************/

/**
 * Return the root slug
 *
 * @since Flux (0.1)
 * @return string
 */
function flux_get_slug( $default = 'timeline' ) {
	return apply_filters( 'flux_get_slug', get_option( '_flux_slug', $default ) );
}

/**
 * Are we including the root slug in front of flux content?
 *
 * @since Flux (0.1)
 * @return bool
 */
function flux_include_root_slug( $default = 1 ) {
	return (bool) apply_filters( 'flux_include_root_slug', (bool) get_option( '_flux_include_root', $default ) );
}

/**
 * Maybe return the root slug, based on whether or not it's included in the url
 *
 * @since Flux (0.1)
 * @return string
 */
function flux_maybe_get_root_slug() {
	$retval = '';

	if ( flux_get_root_slug() && flux_include_root_slug() )
		$retval = trailingslashit( flux_get_root_slug() );

	return apply_filters( 'flux_maybe_get_root_slug', $retval );
}
