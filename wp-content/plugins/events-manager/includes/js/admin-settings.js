jQuery(document).ready(function($){
	//Meta Box Options
	var open_close = $('<a href="#" style="display:block; float:right; clear:right; margin:10px;">'+EM.open_text+'</a>');
	$('#em-options-title').before(open_close);
	open_close.on('click', function(e){
		e.preventDefault();
		if($(this).text() == EM.close_text){
			$(".postbox").addClass('closed');
			$(this).text(EM.open_text);
		}else{
			$(".postbox").removeClass('closed');
			$(this).text(EM.close_text);
		} 
	});
	$(".postbox > h3").on('click', function(){ $(this).parent().toggleClass('closed'); });
	$(".postbox").addClass('closed');
	//Navigation Tabs
	$('.tabs-active .nav-tab-wrapper .nav-tab').on('click', function(){
		el = $(this);
		elid = el.attr('id');
		$('.em-menu-group').hide(); 
		$('.'+elid).show();
		$(".postbox").addClass('closed');
		open_close.text(EM.open_text);
	});
	$('.nav-tab-wrapper .nav-tab').on('click', function(){
		$('.nav-tab-wrapper .nav-tab').removeClass('nav-tab-active').blur();
		$(this).addClass('nav-tab-active');
	});
	var navUrl = document.location.toString();
	if (navUrl.match('#')) { //anchor-based navigation
		var nav_tab = navUrl.split('#').pop().split('+');
		var current_tab = 'a#em-menu-' + nav_tab[0];
		$(current_tab).trigger('click');
		if( nav_tab.length > 1 ){
			section = $("#em-opt-"+nav_tab[1]);
			if( section.length > 0 ){
				section.children('h3').trigger('click');
		    	$('html, body').animate({ scrollTop: section.offset().top - 30 }); //sends user back to current section
			}
		}
	}else{
		//set to general tab by default, so we can also add clicked subsections
		document.location = navUrl+"#general";
	}
	$('.nav-tab-link').on('click', function(){ $($(this).attr('rel')).trigger('click'); }); //links to mimick tabs
	$('input[type="submit"]').on('click', function(){
		var el = $(this).parents('.postbox').first();
		var docloc = document.location.toString().split('#');
		var newloc = docloc[0];
		if( docloc.length > 1 ){
			var nav_tab = docloc[1].split('+');
			var tab_path = nav_tab[0];
			if( el.attr('id') ){
				tab_path = tab_path + "+" + el.attr('id').replace('em-opt-','');
			}
			newloc = newloc + "#" + tab_path;
		}
		document.location = newloc;
		$(this).closest('form').append('<input type="hidden" name="tab_path" value="'+ tab_path +'" />');
	});
	//Page Options
	$('input[name="dbem_cp_events_has_archive"]').on('change', function(){ //event archives
		if( $('input:radio[name="dbem_cp_events_has_archive"]:checked').val() == 1 ){
			$('tbody.em-event-archive-sub-options').show();
		}else{
			$('tbody.em-event-archive-sub-options').hide();
		}
	}).trigger('change');
	$('select[name="dbem_events_page"]').on('change', function(){
		if( $('select[name="dbem_events_page"]').val() == 0 ){
			$('tbody.em-event-page-options').hide();
		}else{
			$('tbody.em-event-page-options').show();
		}
	}).trigger('change');
	$('input[name="dbem_cp_locations_has_archive"]').on('change', function(){ //location archives
		if( $('input:radio[name="dbem_cp_locations_has_archive"]:checked').val() == 1 ){
			$('tbody.em-location-archive-sub-options').show();
		}else{
			$('tbody.em-location-archive-sub-options').hide();
		}
	}).trigger('change');
	//For rewrite titles
	$('input:radio[name=dbem_disable_title_rewrites]').on('change',function(){
		checked_check = $('input:radio[name=dbem_disable_title_rewrites]:checked');
		if( checked_check.val() == 1 ){
			$('#dbem_title_html_row').show();
		}else{
			$('#dbem_title_html_row').hide();	
		}
	});
	$('input:radio[name=dbem_disable_title_rewrites]').trigger('change');
	//for event grouping
	$('select[name="dbem_event_list_groupby"]').on('change', function(){
		if( $('select[name="dbem_event_list_groupby"]').val() == 0 ){
			$('tr#dbem_event_list_groupby_header_format_row, tr#dbem_event_list_groupby_format_row').hide();
		}else{
			$('tr#dbem_event_list_groupby_header_format_row, tr#dbem_event_list_groupby_format_row').show();
		}
	}).trigger('change');
	//ML Stuff
	$('.em-translatable').on('click', function(){
		$(this).nextAll('.em-ml-options').toggle();
	});
	//radio triggers
	$('input[type="radio"].em-trigger').on('change', function(e){
		var el = $(this);
		el.val() == '1' ? $(el.attr('data-trigger')).show() : $(el.attr('data-trigger')).hide();
	});
	$('input[type="radio"].em-trigger:checked').trigger('change');
	$('input[type="radio"].em-untrigger').on('change', function(e){
		var el = $(this);
		el.val() == '0' ? $(el.attr('data-trigger')).show() : $(el.attr('data-trigger')).hide();
	});
	$('input[type="radio"].em-untrigger:checked').trigger('change');
	//checkbox triggers
	$('input[type="checkbox"].em-trigger').on('change', function(e){
		var el = $(this);
		el.prop('checked') ? $(el.attr('data-trigger')).show() : $(el.attr('data-trigger')).hide();
	});
	$('input[type="checkbox"].em-trigger').trigger('change');
	$('input[type="checkbox"].em-untrigger').on('change', function(e){
		var el = $(this);
		!el.prop('checked') ? $(el.attr('data-trigger')).show() : $(el.attr('data-trigger')).hide();
	});
	$('input[type="checkbox"].em-untrigger').trigger('change');
	//admin tools confirm
	$('a.admin-tools-db-cleanup').on('click', function( e ){
		if( !confirm(EM.admin_db_cleanup_warning) ){
			e.preventDefault();
			return false;
		}
	});
	//color pickers
	$('#dbem_category_default_color, #dbem_tag_default_color').wpColorPicker();

	// reset admin setting via ajax
	$('.em-option-resettable').on('click', function( e ){
		e.preventDefault();
	    let el = $(this);
	    let name = el.attr('data-name');
	    let inputs = el.closest('tr').find('input[name="'+name+'"], textarea[name="'+name+'"]');
	    $.get({
	        url : EM.ajaxurl,
	        data : {
	            action : 'em_admin_get_option_default',
	            option_name : name,
	            nonce : el.attr('data-nonce'),
	        },
            success : function(data){
                inputs.val(data);
                inputs.prop('disabled', false);
                alert(EM.option_reset);
            },
            beforeSend: function(){
                inputs.prop('disabled', true);
            },
            error : function(){
                inputs.prop('disabled', false);
                alert('Error - could not revert.');
            },
	        dataType: 'text',
	    })
	});

    let status = $('#em-advanced-formatting');
    let af_toggle_action = function(){
        const am = status.val();
        if( am == 0 ){
            $('.am-af').hide();
        }else if( am == 1 ){
            $('.am-af').show();
            $('.dbem_advanced_formatting_modes_row').show(); // show toggles
            $('.dbem_advanced_formatting_modes .em-trigger:checked').trigger('change');
        }else{
            $('.am-af').show(); // show everything
            $('.dbem_advanced_formatting_modes_row').hide(); // hide toggles
        }
        $('.em-af-toggle, .em-af-status span').hide();
        $('.em-af-toggle.show-'+ am).show();
        $('.em-af-status-'+ am).show();
        if( $('.em-af-status').attr('data-status') != am ){
            $('.em-af-status .em-af-status-save').show();
        }else{
            $('.em-af-status .em-af-status-save').hide();
        }
    };
	$('.em-af-toggle').on('click', function(e){
	    e.preventDefault();
        status.val( this.getAttribute('data-set-status') );
        af_toggle_action();
	});
	af_toggle_action();

    if( typeof EM.admin === 'object' && 'settings' in EM.admin ){
        tippy( $('.dbem_advanced_formatting_modes_row th').toArray(), {
            content : EM.admin.settings.option_override_tooltip,
        });
    }
});