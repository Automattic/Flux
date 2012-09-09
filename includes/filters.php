<?php

/**
 * Flux Filters
 *
 * @package Flux
 * @subpackage Core
 *
 * This file contains the filters that are used through-out Flux. They are
 * consolidated here to make searching for them easier, and to help developers
 * understand at a glance the order in which things occur.
 *
 * There are a few common places that additional filters can currently be found
 *
 *  - Flux: In {@link Flux::setup_actions()} in flux.php
 *  - Component: In {@link BBP_Component::setup_actions()} in
 *                bbp-includes/bbp-classes.php
 *  - Admin: More in {@link BBP_Admin::setup_actions()} in
 *            bbp-admin/bbp-admin.php
 *
 * @see bbp-core-actions.php
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Attach Flux to WordPress
 *
 * Flux uses its own internal actions to help aid in third-party plugin
 * development, and to limit the amount of potential future code changes when
 * updates to WordPress core occur.
 *
 * These actions exist to create the concept of 'plugin dependencies'. They
 * provide a safe way for plugins to execute code *only* when Flux is
 * installed and activated, without needing to do complicated guesswork.
 *
 * For more information on how this works, see the 'Plugin Dependency' section
 * near the bottom of this file.
 *
 *           v--WordPress Actions       v--Flux Sub-actions
 */
add_filter( 'request',                 'flux_request',            10    );
add_filter( 'template_include',        'flux_template_include',   10    );
add_filter( 'wp_title',                'flux_title',              10, 3 );
add_filter( 'body_class',              'flux_body_class',         10, 2 );

// Force comments_status on Flux post types
//add_filter( 'comments_open', 'flux_force_comment_status' );

/**
 * Feeds
 *
 * Flux comes with a number of custom RSS2 feeds that get handled outside
 * the normal scope of feeds that WordPress would normally serve. To do this,
 * we filter every page request, listen for a feed request, and trap it.
 */
//add_filter( 'flux_request', 'flux_request_feed_trap' );

/**
 * Template Compatibility
 *
 * If you want to completely bypass this and manage your own custom Flux
 * template hierarchy, start here by removing this filter, then look at how
 * flux_template_include() works and do something similar. :)
 */
//add_filter( 'flux_template_include', 'flux_template_include_theme_supports', 2, 1 );
//add_filter( 'flux_template_include', 'flux_template_include_theme_compat',   4, 2 );

// Queries
//add_filter( 'posts_request', '_flux_has_replies_where', 10, 2 );
