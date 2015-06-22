<?php

get_header();

?>

<main>

	<?php

	get_template_part('parts/headers');

	?><div class="cycle-slideshow builder-section-banner" data-cycle-slides=".exhibit-banner-slide" data-cycle-log="false"><?php
	foreach( wsu_museum_get_slides() as $exhibit_slide ) {
		?><div class="exhibit-banner-slide" style="background-image: url('<?php echo esc_url( $exhibit_slide ); ?>');"></div><?php
	}
		?>
		<div class="cycle-pager"></div>
	</div><?php

	get_template_part( 'parts/single-layout', get_post_type() );
	?>

	<footer class="main-footer">
		<section class="row halves pager prevnext gutter pad-ends">
			<div class="column one">
				<?php previous_post_link(); ?>
			</div>
			<div class="column two">
				<?php next_post_link(); ?>
			</div>
		</section><!--pager-->
	</footer>

	<?php get_template_part( 'parts/footers' ); ?>

</main><!--/#page-->

<?php get_footer(); ?>