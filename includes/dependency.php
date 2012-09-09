<?php

/**
 * Plugin Dependency
 *
 * The purpose of the following hooks is to mimic the behavior of something
 * called 'plugin dependency' which enables a plugin to have plugins of their
 * own in a safe and reliable way.
 *
 * We do this in Flux by mirroring existing WordPress hookss in many places
 * allowing dependant plugins to hook into the Flux specific ones, thus
 * guaranteeing proper code execution only when Flux is active.
 *
 * The following functions are wrappers for hookss, allowing them to be
 * manually called and/or piggy-backed on top of other hooks if needed.
 */

/** Activation Actions ********************************************************/

/**
 * Runs on Flux activation
 *
 * @since Flux (0.1)
 * @uses register_uninstall_hook() To register our own uninstall hook
 * @uses do_action() Calls 'flux_activation' hook
 */
function flux_activation() {
	do_action( 'flux_activation' );
}

/**
 * Runs on Flux deactivation
 *
 * @since Flux (0.1)
 * @uses do_action() Calls 'flux_deactivation' hook
 */
function flux_deactivation() {
	do_action( 'flux_deactivation' );
}

/**
 * Runs when uninstalling Flux
 *
 * @since Flux (0.1)
 * @uses do_action() Calls 'flux_uninstall' hook
 */
function flux_uninstall() {
	do_action( 'flux_uninstall' );
}

/** Main Actions **************************************************************/

/**
 * Main action responsible for constants, globals, and includes
 *
 * @since Flux (0.1)
 * @uses do_action() Calls 'flux_loaded'
 */
function flux_loaded() {
	do_action( 'flux_loaded' );
}

/**
 * Setup constants
 *
 * @since Flux (0.1)
 * @uses do_action() Calls 'flux_constants'
 */
function flux_constants() {
	do_action( 'flux_constants' );
}

/**
 * Setup globals BEFORE includes
 *
 * @since Flux (0.1)
 * @uses do_action() Calls 'flux_boot_strap_globals'
 */
function flux_boot_strap_globals() {
	do_action( 'flux_boot_strap_globals' );
}

/**
 * Include files
 *
 * @since Flux (0.1)
 * @uses do_action() Calls 'flux_includes'
 */
function flux_includes() {
	do_action( 'flux_includes' );
}

/**
 * Setup globals AFTER includes
 *
 * @since Flux (0.1)
 * @uses do_action() Calls 'flux_setup_globals'
 */
function flux_setup_globals() {
	do_action( 'flux_setup_globals' );
}

/**
 * Register any objects before anything is initialized
 *
 * @since Flux (0.1)
 * @uses do_action() Calls 'flux_register'
 */
function flux_register() {
	do_action( 'flux_register' );
}

/**
 * Initialize any code after everything has been loaded
 *
 * @since Flux (0.1)
 * @uses do_action() Calls 'flux_init'
 */
function flux_init() {
	do_action( 'flux_init' );
}

/**
 * Initialize widgets
 *
 * @since Flux (0.1)
 * @uses do_action() Calls 'flux_widgets_init'
 */
function flux_widgets_init() {
	do_action( 'flux_widgets_init' );
}

/**
 * Setup the currently logged-in user
 *
 * @since Flux (0.1)
 * @uses do_action() Calls 'flux_setup_current_user'
 */
function flux_setup_current_user() {
	do_action( 'flux_setup_current_user' );
}

/** Supplemental Actions ******************************************************/

/**
 * Load translations for current language
 *
 * @since Flux (0.1)
 * @uses do_action() Calls 'flux_load_textdomain'
 */
function flux_load_textdomain() {
	do_action( 'flux_load_textdomain' );
}

/**
 * Sets up the theme directory
 *
 * @since Flux (0.1)
 * @uses do_action() Calls 'flux_register_theme_directory'
 */
function flux_register_theme_directory() {
	do_action( 'flux_register_theme_directory' );
}

/**
 * Setup the post types
 *
 * @since Flux (0.1)
 * @uses do_action() Calls 'flux_register_post_type'
 */
function flux_register_post_types() {
	do_action( 'flux_register_post_types' );
}

/**
 * Setup the post statuses
 *
 * @since Flux (0.1)
 * @uses do_action() Calls 'flux_register_post_statuses'
 */
function flux_register_post_statuses() {
	do_action( 'flux_register_post_statuses' );
}

