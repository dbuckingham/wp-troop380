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
class Troop380_Eagle_Scout {

    public $name;
    public $board_of_review_date;
    public $board_of_review_date_is_real;
    public $year_earned;
    public $permalink;

    public static function eagle_scout_sort_by_board_of_review_date_asc( $a, $b ) {

        $bor1 = strtotime($a->board_of_review_date);
        $bor2 = strtotime($b->board_of_review_date);

        if( $bor1 < $bor2 )  return -1;
        if( $bor1 == $bor2 ) return 0;
        if( $bor1 > $bor2 )  return 1;

    }

    function __construct( $post ) {
        $this->name = $post->post_title;
        $this->board_of_review_date = get_post_meta( $post->ID, 'board_of_review_date', true );
        $this->year_earned = get_post_meta( $post->ID, 'year_earned', true );

        $is_board_of_review_real = get_post_meta( $post->ID, 'board_of_review_date_is_real', true );
        $this->board_of_review_date_is_real = ($is_board_of_review_real == 'on');

        $this->permalink = get_post_permalink($post->ID);
    }

}