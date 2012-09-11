<?php

$history = flux_get_blog_history();

// No posts :(
if ( empty( $history ) )
	return;

echo '<div id="flux-navigation" style="width:150px;position:fixed;">';

echo '<div id="flux-capacitor" style="border-right:5px solid #222;z-index:1000;height:100000px;float:right;">';
echo '</div>';

$current_year = ( get_query_var( 'year' ) ) ? get_query_var( 'year' ) : date( 'Y' );
$current_month = ( get_query_var( 'monthnum' ) ) ? get_query_var( 'monthnum' ) : date( 'n' );

echo '<div id="flux-year-selector" style="float:left;width:70px">';
$years = array_unique( wp_list_pluck( $history, 'year' ) );
echo '<ul>';
foreach( $years as $year ) {
	$year_link = '<a href="' . get_year_link( $year ) . '">' . $year . '</a>';
	if ( $year == $current_year )
		$year_link = '<strong>' . $year_link . '</strong>';
	echo '<li>' . $year_link . '</li>';
}
echo '</ul>';
echo '</div>';

echo '<div id="flux-month-selector" style="margin-left:80px">';
$months = wp_filter_object_list( $history, array( 'year' => $current_year ), 'and', 'month' );
echo '<ul>';
foreach( $months as $month ) {
	$month_link = '<a href="' . get_month_link( $current_year, $month ) . '">' . $month . '</a>';
	if ( $month == $current_month )
		$month_link = '<strong>' . $month_link . '</strong>';
	echo '<li>' . $month_link . '</li>';
}
echo '</ul>';
echo '</div>';

echo '</div>';