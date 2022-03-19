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
class Troop380_Upcoming_Event {

    public $title;
    public $month_date;
    public $location;
    public $patrol;
    public $coordinator;
    public $link;


    function __construct( $post ) {

        require_once plugin_dir_path( __FILE__ ) . '../includes/class-troop380-helpers.php';
    
        $this->title = $post->post_title;

        $month_date = get_post_meta( $post->ID, 'activity_month', true );
        $this->month_date = Troop380_Helpers::format_date( "F Y", $month_date );

        $this->location = get_post_meta( $post->ID, 'location', true );
        $this->patrol = get_post_meta( $post->ID, 'patrol', true );
        $this->coordinator = get_post_meta( $post->ID, 'coordinator', true );
        $this->link = get_post_meta( $post->ID, 'link', true );

    }
}