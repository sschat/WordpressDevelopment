jQuery(document).ready(function($) {

	// listen for the "new" button
	$(document).on("click", "#newEvent", function() {

		var values = {};
		values['what'] = 'new';

		//make the call
		ajaxCall(values, '#add_form');

	});
	// listen for the "edit" button
	$(document).on("click", ".editevent", function() {

		var values = {};
		values['what'] = 'edit';
		values['id'] = $(this).attr('id');

		//make the call
		ajaxCall(values, '#add_form');

	});
	// listen for "Opslaan";
	$(document).on("click", "button#save", function() {

		// get all the inputs into an array.
		var inputs = $(this).closest("form").find(' :input');

		// not sure if you wanted this, but I thought I'd add it.
		// get an associative array of just the values.
		var values = {};
		values['what'] = 'save';

		inputs.each(function() {

			values[this.name] = $(this).val();

		});
		sendResults(values);

		// return false so the browser stops default action
		return false;
	});
	// listen for the "delete" button
	$(document).on("click", ".delete", function() {

		var values = {};
		values['what'] = 'delete';
		values['id'] = $(this).attr('id');

		//make the call
		sendResults(values);

	});
	
	$("<span id='close-form'>X</span>")
		.prependTo('#add_form_wrap')
		.on('click', function(){
			
			$('#add_form_wrap').slideUp();
		});

	// Make the call to the back
	function ajaxCall(values, location) {

		$('#add_form_wrap').slideUp();
		$('#load').fadeIn();
		$('.hasDatepicker').AnyTime_noPicker();
		
		$.post(ajaxP.ajaxurl, {
			action : 'ssa_event_ajax',
			values : values,
			// send the nonce along with the request
			postCommentNonce : ajaxP.postCommentNonce
		}, function(response) {

			//alert(response);
			$(location).html(response);
			$('#load').fadeOut();
			$('#add_form_wrap').slideDown();
		
			$('.hasDatepicker').focus(function() {
				$('.hasDatepicker').unbind('focus').AnyTime_picker({

					format : "%d-%m-%Y %H:%i",
					labelTitle : "Selecteer Datum - Tijd",
					labelYear : "Jaar",
					labelMonth : "Maand",
					labelDayOfMonth : "Dag",
					labelHour : "Uur",
					labelMinute : "Minuut",
					dayAbbreviations : ["Zo", "Ma", "Di", "Wo", "Do", "Vr", "Za"],
					monthAbbreviations : ["Jan", "Feb", "Maa", "Apr", "Mei", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Dec"]
				});
			});
		});
	}
	
	
	function sendResults(values) {

		$('#load').show();

		$.post(ajaxP.ajaxurl, {
			action : 'ssa_event_ajax',

			values : values,

			// send the nonce along with the request
			postCommentNonce : ajaxP.postCommentNonce
		}, function(response) {

			//alert(response);
			location.reload();

		});
	}

});
