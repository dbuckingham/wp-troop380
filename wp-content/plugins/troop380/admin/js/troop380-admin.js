(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

// 	 $(function() {
// 		$("#activity_month").datepicker(
// 					   {
// 						   dateFormat: "mm/yy",
// 						   changeMonth: true,
// 						   changeYear: true,
// 						   showButtonPanel: true,
// 						   onClose: function(dateText, inst) {
   
// 							   function isDonePressed(){
// 								   return ($('#ui-datepicker-div').html().indexOf('ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all ui-state-hover') > -1);
// 							   }
   
// 							   if (isDonePressed()){
// 								   var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
// 								   var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
// 								   $(this).datepicker('setDate', new Date(year, month, 1)).trigger('change');
								   
// 									$("#activity_month").focusout()//Added to remove focus from datepicker input box on selecting date
// 							   }
// 						   },
// 						   beforeShow : function(input, inst) {
   
// 							   inst.dpDiv.addClass('month_year_datepicker')
   
// 							   if ((datestr = $(this).val()).length > 0) {
// 								   year = datestr.substring(datestr.length-4, datestr.length);
// 								   month = datestr.substring(0, 2);
// 								   $(this).datepicker('option', 'defaultDate', new Date(year, month-1, 1));
// 								   $(this).datepicker('setDate', new Date(year, month-1, 1));
// 								   $(".ui-datepicker-calendar").hide();
// 							   }
// 						   }
// 					   })
//    });

	// $(function() {
	// 	$("#activity_month").datepicker(
	// 		{
	// 			dateFormat: 'mm/dd/yy'
	// 		});
	// });

})( jQuery );
