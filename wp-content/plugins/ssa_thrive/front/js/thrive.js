jQuery(document).ready(function($) {

	$.ajax({
		url : "/wp-admin/admin-ajax.php",
		type : 'POST',
		data : 'action=getUnlockedPosts',

		success : function(results) {

			//result is number higher then null iff oke
			if(results) {

				$('.tabbed_area').hide().html(results).fadeIn(1000);
				;

			}

		}
	});
	// ajax



	// When a link is clicked
	$(document).on("click", "a.tab", function() {

		// switch all tabs off
		$(".active").removeClass("active");

		// switch this tab on
		$(this).addClass("active");

		// slide all content up
		$(".content").hide();

		// slide this content up
		var content_show = $(this).attr("title");
		$("#" + content_show).slideToggle();

	});
});
