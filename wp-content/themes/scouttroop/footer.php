<?php if (is_page( array( 3,5,7,9,11,13,15,17,19,21,23,25,27,29,31,33,34,37,38,41,43,45,47,49,51,53,55,57,59,61,63,66,71,73,75,77,79,81,83,85,87,91,93,95,97,103,105,107,109,111,113,115,117,119,121,123,134,138,143,157,166,171,173,175,177,179,181,183,185,187,191,193,195,197,200 ) ) or is_single( array(3,5,7,9,11,13,15,17,19,21,23,34,38,43,57,66,71,73,75,77,79,81,83,85,87,91,93,95,97,103,105,107,109,111,113,115,117,119,121,123,134,138,143,157,166,171,173,175,177,179,181,183,185,187,191,193,195,197,200,210,220,228,230,231,240,250,260,270,280,290,300,305,306,307,308,310,320,330,340,350,360,370,380,390,400,410,420,430,440,450,460,470,480,490,500,510,520,530,540,550,560,570,580,590,600,601,602,605,610,615,620,625,630,640,650,660,670,680,690,700,701,710,720,730,740,750,760,770,780,790,800,801,820,830,840,850,860,870,880,890,891,900,910,920,930,940,950,960,970,980,990 ))) $precred = "<a href='http://troop545.org'>WordPress theme courtesy of Troop 545.</a>"; else $precred="Powered By <a href='http://www.wordpress.org'>WordPress</a>";?>		
	<footer>
		<div class="footer-third">	
			<?php
			if ( is_active_sidebar( 'footer-sidebar' ) ) : ?>
				<div id="tertiary" class="sidebar-container footer" role="complementary">
					<div class="sidebar-inner">
						<div class="widget-area">
							<?php dynamic_sidebar( 'footer-sidebar' ); ?>
						</div><!-- .widget-area -->
					</div><!-- .sidebar-inner -->
				</div><!-- #tertiary -->
			<?php else: ?><?php get_search_form('Search . . .'); ?>
			<?php endif; ?>
		</div>

		<div class="footer-third">
			<?php
			if ( is_active_sidebar( 'footer-middle-sidebar' ) ) : ?>
				<div id="footer-middle-sidebar" class="sidebar-container footer" role="complementary">
					<div class="sidebar-inner">
						<div class="widget-area">
							<?php dynamic_sidebar( 'footer-middle-sidebar' ); ?>
						</div><!-- .widget-area -->
					</div><!-- .sidebar-inner -->
				</div><!-- #footer-middle-sidebar -->
			<?php else: ?>&nbsp;
			<?php endif; ?>		
		</div>

		<div class="footer-third">
			<?php
			if ( is_active_sidebar( 'footer-right-sidebar' ) ) : ?>
				<div id="footer-right-sidebar" class="sidebar-container footer" role="complementary">
					<div class="sidebar-inner">
						<div class="widget-area">
							<?php dynamic_sidebar( 'footer-right-sidebar' ); ?>
						</div><!-- .widget-area -->
					</div><!-- .sidebar-inner -->
				</div><!-- #footer-right-sidebar -->
			<?php else: ?><img id="footer-prepared" src="<?php echo get_template_directory_uri(); ?>/images/prepared_standard_rev.gif" alt="Do Your Best!">	
			<?php endif; ?>			
		</div>
		
		<div class="clear-both"></div>
	</footer>
	<div id="credit-left">
		<div class="no-mobile">
			&copy; <?php echo date("Y"); ?> <?php bloginfo( 'name' ); ?> - <a href="http://www.scouting.org/">Boy Scouts of America</a> | <a href="<?php echo admin_url(); ?>"><span class="no-mobile">WordPress </span>Admin</a>
		</div>
		<div class="mobile-only">
			&copy; <?php echo date("Y"); ?> <?php bloginfo( 'name' ); ?> - <a href="http://www.scouting.org/">Boy Scouts of America</a><br /><a href="<?php echo admin_url(); ?>"><span class="no-mobile">WordPress </span>Admin</a>
		</div>
	</div>
	
	<div id="credit">
		<div class="no-mobile">
			<?php echo $precred; ?> | <a href="http://www.handsomeweb.com/scouts/">Get this WordPress theme for your troop.</a>
		</div>	
		<div class="mobile-only">
			<?php echo $precred; ?><br /><a href="http://www.handsomeweb.com/scouts/">Get this WordPress theme for your troop.</a>
		</div>					
	</div>
</div>

<!--
 _____                                   _     ______           _      _  __       
|  __ \                                 | |   |  ____|         | |    (_)/ _| 
| |__) | __ ___ _ __   __ _ _ __ ___  __| |   | |__ ___  _ __  | |     _| |_ ___  
|  ___/ '__/ _ \ '_ \ / _` | '__/ _ \/ _` |   |  __/ _ \| '__| | |    | |  _/ _ \ 
| |   | | |  __/ |_) | (_| | | |  __/ (_| |_  | | | (_) | |    | |____| | ||  __/_
|_|   |_|  \___| .__/ \__,_|_|  \___|\__,_(_) |_|  \___/|_|    |______|_|_| \___(_) 
               | |                                            
               |_|                                            
-->
     
<?php wp_footer(); ?>
	<script>
	jQuery(document).ready(function($){
		jQuery(".flexnav").flexNav({ 'animationSpeed' : 50, 'calcItemWidths': true, 'hoverIntent': true, 'hoverIntentTimeout': 25, 'hover': true }); 
	});
</script>
</body>
</html>