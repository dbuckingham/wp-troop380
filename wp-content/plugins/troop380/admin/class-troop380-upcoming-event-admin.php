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

        $activity_month = Troop380_Helpers::format_shortdate( get_post_meta( $post->ID, 'activity_month', true ) );
        $location = get_post_meta( $post->ID, 'location', true );
        $patrol = get_post_meta( $post->ID, 'patrol', true );
        $coordinator = get_post_meta( $post->ID, 'coordinator', true );
		?>

		<table>
			<tr>
				<td>Month:</td>
				<td><input type="text" name="activity_month" id="activity_month" class="troop380-datepicker" value="<?php echo $activity_month; ?>" /></td>
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
			'cb' => $columns['cb'],
            'title' => 'Title',
            'month' => 'Month',
			'patrol' => 'Patrol',
			'coordinator' => 'Coordinator'
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

		if ( array_key_exists( 'activity_month', $_POST ) ) {
			
			$activity_month = strtotime($_POST['activity_month']);

			update_post_meta(
				$post_id,
				'activity_month',
				date("Ymd", $activity_month)
			);
		}
	
		if ( array_key_exists( 'location', $_POST ) ) {
			update_post_meta(
				$post_id,
				'location',
				$_POST['location']
			);
		}

		if ( array_key_exists( 'patrol', $_POST ) ) {
			update_post_meta(
				$post_id,
				'patrol',
				$_POST['patrol']
			);
		}

		if ( array_key_exists( 'coordinator', $_POST ) ) {
			update_post_meta(
				$post_id,
				'coordinator',
				$_POST['coordinator']
			);
		}
	}


	/**
	 * Populate new columns in upcoming-events list in admin area
	 * 
	 * @since	 1.1.0
	 */
	public function manage_posts_custom_column( $column, $post_id ) {

		$output = "";

		// Populate column form meta
		switch ( $column ) {
			case "month":
				$activity_month = Troop380_Helpers::format_month_year( get_post_meta($post_id, 'activity_month', true) );
            	$output .= '<span>'; 
				$output .= $activity_month;
				$output .= '</span>';

				echo $output;
            	break;
				
			case "location":
				$location = get_post_meta($post_id, 'location', true);
				$output .= '<span'; 
				$output .= $location;
				$output .= '</span>';

				echo $output;
				break;

			case 'patrol':
				$patrol = get_post_meta( $post_id, 'patrol', true );
				$output .= '<span>';
				$output .= $patrol;
				$output .= '</span>';

				echo $output;
				break;

			case 'coordinator':
				$coordinator = get_post_meta( $post_id, 'coordinator', true );
				$output .= '<span>';
				$output .= $coordinator;
				$output .= '</span>';

				echo $output;
				break;
		}

	}

	/**
	 * Define the sortable columns for upcoming-events post types.
	 * 
	 * @since	1.1.2
	 */
	public function set_upcoming_event_sortable_columns( $columns ) {

		$columns['month'] = 'month';
		$columns['patrol'] = 'patrol';
		$columns['coordinator'] = 'coordinator';

		return $columns;
	}

	/**
	 * Modifies a WP_Query object to sort by meta-values.
	 * 
	 * @since	1.1.2
	 */
	public function upcoming_event_custom_orderby( $query ) {
		if( ! is_admin() )
			return;

		$orderby = $query->get( 'orderby' );

		if( 'month' == $orderby ) {
			$query->set('meta_key', 'activity_month');
			$query->set('meta_type', 'DATETIME');
			$query->set('orderby', 'meta_value');
		}

		if( 'patrol' == $orderby ) {
			$query->set('meta_key', 'patrol');
			$query->set('orderby', 'meta_value');
		}

		if( 'coordinator' == $orderby ) {
			$query->set('meta_key', 'coordinator');
			$query->set('ordery', 'meta_value');
		}
	}

	/**
	 * Modifies a WP_Query object to sort eaglescout post types by Board of Review Date by default.
	 * 
	 * @since	1.1.2
	 */
	public function upcoming_event_default_custom_orderby( $query )	{
		if( $query->get('post_type') == 'upcoming-event' ){
			
			if( $query->get('orderby') == '' ) {
				$query->set('meta_key', 'activity_month');
				$query->set('meta_type', 'DATETIME');
				$query->set('orderby', 'meta_value');
			}
	
			if( $query->get('order') == '' )
				$query->set('order','desc');
		}
	}
}