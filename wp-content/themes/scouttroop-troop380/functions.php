<?php

require_once dirname(__FILE__) . '/includes/class.merit-badge.php';
Merit_Badge::__constructStatic();

add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );

function enqueue_parent_styles() {
   $parenthandle = 'theme-style';
   $theme = wp_get_theme();

   wp_enqueue_style( $parenthandle, get_template_directory_uri() . '/style.css' );
   wp_enqueue_style( 'child-style', get_stylesheet_uri(), array($parenthandle), $theme->get('Version') );
}

add_action( 'cn_metabox', 'cn_register_merit_badge_metabox' );
 
function cn_register_merit_badge_metabox() {

   $atts = array(
		'title'    => 'Merit Badge Counselor',
		'id'       => 'merit_badge_counselor',
		'context'  => 'normal',
		'priority' => 'core',
		'fields'   => array(
			array(
               'name'        => 'Merit Badges',    // Change this field name to something which applies to you project.
               'show_label'  => TRUE,             	// Whether or not to display the 'name'. Changing it to false will suppress the name.
               'id'          => 'merit_badges', 	// Change this so it is unique to you project. Each field id MUST be unique.
               'type'        => 'checkboxgroup',  	// This is the field type being added.
               'options'     => Merit_Badge::merit_badge_names(),
               'default'     => '',                // This is the default selected option. Leave blank for none.
            ),
		),
	);

	cnMetaboxAPI::add( $atts );
}

?>