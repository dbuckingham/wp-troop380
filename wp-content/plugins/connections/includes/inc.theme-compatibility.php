<?php
/**
 * Theme Compatibility
 *
 * Functions for compatibility with other plugins.
 *
 * @package     Connections
 * @subpackage  Functions/Compatibility
 * @copyright   @copyright   Copyright (c) 2015, Steven A. Zahm
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       8.3
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add the "img-as-is" class to the image and logo if the ProPhoto theme is active.
 *
 * @since 8.3
 *
 * @param string $class
 *
 * @return string
 */
function cn_add_img_as_is_class( $class ) {

	if ( class_exists( 'ppContentFilter' ) && method_exists( 'ppContentFilter', 'modifyImgs' ) ) {

		$class = $class . ' img-as-is';
	}

	return $class;
}

add_filter( 'cn_image_class', 'cn_add_img_as_is_class' );

/**
 * Enfold uses ID CSS selectors which override the core Chosen CSS selectors.
 * This will load an alternative CSS file where all the core Chosen CSS selectors are prefixed with #cn-list.
 * This in theory should override the Enfold CSS selectors.
 *
 * @link https://connections-pro.com/support/topic/enfold-theme-issues/
 * @since 11/03/2017 seems this is no longer required.
 */
function cn_enqueue_enfold_css_override() {

	// If SCRIPT_DEBUG is set and TRUE load the non-minified CSS files, otherwise, load the minified files.
	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	$url = cnURL::makeProtocolRelative( CN_URL );

	$theme  = wp_get_theme();
	$parent = $theme->parent();

	if ( false === $parent || null === $parent ) {

		$enqueue = in_array( $theme->get( 'Name' ), array( 'Enfold' ), true );

	} elseif ( $parent instanceof WP_Theme ) {

		$enqueue = in_array( $parent->get( 'Name' ), array( 'Enfold' ), true );

	} else {

		$enqueue = false;
	}

	if ( $enqueue ) {

		wp_deregister_style( 'cn-chosen' );
		wp_register_style( 'cn-chosen', $url . "vendor/chosen/chosen-cn-list$min.css", array(), CN_CURRENT_VERSION );
	}
}

// add_action( 'wp', 'cn_enqueue_enfold_css_override', 11 ); // Priority 11 to run after core CSS.

/**
 * Dequeue the Striking theme CSS on the Connections admin pages.
 *
 * @since 8.42
 */
function cn_dequeue_striking_css() {

	$theme = wp_get_theme();

	if ( 'striking-r' === $theme->get( 'TextDomain' ) ) {

		wp_dequeue_style( 'theme-admin-style' );
	}
}

add_action( 'cn_admin_enqueue_styles', 'cn_dequeue_striking_css' );

/**
 * @since 8.6.7
 */
function cn_presscore_fancy_header_controller() {

	add_filter( 'presscore_page_title', 'cn_presscore_page_title' );
}

/**
 * @since 8.6.7
 *
 * @param string $title
 *
 * @return string
 */
function cn_presscore_page_title( $title ) {

	$config = Presscore_Config::get_instance();

	if ( 'fancy' != $config->get( 'header_title' ) ) {
		return $title;
	}

	// TODO apply 'the_title' filter here
	$custom_title = ( 'generic' == $config->get( 'fancy_header.title.mode' ) ) ? presscore_get_page_title() : $config->get( 'fancy_header.title' );

	$custom_title = cnSEO::filterPostTitle( $custom_title, get_the_ID() );

	if ( $custom_title ) {

		$title_class = presscore_get_font_size_class( $config->get( 'fancy_header.title.font.size' ) );
		if ( 'accent' == $config->get( 'fancy_header.title.color.mode' ) ) {
			$title_class .= ' color-accent';
		}

		$title_style = '';
		if ( 'color' == $config->get( 'fancy_header.title.color.mode' ) ) {
			$title_style = ' style="color: ' . esc_attr( $config->get( 'fancy_header.title.color' ) ) . '"';
		}

		$custom_title = '<h1 class="fancy-title entry-title ' . $title_class . '"' . $title_style . '><span>' . strip_tags( $custom_title ) . '</span></h1>';

	}

	return $custom_title;
}

add_action( 'presscore_before_main_container', 'cn_presscore_fancy_header_controller', 14 );

/**
 * Disable the "Grab the first post image" setting in Divi on pages which include the shortcode.
 *
 * @since 8.20
 *
 * @param bool $value
 *
 * @return bool
 */
function cn_et_grab_image_setting( $value ) {

	global $post;

	if ( $post instanceof WP_Post && true === $value ) {

		return ! has_shortcode( $post->post_content, 'connections' );
	}

	return $value;
}

add_filter( 'et_grab_image_setting', 'cn_et_grab_image_setting' );

/**
 * Remove the `clean_url` filter being applied by the MayaShop theme.
 *
 * @since 8.21
 *
 * @link https://themeforest.net/item/mayashop-a-flexible-responsive-ecommerce-theme/2189918/comments?page=45&filter=all#comment_2425500
 * @link https://connections-pro.com/support/topic/disable-force-https-option/#post-464997
 */
function cn_remove_maya_clean_url_filter() {

	remove_filter( 'clean_url', 'yiw_ssl_url' );
}

add_action( 'after_setup_theme', 'cn_remove_maya_clean_url_filter' );
