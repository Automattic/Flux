<?php

$history = flux_get_blog_history();
if ( empty( $history ) )
	return;

$current_year  = ( get_query_var( 'year'     ) ) ? get_query_var( 'year'     ) : date( 'Y' );
$current_month = ( get_query_var( 'monthnum' ) ) ? get_query_var( 'monthnum' ) : date( 'n' );
$years         = array_unique( wp_list_pluck( $history, 'year' ) );
$year_months = array();
foreach( $years as $year ) {
	$year_months[$year] = wp_filter_object_list( $history, array( 'year' => $year ), 'and', 'month' );
}

?>

<div class="flux-capacitor" style="position: absolute;">
	<div class="flux-year-selector">
		<ul>
			<?php foreach( $years as $year ) {
				$active_year = ( $year == $current_year ) ? true : false;
				$classes = array(
						'flux-year',
						'flux-year-' . $year,
					);
				if ( $active_year )
					$classes[] = 'flux-year-active';
				$classes = apply_filters( 'flux_year_selector_classes', $classes );
				$year_link = '<a class="' . implode( ' ', $classes ) . '" href="' . get_year_link( $year ) . '">' . $year . '</a>';
				echo '<li>' . $year_link . '</li>';
			} ?>

		</ul>
	</div>

	<?php foreach( $year_months as $year => $months ): ?>
	<div class="flux-month-selector" id="<?php echo 'flux-month-selector-' . $year; ?>"<?php if ( $current_year != $year ) echo ' style="display:none"'; ?>>
		<ul>
			<?php foreach( $months as $month ) {
				$active_month = ( $month == $current_month ) ? true : false;
				$classes = array(
						'flux-month',
						'flux-month-' . $month,
					);
				if ( $active_month )
					$classes[] = 'flux-month-active';
				$month_link = '<a class="' . implode( ' ', $classes ) . '" href="' . get_month_link( $current_year, $month ) . '">' . $month . '</a>';
				echo '<li>' . $month_link . '</li>';
			} ?>
		</ul>
	</div>
	<?php endforeach; ?>
</div>
