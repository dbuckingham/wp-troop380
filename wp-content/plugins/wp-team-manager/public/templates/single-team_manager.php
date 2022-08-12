<?php
/**
 * The template for displaying team single post
 *
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php
    if ( have_posts() ) :

    $settings = get_option('wtm_settings');

		// Start the loop.
		while ( have_posts() ) : the_post();

        $post_id = get_the_ID();

  			$social_type = $settings['social_type'];

			  $social_font_size = $settings['social_font_size'];
        // get social settings
        $social_size =$settings['social_image_size'];
        // get link new window settings
        $tm_link_new_window = $settings['link_new_window'];

        $tm_custom_template = $settings['custom_template'];

        //If there is no tm_social_size then load default
        if (!$social_size) {
          $social_size=16;
        }

        if($tm_link_new_window=='True'){
        
          $link_window = 'target="_blank"';
        
        }else{
          
          $link_window = '';
          
        }

        $job_title = get_post_meta($post_id,'tm_jtitle',true);
        $telephone = get_post_meta($post_id,'tm_telephone',true);
        $location = get_post_meta($post_id,'tm_location',true);
        $web_url = get_post_meta($post_id,'tm_web_url',true);
        $vcard = get_post_meta($post_id,'tm_vcard',true);
        $facebook = get_post_meta($post_id,'tm_flink',true);
        $twitter = get_post_meta($post_id,'tm_tlink',true);
        $linkedIn = get_post_meta($post_id,'tm_llink',true);
        $googleplus = get_post_meta($post_id,'tm_gplink',true);
        $dribbble = get_post_meta($post_id,'tm_dribbble',true);
        $youtube = get_post_meta($post_id,'tm_ylink',true);
        $vimeo = get_post_meta($post_id,'tm_vlink',true);
        $instagram = get_post_meta($post_id,'tm_instagram',true);
        $emailid = get_post_meta($post_id,'tm_emailid',true);
          

        $sociallinks = '<ul class="team-member-socials '.esc_attr($social_type).' size-'.$social_size.'">';
        if (!empty($facebook)) {
          $sociallinks .= '<li><a class="facebook-'.$social_size.'" href="' . $facebook. '" '.$link_window.' title="Facebook"><i class="fa fa-facebook-square" aria-hidden="true"></i></a></li>';
        }
        if (!empty($twitter)) {
          $sociallinks .= '<li><a class="twitter-'.$social_size.'" href="' . $twitter. '" '.$link_window.' title="Twitter"><i class="fa fa-twitter-square" aria-hidden="true"></i></a></li>';
        }
        if (!empty($linkedIn)) {
          $sociallinks .= '<li><a class="linkedIn-'.$social_size.'" href="' . $linkedIn. '" '.$link_window.' title="LinkedIn"><i class="fa fa-linkedin-square" aria-hidden="true"></i></a></li>';
        }
        if (!empty($googleplus)) {
          $sociallinks .= '<li><a class="googleplus-'.$social_size.'" href="' . $googleplus. '" '.$link_window.' title="Google Plus"><i class="fa fa-google-plus-square" aria-hidden="true"></i></a></li>';
        }
        if (!empty($instagram)) {
          $sociallinks .= '<li><a class="instagram-'.$social_size.'" href="' . $instagram. '" '.$link_window.' title="Instagram"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>';
        }		        
        if (!empty($dribbble)) {
          $sociallinks .= '<li><a class="dribbble-'.$social_size.'" href="' . $dribbble. '" '.$link_window.' title="Dribbble"><i class="fa fa-dribbble" aria-hidden="true"></i></a></li>';
        }        
        if (!empty($youtube)) {
          $sociallinks .= '<li><a class="youtube-'.$social_size.'" href="' . $youtube. '" '.$link_window.' title="Youtube"><i class="fa fa-youtube-square" aria-hidden="true"></i></a></li>';
        }
        if (!empty($vimeo)) {
          $sociallinks .= '<li><a class="vimeo-'.$social_size.'" href="' . $vimeo. '" '.$link_window.' title="Vimeo"><i class="fa fa-vimeo-square" aria-hidden="true"></i></a></li>';
        }		        
        if (!empty($emailid)) {
          $sociallinks .= '<li><a class="emailid-'.$social_size.'" href="mailto:' . $emailid. '" title="Email"><i class="fa fa-envelope-o" aria-hidden="true"></i></a></li>';
        }                                                        
        $sociallinks .= '</ul>';


        $otherinfo = '<ul class="team-member-other-info">';
        if (!empty($telephone)) {
          $otherinfo .= '<li><span> '.__('Tel:','wp-team-manager').' </span><a href="tel://'.$telephone.'">'.$telephone.'</a></li>';
        }
        if (!empty($location)) {
          $otherinfo .= '<li><span> '.__('Location:','wp-team-manager').' </span>'.$location.'</li>';
        }
        if (!empty($web_url)) {
          $otherinfo .= '<li><span> '.__('Website:','wp-team-manager').' </span><a href="'.$web_url.'" target="_blank">Link</a></li>';
        }
        if (!empty($vcard)) {
          $otherinfo .= '<li><span> '.__('Vcard:','wp-team-manager').' </span><a href="'.$vcard.'" >Download</a></li>';
        }                                               
        $otherinfo .= '</ul>';
        ?>


    <?php
    // check if the post has a Post Thumbnail assigned to it.
    if ( has_post_thumbnail() ) {
      the_post_thumbnail();
    } 
    ?>

    <header class="entry-header">
      <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
    </header><!-- .entry-header -->

    <div class="entry-content">
      <?php the_content(); ?>
      <?php echo $sociallinks; ?>
      <?php echo $otherinfo; ?>
      <?php
        wp_link_pages( array(
          'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'wp-team-manager' ) . '</span>',
          'after'       => '</div>',
          'link_before' => '<span>',
          'link_after'  => '</span>',
          'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'wp-team-manager' ) . ' </span>%',
          'separator'   => '<span class="screen-reader-text">, </span>',
        ) );
      ?>
    </div><!-- .entry-content -->

      <?php edit_post_link( __( 'Edit', 'wp-team-manager' ), '<footer class="entry-footer"><span class="edit-link">', '</span></footer><!-- .entry-footer -->' ); ?>

      <?php // End the loop.
      endwhile;
    endif;
      ?>
     </article><!-- #post-## -->
		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>
