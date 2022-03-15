<?php

/**
 * The admin-specific Upcoming Event functionality of the plugin.
 *
 * @link       https://github.com/dbuckingham/wp-troop380
 * @since      1.2.0
 *
 * @package    troop380
 * @subpackage troop380/admin
 */

/**
 * The admin-specific Upcoming Event functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    troop380
 * @subpackage troop380/admin
 * @author     David Buckingham <david.buckingham@outlook.com>
 */
class Troop380_Upcoming_Event_Admin {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.2.0
	 */
	public function __construct() {

		require_once plugin_dir_path( __FILE__ ) . '../includes/class-troop380-helpers.php';

	}


    /**
	 * Create a meta box for our custom fields
	 * 
	 * @since	 1.2.0
	 */
	public function rerender_meta_options() {

		add_meta_box( 'upcoming-event-meta', 'Upcoming Event Details', array($this, "display_meta_options"), 'upcoming-event', 'normal', 'default' );

	}


    /**
	 * Display meta box and custom fields
	 * 
	 * @since	 1.2.0
	 */
	public function display_meta_options() {

		global $post;

		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_style( 'jquery-ui-style', '//code.jquery.com/ui/1.13.1/themes/smoothness/jquery-ui.css', true);

        // TODO - Retrieve meta values
        $activity_month = "";
        $location = "";
        $patrol = "";
        $coordinator = "";
		?>

		<script>
		jQuery(document).ready(function(){
			jQuery('#activity_month').datepicker({
				dateFormat : 'mm/dd/yy'
			});
		});
		</script>
		
		<table>
			<tr>
				<td>Month:</td>
				<td><input type="text" name="activity_month" id="activity_month" value="<?php echo $activity_month; ?>" /></td>
			</tr>
            <tr>
				<td>Location:</td>
				<td><input type="text" name="location" id="location" value="<?php echo $location; ?>" /></td>
			</tr>
            <tr>
				<td>Patrol:</td>
				<td><input type="text" name="patrol" id="patrol" value="<?php echo $patrol; ?>" /></td>
			</tr>
			<tr>
                <td>Coordinator:</td>
                <td><input type="text" name="coordinator" id="coordinator" value="<?php echo $coordinator; ?>" /></td>
            </tr>
		</table>
		
		<?php
	}


	/**
	 * Modify columns in Upcoming Event list in admin area.
	 * 
	 * @since	 1.2.0
	 */
	public function manage_upcoming_event_posts_columns( $columns ) {

		$custom_columns = array(
            'month' => 'Month',
            'title' => 'Title',
            'patrol' => 'Patrol'
		);

		return $custom_columns;

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
				$board_of_review_date = Troop380_Helpers::format_board_of_review_date( get_post_meta($post_id, 'board_of_review_date', true) );
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