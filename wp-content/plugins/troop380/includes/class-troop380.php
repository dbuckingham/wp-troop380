<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/dbuckingham/wp-troop380
 * @since      1.0.0
 *
 * @package    troop380
 * @subpackage troop380/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    troop380
 * @subpackage troop380/includes
 * @author     David Buckingham <david.buckingham@outlook.com>
 */
class Troop380 {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Troop380_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'TROOP380_VERSION' ) ) {
			$this->version = TROOP380_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'troop380';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Troop380_Loader. Orchestrates the hooks of the plugin.
	 * - Troop380_i18n. Defines internationalization functionality.
	 * - Troop380_Admin. Defines all hooks for the admin area.
	 * - Troop380_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-troop380-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-troop380-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-troop380-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-troop380-public.php';

		/**
		 * Custom Post Types
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-troop380-post-types.php';


		$this->loader = new Troop380_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Plugin_Name_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Troop380_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Troop380_Admin( $this->get_plugin_name(), $this->get_version() );
		$plugin_post_types = new Troop380_Post_types();

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'init', $plugin_post_types, 'create_custom_post_type', 999);

		/**
		 * Add metabox and register custom fields
		 *
		 * @link https://code.tutsplus.com/articles/rock-solid-wordpress-30-themes-using-custom-post-types--net-12093
		 */
		$this->loader->add_action( 'admin_init', $plugin_admin, 'rerender_meta_options' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_meta_options' );

		/**
		 * Modify columns in eagle scouts list in admin area.
		 *
		 * The hooks to create custom columns and their associated data for a custom post type
		 * are manage_{$post_type}_posts_columns and
		 * manage_{$post_type}_{$post_type_type}_custom_column or manage_{$post_type_hierarchical}_custom_column respectively,
		 * where {$post_type} is the name of the custom post type and {$post_type_hierarchical} is post or page.
		 *
		 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/manage_posts_custom_column
		 * @link https://wordpress.stackexchange.com/questions/253640/adding-custom-columns-to-custom-post-types/253644#253644
		 */
		$this->loader->add_filter( 'manage_eaglescout_posts_columns', $plugin_admin, 'manage_eaglescout_posts_columns' );
		$this->loader->add_action( 'manage_eaglescout_posts_custom_column', $plugin_admin, 'manage_eaglescout_posts_custom_column', 10, 2 );

		// TODO - review the admin_head action.
		// $this->loader->add_action( 'admin_head', $plugin_admin, 'add_style_to_admin_head' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Troop380_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		/**
		 * Register shortcodes via loader
		 *
		 * Use: [short-code-name args]
		 *
		 * @link https://github.com/DevinVinson/WordPress-Plugin-Boilerplate/issues/262
		 */
		$this->loader->add_shortcode( "eaglescouts", $plugin_public, "eaglescouts_shortcode", $priority = 10, $accepted_args = 2 );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Plugin_Name_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
