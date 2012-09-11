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
	<div id="flux-year-selector" style="float:left; width:70px">
		<ul>
			<?php foreach( $years as $year ) : ?>

				<?php

				$year_link = '<a href="' . get_year_link( $year ) . '">' . $year . '</a>';

				if ( $year == $current_year )
					$year_link = '<strong>' . $year_link . '</strong>';

				echo '<li>' . $year_link . '</li>';

				?>

			<?php endforeach; ?>

		</ul>
	</div>

	<div id="flux-month-selector" style="margin-left:80px">
		<ul>

			<?php foreach( $months as $month ) : ?>

				<?php

				$month_link = '<a href="' . get_month_link( $current_year, $month ) . '">' . $month . '</a>';

				if ( $month == $current_month )
					$month_link = '<strong>' . $month_link . '</strong>';

				echo '<li>' . $month_link . '</li>';

			?>

			<?php endforeach; ?>

		</ul>
	</div>
</div>
