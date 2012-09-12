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
add_filter( 'post_class',              'flux_post_class',         10, 2 );
add_filter( 'sidebars_widgets',        'flux_sidebar_widgets',    10    );

// Template Compatibility
add_filter( 'flux_template_include', 'flux_template_include_theme_compat',   4, 2 );

// Theme Classes
add_filter( 'flux_body_class', 'flux_add_body_class', 10, 2 );
add_filter( 'flux_post_class', 'flux_add_post_class', 10, 3 );
