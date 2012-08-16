jQuery(document).ready(function($) {
    
    var follower_id   = document.getElementById('follower_id').value;  // logged in user
    var object            = document.getElementById('object').value;  // what object
    var object_id       = document.getElementById('object_id').value;  // what object_id
    var status            = document.getElementById('status').value;  // set it to follow or not


        
    // set the text of tht button to the received status
    $('#followbtn').html(status);

    // When button clicked check / uncheck the follower
    // invert current status

   $('#followbtn').click(function(){


            $('#followbtn').fadeOut(500, function(){

                $('#followload').fadeIn(500, function(){

                    $.ajax({
                        url:"/wp-admin/admin-ajax.php",
                        type:'POST',
                        data:'action=followicate&follower_id=' + follower_id + '&object=' + object + '&object_id=' + object_id + '&s=' + status,

                        success:function(results)
                        {

               
                       
                            //result is number higher then null iff oke
                            if(results){
                                     status = results;
                                    $('#followbtn').html(results);

                                    $('#followload').fadeOut(500, function(){

                                        $('#followbtn').fadeIn();

                                    });


                            }

                        }

                    }); // ajax

                 });

            }); // followbtn button


        }); // click
        
});