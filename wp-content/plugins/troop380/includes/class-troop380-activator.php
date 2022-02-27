<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/dbuckingham/wp-troop380
 * @since      1.0.0
 *
 * @package    troop380
 * @subpackage troop380/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    troop380
 * @subpackage troop380/includes
 * @author     David Buckingham <david.buckingham@outlook.com>
 */
class Troop380_Activator {

	/**
	 * @since    1.0.0
	 */
	public static function activate() {

		/**
		 * Custom Post Types
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-troop380-post-types.php';
		$plugin_post_types = new Troop380_Post_Types();

		/**
		 * The problem with the initial activation code is that when the activation hook runs, it's after the init hook has run,
		 * so hooking into init from the activation hook won't do anything.
		 * You don't need to register the CPT within the activation function unless you need rewrite rules to be added
		 * via flush_rewrite_rules() on activation. In that case, you'll want to register the CPT normally, via the
		 * loader on the init hook, and also re-register it within the activation function and
		 * call flush_rewrite_rules() to add the CPT rewrite rules.
		 *
		 * @link https://github.com/DevinVinson/WordPress-Plugin-Boilerplate/issues/261
		 */
		$plugin_post_types->create_custom_post_type();

		/**
		 * This is only required if the custom post type has rewrite!
		 *
		 * Remove rewrite rules and then recreate rewrite rules.
		 *
		 * This function is useful when used with custom post types as it allows for automatic flushing of the WordPress
		 * rewrite rules (usually needs to be done manually for new custom post types).
		 * However, this is an expensive operation so it should only be used when absolutely necessary.
		 * See Usage section for more details.
		 *
		 * Flushing the rewrite rules is an expensive operation, there are tutorials and examples that suggest
		 * executing it on the 'init' hook. This is bad practice. It should be executed either
		 * on the 'shutdown' hook, or on plugin/theme (de)activation.
		 *
		 * @link https://codex.wordpress.org/Function_Reference/flush_rewrite_rules
		 */
		flush_rewrite_rules();


		// Create Eagle Scout Custom Post Types from database records.
		self::upgrade_to_v_1_1_0();
	}

	private static function upgrade_to_v_1_1_0() {
		global $wpdb;

		$query = "SELECT * FROM information_schema.TABLES WHERE TABLE_NAME = 'wp_troop380_eaglescout';";
		$eagle_scout_table = $wpdb->get_results($query);
		
		if( ($wpdb->num_rows <= 0) ) { return; }
		
		$query = "SELECT id, YEAR(date_earned) 'year', firstname, lastname, date_earned, date_earned_is_real FROM wp_troop380_eaglescout ORDER BY date_earned, lastname, firstname;";
		
		$rows = $wpdb->get_results($query);
		
		foreach($rows as $row) {
		
			$wp_troop380_eaglescout_id = $row->id;
		
			$args = array(
				"post_type" => "eaglescout",
				"meta_key" => "_wp_troop380_eaglescout_id",
				"meta_value" => $wp_troop380_eaglescout_id
				);
		
			$query = new WP_Query( $args );
		
			$posts = $query->posts;
		
			if( count( $posts ) == 0 ) {
				// Eagle Scout post does not exist and needs to be created.
				
				$postarr = array(
					"post_title" => $row->firstname . " ". $row->lastname,
					"post_type" => "eaglescout",
					"post_status" => "publish",
					"meta_input"=> array(
						"board_of_review_date" => date( "m/d/Y", strtotime( $row->date_earned ) ),
						"board_of_review_date_is_real" => $row->date_earned_is_real,
						"year_earned" => date( "Y", strtotime( $row->date_earned ) ),
						"_wp_troop380_eaglescout_id" => $wp_troop380_eaglescout_id
					)
				);
				
				$message = "Creating eaglescout post for " . $row->firstname . " " . $row->lastname . "(" . $wp_troop380_eaglescout_id . ")";
				error_log($message);

				$result = wp_insert_post( $postarr );
			}
			else {
				$mssage = "Skipping eaglescout post for " . $row->firstname . " " . $row->lastname . "(" . $wp_troop380_eaglescout_id . ")";
			}
		}
	}


}
