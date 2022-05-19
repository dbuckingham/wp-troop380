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

    public static function run( $atts = [] ) {

        require_once plugin_dir_path( __FILE__ ) . 'class-troop380-eagle-scout.php';

        $atts = array_change_key_case( (array)$atts, CASE_LOWER );

        $eaglescout_atts = shortcode_atts(
            array(
                "mode" => "full",
                "order" => "desc",
                "offset" => 0,
                "show_board_of_review_date" => "true",
                "heading_level" => "2"
            ), $atts
        );

        switch( $eaglescout_atts["mode"] ) {
            case "year":
                $output = self::build_list_of_eagle_scouts_for_year( $eaglescout_atts );
                break;

            default:
                $output = self::build_full_list_of_eagle_scouts( $eaglescout_atts );
        }

        return $output;
    }

    private static function build_full_list_of_eagle_scouts( $atts ) {

        $eagle_scouts = self::get_eagle_scouts_from_posts($atts["order"]);
        $grouped_eagle_scouts = self::get_eagle_scouts_grouped_by_year( $eagle_scouts );

        $output = self::display_header( $grouped_eagle_scouts );

        $output = $output . self::display_list( $grouped_eagle_scouts, $atts );

        return $output;
    }

    private static function build_list_of_eagle_scouts_for_year( $atts ) {
        $current_year = 2022;

        $offset = (int)$atts["offset"];
        $for_year = $current_year + $offset;

        $output = $for_year;

        $eagle_scouts = self::get_eagle_scouts_from_posts_for_year( $for_year, $atts["order"] );
        $grouped_eagle_scouts = self::get_eagle_scouts_grouped_by_year( $eagle_scouts );

        $output = self::display_list( $grouped_eagle_scouts, $atts );

        return $output;
    }

    private static function get_eagle_scouts_grouped_by_year( $eagle_scouts ) {
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

    private static function get_eagle_scouts_from_posts( $order ) {
        
        $args = array(
            "post_type"  => "eaglescout",
            "nopaging"  => true,
            "meta_key" => "board_of_review_date",
            "meta_type" => "DATETIME",
            "orderby" => "meta_value",
            "order" => $order
        );
        $query = new WP_Query( $args );

        $posts = $query->posts;

        $eagle_scouts = array();
        foreach($posts as $post) {
            $eagle_scout = new Troop380_Eagle_Scout( $post );
            array_push($eagle_scouts, $eagle_scout);
        }

        return $eagle_scouts;

    }

    private static function get_eagle_scouts_from_posts_for_year( $for_year, $order ) {
        
        $args = array(
            "post_type"  => "eaglescout",
            "nopaging"  => true,
            "meta_query" => array(
                "key" => "year_earned",
                "value" => $for_year,
                "compare" => "="
            ),
            "meta_key" => "board_of_review_date",
            "meta_type" => "DATETIME",
            "orderby" => "meta_value",
            "order" => $order
        );
        $query = new WP_Query( $args );

        $posts = $query->posts;

        $eagle_scouts = array();
        foreach($posts as $post) {
            $eagle_scout = new Troop380_Eagle_Scout( $post );
            array_push($eagle_scouts, $eagle_scout);
        }

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

    private static function display_list( $eagle_scouts_by_year, $atts ) {

        $output = "";
        $show_board_of_review_date = filter_var( $atts["show_board_of_review_date"], FILTER_VALIDATE_BOOLEAN );

        foreach( $eagle_scouts_by_year as $year => $eagle_scouts_in_year ) {
            $output .= "<h" . $atts["heading_level"] . " id=" . $year . " class='eagleScoutYear'>" . $year . "</h" . $atts["heading_level"] . ">";

            foreach( $eagle_scouts_in_year as $eagle_scout ) {
                $output .= "<div><a href='" . $eagle_scout->permalink . "'>" . $eagle_scout->name . "</a>" . ( 
                    ( $show_board_of_review_date && $eagle_scout->board_of_review_date_is_real ) ? 
                    " (" . $eagle_scout->board_of_review_date . ")" : 
                    "" ) . "</div>";
            }
        }

        return $output;
    }

}