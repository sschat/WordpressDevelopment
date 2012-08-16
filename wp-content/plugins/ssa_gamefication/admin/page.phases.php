<div id="icon-users" class="icon32"><br/></div>
<h2>Game Phases</h2>

<div style="background:#ECECEC;border:1px solid #CCC;padding:0 10px;margin-top:5px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">
<p>This page defines the phases of a single group</p>
<p>The phases show the user where they are in the group.</p>
</div>



<?php
    global $wpdb;


    if($_POST['submit-phase']){

          $wpdb->query(  $wpdb->prepare(
        "INSERT INTO netwerk_game_phases
        (  id, name, descr, message, image, points, group_id )
        VALUES ( %s, %s, %s, %s, %s, %s, %s )
        ON DUPLICATE KEY
        UPDATE

        name=%s,
        descr=%s,
        message=%s,
        image=%s,
        points=%s,
        group_id=%s"
        ,
        $_POST['id'], $_POST['name'],$_POST['descr'],$_POST['message'], $_POST['image'], $_POST['points'], $_POST['group'],
        $_POST['name'], $_POST['descr'],$_POST['message'], $_POST['image'], $_POST['points'], $_POST['group']  )) ;

        // TODO
        // if false returned, log it to the plugin error log
         
    }

    if($_GET['action']=='delete'){
        if(!$_GET['phase']){
            echo "<h3>Hmm, iets fout gegaan?</h3>";
        } else {

            $x = $wpdb->query( $wpdb->prepare(
            "DELETE FROM netwerk_game_phases
            WHERE id = '%s'",
            $_GET['phase']
            ) );

        } // einde rule

    }
    if($_GET['action']=='edit'){
        if(!$_GET['phase']){
            echo "<h3>Hmm, iets fout gegaan?</h3>";
        }
    }
    if($_GET['action']&&$_GET['action']!='delete'){


                if($_GET['phase']){
                $sql = 'SELECT *
                    FROM netwerk_game_phases where id=' . $_GET['phase'] . ';';

                $data = $wpdb -> get_row($sql );
                }
                
                // get the groups in
                $sql = 'SELECT id , name, descr FROM netwerk_game_groups;';
                $game_groups = $wpdb -> get_results($sql );

               ?>

                

                <form id="add-phase" method="post" action="<?= get_admin_url(); ?>admin.php?page=page.phases">

                          <table class="form-table" >

                                <tr valign="top">
                                        <th scope="row"><label for="name">Phase Name <span style="color:silver">(id:<?=$data->id?>)</span></label></th>
                                        <td>
                                                <input type="text" value="<?=$data->name?>" class="regular-text" id="name" name="name"  />
                                                <span class="description">the name of the phase</span>
                                        </td>
                                </tr>

                                <tr valign="top">
                                        <th scope="row"><label for="descr">Description</label></th>
                                        <td>
                                                <textarea id="descr" name="descr" cols="40" rows="5"><?=$data->descr?></textarea>
                                                <span class="description">Phase Description</span>
                                        </td>
                                </tr>
                                

                               <tr valign="top">
                                        <th scope="row"><label for="message">Message</label></th>
                                        <td>
                                                <textarea id="descr" name="message" cols="40" rows="5"><?=$data->message?></textarea>
                                                <span class="description">What the user gets to see when this phase is reached</span>
                                        </td>
                                </tr>
                             
                               <tr valign="top">
                                        <th scope="row"><label for="image">Image</label></th>
                                        <td>
                                                <input type="text" value="<?=$data->image?>" class="regular-text" id="image" name="image"  />
                                                <span class="description">Give a nice icon to the phase</span>
                                        </td>
                                </tr>
                                
                                  <tr valign="top">
                                        <th scope="row"><label for="points">Points</label></th>
                                        <td>
                                                <input type="text" value="<?=$data->points?>" class="regular-text" id="points" name="points"  />
                                                <span class="description">How many points needed?</span>
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
                                
                                
                          </table>
                          <input type="hidden" value="<?=$data->id?>" id="id" name="id"  />
                          <input class="button-primary" type="submit" name="submit-phase" value="<?=$_GET['action']?>" />
                          <a class="button-secondary" href="?page=page.phases" title="No Phase">Exit</a>

        </form>


        <?
  // einde action
   } else {


            //Our class extends the WP_List_Table class, so we need to make sure that it's there
            if(!class_exists('WP_List_Table')){
                    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
            }
            require_once( dirname(__FILE__) . '/list.phases.php' );

            //Prepare Table of elements
            $wp_phase_table = new Phase_Table();
            $wp_phase_table->prepare_items();

            //Table of elements
            ?>
                

                <form id="phase-filter" method="get">
                    <!-- For plugins, we also need to ensure that the form posts back to our current page -->
                    <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
                    <!-- Now we can render the completed list table -->
                    <?php $wp_phase_table->display() ?>
                </form>


                <a class="button-secondary" href="?page=page.phases&action=insert" title="New Phase">New Phase</a>
<?    }// or edit stuff
