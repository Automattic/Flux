<div style="position:relative;float:left;">
<?php flux_get_template_part( 'navigation' ); ?>
</div>

<?php
$args = array(
		'post_type'       => 'post',
	);
$flux_posts = new WP_Query( $args );
?>

<div id="flux-content" style="margin-left:200px;">

	<?php if ( $flux_posts->have_posts() ): ?>

	<?php while ( $flux_posts->have_posts() ): $flux_posts->the_post(); ?>

		<div class="flux-post">

		<?php flux_get_template_part( 'content', get_post_format() ); ?>

		</div>

	<?php endwhile; ?>

	<?php endif; ?>

</div>