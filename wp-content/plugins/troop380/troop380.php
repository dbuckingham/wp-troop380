<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/dbuckingham/wp-troop380
 * @since             1.0.0
 * @package           troop380
 *
 * @wordpress-plugin
 * Plugin Name:       Troop 380
 * Plugin URI:        https://github.com/dbuckingham/wp-troop380
 * Description:       A WP plugin of useful, scouting related features, built for Troop 380 of the Lincoln Heritage Council.
 * Version:           1.2.0
 * Author:            David Buckingham
 * Author URI:        https://github.com/dbuckingham/wp-troop380
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       troop380
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'TROOP380_VERSION', '1.2.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-troop380-activator.php
 */
function activate_troop380() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-troop380-activator.php';
	Troop380_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-troop380-deactivator.php
 */
function deactivate_troop380() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-troop380-deactivator.php';
	Troop380_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_troop380' );
register_deactivation_hook( __FILE__, 'deactivate_troop380' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-troop380.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_troop380() {

	$plugin = new Troop380();
	$plugin->run();

}
run_troop380();