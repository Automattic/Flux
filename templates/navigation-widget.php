<?php

$history = flux_get_blog_history();

// No posts :(
if ( empty( $history ) )
	return;

$current_year = ( get_query_var( 'year' ) ) ? get_query_var( 'year' ) : date( 'Y' );
$current_month = ( get_query_var( 'monthnum' ) ) ? get_query_var( 'monthnum' ) : date( 'm' );

echo '<div class="year-selector" style="float:right">';
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

echo '<div class="month-selector" style="float:left">';
$months = wp_filter_object_list( $history, array( 'year' => $current_year ), 'and', 'month' );
echo '<ul>';
foreach( $months as $month ) {
	$month_link = '<a href="' . get_month_link( $current_year, $month ) . '">' . $month . '</a>';
	if ( $year == $current_month )
		$month_link = '<strong>' . $month_link . '</strong>';
	echo '<li>' . $month_link . '</li>';
}
echo '</ul>';
echo '</div>';