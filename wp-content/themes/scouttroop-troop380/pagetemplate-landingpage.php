<?php
/*
Template Name: Landing Page
*/
?>
<?php get_header(); ?>
	<div id="page-content">
		<div id="content-full">	
			<section>
				
				<?php if ( have_posts() ) : ?>

					<?php /* The loop */ ?>
					<?php while ( have_posts() ) : the_post(); ?>

						<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			    <?php
			        // The content
			        the_content();
			    ?>
						</article>
					<?php endwhile; ?>

				<?php else : ?>

					<?php get_template_part( 'content', 'none' ); ?>

				<?php endif; ?>

			</section>		
		</div> <!-- End content-wide -->
 	</div> <!-- End page-content -->

	<?php get_footer(); ?>