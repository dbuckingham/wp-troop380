<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://github.com/dbuckingham/wp-troop380
 * @since      1.0.0
 *
 * @package    troop380
 * @subpackage troop380/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    troop380
 * @subpackage troop380/includes
 * @author     David Buckingham <david.buckingham@outlook.com>
 */
class Troop380_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

		/**
		 * This only required if custom post type has rewrite!
		 */
		flush_rewrite_rules();

	}

}
