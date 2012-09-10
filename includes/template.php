<?php

/**
 * Flux Template Functions
 *
 * This file contains functions necessary to mirror the WordPress core template
 * loading process. Many of those functions are not filterable, and even then
 * would not be robust enough to predict where Flux templates might exist.
 *
 * @package Flux
 * @subpackage TemplateFunctions
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Adds Flux theme support to any active WordPress theme
 *
 * @since Flux (0.1)
 *
 * @param string $slug
 * @param string $name Optional. Default null
 * @uses flux_locate_template()
 * @uses load_template()
 * @uses get_template_part()
 */
function flux_get_template_part( $slug, $name = null ) {

	// Execute code for this part
	do_action( 'get_template_part_' . $slug, $slug, $name );

	// Setup possible parts
	$templates = array();
	if ( isset( $name ) )
		$templates[] = $slug . '-' . $name . '.php';
	$templates[] = $slug . '.php';

	// Allow template parst to be filtered
	$templates = apply_filters( 'flux_get_template_part', $templates, $slug, $name );

	// Return the part that is found
	return flux_locate_template( $templates, true, false );
}

/**
 * Retrieve the name of the highest priority template file that exists.
 *
 * Searches in the STYLESHEETPATH before TEMPLATEPATH so that themes which
 * inherit from a parent theme can just overload one file. If the template is
 * not found in either of those, it looks in the theme-compat folder last.
 *
 * @since Flux (0.1)
 *
 * @param string|array $template_names Template file(s) to search for, in order.
 * @param bool $load If true the template file will be loaded if it is found.
 * @param bool $require_once Whether to require_once or require. Default true.
 *                            Has no effect if $load is false.
 * @return string The template filename if one is located.
 */
function flux_locate_template( $template_names, $load = false, $require_once = true ) {

	// No file found yet
	$located = false;

	// Try to find a template file
	foreach ( (array) $template_names as $template_name ) {

		// Continue if template is empty
		if ( empty( $template_name ) )
			continue;

		// Trim off any slashes from the template name
		$template_name = ltrim( $template_name, '/' );

		// Check child theme first
		if ( file_exists( trailingslashit( STYLESHEETPATH ) . $template_name ) ) {
			$located = trailingslashit( STYLESHEETPATH ) . $template_name;
			break;

		// Check parent theme next
		} elseif ( file_exists( trailingslashit( TEMPLATEPATH ) . $template_name ) ) {
			$located = trailingslashit( TEMPLATEPATH ) . $template_name;
			break;

		// Check theme compatibility last
		} elseif ( file_exists( trailingslashit( flux_get_templates_dir() ) . $template_name ) ) {
			$located = trailingslashit( flux_get_templates_dir() ) . $template_name;
			break;
		}
	}

	if ( ( true == $load ) && !empty( $located ) )
		load_template( $located, $require_once );

	return $located;
}

/**
 * Retrieve path to a template
 *
 * Used to quickly retrieve the path of a template without including the file
 * extension. It will also check the parent theme and theme-compat theme with
 * the use of {@link flux_locate_template()}. Allows for more generic template
 * locations without the use of the other get_*_template() functions.
 *
 * @since Flux (0.1)
 *
 * @param string $type Filename without extension.
 * @param array $templates An optional list of template candidates
 * @uses flux_set_theme_compat_templates()
 * @uses flux_locate_template()
 * @uses flux_set_theme_compat_template()
 * @return string Full path to file.
 */
function flux_get_query_template( $type, $templates = array() ) {
	$type = preg_replace( '|[^a-z0-9-]+|', '', $type );

	if ( empty( $templates ) )
		$templates = array( "{$type}.php" );

	// Filter possible templates, try to match one, and set any Flux theme
	// compat properties so they can be cross-checked later.
	$templates = apply_filters( "flux_get_{$type}_template", $templates );
	$templates = flux_set_theme_compat_templates( $templates );
	$template  = flux_locate_template( $templates );
	$template  = flux_set_theme_compat_template( $template );

	return apply_filters( "flux_{$type}_template", $template );
}

