<?php

/**
 * Fired during plugin activation.
 *
 * @since      1.0.0
 * 
 * @package    troop380
 * @subpackage troop380/includes
 */

 /**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    troop380
 * @subpackage troop380/includes
 * @author     David Buckingham <david.buckingham@outlook.com>
 */
class Troop380_Activator {

    private static $troop380_db_version = '1.0';

    public static function activate() {

        self::create_eagle_scout_table();

        add_option('troop380_db_version', self::$troop380_db_version);
    }

    private static function create_eagle_scout_table()
    {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $table_name = $wpdb->prefix . 'troop380_eaglescout';

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL auto_increment,
            firstname varchar(256) NOT NULL,
            lastname varchar(256) NOT NULL,
            date_earned date NOT NULL,
            date_earned_is_real bit DEFAULT 0,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
}