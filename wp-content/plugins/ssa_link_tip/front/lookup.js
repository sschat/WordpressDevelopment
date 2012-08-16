// wordpress stuff
jQuery(document).ready(function($) {


    $('#status').blur(function(){
        //alert(this.value);

        lookup(this.value);
    });

    $('#status').keyup(function(e) {
            //alert(e.keyCode);
            if(e.keyCode == 13) {
                    lookup(this.value);
            }
    });

    $('#status_submit').click(function(e) {

                    add_status();

    });



    /// FUNCTION ///////

    $(function() {

         lookup = function (status){

            $("#status_result").html('Site ophalen');

            jQuery.post(
            MyAjax.ajaxurl,
            {
                action : 'ajax_lookup_link',
                status : status,

                // send the nonce along with the request
                postCommentNonce : MyAjax.postCommentNonce
            },
            function( response ) {

                $("#status_result").hide().html(response).fadeIn();

           });
        } // end lookup

       add_status = function(){

        // Get values of:
            // status text area
            // if any link has been added
                // add
                    // type
                    // title
                    // author
                    // descr
                    // details
                    // img / embed code
            stop = false;
            
            
            link_type      =   $('#sr_type').val();
            link_title       =   $('#sr_title').text();
            link_author  =   $('#sr_by').text();
            link_descr    =   $('#sr_desc').text();
            link_details  =   $('#sr_details').text();
            link_code     =   $('div#sr_vid').html();

            my_title        =   $('#my_title').val();
            my_reason   =   $('#my_reason').val();
            my_link        =   $('#my_link').val();

alert(link_title);



            if($('#status').val()=="" ){
                $("#link_err").hide().html('Please share your link').fadeIn();
                stop = true;
            } else {
                $("#link_err").hide();
            }
            
            if($('#my_title').val()=="" ){
                $("#my_title_err").hide().html('Please add a title').fadeIn();
                stop = true;
            } else {
                $("#my_title_err").hide();
            }

            if($('#my_reason').val()=="" ){
                $("#my_reason_err").hide().html('Please share your motiviation').fadeIn();
                stop=true;
            } else {
                $("#my_reason_err").hide();
            }
            if(stop){ exit; }
            
            

            status = document.getElementById('status').value;
           // $("#status_result").html('Saving status');

            jQuery.post(
            MyAjax.ajaxurl,
            {
                action : 'add_status',
                status : status,
                link_type : link_type,
                link_title : link_title ,
                link_author : link_author,
                link_descr : link_descr,
                link_details : link_details,
                link_code : link_code,
                
                my_title  :  my_title,
                my_reason :   my_reason,
                my_link    :    my_link,
            
                // send the nonce along with the request
                postCommentNonce : MyAjax.postCommentNonce
            },
            function( response ) {

                $("#status_result").hide().html(response).fadeIn();

           });

       }

    });


}); // end document ready