/**
 * Get the possible subdirectories to check for templates in
 *
 * @since Flux (0.1)
 * @param array $templates Templates we are looking for
 * @return array Possible subfolders to look in
 */
function flux_get_template_locations( $templates = array() ) {
	$locations = array(
		'flux',
		'timeline',
		''
	);
	return apply_filters( 'flux_get_template_locations', $locations, $templates );
}

/**
 * Add template locations to template files being searched for
 *
 * @since Flux (0.1)
 *
 * @param array $templates
 * @return array() 
 */
function flux_add_template_locations( $templates = array() ) {
	$retval = array();

	// Get alternate locations
	$locations = flux_get_template_locations( $templates );

	// Loop through locations and templates and combine
	foreach ( $locations as $location )
		foreach ( $templates as $template )
			$retval[] = trailingslashit( $location ) . $template;

	return apply_filters( 'flux_add_template_locations', $retval, $templates );
}

/**
 * Add checks for Flux conditions to parse_query action
 *
 * @since Flux (0.1)
 *
 * @param WP_Query $posts_query
 * @uses WP_User to get the user data
 */
function flux_parse_query( $posts_query ) {

	// Bail if $posts_query is not the main loop
	if ( ! $posts_query->is_main_query() )
		return;

	// Bail if filters are suppressed on this query
	if ( true == $posts_query->get( 'suppress_filters' ) )
		return;

	// Bail if in admin
	if ( is_admin() )
		return;

	// Bail if not a timeline request
	$flux = $posts_query->get( flux_get_id() );
	if ( empty( $flux ) )
		return;

	// Set theme compat to true
	$posts_query->flux = true;
}

/** Custom Functions **********************************************************/

/**
 * Attempt to load a custom Flux functions file, similar to each theme's
 * functions.php file.
 *
 * @since Flux (0.1)
 *
 * @global string $pagenow
 * @uses flux_locate_template()
 */
function flux_load_theme_functions() {
	global $pagenow;

	// If Flux is being deactivated, do not load any more files
	if ( flux_is_deactivation() )
		return;

	if ( ! defined( 'WP_INSTALLING' ) || ( !empty( $pagenow ) && ( 'wp-activate.php' !== $pagenow ) ) ) {
		flux_locate_template( 'flux-functions.php', true );
	}
}

/** Functions *****************************************************************/

/**
 * Gets the Flux compatable theme used in the event the currently active
 * WordPress theme does not explicitly support Flux. This can be filtered,
 * or set manually. Tricky theme authors can override the default and include
 * their own Flux compatability layers for their themes.
 *
 * @since Flux (0.1)
 * @uses apply_filters()
 * @return string
 */
function flux_get_templates_dir() {
	return apply_filters( 'flux_get_templates_dir', flux()->templates_dir );
}

/**
 * Gets the Flux compatable theme used in the event the currently active
 * WordPress theme does not explicitly support Flux. This can be filtered,
 * or set manually. Tricky theme authors can override the default and include
 * their own Flux compatability layers for their themes.
 *
 * @since Flux (0.1)
 * @uses apply_filters()
 * @return string
 */
function flux_get_templates_url() {
	return apply_filters( 'flux_get_templates_url', flux()->template_url );
}

/**
 * Gets true/false if page is currently inside theme compatibility
 *
 * @since Flux (0.1)
 * @return bool
 */
function flux_is_theme_compat_active() {

	if ( empty( flux()->theme_compat->active ) )
		return false;

	return flux()->theme_compat->active;
}

/**
 * Sets true/false if page is currently inside theme compatibility
 *
 * @since Flux (0.1)
 * @param bool $set
 * @return bool
 */
function flux_set_theme_compat_active( $set = true ) {
	flux()->theme_compat->active = $set;

	return (bool) flux()->theme_compat->active;
}

/**
 * Set the theme compat templates global
 *
 * Stash possible template files for the current query. Useful if plugins want
 * to override them, or see what files are being scanned for inclusion.
 *
 * @since Flux (0.1)
 */
