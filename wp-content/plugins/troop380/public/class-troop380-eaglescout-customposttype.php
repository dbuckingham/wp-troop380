<?php

/**
 * The functionality of the Eagle Scouts Custom Post Type
 *
 * @since      1.1.0
 *
 * @package    troop380
 * @subpackage troop380/public
 */

/**
 * The functionality of the Eagle Scouts Custom Post Type
 *
 * @package    troop380
 * @subpackage troop380/public
 * @author     David Buckingham <david.buckingham@outlook.com>
 */
class Troop380_EagleScout_CustomPostType {

    /**
     * Register the Eagle Scout custom post type.
     * 
     * @since    1.1.0
     */
    public static function register()
    {
        register_post_type('troop380_eaglescout',
			array(
				'labels'      => array(
					'name'          => __('Eagle Scouts', 'textdomain'),
					'singular_name' => __('Eagle Scout', 'textdomain'),
				),
					// 'description' 	=> 'An Eagle Scout of Troop 380',
					'public'      	=> true,
					'has_archive'	=> true,
					'rewrite' 		=> array( 'slug' => 'eaglescouts' ),
					'menu_icon'		=> 'dashicons-admin-users'
			)
		);
    }

	/**
     * Add the meta boxes for the Eagle Scout custom post type.
     * 
     * @since    1.1.0
     */
    public static function metaboxes()
    {
        // $screens = ['troop380_eaglescout'];
		// foreach($screens as $screen)
		// {
		// 	add_meta_box(
		// 		'troop380_eaglescout_details',
		// 		'Eagle Scout Details',

		// 	);
		// }
    }

}