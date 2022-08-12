<?php

/**
 * Fired during plugin activation
 *
 * @link       http://www.dynamicweblab.com/
 * @since      1.0.0
 *
 * @package    Wp_Team_Manager
 * @subpackage Wp_Team_Manager/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Team_Manager
 * @subpackage Wp_Team_Manager/includes
 * @author     Maidul <info@dynamicweblab.com>
 */
class Wp_Team_Manager_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		flush_rewrite_rules();
		wtm_version_update_check();
		
	}

}
