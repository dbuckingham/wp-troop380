<?php get_header(); ?>
	<div id="page-content">
		<div id="content-full">
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
						$content = get_the_content();

						if( strlen($content) > 0 )
						{
							// The content
							the_content();
						}
						else
						{
							?>
								<div class="eagle-scout-default-content">
									<p>Troop 380 would love to showcase more about our Eagle Scouts!  Showcasing information about 
									your Eagle Scout Service Project, merit badges you earned, or stories about your experience 
									in Scouting could offer encouragement to current scouts!</p>

									<p>If you would like to include more information on this page, please e-mail <a href="mailto:webmaster@bsa380.com?subject=My%20Eagle%20Scout%20update%20for%20the%20Eagles%20Nest">webmaster@bsa380.com</a></p>
								</div>
							<?php
						}
					?>
				</article>

			</section>		
		</div> <!-- End content-wide -->

 	</div> <!-- End page-content -->

<?php get_footer(); ?>