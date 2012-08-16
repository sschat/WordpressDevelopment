<div id="icon-users" class="icon32"><br/></div>
<h2>Game rules</h2>

<div style="background:#ECECEC;border:1px solid #CCC;padding:0 10px;margin-top:5px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">
<p>This pages defines the rules of the game</p>
<p>At what moment is a rule triggered, and how heavy is its impact on the game</p>
</div>



<?php
    global $wpdb;


    if($_POST['submit-rule']){

          $wpdb->query(  $wpdb->prepare(
        "INSERT INTO netwerk_game_rules
        (  id, name, descr, message, group_id, moment, weight )
        VALUES ( %s, %s, %s, %s, %s, %s, %s )
        ON DUPLICATE KEY
        UPDATE

        name=%s,
        descr=%s,
        message=%s,
        group_id=%s,
        moment=%s,
        weight=%s"
        ,
        $_POST['id'], $_POST['name'], $_POST['descr'], $_POST['message'], $_POST['group'], $_POST['moment'], $_POST['weight'],
        $_POST['name'], $_POST['descr'], $_POST['message'],$_POST['group'], $_POST['moment'], $_POST['weight']   )) ;

        // TODO
        // if false returned, log it to the plugin error log
         
    }

    if($_GET['action']=='delete'){
        if(!$_GET['rule']){
            echo "<h3>Hmm, iets fout gegaan?</h3>";
        } else {

            $x = $wpdb->query( $wpdb->prepare(
            "DELETE FROM netwerk_game_rules
            WHERE id = '%s'",
            $_GET['rule']
            ) );

        } // einde rule

    }
    if($_GET['action']=='edit'){
        if(!$_GET['rule']){
            echo "<h3>Hmm, iets fout gegaan?</h3>";
        }
    }
    if($_GET['action']&&$_GET['action']!='delete'){


                if($_GET['rule']){
                $sql = 'SELECT *
                    FROM netwerk_game_rules where id=' . $_GET['rule'] . ';';

                $data = $wpdb -> get_row($sql );
                }

                $sql = 'SELECT id , name, descr FROM netwerk_game_groups;';
                $game_groups = $wpdb -> get_results($sql );


                ?>

                

                <form id="add-rule" method="post" action="<?= get_admin_url(); ?>admin.php?page=page.rules">

                          <table class="form-table" >

                                <tr valign="top">
                                        <th scope="row"><label for="name">Rule Name <span style="color:silver">(id:<?=$data->id?>)</span></label></th>
                                        <td>
                                                <input type="text" value="<?=$data->name?>" class="regular-text" id="name" name="name"  />
                                                <span class="description">the name of the rule</span>
                                        </td>
                                </tr>

                                <tr valign="top">
                                        <th scope="row"><label for="descr">Description</label></th>
                                        <td>
                                                <textarea id="descr" name="descr" cols="40" rows="5"><?=$data->descr?></textarea>
                                                <span class="description">Rule description</span>
                                        </td>


                                </tr>
                                <tr valign="top">
                                        <th scope="row"><label for="message">User Message</label></th>
                                        <td>
                                                <textarea id="message" name="message" cols="40" rows="5"><?=$data->message?></textarea>
                                                <span class="description">What will the user see in his box?</span>
                                        </td>


                                </tr>


                                <tr valign="top">
                                        <th scope="row"><label for="group">Group</label></th>
                                        <td>
                                                <select id="group" name="group">
                                                    <? foreach ($game_groups as $group){?>
                                                                <option value="<?=$group->id?>" <?=($group->id==$data->group_id?"selected":"")?> ><?=$group->name?> - <?=$group->descr?></option>
                                                  <?}?>
                                                </select>
                                                <span class="description">Belonging to what group</span>
                                        </td>
                                </tr>
                                
                                <tr valign="top">
                                        <th scope="row"><label for="moment">Moment</label></th>
                                        <td>
                                               <select id="moment" name="moment">
                                                    <? foreach ($gameActions as $action){?>
                                                                <option value="<?=$action['moment']?>" <?=($action['moment']==$data->moment?"selected":"")?> ><?=$action['moment']?>  -  <?=$action['descr']?></option>
                                                  <?}?>
                                                </select>
                                                <span class="description">When is this rule triggered?</span>
                                        </td>
                                </tr>
                               
                                <tr valign="top">
                                        <th scope="row"><label for="moment">Weight</label></th>
                                        <td>
                                                <input type="text" value="<?=$data->weight?>" class="regular-text" id="weight" name="weight" />
                                                <span class="description">Compared to other rules, how much effect does it have? eg "10"</span>
                                        </td>
                                </tr>
                                
                          </table>
                          <input type="hidden" value="<?=$data->id?>" id="id" name="id"  />
                          <input class="button-primary" type="submit" name="submit-rule" value="<?=$_GET['action']?>" />
                          <a class="button-secondary" href="?page=page.rules" title="No Rule">Exit</a>

        </form>

      <?
      // temp disabled. Table is replaced by column "weight"
      //include('game_rule_point_section.php');
      ?>



        <?
  // einde action
   } else {


            //Our class extends the WP_List_Table class, so we need to make sure that it's there
            if(!class_exists('WP_List_Table')){
                    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
            }
            require_once( dirname(__FILE__) . '/list.rules.php' );

            //Prepare Table of elements
            $wp_rule_table = new Rule_Table();
            $wp_rule_table->prepare_items();

            //Table of elements
            ?>
                

                <form id="rules-filter" method="get">
                    <!-- For plugins, we also need to ensure that the form posts back to our current page -->
                    <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
                    <!-- Now we can render the completed list table -->
                    <?php $wp_rule_table->display() ?>
                </form>


                <a class="button-secondary" href="?page=page.rules&action=insert" title="New Rule">New Rule</a>
<?    }// or edit stuff