function flux_set_theme_compat_templates( $templates = array() ) {
	flux()->theme_compat->templates = $templates;

	return flux()->theme_compat->templates;
}

/**
 * Set the theme compat template global
 *
 * Stash the template file for the current query. Useful if plugins want
 * to override it, or see what file is being included.
 *
 * @since Flux (0.1)
 */
function flux_set_theme_compat_template( $template = '' ) {
	flux()->theme_compat->template = $template;

	return flux()->theme_compat->template;
}

/**
 * Set the theme compat original_template global
 *
 * Stash the original template file for the current query. Useful for checking
 * if Flux was able to find a more appropriate template.
 *
 * @since Flux (0.1)
 */
function flux_set_theme_compat_original_template( $template = '' ) {
	flux()->theme_compat->original_template = $template;

	return flux()->theme_compat->original_template;
}

/**
 * Set the theme compat original_template global
 *
 * Stash the original template file for the current query. Useful for checking
 * if Flux was able to find a more appropriate template.
 *
 * @since Flux (0.1)
 */
function flux_is_theme_compat_original_template( $template = '' ) {

	if ( empty( flux()->theme_compat->original_template ) )
		return false;

	return (bool) ( flux()->theme_compat->original_template == $template );
}

/**
 * This fun little function fills up some WordPress globals with dummy data to
 * stop your average page template from complaining about it missing.
 *
 * @since Flux (0.1)
 * @global WP_Query $wp_query
 * @global object $post
 * @param array $args
 */
