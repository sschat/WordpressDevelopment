//jquery

/* 
 * AJAX script for the admin SCAN page
 * 
 * this page listens to "add / edit"  records
 * - new / edit scan
 * - new / edit theme
 * - new / edit question
 * - new / edit score of 'type'
 * 
 * makes the request and shows on page
 * 
 * does page refreshes after save / delete
 * 
 * included via ControllerScanAdmin.php
 * 
 */

$(document).ready(function() {




	// listen for "Opslaan";
	$(document).on("click", "button#save", function() {

		//if a save is clicked:
		// get the values:
		// form id
		var what = $(this).closest("form").attr("id");

		// get all the inputs into an array.
		var inputs = $(this).closest("form").find(' :input');

		// not sure if you wanted this, but I thought I'd add it.
		// get an associative array of just the values.
		var values = {};
		values['what'] = what;

		inputs.each(function() {

			values[this.name] = $(this).val();

		});
		sendResults(values);

		// return false so the browser stops default action
		return false;
	});
	
	// listen for "wijzig score" button
	$(document).on("click", "a.editscore", function() {

		
		
		var values = {};
		values['id'] =  $(this).closest("tr.row").attr("id");
		values['what'] = $(this).attr("id");
		values['type'] = $(this).attr("name");
	
		//alert(values['id'] + "/" + values['what'] + "/" + values['type']);
	
		if(values['what'] == 'getScore') {

			if($(this).attr("name") == 'theme') {

				values['pid'] = MyAjax.i_themeID;

			}
			if($(this).attr("name") == 'scan') {

				values['pid'] = MyAjax.i_scanID;
				;

			}
			
			

		}
		$('#add_form_header').html('he');
			//$('#add_form_wrap').css('float', 'right');
		
		// get the form into the DOM
		getForm(values);
		
		// return false so the browser stops default action
		return false;
	});

	// listen for "wijzig vraag" button
	$(document).on("click", "a.editqst", function() {

		
		
		var values = {};
		values['id'] =  $(this).closest("tr.row").attr("id");
		values['what'] = $(this).attr("id");
		values['type'] = $(this).attr("name");
	
		//alert(values['id'] + "/" + values['what'] + "/" + values['type']);
	
		

		values['pid'] = MyAjax.i_themeID;

		
		//$('#add_form_wrap').css('float', 'left');

		
		// get the form into the DOM
		getForm(values);
		
		// return false so the browser stops default action
		return false;
		
	});
	
	
	
	// listen for "nieuw" button
	$(document).on("click", "button.add", function() {

		

		//if a "Add" is clicked:
		// get new form in
		var values = {};
		values['id'] = null;
		values['what'] = $(this).attr("id");
		values['type'] = $(this).attr("name");

		// table to add is a SCORE table. Parent is THIS theme or page
		// who is your daddy?

		if(values['what'] == 'getScore') {

			if($(this).attr("name") == 'theme') {

				values['pid'] = MyAjax.i_themeID;

			}
			if($(this).attr("name") == 'scan') {

				values['pid'] = MyAjax.i_scanID;
				;

			}

			

		} else {

			if($(this).attr("name") == 'qst') {

				values['pid'] = MyAjax.i_themeID;

			}

			if($(this).attr("name") == 'theme') {

				values['pid'] = MyAjax.i_scanID;

			}
			if($(this).attr("name") == 'scan') {

				values['pid'] = null;

			}

			
		}

		// get the form into the DOM
		getForm(values);
		
		// return false so the browser stops default action
		return false;
	});

	// listen for "delete" button
	$(document).on("click", "a.delete", function() {

		
		
		var values = {};
		
		
		values['id'] = $(this).attr("id");
		values['type'] = $(this).attr("name");
		
	
		//alert(values['id'] + "/" + values['what'] + "/" + values['type']);
		
		// get the form into the DOM
		deleteRow(values);
		
		// return false so the browser stops default action
		return false;
		
	});
	
	// small check for clicking OUTSIDE the form, then collapse
	mouse_is_inside=false; 
	$('#add_form_wrap').hover(function(){ 
        mouse_is_inside=true; 
    }, function(){ 
        mouse_is_inside=false; 
    });

    $(document).mouseup(function(){ 
        if(! mouse_is_inside) $('#add_form_wrap').slideUp();
    });
	
	

});

function getForm(values){
	
	$('#add_form_wrap').slideUp()
	$('#load').show();
	
	$.post(MyAjax.ajaxurl, {
			action : 'ssa_scan_form',

			data : values,

			// send the nonce along with the request
			postCommentNonce : MyAjax.postCommentNonce
		}, function(response) {

			//alert(response);

			$('#add_form').html(response);
			$('#add_form_wrap').slideDown()
			
			$('#load').hide();

		});
	
}

function sendResults(values) {

	$('#load').show();

	$.post(MyAjax.ajaxurl, {
		action : 'ssa_scan_save',

		data : values,

		// send the nonce along with the request
		postCommentNonce : MyAjax.postCommentNonce
	}, function(response) {

		//alert(response);
		location.reload();

	});
}
function deleteRow(values) {

	$('#load').show();

	$.post(MyAjax.ajaxurl, {
		action : 'ssa_scan_delete',

		data : values,

		// send the nonce along with the request
		postCommentNonce : MyAjax.postCommentNonce
	}, function(response) {

		//alert(response);
		location.reload();

	});
}

