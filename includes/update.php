<?php

/**
 * Flux Updater
 *
 * @package Flux
 * @subpackage Updater
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * If there is no raw DB version, this is the first installation
 *
 * @since Flux (r3764)
 *
 * @uses get_option()
 * @uses flux_get_db_version() To get Flux's database version
 * @return bool True if update, False if not
 */
function flux_is_install() {
	return ! flux_get_db_version_raw();
}

/**
 * Compare the Flux version to the DB version to determine if updating
 *
 * @since Flux (r3421)
 *
 * @uses get_option()
 * @uses flux_get_db_version() To get Flux's database version
 * @return bool True if update, False if not
 */
function flux_is_update() {
	$raw    = (int) flux_get_db_version_raw();
	$cur    = (int) flux_get_db_version();
	$retval = (bool) ( $raw < $cur );
	return $retval;
}

/**
 * Determine if Flux is being activated
 *
 * Note that this function currently is not used in Flux core and is here
 * for third party plugins to use to check for Flux activation.
 *
 * @since Flux (0.1)
 *
 * @return bool True if activating Flux, false if not
 */
function flux_is_activation( $basename = '' ) {

	$action = false;
	if ( ! empty( $_REQUEST['action'] ) && ( '-1' != $_REQUEST['action'] ) )
		$action = $_REQUEST['action'];
	elseif ( ! empty( $_REQUEST['action2'] ) && ( '-1' != $_REQUEST['action2'] ) )
		$action = $_REQUEST['action2'];

	// Bail if not activating
	if ( empty( $action ) || !in_array( $action, array( 'activate', 'activate-selected' ) ) )
		return false;

	// The plugin(s) being activated
	if ( $action == 'activate' )
		$plugins = isset( $_GET['plugin'] ) ? array( $_GET['plugin'] ) : array();
	else
		$plugins = isset( $_POST['checked'] ) ? (array) $_POST['checked'] : array();

	// Set basename if empty
	if ( empty( $basename ) && !empty( flux()->basename ) )
		$basename = flux()->basename;

	// Bail if no basename
	if ( empty( $basename ) )
		return false;

	// Is Flux being activated?
	return in_array( $basename, $plugins );
}

/**
 * Determine if Flux is being deactivated
 *
 * @since Flux (0.1)
 * @return bool True if deactivating Flux, false if not
 */
function flux_is_deactivation( $basename = '' ) {

	$action = false;
	if ( ! empty( $_REQUEST['action'] ) && ( '-1' != $_REQUEST['action'] ) )
		$action = $_REQUEST['action'];
	elseif ( ! empty( $_REQUEST['action2'] ) && ( '-1' != $_REQUEST['action2'] ) )
		$action = $_REQUEST['action2'];

	// Bail if not deactivating
	if ( empty( $action ) || !in_array( $action, array( 'deactivate', 'deactivate-selected' ) ) )
		return false;

	// The plugin(s) being deactivated
	if ( $action == 'deactivate' )
		$plugins = isset( $_GET['plugin'] ) ? array( $_GET['plugin'] ) : array();
	else
		$plugins = isset( $_POST['checked'] ) ? (array) $_POST['checked'] : array();

	// Set basename if empty
	if ( empty( $basename ) && !empty( flux()->basename ) )
		$basename = flux()->basename;

	// Bail if no basename
	if ( empty( $basename ) )
		return false;

	// Is Flux being deactivated?
	return in_array( $basename, $plugins );
}

/**
 * Update the DB to the latest version
 *
 * @since Flux (r3421)
 * @uses update_option()
 * @uses flux_get_db_version() To get Flux's database version
 */
function flux_version_bump() {
	$db_version = flux_get_db_version();
	update_option( '_flux_db_version', $db_version );
}

/**
 * Setup the Flux updater
 *
 * @since Flux (r3419)
 *
 * @uses flux_version_updater()
 * @uses flux_version_bump()
 * @uses flush_rewrite_rules()
 */
function flux_setup_updater() {

	// Bail if no update needed
	if ( ! flux_is_update() )
		return;

	// Call the automated updater
	flux_version_updater();
}

/**
 * Flux's version updater looks at what the current database version is, and
 * runs whatever other code is needed.
 *
 * This is most-often used when the data schema changes, but should also be used
 * to correct issues with Flux meta-data silently on software update.
 *
 * @since Flux (r4104)
 */
function flux_version_updater() {

	// Get the raw database version
	$raw_db_version = (int) flux_get_db_version_raw();


	/** All done! *************************************************************/

	// Bump the version
	flux_version_bump();

	// Delete rewrite rules to force a flush
	flux_delete_rewrite_rules();
}