function flux_theme_compat_reset_post( $args = array() ) {
	global $wp_query, $post;

	// Default arguments
	$defaults = array(
		'ID'                    => -9999,
		'post_status'           => 'publish',
		'post_author'           => 0,
		'post_parent'           => 0,
		'post_type'             => 'page',
		'post_date'             => 0,
		'post_date_gmt'         => 0,
		'post_modified'         => 0,
		'post_modified_gmt'     => 0,
		'post_content'          => '',
		'post_title'            => '',
		'post_category'         => 0,
		'post_excerpt'          => '',
		'post_content_filtered' => '',
		'post_mime_type'        => '',
		'post_password'         => '',
		'post_name'             => '',
		'guid'                  => '',
		'menu_order'            => 0,
		'pinged'                => '',
		'to_ping'               => '',
		'ping_status'           => '',
		'comment_status'        => 'closed',
		'comment_count'         => 0,

		'is_404'          => false,
		'is_page'         => false,
		'is_single'       => false,
		'is_archive'      => false,
		'is_tax'          => false,
	);

	// Switch defaults if post is set
	if ( isset( $wp_query->post ) ) {		  
		$defaults = array(
			'ID'                    => $wp_query->post->ID,
			'post_status'           => $wp_query->post->post_status,
			'post_author'           => $wp_query->post->post_author,
			'post_parent'           => $wp_query->post->post_parent,
			'post_type'             => $wp_query->post->post_type,
			'post_date'             => $wp_query->post->post_date,
			'post_date_gmt'         => $wp_query->post->post_date_gmt,
			'post_modified'         => $wp_query->post->post_modified,
			'post_modified_gmt'     => $wp_query->post->post_modified_gmt,
			'post_content'          => $wp_query->post->post_content,
			'post_title'            => $wp_query->post->post_title,
			'post_category'         => $wp_query->post->post_category,
			'post_excerpt'          => $wp_query->post->post_excerpt,
			'post_content_filtered' => $wp_query->post->post_content_filtered,
			'post_mime_type'        => $wp_query->post->post_mime_type,
			'post_password'         => $wp_query->post->post_password,
			'post_name'             => $wp_query->post->post_name,
			'guid'                  => $wp_query->post->guid,
			'menu_order'            => $wp_query->post->menu_order,
			'pinged'                => $wp_query->post->pinged,
			'to_ping'               => $wp_query->post->to_ping,
			'ping_status'           => $wp_query->post->ping_status,
			'comment_status'        => $wp_query->post->comment_status,
			'comment_count'         => $wp_query->post->comment_count,

			'is_404'          => false,
			'is_page'         => false,
			'is_single'       => false,
			'is_archive'      => false,
			'is_tax'          => false,
		);
	}
	$dummy = wp_parse_args( $args, $defaults, 'theme_compat_reset_post' );

	// Clear out the post related globals
	unset( $wp_query->posts );
	unset( $wp_query->post  );
	unset( $post            );

	// Setup the dummy post object
	$wp_query->post                        = new stdClass; 
	$wp_query->post->ID                    = $dummy['ID'];
	$wp_query->post->post_status           = $dummy['post_status'];
	$wp_query->post->post_author           = $dummy['post_author'];
	$wp_query->post->post_parent           = $dummy['post_parent'];
	$wp_query->post->post_type             = $dummy['post_type'];
	$wp_query->post->post_date             = $dummy['post_date'];
	$wp_query->post->post_date_gmt         = $dummy['post_date_gmt'];
	$wp_query->post->post_modified         = $dummy['post_modified'];
	$wp_query->post->post_modified_gmt     = $dummy['post_modified_gmt'];
	$wp_query->post->post_content          = $dummy['post_content'];
	$wp_query->post->post_title            = $dummy['post_title'];
	$wp_query->post->post_category         = $dummy['post_category'];
	$wp_query->post->post_excerpt          = $dummy['post_content_filtered'];
	$wp_query->post->post_content_filtered = $dummy['post_content_filtered'];
	$wp_query->post->post_mime_type        = $dummy['post_mime_type'];
	$wp_query->post->post_password         = $dummy['post_password'];
	$wp_query->post->post_name             = $dummy['post_name'];
	$wp_query->post->guid                  = $dummy['guid'];
	$wp_query->post->menu_order            = $dummy['menu_order'];
	$wp_query->post->pinged                = $dummy['pinged'];
	$wp_query->post->to_ping               = $dummy['to_ping'];
	$wp_query->post->ping_status           = $dummy['ping_status'];
	$wp_query->post->comment_status        = $dummy['comment_status'];
	$wp_query->post->comment_count         = $dummy['comment_count'];

	// Set the $post global
	$post = $wp_query->post;

	// Setup the dummy post loop
	$wp_query->posts[0] = $wp_query->post;

	// Prevent comments form from appearing
	$wp_query->post_count = 1;
	$wp_query->is_404     = $dummy['is_404'];
	$wp_query->is_page    = $dummy['is_page'];
	$wp_query->is_single  = $dummy['is_single'];
	$wp_query->is_archive = $dummy['is_archive'];
	$wp_query->is_tax     = $dummy['is_tax'];

	// If we are resetting a post, we are in theme compat
	flux_set_theme_compat_active();
}

/**
 * Reset main query vars and filter 'the_content' to output a Flux
 * template part as needed.
 *
 * @since Flux (0.1)
 * @param string $template
 */
function flux_template_include_theme_compat( $template = '' ) {

	// Bail if the template already matches a Flux template.
	if ( ! is_flux_query() || !empty( flux()->theme_compat->flux_template ) )
		return $template;

	ob_start();

	flux_get_template_part( 'content', 'timeline' );

	// Reset the post with our new title
	flux_theme_compat_reset_post( array(
		'ID'             => 0,
		'post_author'    => 0,
		'post_date'      => 0,
		'post_content'   => ob_get_contents(),
		'post_type'      => 'flux',
		'post_title'     => __( 'Timeline', 'flux'),
		'post_status'    => 'publish',
		'comment_status' => 'closed'
	) );

	ob_end_clean();

	// Remove all filters from the_content
	remove_all_filters( 'the_content' );

	// Add a filter on the_content late, which we will later remove
	add_filter( 'the_content', 'flux_replace_the_content' );

	return apply_filters( 'flux_template_include_theme_compat', flux_get_theme_compat_templates() );
}

/**
 * Add a nifty archive navigation tool to the sidebar
 */
function flux_add_sidebar_navigation( $name ) {
	flux_get_template_part( 'navigation-widget' );
}
