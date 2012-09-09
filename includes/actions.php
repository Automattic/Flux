<?php

/**
 * Flux Actions
 *
 * @package Flux
 * @subpackage Core
 *
 * This file contains the actions that are used through-out Flux. They are
 * consolidated here to make searching for them easier, and to help developers
 * understand at a glance the order in which things occur.
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
 *           v--WordPress Actions      v--Flux Sub-actions
 */
add_action( 'plugins_loaded',         'flux_loaded',                 10 );
add_action( 'init',                   'flux_init',                   0  ); // Early for flux_register
add_action( 'parse_query',            'flux_parse_query',            2  ); // Early for overrides
add_action( 'widgets_init',           'flux_widgets_init',           10 );
add_action( 'generate_rewrite_rules', 'flux_generate_rewrite_rules', 10 );
add_action( 'wp_enqueue_scripts',     'flux_enqueue_scripts',        10 );
add_action( 'wp_head',                'flux_head',                   10 );
add_action( 'wp_footer',              'flux_footer',                 10 );
add_action( 'set_current_user',       'flux_setup_current_user',     10 );
add_action( 'setup_theme',            'flux_setup_theme',            10 );
add_action( 'after_setup_theme',      'flux_after_setup_theme',      10 );
add_action( 'template_redirect',      'flux_template_redirect',      10 );

/**
 * flux_loaded - Attached to 'plugins_loaded' above
 *
 * Attach various loader actions to the flux_loaded action.
 * The load order helps to execute code at the correct time.
 *                                                           v---Load order
 */
add_action( 'flux_loaded', 'flux_constants',                 2  );
add_action( 'flux_loaded', 'flux_boot_strap_globals',        4  );
add_action( 'flux_loaded', 'flux_includes',                  6  );
add_action( 'flux_loaded', 'flux_setup_globals',             8  );
add_action( 'flux_loaded', 'flux_setup_option_filters',      10 );
add_action( 'flux_loaded', 'flux_register_theme_packages',   16 );
add_action( 'flux_loaded', 'flux_load_textdomain',           18 );

/**
 * flux_init - Attached to 'init' above
 *
 * Attach various initialization actions to the init action.
 * The load order helps to execute code at the correct time.
 *                                                v---Load order
 */
add_action( 'flux_init', 'flux_register',         0   );
add_action( 'flux_init', 'flux_add_rewrite_tags', 20  );
add_action( 'flux_init', 'flux_ready',            999 );

// Register the codez
add_action( 'flux_register', 'flux_register_shortcodes',     10 );

// Try to load the flux-functions.php file from the active themes
add_action( 'flux_after_setup_theme', 'flux_load_theme_functions', 10 );