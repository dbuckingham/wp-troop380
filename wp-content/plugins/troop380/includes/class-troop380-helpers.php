<?php

/**
 * Insert description...
 *
 * @since      1.1.4
 *
 * @package    troop380
 * @subpackage troop380/includes
 */

/**
 * Insert description...
 *
 * @package    troop380
 * @subpackage troop380/includes
 * @author     David Buckingham <david.buckingham@outlook.com>
 */
class Troop380_Helpers {

	public static function format_month_year( $date_string ) {
		return self::format_date( "m/Y", $date_string );
	}

	public static function format_shortdate( $date_string ) {
		return self::format_date( "m/d/Y", $date_string );
	}

	public static function format_date( $format_string, $date_string ) {
		if( $date_string == "" ) {
			return date( $format_string );
		}

		return date( $format_string, strtotime( $date_string ) );
	}

	public static function get_first_of_the_month() {
		return date( "Ymd", strtotime( date('m') . "/01/" . date('Y') ) );
	}

}