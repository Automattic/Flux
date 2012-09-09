<?php

function flux_get_id() {
	return apply_filters( 'flux_get_id', 'flux_id' );
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