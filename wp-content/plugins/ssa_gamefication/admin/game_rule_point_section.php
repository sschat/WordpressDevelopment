<?
 $sql = 'SELECT id,  counter, points FROM netwerk_game_rule_points where rule_id=' . $_GET['rule'] .  ';';
$rule_points = $wpdb -> get_results($sql );
?>


<script>
jQuery(document).ready(function($) {

   $('#insertPoints').click(function(){

    var rule_id   = document.getElementById('rule_id').value;  // logged in user
    var counter = document.getElementById('counter').value;  // what object
    var points   = document.getElementById('points').value;  // what object_id


                    $.ajax({
                        url:"/wp-admin/admin-ajax.php",
                        type:'POST',
                        data:'action=insertPoints&rule_id=' + rule_id + '&counter=' + counter + '&points=' + points,

                        success:function(results)
                        {
                            //result is number higher then null iff oke
                            if(results){



                                $('#pointResult').append(
                                " <tr id=\"line-" +  results + "\">" +
                                    "<td>" + counter + "</td>" +
                                    "<td>" + points + "</td>" +
                                    "<td><a class=\"button-secondary remove\" href=\"#\" id=\" "  + results + " \">remove</a></td>" +
                                "</tr>");

                                $('#line-' + results).css('background-color', '#eee');
                                                             
                               
                            }
                        }

                    }); // ajax

        }); // click

           $('.remove').click(function(){

               id = (this.id);


               $('#line-' + id).css('background-color', '#aaa');

                $.ajax({
                        url:"/wp-admin/admin-ajax.php",
                        type:'POST',
                        data:'action=deletePoints&id=' + id,

                        success:function(results)
                        {
                            //result is number higher then null iff oke
                            if(results){

                                $('#line-' + id).fadeOut();

                            }
                        }

                    }); // ajax

            });

    });
</script>


    <div id="icon-users" class="icon32"><br/></div>
    <h3>Rule Points</h3>

    <div style="background:#ECECEC;border:1px solid #CCC;padding:0 10px;margin-top:5px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">
        <p>This pages defines the rules of the game</p>
    </div>

    <div id="point-table">

        <table class="form-table" id="pointResult">
                        <tr valign="top">
                            <th scope="row"><label for="counter">Counter</label></th>
                            <th scope="row"><label for="points">Points</label></th>
                            <th scope="row"><label for="remove">Remove</label></th>
                        </tr>
                         <?foreach ($rule_points as $point){?>
                         <tr id="<?="line-" . $point->id?>">
                                <td><?=$point->counter?></td>
                                <td><?=$point->points?></td>
                                <td><a class="button-secondary remove" href="#" id="<?=$point->id?>" title="New points">remove</a></td>
                        </tr>
                        <?}?>
          </table>


    </div>


    <form id="rules-filter" method="get">
          <table class="form-table" >
                        <tr valign="top">
                                <th scope="row"><label for="counter">Counter</label></th>
                                <td>
                                        <input type="text" value="1" class="small-text" id="counter" name="counter" />
                                        <span class="description">The counter is the default "plusOne" of any rule</span>
                                </td>
                        
                                <th scope="row"><label for="points">Points</label></th>
                                <td>
                                        <input type="text" value="1" class="small-text" id="points" name="points" />
                                        <span class="description">How many points is this worth?</span>
                                </td>
                        </tr>
          </table>
      
        <input type="hidden" id="rule_id" value="<?php echo $_REQUEST['rule'] ?>" />
    </form>

<a class="button-secondary" href="#" id="insertPoints" title="New points">Add points</a>