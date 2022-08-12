var $jwptm = jQuery.noConflict();
$jwptm(function(){

	var selector = $jwptm('#shortcode_output_box'),
		catslug = $jwptm( "#tm_cat" ).val(),
		orderby = $jwptm( "#tm_orderby" ).val(),
		tm_limit = $jwptm( "#tm_limit" ).val(),
		tm_show_id = $jwptm( "#tm_show_id" ).val(),
		tm_remove_id = $jwptm( "#tm_remove_id" ).val(),
		tm_layout = $jwptm( "#tm_layout" ).val(),
		tm_column = $jwptm( "#tm_column" ).val(),
		tm_image_layout = $jwptm( "#tm_image_layout" ).val();
		tm_image_size = $jwptm( "#tm_image_size" ).val();
		tm_primary_color = $jwptm( "#tm_primary_color" ).val();
		tm_hide_info = $jwptm(this).attr("checked") ? 1 : 0;

		
	$jwptm( '#tm_cat' ).on( "keyup keydown change", function() {

		catslug = $jwptm(this).val();

	});
	$jwptm( '#tm_orderby' ).on( "keyup keydown change", function() {

		orderby = $jwptm(this).val();
	});
	$jwptm( '#tm_limit' ).on( "keyup keydown change", function() {

		tm_limit = $jwptm(this).val();
	});	
	$jwptm( '#tm_show_id' ).on( "keyup keydown change", function() {

		tm_show_id = $jwptm(this).val();
	});		
	$jwptm( '#tm_remove_id' ).on( "keyup keydown change", function() {

		tm_remove_id = $jwptm(this).val();
	});		
	$jwptm( '#tm_layout' ).on( "keyup keydown change", function() {

		tm_layout = $jwptm(this).val();
	});	
	$jwptm( '#tm_column' ).on( "keyup keydown change", function() {

		tm_column = $jwptm(this).val();
	});	
	
	$jwptm( '#tm_image_layout' ).on( "keyup keydown change", function() {

		tm_image_layout = $jwptm(this).val();
	});		

	$jwptm( '#tm_image_size' ).on( "keyup keydown change", function() {

		tm_image_size = $jwptm(this).val();
 	});	
	 
	 $jwptm( '#tm_primary_color' ).wpColorPicker({
		change: function(event, ui){
			tm_primary_color = ui.color.toString();
			tm_short_code();
		} 
	  });

	  $jwptm( '#tm_hide_info' ).on( "keyup keydown change", function() {

		tm_hide_info = $jwptm(this).attr("checked") ? 1 : 0;
 	});	

	function tm_short_code(){
		var shortcodegenerated = 
		"[team_manager category='"+catslug+"' orderby='"+orderby+"' limit='"+tm_limit+"' post__in='"+tm_show_id+"' exclude='"+tm_remove_id+"' layout='"+tm_layout+"' column='"+tm_column+"' image_layout='"+tm_image_layout+"' image_size='"+tm_image_size+"' color='"+tm_primary_color+"' hide_meta='"+tm_hide_info+"']";

		selector.empty().append(shortcodegenerated);
	}  


	$jwptm('#tm_short_code :input').on( "keyup keydown change", function() {

		tm_short_code();

	});

    if ($jwptm('.tm-color').length) {
        $jwptm('.tm-color').wpColorPicker();
    }

	selector.on('click', function () {
        $jwptm(this).select();
        document.execCommand('copy');
    });

});