/**
 * Register the built in Flux taxonomies
 *
 * @since Flux (0.1)
 * @uses do_action() Calls 'flux_register_taxonomies'
 */
function flux_register_taxonomies() {
	do_action( 'flux_register_taxonomies' );
}

/**
 * Register the default Flux views
 *
 * @since Flux (0.1)
 * @uses do_action() Calls 'flux_register_views'
 */
function flux_register_views() {
	do_action( 'flux_register_views' );
}

/**
 * Enqueue Flux specific CSS and JS
 *
 * @since Flux (0.1)
 * @uses do_action() Calls 'flux_enqueue_scripts'
 */
function flux_enqueue_scripts() {
	do_action( 'flux_enqueue_scripts' );
}

/**
 * Add the Flux-specific rewrite tags
 *
 * @since Flux (0.1)
 * @uses do_action() Calls 'flux_add_rewrite_tags'
 */
function flux_add_rewrite_tags() {
	do_action( 'flux_add_rewrite_tags' );
}

/** Final Action **************************************************************/

/**
 * Flux has loaded and initialized everything, and is okay to go
 *
 * @since Flux (0.1)
 * @uses do_action() Calls 'flux_ready'
 */
function flux_ready() {
	do_action( 'flux_ready' );
}

/** Theme Permissions *********************************************************/

/**
 * The main action used for redirecting Flux theme actions that are not
 * permitted by the current_user
 *
 * @since Flux (0.1)
 * @uses do_action()
 */
function flux_template_redirect() {
	do_action( 'flux_template_redirect' );
}

/** Theme Helpers *************************************************************/

/**
 * The main action used for executing code before the theme has been setup
 *
 * @since Flux (0.1)
 * @uses do_action()
 */
function flux_register_theme_packages() {
	do_action( 'flux_register_theme_packages' );
}

/**
 * The main action used for executing code before the theme has been setup
 *
 * @since Flux (0.1)
 * @uses do_action()
 */
function flux_setup_theme() {
	do_action( 'flux_setup_theme' );
}

/**
 * The main action used for executing code after the theme has been setup
 *
 * @since Flux (0.1)
 * @uses do_action()
 */
function flux_after_setup_theme() {
	do_action( 'flux_after_setup_theme' );
}

/**
 * The main action for executing code in the wp_head() action
 *
 * @since Flux (0.1)
 * @uses do_action()
 */
function flux_head() {
	do_action( 'flux_head' );
}

/**
 * The main action for executing code in the wp_head() action
 *
 * @since Flux (0.1)
 * @uses do_action()
 */
function flux_footer() {
	do_action( 'flux_footer' );
}

/** Filters *******************************************************************/

/**
 * Piggy back filter for WordPress's 'request' filter
 *
 * @since Flux (0.1)
 * @param array $query_vars
 * @return array
 */
function flux_request( $query_vars = array() ) {
	return apply_filters( 'flux_request', $query_vars );
}

/**
 * The main filter used for theme compatibility and displaying custom Flux
 * theme files.
 *
 * @since Flux (0.1)
 * @uses apply_filters()
 * @param string $template
 * @return string Template file to use
 */
function flux_template_include( $template = '' ) {
	return apply_filters( 'flux_template_include', $template );
}

/**
 * Generate Flux-specific rewrite rules
 *
 * @since Flux (0.1)
 * @param WP_Rewrite $wp_rewrite
 * @uses do_action() Calls 'flux_generate_rewrite_rules' with {@link WP_Rewrite}
 */
function flux_generate_rewrite_rules( $wp_rewrite ) {
	do_action_ref_array( 'flux_generate_rewrite_rules', array( &$wp_rewrite ) );
}

/** Templates *****************************************************************/

/**
 * The main action used for filtering the title
 *
 * @since Flux (0.1)
 * @param type $title
 * @uses apply_filters() Calls 'flux_title' to filter the title
 * @return string
 */
function flux_title( $title = '' ) {
	return apply_filters( 'flux_title', $title );
}

/**
 * The main action used for filtering the title
 *
 * @since Flux (0.1)
 * @param type $title
 * @uses apply_filters() Calls 'flux_body_class' to filter the body class
 * @return string
 */
function flux_body_class( $class = '' ) {
	return apply_filters( 'flux_body_class', $class );
}
