<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/dbuckingham/wp-troop380
 * @since      1.0.0
 *
 * @package    troop380
 * @subpackage troop380/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    troop380
 * @subpackage troop380/admin
 * @author     David Buckingham <david.buckingham@outlook.com>
 */
class Troop380_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/troop380-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/troop380-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Save custom fields
	 * 
	 * @since	 1.1.0
	 */
	public function save_meta_options( $post_id ) {

		if ( ! current_user_can( 'edit_posts' ) ) return;

		if ( array_key_exists( 'board_of_review_date', $_POST ) ) {
			
			// $board_of_review_date = date_parse($_POST['board_of_review_date']);
			$board_of_review_date = strtotime($_POST['board_of_review_date']);

			update_post_meta(
				$post_id,
				'board_of_review_date',
				date("m/d/Y", $board_of_review_date) // $_POST['board_of_review_date']
			);
	
			update_post_meta(
				$post_id,
				'year_earned',
				date("Y", $board_of_review_date) // $board_of_review_date['year']
			);
		}
	
		if ( array_key_exists( 'board_of_review_date_is_real', $_POST ) ) {
			update_post_meta(
				$post_id,
				'board_of_review_date_is_real',
				$_POST['board_of_review_date_is_real']
			);
		}

	}


	/**
	 * Create a meta box for our custom fields
	 * 
	 * @since	 1.1.0
	 */
	public function rerender_meta_options() {

		// $screens = ['eaglescout'];
		// foreach($screens as $screen){
	
			add_meta_box( 'eaglescout-meta', __( 'Eagle Scout Details' ), array($this, "display_meta_options"), 'eaglescout', 'normal', 'default' );
		// }

	}


	/**
	 * Display meta box and custom fields
	 * 
	 * @since	 1.1.0
	 */
	public function display_meta_options() {

		global $post;
		// $custom = get_post_custom($post->ID);

		// https://www.designbyhn.se/adding-a-datepicker-to-a-wordpress-metabox/
		// Enqueue Datepicker + jQuery UI CSS
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_style( 'jquery-ui-style', '//code.jquery.com/ui/1.13.1/themes/smoothness/jquery-ui.css', true);

		// Retrieve current date for the Eagle Scout
		$board_of_review_date = get_post_meta( $post->ID, 'board_of_review_date', true ); // $custom["board_of_review_date"][0];
		$board_of_review_date_is_real =  get_post_meta( $post->ID, 'board_of_review_date_is_real', true ); // $custom["board_of_review_date_is_real"][0];
		?>

		<script>
		jQuery(document).ready(function(){
			jQuery('#board_of_review_date').datepicker({
				dateFormat : 'mm/dd/yy'
			});
		});
		</script>
		
		<table>
			<tr>
				<td>Board of Review Date:</td>
				<td><input type="text" name="board_of_review_date" id="board_of_review_date" value="<?php echo $board_of_review_date; ?>" /></td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type="checkbox" name="board_of_review_date_is_real" id="board_of_review_date_is_real" <?php echo $board_of_review_date_is_real ? 'checked' : ''; ?> />
						Verified Board of Review Date<br />
						<span style="font-style: italic; margin-left: 21px;">The Board of Review date is used to group Eagle Scouts by year, and sort them by the date which they earned the Eagle Rank.  If the actual Board of Review Date is not known, specify a date during the year it was earned, and leave the "Verified Board of Review Date" unchecked.</span>
				</td>
			</tr>
		</table>
		
		<?php
	}

	/**
	 * Modify columns in Eagle Scout list in admin area.
	 * 
	 * @since	 1.1.0
	 */
	public function manage_eaglescout_posts_columns( $columns ) {

		$inserted = [
			'year_earned' => __('Year Earned', 'textdomain'), 
			'board_of_review' => __('Board of Review', 'textdomain')
		];
		array_splice($columns, 2, 0, $inserted);
	
		return $columns;

	}


	/**
	 * Populate new columns in customers list in admin area
	 * 
	 * @since	 1.1.0
	 */
	public function manage_eaglescout_posts_custom_column( $column, $post_id ) {

		// Populate column form meta
		switch ( $column ) {
			// case "year_earned":
			case '0':
				$year_earned = get_post_meta($post_id, 'year_earned', true);
            	echo '<span>'; _e($year_earned, 'textdomain'); '</span>';
            	break;
				
			// case "board_of_review_date":
			case '1':
				$board_of_review_date = get_post_meta($post_id, 'board_of_review_date', true);	
				$board_of_review_date_is_real = (get_post_meta($post_id, 'board_of_review_date_is_real', true) == 'on');

				echo '<span'; echo ($board_of_review_date_is_real) ? '>' : ' style="font-style: italic;">'; _e($board_of_review_date, 'textdomain'); echo '</span>';
				break;
		}

	}
}