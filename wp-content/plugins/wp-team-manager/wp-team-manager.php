<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://dynamicweblab.com/
 * @since             1.6.3
 * @package           Wp_Team_Manager
 *
 * @wordpress-plugin
 * Plugin Name:       WordPress Team Manager
 * Plugin URI:        https://wordpress.org/plugins/wp-team-manager/
 * Description:       This plugin allows you to manage the members of your team or staff and display them using shortcode.
 * Version:           1.6.9
 * Author:            DynamicWebLab
 * Author URI:        https://dynamicweblab.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-team-manager
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( 'WTM_BASE_DIR' ) )
    define( 'WTM_BASE_DIR', dirname( __FILE__ ) );

$wtm_plugin_data = get_file_data(__FILE__, array('Version' => 'Version'), false);

define( 'WTM_TEAM_VERSION', $wtm_plugin_data['Version']);

//Run code when plugin update to version 1.6.7

function wtm_version_update_check()
{
		
		$wtm_plugin_data = get_file_data(__FILE__, array('Version' => 'Version'), false);
		
		update_option( 'wtm_version', $wtm_plugin_data['Version'] );

		//Set default options
		if ( ! get_option( 'wtm_settings' ) ) {

			$wtm_default_data = array(
				'social_type' => (get_option('tm_social_size')) ? 'icon' : 'font-awesome',//fall back support for older version
				'social_font_size' => 16,
				'social_image_size' => (get_option('tm_social_size')) ? get_option('tm_social_size') : 16,
				'link_new_window' => (get_option('tm_link_new_window')) ? 1 : 0,
				'single_view' => (get_option('single_team_member_view')) ? 1 : 0,
				'custom_template' => (get_option('tm_custom_template')) ? get_option('tm_custom_template') : '',
				'primary_color' => '',
				'custom_css' => (get_option('tm_custom_css')) ? get_option('tm_custom_css') : '',
			);

			update_option( 'wtm_settings', $wtm_default_data );
	
			//Cleanup old settings

			delete_option( 'tm_social_size' );
			delete_option( 'tm_custom_css' );
			delete_option( 'tm_link_new_window' );
			delete_option( 'single_team_member_view' );
			delete_option( 'tm_custom_template' );

		}

}


add_action('plugins_loaded', 'wtm_version_update_check');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-team-manager-activator.php
 */
function activate_wp_team_manager() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-team-manager-activator.php';
	Wp_Team_Manager_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-team-manager-deactivator.php
 */
function deactivate_wp_team_manager() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-team-manager-deactivator.php';
	Wp_Team_Manager_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_team_manager' );
register_deactivation_hook( __FILE__, 'deactivate_wp_team_manager' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-team-manager.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_team_manager() {

	$plugin = new Wp_Team_Manager();
	$plugin->run();

}
run_wp_team_manager();
