<?php
/**
 * Template functions.
 *
 * @package     Connections
 * @subpackage  Template Functions
 * @copyright   Copyright (c) 2013, Steven A. Zahm
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       unknown
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'qTipvCard' ) ) {

	class qTipvCard {
		/**
		 * Load the template filters.
		 *
		 * @author Steven A. Zahm
		 * @version 1.0
		 */
		public function __construct() {

			wp_enqueue_style( 'connections-qtip' );

			// Update the permitted shortcode attribute the user may use and overrride the template defaults as needed.
			add_filter( 'cn_list_atts_permitted-qtip-vcard', array( &$this, 'initShortcodeAtts' ) );
			add_filter( 'cn_list_atts-qtip-vcard', array( &$this, 'initTemplateOptions' ) );

			add_action(
				'wp_footer',
				function() {
					wp_print_scripts( 'jquery-qtip' );
				}
			);
		}

		/**
		 * Initiate the permitted template shortcode options and load the default values.
		 *
		 * @author Steven A. Zahm
		 * @version 1.0
		 */
		public function initShortcodeAtts( $permittedAtts = array() ) {
			// $permittedAtts['cnvcard_test'] ='init';

			return $permittedAtts;
		}

		/**
		 * Initiate the template options using the user supplied shortcode option values.
		 *
		 * @author Steven A. Zahm
		 * @version 1.0
		 */
		public function initTemplateOptions( $atts ) {
			// $convert = new cnFormatting();
			// $atts['cnvcard-test'] ='true';
			return $atts;
		}
	}

	// print_r($this);
	$this->qTipvCard = new qTipvCard();
}
