<?php

$history = flux_get_blog_history();
if ( empty( $history ) )
	return;

$current_year  = ( get_query_var( 'year'     ) ) ? get_query_var( 'year'     ) : date( 'Y' );
$current_month = ( get_query_var( 'monthnum' ) ) ? get_query_var( 'monthnum' ) : date( 'n' );
$years         = array_unique( wp_list_pluck( $history, 'year' ) );
$months        = wp_filter_object_list( $history, array( 'year' => $current_year ), 'and', 'month' );

?>

<div id="flux-capacitor" style="position: absolute;">
	<div id="flux-year-selector">
		<ul>
			<?php foreach( $years as $year ) {
				$active_year = ( $year == $current_year ) ? true : false;
				$classes = array(
						'flux-year',
					);
				if ( $active_year )
					$classes[] = 'flux-year-active';
				$classes = apply_filters( 'flux_year_selector_classes', $classes );
				$year_link = '<a class="' . implode( ' ', $classes ) . '" href="' . get_year_link( $year ) . '">' . $year . '</a>';
				echo '<li>' . $year_link . '</li>';
			} ?>

		</ul>
	</div>

	<div id="flux-month-selector">
		<ul>
			<?php foreach( $months as $month ) {
				$active_month = ( $month == $current_month ) ? true : false;
				$classes = array(
						'flux-month',
					);
				if ( $active_month )
					$classes[] = 'flux-month-active';
				$month_link = '<a class="' . implode( ' ', $classes ) . '" href="' . get_month_link( $current_year, $month ) . '">' . $month . '</a>';
				echo '<li>' . $month_link . '</li>';
			} ?>
		</ul>
	</div>
</div>
