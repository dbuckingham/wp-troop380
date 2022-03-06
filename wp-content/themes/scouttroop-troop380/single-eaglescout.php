<?php get_header(); ?>
	<div id="page-content">
		<div id="content-wide">
			<!-- Eagle Scout Post from Theme -->
			<section>					
		
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header>
					<?php
					if ( is_single() or is_page()  ) {
					?>
						<h1 class="entry-title">
							<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark">
								<?php the_title(); ?>
							</a>
						</h1>
						
						<h2 class="eagle-scout-class-of">Eagle Scout Class of <?php echo get_post_meta($post->ID, 'year_earned', true); ?></h2>
						
						<?php
						$board_of_review_date_is_real = (get_post_meta($post->ID, 'board_of_review_date_is_real', true) == "on");
						if( $board_of_review_date_is_real )
						{
						?>
						<span class="eagle-scout-meta">Earned on <?php echo date( get_option('date_format'), strtotime( get_post_meta($post->ID, 'board_of_review_date', true) ) ); ?><span>
						<?php 
						}
						?>

						<?php
						$scoutmaster = get_post_meta($post->ID, 'scoutmaster', true);
						if( $scoutmaster != "" )
						{
						?>
						<br /><span class="eagle-scout-meta"><?php echo get_post_meta($post->ID, 'scoutmaster', true); ?>, Scoutmaster</span>
						<?php
						}
						?>
					<?php
					}
					?>
					</header>

					<?php
						// The content
						the_content();
								
								?><p><?php the_tags(); ?></p><?php
								wp_link_pages('before=<p>&after= </p>&next_or_number=number&pagelink=page %');
						
						// If singular and comments are open
						if ( is_singular() && comments_open() )
						comments_template( '', true );
					?>
				</article>

			</section>		
		</div> <!-- End content-wide -->

 	</div> <!-- End page-content -->

<?php get_footer(); ?>