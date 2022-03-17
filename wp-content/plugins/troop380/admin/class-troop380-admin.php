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

		require_once plugin_dir_path( __FILE__ ) . '../includes/class-troop380-helpers.php';
		
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

		wp_enqueue_style( 'jquery-ui-style', '//code.jquery.com/ui/1.13.1/themes/smoothness/jquery-ui.css', true);
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

		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/troop380-admin.js', array( 'jquery', 'jquery-ui-datepicker' ), $this->version, true );
	}

	/**
	 * Save custom fields
	 * 
	 * @since	 1.1.0
	 */
	public function save_meta_options( $post_id ) {

		if ( ! current_user_can( 'edit_posts' ) ) return;

		if ( array_key_exists( 'board_of_review_date', $_POST ) ) {
			
			$board_of_review_date = strtotime($_POST['board_of_review_date']);

			update_post_meta(
				$post_id,
				'board_of_review_date',
				date("Ymd", $board_of_review_date)
			);
	
			update_post_meta(
				$post_id,
				'year_earned',
				date("Y", $board_of_review_date)
			);
		}
	
		if ( array_key_exists( 'board_of_review_date_is_real', $_POST ) ) {
			update_post_meta(
				$post_id,
				'board_of_review_date_is_real',
				$_POST['board_of_review_date_is_real']
			);
		}

		if ( array_key_exists( 'scoutmaster', $_POST ) ) {
			update_post_meta(
				$post_id,
				'scoutmaster',
				$_POST['scoutmaster']
			);
		}

	}

	/**
	 * Create a meta box for our custom fields
	 * 
	 * @since	 1.1.0
	 */
	public function rerender_meta_options() {

		add_meta_box( 'eaglescout-meta', __( 'Eagle Scout Details' ), array($this, "display_meta_options"), 'eaglescout', 'normal', 'default' );

	}

	/**
	 * Display meta box and custom fields
	 * 
	 * @since	 1.1.0
	 */
	public function display_meta_options() {

		global $post;

		// Retrieve Board of Review date for the Eagle Scout
		$board_of_review_date = Troop380_Helpers::format_shortdate( get_post_meta( $post->ID, 'board_of_review_date', true ) );
		$board_of_review_date_is_real =  get_post_meta( $post->ID, 'board_of_review_date_is_real', true );

		// Retrieve Scoutmaster
		$scoutmaster = get_post_meta( $post->ID, 'scoutmaster', true );
		?>

		<table>
			<tr>
				<td>Board of Review Date:</td>
				<td><input type="text" name="board_of_review_date" id="board_of_review_date" class="troop380-datepicker" value="<?php echo $board_of_review_date; ?>" /></td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type="checkbox" name="board_of_review_date_is_real" id="board_of_review_date_is_real" <?php echo $board_of_review_date_is_real ? 'checked' : ''; ?> />
						Verified Board of Review Date<br />
						<span style="font-style: italic;">The Board of Review date is used to group Eagle Scouts by year, and sort them by the date which they earned the Eagle Rank.  If the actual Board of Review Date is not known, specify a date during the year it was earned, and leave the "Verified Board of Review Date" unchecked.</span>
				</td>
			</tr>
			<tr>
				<td>Scoutmaster:</td>
				<td><input type="text" name="scoutmaster" id="scoutmaster" value="<?php echo $scoutmaster; ?>" /></td>
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

		$custom_columns = array(
			'title' => 'Title',
			'year_earned' => 'Year Earned',
			'board_of_review' => 'Board of Review',
			'scoutmaster' => 'Scoutmaster',
			'date' => 'Date'
		);

		return $custom_columns;

	}

	/**
	 * Populate new columns in customers list in admin area
	 * 
	 * @since	 1.1.0
	 */
	public function manage_eaglescout_posts_custom_column( $column, $post_id ) {

		$output = "";

		// Populate column form meta
		switch ( $column ) {
			case "year_earned":
				$year_earned = get_post_meta($post_id, 'year_earned', true);
            	$output .= '<span>'; 
				$output .= $year_earned;
				$output .= '</span>';

				echo $output;
            	break;
				
			case "board_of_review":
				$board_of_review_date = Troop380_Helpers::format_shortdate( get_post_meta($post_id, 'board_of_review_date', true) );
				$board_of_review_date_is_real = (get_post_meta($post_id, 'board_of_review_date_is_real', true) == 'on');

				$output .= '<span'; 
				$output .= ($board_of_review_date_is_real) ? '>' : ' style="font-style: italic;">'; 
				$output .= $board_of_review_date;
				$output .= '</span>';

				echo $output;
				break;

			case 'scoutmaster':
				$scoutmaster = get_post_meta( $post_id, 'scoutmaster', true );
				$output .= '<span>';
				$output .= $scoutmaster;
				$output .= '</span>';

				echo $output;
				break;
		}

	}

	/**
	 * Define the sortable columns for eaglescout post types.
	 * 
	 * @since	1.1.2
	 */
	public function set_eaglescout_sortable_columns( $columns ) {

		$columns['year_earned'] = 'year_earned';
		$columns['board_of_review'] = 'board_of_review';
		$columns['scoutmaster'] = 'scoutmaster';

		return $columns;
	}

	/**
	 * Modifies a WP_Query object to sort by meta-values.
	 * 
	 * @since	1.1.2
	 */
	public function eaglescout_custom_orderby( $query ) {
		if( ! is_admin() )
			return;

		$orderby = $query->get( 'orderby' );

		if( 'year_earned' == $orderby ) {
			$query->set('meta_key', 'year_earned');
			$query->set('orderby', 'meta_value_num');
		}

		if( 'board_of_review' == $orderby ) {
			$query->set('meta_key', 'board_of_review_date');
			$query->set('meta_type', 'DATETIME');
			$query->set('orderby', 'meta_value');
		}

		if( 'scoutmaster' == $orderby ) {
			$query->set('meta_key', 'scoutmaster');
			$query->set('ordery', 'meta_value');
		}
	}

	/**
	 * Modifies a WP_Query object to sort eaglescout post types by Board of Review Date by default.
	 * 
	 * @since	1.1.2
	 */
	public function eaglescout_default_custom_orderby( $query )	{
		if( $query->get('post_type') == 'eaglescout' ){
			
			if( $query->get('orderby') == '' ) {
				$query->set('meta_key', 'board_of_review_date');
				$query->set('meta_type', 'DATETIME');
				$query->set('orderby', 'meta_value');
			}
	
			if( $query->get('order') == '' )
				$query->set('order','desc');
		}
	}
}