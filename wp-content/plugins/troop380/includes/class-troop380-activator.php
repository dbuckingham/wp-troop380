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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-troop380-eagle-scout-post-type.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-troop380-merit-badge-post-type.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-troop380-upcoming-event-post-type.php';
		
		$plugin_eagle_scout_post_type = new Troop380_Eagle_Scout_Post_Type();
		$plugin_merit_badge_post_type = new Troop380_Merit_Badge_Post_Type();
		$plugin_upcoming_event_post_type = new Troop380_Upcoming_Event_Post_Type();

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
		$plugin_eagle_scout_post_type->create_custom_post_type();
		$plugin_merit_badge_post_type->create_custom_post_type();
		$plugin_upcoming_event_post_type->create_custom_post_type();

        // Create Eagle Scout Custom Post Types from database records.
		$plugin_eagle_scout_post_type->upgrade_to_v_1_1_0();

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
		
	}

}
