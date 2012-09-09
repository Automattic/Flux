<?php

/**
 * The Flux Plugin
 *
 * Flux is forum software with a twist from the creators of WordPress.
 *
 * $Id: flux.php 4204 2012-09-04 19:04:23Z bumpbot $
 *
 * @package Flux
 * @subpackage Main
 */

/**
 * Plugin Name: Flux
 * Plugin URI:  http://flux.org
 * Description: Flux is forum software with a twist from the creators of WordPress.
 * Author:      The Flux Community
 * Author URI:  http://flux.org
 * Version:     0.1
 * Text Domain: flux
 * Domain Path: /languages/
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'Flux' ) ) :
/**
 * Main Flux Class
 *
 * Tap tap tap... Is this thing on?
 *
 * @since Flux (0.1)
 */
final class Flux {

	/** Magic *****************************************************************/

	/**
	 * Flux uses many variables, most of which can be filtered to customize
	 * the way that it works. To prevent unauthorized access, these variables
	 * are stored in a private array that is magically updated using PHP 5.2+
	 * methods. This is to prevent third party plugins from tampering with
	 * essential information indirectly, which would cause issues later.
	 *
	 * @see Flux::setup_globals()
	 * @var array
	 */
	private $data;

	/** Not Magic *************************************************************/

	/**
	 * @var mixed False when not logged in; WP_User object when logged in
	 */
	public $current_user = false;

	/**
	 * @var obj Add-ons append to this (Akismet, BuddyPress, etc...)
	 */
	public $extend;

	/**
	 * @var array Topic views
	 */
	public $views        = array();

	/**
	 * @var array Overloads get_option()
	 */
	public $options      = array();

	/**
	 * @var array Overloads get_user_meta()
	 */
	public $user_options = array();

	/** Singleton *************************************************************/

	/**
	 * @var Flux The one true Flux
	 */
	private static $instance;

	/**
	 * Main Flux Instance
	 *
	 * Flux is fun
	 * Please load it only one time
	 * For this, we thank you
	 *
	 * Insures that only one instance of Flux exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since Flux (0.1)
	 * @staticvar array $instance
	 * @uses Flux::setup_globals() Setup the globals needed
	 * @uses Flux::includes() Include the required files
	 * @uses Flux::setup_actions() Setup the hooks and actions
	 * @see flux()
	 * @return The one true Flux
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new Flux;
			self::$instance->setup_data();
			self::$instance->includes();
			self::$instance->setup_actions();
		}
		return self::$instance;
	}

	/** Magic Methods *********************************************************/

	/**
	 * A dummy constructor to prevent Flux from being loaded more than once.
	 *
	 * @since Flux (0.1)
	 * @see Flux::instance()
	 * @see flux();
	 */
	private function __construct() { /* Do nothing here */ }

