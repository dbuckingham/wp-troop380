<?php

/**
 * Insert description...
 *
 * @since      1.1.0
 *
 * @package    troop380
 * @subpackage troop380/public
 */

/**
 * Insert description...
 *
 * @package    troop380
 * @subpackage troop380/public
 * @author     David Buckingham <david.buckingham@outlook.com>
 */
class Troop380_Eagle_Scout_Shortcode {

    public static function run( $atts ) {

        require_once plugin_dir_path( __FILE__ ) . 'class-troop380-eagle-scout.php';

        $eagle_scouts = self::get_eagle_scouts_grouped_by_year();

        $output = self::display_header( $eagle_scouts );

        $output = $output . self::display_list( $eagle_scouts );

        return $output;
    }

    private static function get_eagle_scouts_grouped_by_year() {
        $eagle_scouts = self::get_eagle_scouts_from_posts();

        $grouped_by_year = array();
        foreach($eagle_scouts as $eagle_scout)
        {
            $key = $eagle_scout->year_earned;

            if(array_key_exists($key, $grouped_by_year)) {
                array_push($grouped_by_year[$key], $eagle_scout);
            }
            else {
                $grouped_by_year[$key] = array($eagle_scout);
            }
        }

         return $grouped_by_year;
    }

    private static function get_eagle_scouts_from_posts() {
        
        $args = array(
            'post_type'  => 'eaglescout',
            'nopaging'  => true
        );
        $query = new WP_Query( $args );

        $posts = $query->posts;

        $eagle_scouts = array();
        foreach($posts as $post) {
            $eagle_scout = new Troop380_Eagle_Scout( $post );
            array_push($eagle_scouts, $eagle_scout);
        }

        usort($eagle_scouts, 'Troop380_Eagle_Scout::eagle_scout_sort_by_board_of_review_date_asc' );

        return $eagle_scouts;

    }

    private static function display_header( $eagle_scouts_by_year ) {

        $first = true;

        $output = "<p>";

        foreach($eagle_scouts_by_year as $year => $eagle_scouts_in_year) {
            if(!$first) {
                $output .= " | ";
            }

            $output .= "<a href='#" . $year . "'>" . $year . "</a> (" . count($eagle_scouts_in_year) . ")";

            $first = false;
        }

        $output .= "</p>";

        return $output;
    }

    private static function display_list( $eagle_scouts_by_year ) {

        $output = "";

        foreach( $eagle_scouts_by_year as $year => $eagle_scouts_in_year ) {
            $output .= "<h2 id=" . $year . " class='eagleScoutYear'>" . $year . "</h2>";

            foreach( $eagle_scouts_in_year as $eagle_scout ) {
                $output .= "<div><a href='" . $eagle_scout->permalink . "'>" . $eagle_scout->name . "</a>" . ( 
                    $eagle_scout->board_of_review_date_is_real ? 
                    " (" . $eagle_scout->board_of_review_date . ")" : 
                    "" ) . "</div>";
            }
        }

        return $output;
    }

}