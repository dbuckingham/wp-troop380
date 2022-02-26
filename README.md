# Troop 380

A WP plugin of useful, scouting related features, built for Troop 380 of the Lincoln Heritage Council.

## Resources
- [QuickStart: Compose and WordPress](https://docs.docker.com/compose/wordpress/)
- [WordPress Plugin Boilerplate](http://wppb.io/)
- [WordPress Plugin Boilerplate Generator](https://wppb.me/)
- [WordPress Plugin Boilerplate (Repo)](https://github.com/DevinVinson/WordPress-Plugin-Boilerplate)
- [WordPress Plugin Boilerplate Tutorials](https://github.com/JoeSz/WordPress-Plugin-Boilerplate-Tutorial/tree/master/plugin-name/tutorials)
- [PHP Style Guide](https://gist.github.com/ryansechrest/8138375)

## WP_Query Snippets

```
<?php

$wp_troop380_eaglescout_id = 92;

$args = array(
	"post_type" => "eaglescout",
	"meta_key" => "_wp_troop380_eaglescout_id",
	"meta_value" => $wp_troop380_eaglescout_id
	);

// print_r( $args );
// echo "<br /><br />";

$query = new WP_Query( $args );

$posts = $query->posts;

foreach( $posts as $post ) {
	
	print_r( $post );
	echo "<br /><br />";
	
}
```

```
<?php

global $wpdb;

$query = "SELECT * FROM information_schema.TABLES WHERE TABLE_NAME = 'wp_troop380_eaglescout';";
$eagle_scout_table = $wpdb->get_results($query);

if( ($wpdb->num_rows <= 0) ) { return; }

$query = "SELECT id, YEAR(date_earned) 'year', firstname, lastname, date_earned, date_earned_is_real FROM wp_troop380_eaglescout ORDER BY date_earned, lastname, firstname LIMIT 1;";

$rows = $wpdb->get_results($query);

foreach($rows as $row) {

    $wp_troop380_eaglescout_id = $row->id;

    $args = array(
        "post_type" => "eaglescout",
        "meta_key" => "_wp_troop380_eaglescout_id",
        "meta_value" => $wp_troop380_eaglescout_id
        );

    $query = new WP_Query( $args );

    $posts = $query->posts;

	// print_r( $wp_troop380_eaglescout_id );
	// echo "<br />";
	
	if( count( $posts ) == 0 ) {
		// Eagle Scout post does not exist and needs to be created.
		
		$postarr = array(
			"post_title" => $row->firstname . " ". $row->lastname,
			"post_type" => "eaglescout",
			"post_status" => "publish",
			"meta_input"=> array(
				"board_of_review_date" => date( "m/d/Y", strtotime( $row->date_earned ) ),
				"board_of_review_date_is_real" => $row->date_earned_is_real,
				"year_earned" => date( "Y", strtotime( $row->date_earned ) ),
				"_wp_troop380_eaglescout_id" => $wp_troop380_eaglescout_id
			)
		);
		
		print_r( $postarr );
		echo "<br />";
		
		$result = wp_insert_post( $postarr );
		print_r($result);
		echo "<br />";
		
	}
}
```