	/**
	 * A dummy magic method to prevent Flux from being cloned
	 *
	 * @since Flux (0.1)
	 */
	public function __clone() { _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'flux' ), '2.1' ); }

	/**
	 * A dummy magic method to prevent Flux from being unserialized
	 *
	 * @since Flux (0.1)
	 */
	public function __wakeup() { _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'flux' ), '2.1' ); }

	/**
	 * Magic method for checking the existence of a certain custom field
	 *
	 * @since Flux (0.1)
	 */
	public function __isset( $key ) { return isset( $this->data[$key] ); }

	/**
	 * Magic method for getting Flux varibles
	 *
	 * @since Flux (0.1)
	 */
	public function __get( $key ) { return isset( $this->data[$key] ) ? $this->data[$key] : null; }

	/**
	 * Magic method for setting Flux varibles
	 *
	 * @since Flux (0.1)
	 */
	public function __set( $key, $value ) { $this->data[$key] = $value; }

	/** Private Methods *******************************************************/

	/**
	 * Set some smart defaults to class variables. Allow some of them to be
	 * filtered to allow for early overriding.
	 *
	 * @since Flux (0.1)
	 * @access private
	 * @uses plugin_dir_path() To generate Flux plugin path
	 * @uses plugin_dir_url() To generate Flux plugin url
	 * @uses apply_filters() Calls various filters
	 */
	private function setup_data() {

		/** Versions **********************************************************/

		$this->version    = '0.1';
		$this->db_version = '1';

		/** Paths *************************************************************/

		// Setup some base path and URL information
		$this->file          = __FILE__;
		$this->basename      = apply_filters( 'flux_plugin_basenname', plugin_basename( $this->file ) );
		$this->plugin_dir    = apply_filters( 'flux_plugin_dir_path',  plugin_dir_path( $this->file ) );
		$this->plugin_url    = apply_filters( 'flux_plugin_dir_url',   plugin_dir_url ( $this->file ) );

		// Includes
		$this->includes_dir  = apply_filters( 'flux_includes_path',    trailingslashit( $this->plugin_dir . 'includes'  ) );

		// Templates
		$this->templates_dir = apply_filters( 'flux_templates_dir',    trailingslashit( $this->plugin_dir . 'templates' ) );
		$this->templates_url = apply_filters( 'flux_templates_url',    trailingslashit( $this->plugin_url . 'templates' ) );

		// Languages
		$this->lang_dir      = apply_filters( 'flux_lang_dir',         trailingslashit( $this->plugin_dir . 'languages' ) );

		/** Theme Compat ******************************************************/

		$this->theme_compat   = new stdClass(); // Base theme compatibility class
		$this->filters        = new stdClass(); // Used when adding/removing filters

		/** Users *************************************************************/

		$this->current_user   = new stdClass(); // Currently logged in user
		$this->displayed_user = new stdClass(); // Currently displayed user

		/** Misc **************************************************************/

		$this->extend         = new stdClass(); // Plugins add data here
		$this->errors         = new WP_Error(); // Feedback
		$this->tab_index      = apply_filters( 'flux_default_tab_index', 100 );

		/** Admin *************************************************************/

		if ( is_admin() ) {
			$this->admin_dir        = trailingslashit( $this->plugin_dir . 'admin'  ); // Admin path
			$this->admin_url        = trailingslashit( $this->plugin_url . 'admin'  ); // Admin url
			$this->admin_images_url = trailingslashit( $this->admin_url  . 'images' ); // Admin images URL
			$this->admin_styles_url = trailingslashit( $this->admin_url  . 'styles' ); // Admin styles URL
		}

		/** Cache *************************************************************/

		// Add Flux to global cache groups
		wp_cache_add_global_groups( 'flux' );
	}

	/**
	 * Include required files
	 *
	 * @since Flux (0.1)
	 * @access private
	 * @uses is_admin() If in WordPress admin, load additional file
	 */
	private function includes() {

		/** Core **************************************************************/

		require( $this->includes_dir . 'dependency.php' ); // Core dependencies
		require( $this->includes_dir . 'functions.php'  ); // Core functions
		require( $this->includes_dir . 'cache.php'      ); // Cache helpers
		require( $this->includes_dir . 'options.php'    ); // Configuration options
		require( $this->includes_dir . 'widgets.php'    ); // Sidebar widgets
		require( $this->includes_dir . 'shortcodes.php' ); // Shortcodes for use with pages and posts
		require( $this->includes_dir . 'update.php'     ); // Database updater
		require( $this->includes_dir . 'template.php'   ); // Template functions

		/** Hooks *************************************************************/

		require( $this->includes_dir . 'actions.php'    ); // All actions
		require( $this->includes_dir . 'filters.php'    ); // All filters

		/** Admin *************************************************************/

		// Quick admin check and load if needed
		if ( is_admin() ) {
			require( $this->admin_dir . 'admin.php'   );
			require( $this->admin_dir . 'actions.php' );
		}
	}

	/**
	 * Setup the default hooks and actions
	 *
	 * @since Flux (0.1)
	 * @access private
	 * @uses add_action() To add various actions
	 */
	private function setup_actions() {

		// Add actions to plugin activation and deactivation hooks
		add_action( 'activate_'   . $this->basename, 'flux_activation'   );
		add_action( 'deactivate_' . $this->basename, 'flux_deactivation' );

		// If Flux is being deactivated, do not add any actions
		if ( flux_is_deactivation( $this->basename ) )
			return;

		// Array of Flux core actions
		$actions = array(
			'setup_theme',              // Setup the default theme compat
			'setup_current_user',       // Setup currently logged in user
			'load_textdomain',          // Load textdomain (flux)
			'add_rewrite_tags',         // Add rewrite tags (view|user|edit)
			'generate_rewrite_rules'    // Generate rewrite rules (view|edit)
		);

		// Add the actions
		foreach( $actions as $class_action )
			add_action( 'flux_' . $class_action, array( $this, $class_action ), 5 );

		// All Flux actions are setup (includes bbp-core-hooks.php)
		do_action_ref_array( 'flux_after_setup_actions', array( &$this ) );
	}

	/** Public Methods ********************************************************/

	/**
	 * Setup the default Flux theme compatability location.
	 *
	 * @since Flux (0.1)
	 */
	public function setup_theme() {

		// Bail if something already has this under control
		if ( ! empty( $this->theme_compat->theme ) )
			return;

		// Setup the theme package to use for compatibility
		
	}

	/**
	 * Load the translation file for current language. Checks the languages
	 * folder inside the Flux plugin first, and then the default WordPress
	 * languages folder.
	 *
	 * Note that custom translation files inside the Flux plugin folder
	 * will be removed on Flux updates. If you're creating custom
	 * translation files, please use the global language folder.
	 *
	 * @since Flux (0.1)
	 *
	 * @uses apply_filters() Calls 'flux_locale' with the
	 *                        {@link get_locale()} value
	 * @uses load_textdomain() To load the textdomain
	 * @return bool True on success, false on failure
	 */
	public function load_textdomain() {
		$locale = get_locale();                                      // Default locale
		$locale = apply_filters( 'plugin_locale', $locale, 'flux' ); // Traditional WordPress plugin locale filter
		$locale = apply_filters( 'flux_locale',   $locale         ); // Flux specific locale filter
		$mofile = sprintf( 'flux-%s.mo', $locale );                  // Get mo file name

		// Setup paths to current locale file
		$mofile_local  = $this->lang_dir . $mofile;
		$mofile_global = WP_LANG_DIR . '/flux/' . $mofile;

		// Look in local /wp-content/plugins/flux/bbp-languages/ folder
		if ( file_exists( $mofile_local ) ) {
			return load_textdomain( 'flux', $mofile_local );

		// Look in global /wp-content/languages/flux folder
		} elseif ( file_exists( $mofile_global ) ) {
			return load_textdomain( 'flux', $mofile_global );
		}

		// Nothing found
		return false;
	}

	/**
	 * Register the Flux views
	 *
	 * @since Flux (0.1)
	 */
	public static function register_views() {

	}

	/**
	 * Setup the currently logged-in user
	 *
	 * Do not to call this prematurely, I.E. before the 'init' action has
	 * started. This function is naturally hooked into 'init' to ensure proper
	 * execution. get_currentuserinfo() is used to check for XMLRPC_REQUEST to
	 * avoid xmlrpc errors.
	 *
	 * @since Flux (0.1)
	 * @uses wp_get_current_user()
	 */
	public function setup_current_user() {
		$this->current_user = &wp_get_current_user();
	}

	/** Custom Rewrite Rules **************************************************/

	/**
	 * Add the Flux-specific rewrite tags
	 *
	 * @since Flux (0.1)
	 * @uses add_rewrite_tag() To add the rewrite tags
	 */
	public static function add_rewrite_tags() {
		add_rewrite_tag( '%%' . flux_get_id() . '%%', '([^/]+)' ); // Timeline tag
	}

	/**
	 * Register Flux-specific rewrite rules for uri's that are not
	 * setup for us by way of custom post types or taxonomies. This includes:
	 * - Front-end editing
	 * - Topic views
	 * - User profiles
	 *
	 * @since Flux (0.1)
	 * @param WP_Rewrite $wp_rewrite Flux-sepecific rules are appended in
	 *                                $wp_rewrite->rules
	 */
	public static function generate_rewrite_rules( $wp_rewrite ) {

		// Slugs
		$flux_slug = flux_get_slug();
		$flux_id   = flux_get_id();

		// Rewrite rule matches used repeatedly below
		$root_rule = '/?$';
		$feed_rule = '/feed/?$';
		$page_rule = '/page/?([0-9]{1,})/?$';

		// New Flux specific rules to merge with existing that are not
		// handled automatically by custom post types or taxonomy types
		$rules = array(
			$flux_slug . $page_rule => 'index.php?' . $flux_id . '=archive&paged=' . $wp_rewrite->preg_index( 2 ),
			$flux_slug . $feed_rule => 'index.php?' . $flux_id . '=archive&feed='  . $wp_rewrite->preg_index( 2 ),
			$flux_slug . $root_rule => 'index.php?' . $flux_id . '=archive',
		);

		// Merge Flux rules with existing
		$wp_rewrite->rules = array_merge( $rules, $wp_rewrite->rules );

		// Return merged rules
		return $wp_rewrite;
	}
}

/**
 * The main function responsible for returning the one true Flux Instance
 * to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $f = flux(); ?>
 *
 * @return The one true Flux Instance
 */
function flux() {
	return flux::instance();
}

/**
 * Hook Flux early onto the 'plugins_loaded' action.
 *
 * This gives all other plugins the chance to load before Flux, to get their
 * actions, filters, and overrides setup without Flux being in the way.
 */
if ( defined( 'FLUX_LATE_LOAD' ) ) {
	add_action( 'plugins_loaded', 'flux', (int) FLUX_LATE_LOAD );

// "And now here's something we hope you'll really like!"
} else {
	flux();
}

endif; // class_exists check
