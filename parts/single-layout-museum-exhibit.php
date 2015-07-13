<section class="row side-right gutter pad-ends">

	<div class="column one">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'articles/post', get_post_type() ) ?>

		<?php endwhile; ?>

	</div><!--/column-->

	<div class="column two">
		<?php echo apply_filters( 'the_content', wsu_museum_get_sidebar_content() ); ?>
	</div>

</section>

<section class="row single gutter pad-ends museum-gallery">
	<div class="column one">
		<?php echo apply_filters( 'the_content', wsu_museum_get_gallery_content() ); ?>
	</div>
</section>