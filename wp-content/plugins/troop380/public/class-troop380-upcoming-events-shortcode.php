<?php

/**
 * Insert description...
 *
 * @since      1.2.0
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
class Troop380_Upcoming_Events_Shortcode {

    public static function run( $atts = [] ) {

        require_once plugin_dir_path( __FILE__ ) . '../includes/class-troop380-helpers.php';
        require_once plugin_dir_path( __FILE__ ) . 'class-troop380-upcoming-event.php';

        $atts = array_change_key_case( (array)$atts, CASE_LOWER );

        $upcoming_events = self::get_upcoming_events_from_posts();

        $output = "<div class='t380-upcoming-events'>";
        if( empty($upcoming_events) ) {
            $output .= "<div class='card no-upcoming-events'>";
            $output .= "There are no upcoming events planned.";
            $output .= "</div>";
        }
        else {
            foreach($upcoming_events as $upcoming_event) {
                $output .= self::format_upcoming_event_for_shortcode( $upcoming_event );
            }
        }
        $output .= "</div>";

        return $output;
    }

    private static function get_upcoming_events_from_posts() {

        $first_of_the_month = Troop380_Helpers::get_first_of_the_month();

        $args = array(
            "post_type" => "upcoming-event",
            "nopaging" => false,
            "post_per_page" => 6,
            "meta_key" => "activity_month",
            "meta_type" => "DATETIME",
            "orderby" => "meta_value",
            "order" => "asc",
            "meta_query" => array(
                array(
                    "key" => "activity_month",
                    "value" => $first_of_the_month,
                    "compare" => ">="
                )
            )
        );
        $query = new WP_Query( $args );

        $posts = $query->posts;

        $upcoming_events = array();
        foreach($posts as $post) {
            $upcoming_event = new Troop380_Upcoming_Event( $post );
            array_push($upcoming_events, $upcoming_event);
        }

        return $upcoming_events;
    }

    private static function format_upcoming_event_for_shortcode( $upcoming_event ) {

        $output = "<div class='card'>";
        $output .= "<article>";
        $output .= "<h1>" . Troop380_Helpers::format_date("F Y", $upcoming_event->month_date) . "</h1>";
        $output .= "<div class='title'>" . $upcoming_event->title . "</div>";
        $output .= "<div class='location'>" . $upcoming_event->location . "</div>";
        
        $output .= "<div class='info last'>";
        if( $upcoming_event->link != "" )
        {
            $output .= "<a href='" . $upcoming_event->link . "'>Click here for more information.</a>";
        }
        $output .= "</div>";

        $orgnizer = !( "" == $upcoming_event->patrol || "TBD" == $upcoming_event->patrol );
        if( $orgnizer )
        {
            $output .= "<span>";
            $output .= "Organized by the " . $upcoming_event->patrol . "<br />";
            $output .= "(" . $upcoming_event->coordinator . ")";
            $output .= "</span>";
        }
        
        $output .= "</article>";
        $output .= "</div>";

        return $output;

    }

}