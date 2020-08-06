<?php

/**
 * The functionality of the [eaglescout] shortcode.
 *
 * @since      1.0.0
 *
 * @package    troop380
 * @subpackage troop380/public
 */

/**
 * The functionality of the [eaglescout] shortcode.
 *
 * @package    troop380
 * @subpackage troop380/public
 * @author     David Buckingham <david.buckingham@outlook.com>
 */
class Troop380_EagleScout_Shortcode {

    /**
	 * Render the shortcode when executed.
	 *
	 * @since    1.0.0
	 */
	public static function render( $atts ) {

        if(self::get_eagle_scout_count() == 0) {
            return "<p>There are no Eagle Scouts.</p>";
        }

        return self::build_header() . self::build_list();
    }
    
    private static function get_eagle_scout_count() {
        global $wpdb;

        $query = "SELECT COUNT(*) FROM wp_troop380_eaglescout;";

        $eagleScoutCount = $wpdb->get_var($query);

        return $eagleScoutCount;
    }

    private static function build_header() {
        global $wpdb;
        $output = "<p>";
        $first = true;
        
		$query = "SELECT YEAR(date_earned) 'year', COUNT(*) 'qty' FROM wp_troop380_eaglescout GROUP BY YEAR(date_earned) ORDER BY YEAR(date_earned);";

		$yearsWithEagleScouts = $wpdb->get_results($query);
        
		foreach($yearsWithEagleScouts as $yearWithEagleScouts){
			if(!$first) {
				$output .= " | ";
			}
			
			$output .= "<a href='#$yearWithEagleScouts->year'>$yearWithEagleScouts->year</a> ($yearWithEagleScouts->qty)";

			$first = false;
        }
        
        $output .= "</p>";

        return $output;
    }

    private static function build_list() {
        global $wpdb;
        $output = "";

        $query = "SELECT YEAR(date_earned) 'year', firstname, lastname, date_earned, date_earned_is_real FROM wp_troop380_eaglescout ORDER BY date_earned, lastname, firstname;";

        $eagleScouts = $wpdb->get_results($query);

        $previousYear = "";
        foreach($eagleScouts as $eagleScout)
        {
            if($eagleScout->year != $previousYear)
            {
                $output .= "<h2 id='$eagleScout->year'>$eagleScout->year</h2>";
                $previousYear = $eagleScout->year;
            }

            $output .= "<div>";
            $output .= "$eagleScout->firstname $eagleScout->lastname";

            if("1" === $eagleScout->date_earned_is_real) {
                $output .= " (" . date_format(date_create($eagleScout->date_earned), "m/d/Y") . ")";
            }

            $output .= "</div>";
        }

        return $output;
    }

}