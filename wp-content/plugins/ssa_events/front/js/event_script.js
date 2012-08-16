jQuery.noConflict();
jQuery(document).ready(function($) {
	
	
	$(".event_button").html("Aanmelden");
	
	
	var eventID = {};
	var values = {};
	var userID = AjaxZ.userID;
	
	var alertCount = 0 ; // used for counting the AlertPopup. Just once please!
	var pathname = window.location;
	
	
	
	//save the current url on where user registers
	values['register_post'] = AjaxZ.register_post;
	
	// get current status
	values['what']= 'get';
	
	// get all boxes and their status to this user
	var boxes = $('.event_box_wrap').each( function(){
	        
        eventID = $(this).attr('id');
		
		values['eventID'] = eventID;
		
		ajax_status(values, 'stop');
		
		
	});
	
	
		
	// listen voor "aanmelden"
	$('.event_button_wrap, .afmelden').on('click', function(){
				
		// check if user is logged in
		if( userID  || userID == 0 ){
			alert('Alleen ingelogde gebruikers kunnen zich aanmelden');
			return false;
		}				
				
		eventID = $(this).attr('id');
	
		values['what']= 'set';
		values['eventID']= eventID;
		var status = ajax_status(values, "go");
				
		
	});
	
	// listen voor "show_all"
	$('.event_attendees span.show_all').on('click', function(){

		$(this)
			.parent()
			.find('.attendeeBigList')
			.fadeToggle();

		label = $(this).text();	
		if(label == 'verberg') { 
			label = 'toon alles';
			} else {
				label = 'verberg';
			};
		$(this).text(label);		
		
	});

	// Value['what'] makes this status GET or SET it
	function ajax_status(values, update){
		
		var x = "#" + values['eventID'];
		
		
		
		
		//loading gif
		$( x + ' .event_button').html('<img src=' + AjaxZ.EVENTS_URL + 'makeup/floading.gif width="25px" />');
		
		// return the userID (to be sure)
		values['userID']= userID;
		
		$.post(AjaxZ.ajaxurl, {
				
					action : 'ssa_event_front_ajax',
					values : values,
					postCommentNonce : AjaxZ.postCommentNonce
				
				}, function(response) {
					
					response = parseInt (response, 10);
					//alert( x + ":" + response );
									
									
					// handle the reponse
					if(response == -1){
						if(alertCount==0){
						 	f = '<div id="form" style="display:none;">';
							f += '<p><strong>LOGIN</strong></p>';
							f += '<form name="loginform" id="loginform" action="http://' + location.host + '/wp-login.php" method="post">';
							f += '<div id="loginform_logo"></div>';
							f += '<p><label>Gebruikersnaam<br /><input type="text" name="log" id="user_login" class="input" value="" size="20" tabindex="10" /></label></p>';
							f += '<p><label>Wachtwoord<br /><input type="password" name="pwd" id="user_pass" class="input" value="" size="20" tabindex="20" /></label></p>';
							f += '<p class="forgetmenot"><label><input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="90" />Deze gegevens onthouden</label></p>';
							f += '<p class="submit"><input type="submit" name="wp-submit" id="wp-submit" class="button button-primary" value="Inloggen" tabindex="100" />';
							f += '<input type="hidden" name="redirect_to" value="' + pathname + '" /><input type="hidden" name="testcookie" value="1" /></p></form></div>';
							alertCount++;
							}
							
		
						$(x + " .event_button_location ").html( f + 'Om u aan te kunnen melden dient u eerst in te loggen. <a id="thickBoxLink" class="thickbox" href="#TB_inline?height=500&width=280&inlineId=form&modal=true" title="Login">Inloggen</a>');
					}
					
					if(!response  || response == 0 ){
						
						var status = 'Aanmelden';
						$(x + " .event_button_wrap ")
							.css('background-color', '#0fa44b');
							
						$(x + "  span.afmelden ").hide();		
							
						if(update == "go"){
						updateAttendees(0);
						}
						
					}
					
					if(response == 2 ){
						
						var status = 'U bent aanwezig';
						$(x + " .event_button_wrap ").css('background-color', '#28a0db');
						$(x + "  span.afmelden ").show();	
						
						if(update == "go"){
						updateAttendees(2);
						}
						
					}
					
					
					if(response == 99 ){
						
						var status = 'VOL';
						$(x + " .event_button_wrap ").css('background-color', '#ff5d00');
						
						
					}					
					//update the button text
					
					$(x + " .event_button" ).html(status);
					
				
			});
		
	}
	
	function updateAttendees(status){
		
		var y = "#user" + userID;
		var m = "#" + values['eventID'];
		var seats = $('.event_box_wrap' + m).find('.event_attendees span.seats').text();
		var seats_left = $('.event_box_wrap' + m).find('.event_button_location span.seats_left').text();
		
		// remove the attendees image of the list
		if(status==0){
			$('.event_box_wrap' + m).find('.attendee' + y ).fadeOut();
			
			//update the numbers
			// nr of aanmeldingen		
			seats--; seats_left++;
						
		}
		
		// add the attendees image of the list
		if(status==2){
			
			$('.event_box_wrap' + m).find('.attendee' + y ).fadeIn();
		
			//update the numbers
			// nr of aanmeldingen
			seats++; seats_left--;
			
			
			
		}
		
		// show the updated numbers
		$('.event_box_wrap' + m).find('.event_attendees span.seats').text( seats  );
		$('.event_box_wrap' + m).find('.event_button_location span.seats_left').text(seats_left);
		
	}
	
	
});
