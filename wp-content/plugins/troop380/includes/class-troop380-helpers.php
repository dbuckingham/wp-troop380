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

    /**
	 * Used to format the Board of Review Date meta value for display.
	 * 
	 * @since	1.1.4
	 */
	public static function format_board_of_review_date( $board_of_review_date ) {
		if( $board_of_review_date == "" ) {
			return date( "m/d/Y" );
		}

		return date( "m/d/Y", strtotime( $board_of_review_date ) );
	}

}