<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.dynamicweblab.com/
 * @since      1.0.0
 *
 * @package    Wp_Team_Manager
 * @subpackage Wp_Team_Manager/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Team_Manager
 * @subpackage Wp_Team_Manager/public
 * @author     Maidul <info@dynamicweblab.com>
 */
class Wp_Team_Manager_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_shortcode( 'team_manager', array($this, 'shortcode') );
		add_filter('widget_text', 'do_shortcode' );

	}

	/**
	 * Get social links
	 */
	public function get_social_links($post_id)
	{

		if(empty($post_id)){
			return false;
		}

		$output = '';

		//Social links
		$facebook = get_post_meta($post_id,'tm_flink',true);
		$twitter = get_post_meta($post_id,'tm_tlink',true);
		$linkedIn = get_post_meta($post_id,'tm_llink',true);
		$googleplus = get_post_meta($post_id,'tm_gplink',true);
		$dribbble = get_post_meta($post_id,'tm_dribbble',true);
		$youtube = get_post_meta($post_id,'tm_ylink',true);
		$vimeo = get_post_meta($post_id,'tm_vlink',true);
		$instagram = get_post_meta($post_id,'tm_instagram',true);
		$emailid = get_post_meta($post_id,'tm_emailid',true);
			

		$output = '<ul class="team-member-socials font-awesome">';
		if (!empty($facebook)) {
			$output .= '<li><a href="' . $facebook. '" title="Facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>';
		}
		if (!empty($twitter)) {
			$output .= '<li><a href="' . $twitter. '" title="Twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>';
		}
		if (!empty($linkedIn)) {
			$output .= '<li><a href="' . $linkedIn. '" title="LinkedIn"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>';
		}
		if (!empty($googleplus)) {
			$output .= '<li><a href="' . $googleplus. '" title="Google Plus"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>';
		}
		if (!empty($instagram)) {
			$output .= '<li><a href="' . $instagram. '" title="Instagram"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>';
		}		        
		if (!empty($dribbble)) {
			$output .= '<li><a href="' . $dribbble. '" title="Dribbble"><i class="fa fa-dribbble" aria-hidden="true"></i></a></li>';
		}        
		if (!empty($youtube)) {
			$output .= '<li><a href="' . $youtube. '" title="Youtube"><i class="fa fa-youtube" aria-hidden="true"></i></a></li>';
		}
		if (!empty($vimeo)) {
			$output .= '<li><a href="' . $vimeo. '" title="Vimeo"><i class="fa fa-vimeo" aria-hidden="true"></i></a></li>';
		}		        
		if (!empty($emailid)) {
			$output .= '<li><a href="mailto:' . $emailid. '" title="Email"><i class="fa fa-envelope-o" aria-hidden="true"></i></a></li>';
		}                                                        
		$output .= '</ul>';

		return $output;

	}

	/**
	 * Team manager short code [team_manager]
	 *
	 * @since    1.0.0
	 */
	
	public function shortcode($atts)
	{
		
	   extract( shortcode_atts( array(
	      		'team_groups' => '',
	      		'orderby' => 'menu_order',
				'layout' => 'grid',
				'column' => '2',
	      		'image_layout' => 'rounded',
	      		'image_size' => 'thumbnail',
				'post__in'   => '',
				'color'   => '',
				'hide_meta'   => '',
	    ), $atts ) );

		global $_wp_additional_image_sizes;

		$settings = get_option('wtm_settings');
		
	    // get link new window settings
	    $single_team_member_view = $settings['single_view'];
	    // get custom template
		$tm_custom_template = $settings['custom_template'];
		
	    
	    //If there is no tm_custom_template then load default

	    if (!$tm_custom_template) {

	      $tm_custom_template='<div class="%layout%">
	    <div class="team-member-info">
	    %image%
	     %sociallinks%
	    </div><div class="team-member-des">
	    <h2 class="team-title">%title%</h2>
	    <h4 class="team-position">%jobtitle%</h4>
	    %content%
	    %otherinfo%
	    </div>
	    </div>';
	      
	    }
	    	$asc_desc = 'DESC';

	    	$posts_per_page = -1;
	    
		    if ( $atts['orderby'] == 'title' || $atts['orderby'] == 'menu_order' ) {

		      $asc_desc = 'ASC';

		    }

		    
		    if($atts['limit'] >= 1) { 

		    $posts_per_page = $atts['limit'];

		    } 

				$uniqu_id = mt_rand();

				$output = '';

				$layoutID = "wtm-team-" . $uniqu_id;

				$column = isset($atts['column']) ? $atts['column'] : 2;

				$grid = $column == 5 ? '24' : 12 / $column;

				$grid_class = "wtm-col-md-{$grid} wtm-col-sm-{$grid} wtm-col-xs-12";

				$layout = isset($atts['layout']) ? $atts['layout'] : 'grid';
				
				$image_layout = isset($atts['image_layout']) ? $atts['image_layout'] : ''; 
				   
				$image_size = isset($atts['image_size']) ? $atts['image_size'] : 'medium'; 

				$color = isset($atts['color']) ? $atts['color'] : null;  

				$hide_meta = isset($atts['hide_meta']) ? $atts['hide_meta'] : null;  

				$args = array( 
						'post_type' => 'team_manager',
						'team_groups'=> $atts['category'] ,  
						'posts_per_page'=> $posts_per_page, 
						'orderby' => $atts['orderby'], 
						'order' => $asc_desc
						); 

		           if(!empty($atts['exclude'])) {	

		           $postnotarray = explode(',', $atts['exclude']);

		           if(!empty($postnotarray)) {

		            $args['post__not_in'] = $postnotarray;

		            }

		          }

		          if(!empty($atts['post__in'])) {

		           $postarray = explode(',', $atts['post__in']);

		           if(!empty($postarray)) {

		            $args['post__in'] = $postarray;

		            }

		          }    

		    $tm_loop = new WP_Query(apply_filters('wptm_team_query_args', $args));      

		    // The Loop
		    if ( $tm_loop->have_posts() ) { 

					//add inline css

					if(!empty($color)){
						$output = "<style type='text/css'>#{$layoutID} .team-title,#{$layoutID} .team-member-other-info span{
								color: {$color}
							}
							#{$layoutID} ul.team-member-socials.font-awesome li a{
								background-color: {$color}
							}
						}</style>"; 
					}


		      $output .= '<div id="'.esc_attr($layoutID).'" class="wp-team-manager-wrapper team-list '.esc_attr($atts['layout']).'-wrapper">';
		      while ( $tm_loop->have_posts() ) {
						$tm_loop->the_post();

						$output .= "<div class='{$grid_class}'>"; 
						
						$post_id = get_the_ID();
						$content = '';
						$title = get_the_title();       
						$job_title = get_post_meta($post_id,'tm_jtitle',true);
						$short_bio = get_post_meta($post_id,'tm_sbio',true);

						if(!empty($short_bio)){

							$content = $short_bio;

						}else{

							$content = get_the_content();
							$content = apply_filters('the_content', $content);
							$content = str_replace(']]>', ']]&gt;', $content); 
							
						}
						


				$sociallinks = $this->get_social_links($post_id);

		        $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $image_size );   
        
		       
		        $details_start = '<a href="'.get_permalink().'">';
		        $details_end = '</a>';

		        if (!empty($single_team_member_view)) {

		          $details_start=$details_end='';

		        }


		        if (isset($image[0])) {
				  
				  $width = $image[1];

		          $image = "$details_start<img class='team-picture ".$image_layout."' src='".$image[0]."' width='".$width."' title='".$title."' />$details_end";
		        
		        }else{
		          
		          $image = "$details_start<img class='team-picture ".$image_layout."' src='".plugins_url( 'img/demo.gif',__FILE__)."' width='150' title='".$title."' />$details_end";
		        
		        }



				$otherinfo = '';
						
				if(empty($hide_meta)){

				//Team Member Information 
				$telephone = get_post_meta($post_id,'tm_telephone',true);
		        $location = get_post_meta($post_id,'tm_location',true);
		        $web_url = get_post_meta($post_id,'tm_web_url',true);
		        $vcard = get_post_meta($post_id,'tm_vcard',true);

		        $otherinfo .= '<ul class="team-member-other-info">';
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

						}


		        $find = array('/%layout%/i','/%title%/i', '/%content%/i', '/%image%/i','/%jobtitle%/i','/%otherinfo%/i','/%sociallinks%/i');
		        
		        $replace = array($layout,$title, $content,$image,$job_title,$otherinfo,$sociallinks);
		        
		        $output .= preg_replace($find, $replace, $tm_custom_template);

						$output .= '</div>';

					}
						
		        $output .= '</div>';

		    }
		    /* Restore original Post Data */
		    wp_reset_postdata();

		    return $output;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Team_Manager_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Team_Manager_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( $this->plugin_name . '-font-awesome', plugin_dir_url( __FILE__ ) .'vendor/font-awesome/css/font-awesome.min.css' );

		//wp_enqueue_style( $this->plugin_name . '-owl-carousel', plugin_dir_url( __FILE__ ) .'vendor/owl-carousel/assets/owl.theme.default.min.css' );


		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/tm-style.css', array(), $this->version, 'all' );

		$settings = get_option('wtm_settings');
		$custom_css = '';

		if(!empty($settings['primary_color'])){
			$color = $settings['primary_color'];
			$custom_css .= "
			.team-member-des .team-title,.team-member-other-info span{
				color: {$color}
			}
			ul.team-member-socials.font-awesome li a{
				background-color: {$color}
			}			
			";
		}
		if(!empty($settings['social_font_size'])){
			$font_size = $settings['social_font_size'];
			$custom_css .= "
			ul.team-member-socials.font-awesome li>a>i{
				font-size: {$font_size}px;
			}
			";
		}
		if(!empty($settings['custom_css'])){
			$custom_css .= $settings['custom_css'];
		}
		
		wp_add_inline_style( $this->plugin_name, $custom_css );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Team_Manager_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Team_Manager_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_script( $this->plugin_name , plugin_dir_url( __FILE__ ) . 'js/wp-team-manager-public.js', array( 'jquery' ), $this->version, true );

		//wp_enqueue_script( $this->plugin_name . '-owl-carousel', plugin_dir_url( __FILE__ ) . 'vendor/owl-carousel/owl.carousel.min.js', array( 'jquery' ), $this->version, true );

	}

}
