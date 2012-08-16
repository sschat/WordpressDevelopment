/*
 *
 * javascript to manage the front page
 *
 * Listens for next / prev button
 * Listens for option click, saves each option directty
 *
 * and gets the results in when on last page
 *
 */

var speed = 200;
var cnt = 0;

jQuery.noConflict();
jQuery(document).ready(function($) {

	$('#scan_start').fadeIn();

	// render some nicer buttons
	$("form.jqtransform").jqTransform();

	//first get the number of sections (sections + last page)
	var sections = $('.btn_start').attr('id');


	// listen for "Start";
	$(document).on("click", ".btn_start", function() {
		showSection(cnt, false);
	// update the "counter", so we know what section we showing
			cnt++;
	});
	
	// listen for "Next";
	$(document).on("click", "button.next", function(e) {
		
		e.preventDefault();
		
		
		// check if all questions answered
		var AllDone = nextPage( $(this) );
		
		
		if( !AllDone ){
			
			return false;
		
		} else {
			
			// per section do stuff
			if(cnt < sections) {
	
				showSection(cnt, false);
	
			} else {
	
				lastPage()
	
			}
	
			// update the "counter", so we know what section we showing
			cnt++;

		}
		
		return false;
	});
	
	
	// listen for "Vorig";
	$(document).on("click", "button.prev", function() {
		// per section do stuff

		showSection(cnt, true);

		// update the "counter", so we know what section we showing
		cnt--;

		return false;

	});
	
	
	// listen for "keuze geselecteerd";
	$(document).on("click", ".qst_option", function() {

		var id = $(this).attr("name");
		var value = $(this).attr("value");
		
		$('#qst_' + id).removeClass('missing_answer');
		
		saveOption(id, value);

	});


	function nextPage( a ) {

		var form = $(a).closest('form');
		var continues = true;

		var questions = new Array(), answered = new Array(), notAnswered = new Array(); notAnswered_clean = new Array();

		// how many questions on this current page?
		form.find('input[type=radio]').each(function(e) {
			
			
			$this = $(this);
			questions[ $this.attr('name') ] = $this.attr('name');


		});
		
		// how many of them ore answered?
		var selected = form.find('input[type=radio]:checked').each(function(e) {
			
			answered[this.name] = this.name;
			
		});
		
		// which one are NOT answered?
		notAnswered = jQuery.grep(questions, function(item) {
			return jQuery.inArray(item, answered) < 0;
		});
		

				
		// make visual Notice on the NOt answererd ones
		$.each(notAnswered, function(i, id) {

			$('#qst_' + id).addClass('missing_answer');
			
			if(typeof id == 'string'){
			console.log('not answered yet: ' + id);
			continues = false;
			}
			
			
			
			
		})
	
		return continues;
		

		
	}

	function saveOption(id, value) {

		var values = {};
		values["id"] = id;
		values['value'] = value;

		$.post(AjaxP.ajaxurl, {
			action : 'ssa_scan_savequestion',
			data : values,

			// send the nonce along with the request
			postCommentNonce : AjaxP.postCommentNonce
		}, function(response) {

			//alert(response);

			if(response == 0) {
				alert('Opslaan mislukt. Graag uw keuze her-selecteren. ');
			}
			if(response == -1) {
				alert('Resultaat wordt niet opgeslagen. Graag eerst inloggen.');
			}

		});
	}

	function showSection(cnt, prev) {

		// we go back
		if(prev) {

			// fade this section out
			$('#' + cnt).fadeOut(speed, function() {
				prevs = cnt - 1;
				if(prevs < 1) {

					$('#scan_startpage').fadeIn(speed).animate({
						scrollTop : 0
					}, 0);

				} else {
					//show the previous section one
					$('#' + prevs).fadeIn(speed).animate({
						scrollTop : 0
					}, 0);
				}

			});
		} else {

			if($('#scan_startpage').is(":visible")) {

				$('#scan_startpage').fadeOut(speed, function() {
					next = cnt + 1;
					//show the next page one
					$('#' + next).fadeIn(speed).animate({
						scrollTop : 0
					}, 0);

				});
			}

			// fade the current one

			$('#' + cnt).fadeOut(speed, function() {
				next = cnt + 1;
				//show the next one
				$('#' + next).fadeIn(speed);

			});
		}
		// scroll to top of page
		$('body').animate({
			scrollTop : 200
		}, 500);
		$('html').animate({
			scrollTop : 200
		}, 500);
	}

	function lastPage() {

		$('#scan_loading').fadeIn(speed);

		//get the scan id
		var scanID = $('#scan_startpage').attr('name');

		// fade the current one
		$('#' + cnt).fadeOut(speed, function() {

			$('.scan_endpage').fadeIn(speed, function() {

				$.post(AjaxP.ajaxurl, {
					action : 'ssa_scan_showScanResults',
					scanID : scanID,

					// send the nonce along with the request
					postCommentNonce : AjaxP.postCommentNonce
				}, function(response) {

					$('#scan_loading').hide();

					if(response == 0) {
						response = ('<div class="theme_header" id=""><h3>Helaas...</h3>Resultaat op dit moment niet beschikbaar. Probeert u het later nog even. (Uw keuzes zijn wel opgeslagen) </div>');
					}
					if(response == -1) {
						response = ('<div class="theme_header" id=""><h3>Helaas...</h3>Resultaat niet beschikbaar. Graag eerst inloggen.</div>');
					}

					// make the results pop
					$('.scan_endpage').hide();
					$('.scan_endpage').html(response)

					$('.themeBarScore').hide();
					$('.themeBarScore span').hide();

					$('.scan_endpage').fadeIn(1000, function() {

						$('.themeBarScore').show("slide").delay(500, function() {
							$('.themeBarScore span').fadeIn();
						});
					});
				});
			});
		});
		// scroll to top of page
		$('html').animate({
			scrollTop : 200
		}, 500);
		$('body').animate({
			scrollTop : 200
		}, 500);

	}

});
