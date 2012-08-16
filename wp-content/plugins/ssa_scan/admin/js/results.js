$(document).ready(function() {
	
	var values = {};
	var location = "";
	var scanID = "";
	var id = false;
	var user1 = 0;
	var user2 = 0;
	
	// listen for "Selecting Scan";
	$("#selectScanId").change(function() {
		
		id = false;
		scanID = $(this).val();
		values["what"] = 'getUsers';
		values["id"] = scanID;
		location = '.selectUserId';
		
		getResults(values);
		
		
		
	});

	
	
	// listen for "Selecting User";
	$(".selectUserId").change(function() {
	
		id = $(this).attr('id');
		
		
		values["what"] = 'getResults';
		values["id"] = $(this).val();
		values['scanID'] = scanID;
		
		location = '.ScanResultWrap';
		
		getResults(values);
		
		if(id==1) user1 = $(this).val();
		if(id==2) user2 = $(this).val();
	});

	
	
	
	
//todo
// listen for the theme click
// if clicked qet question results for this theme
	$(document).on("click", ".themeBarPlaceholder", function() {

			
		id = $(this).attr('id');
		
		values["what"] = 'getQuestionResults';
		values["id"] = id; // theme id
		
		values['user1'] = user1;
		values['user2'] = user2;
		
		location = 'question';
		
		getResults(values);
		
		
	});



	// basix communication 
	function getResults(values) {

		$.post(ajaxResults.ajaxurl, {
			action : 'ssa_scan_showAdminScanResults',

			data : values,

			// send the nonce along with the request
			postCommentNonce : ajaxResults.postCommentNonce
		}, function(response) {
			
			if(location == 'question'){
				
				
				
				
			} else {
			
				
				if(id){
					
					$('#ScanResultMain_' + id).html(response).fadeIn();
						
				} else {
					
					$(location).append(response).fadeIn();
				
				}
				
			}
		});
	}
	
	

});
