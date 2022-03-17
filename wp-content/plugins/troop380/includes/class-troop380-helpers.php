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
		return self::format_date( $date_string, "m/Y" );
	}

	public static function format_shortdate( $date_string ) {
		return self::format_date( $date_string, "m/d/Y" );
	}

	public static function format_date( $date_string, $format_string ) {
		if( $date_string == "" ) {
			return date( $format_string );
		}

		return date( $format_string, strtotime( $date_string ) );
	}

}