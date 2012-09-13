<div id="flux-wrapper" style="position: relative;">

	<div id="flux-navigation">

		<div id="flux-capacitor"></div>

		<?php flux_get_template_part( 'navigation' ); ?>

	</div>

	<?php
		$flux_posts = new WP_Query( array(
			'post_type'               => 'post',
			'ignore_sticky_posts'     => 1,
		) );
	?>

	<div id="flux-content" style="margin-left: 200px;">

		<?php if ( $flux_posts->have_posts() ): ?>

		<?php while ( $flux_posts->have_posts() ): $flux_posts->the_post(); ?>

			<div class="flux-post">

			<?php flux_get_template_part( 'content', get_post_format() ); ?>

			</div>

		<?php endwhile; ?>

		<?php endif; ?>

	</div>
</div>
