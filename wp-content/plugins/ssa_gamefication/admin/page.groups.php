<div id="icon-users" class="icon32"><br/></div>
<h2>Game Groups</h2>

<div style="background:#ECECEC;border:1px solid #CCC;padding:0 10px;margin-top:5px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">
<p>This page defines the groups of the game</p>
<p>The rules can be joined or combined into groups. This way you can sort the behaviour of the users</p>
</div>



<?php
    global $wpdb;


    if($_POST['submit-group']){

          $wpdb->query(  $wpdb->prepare(
        "INSERT INTO netwerk_game_groups
        (  id, name, descr )
        VALUES ( %s, %s, %s )
        ON DUPLICATE KEY
        UPDATE

        name=%s,
        descr=%s"
        ,
        $_POST['id'], $_POST['name'],$_POST['descr'],
        $_POST['name'], $_POST['descr']  )) ;

        // TODO
        // if false returned, log it to the plugin error log
         
    }

    if($_GET['action']=='delete'){
        if(!$_GET['group']){
            echo "<h3>Hmm, iets fout gegaan?</h3>";
        } else {

            $x = $wpdb->query( $wpdb->prepare(
            "DELETE FROM netwerk_game_groups
            WHERE id = '%s'",
            $_GET['group']
            ) );

        } // einde rule

    }
    if($_GET['action']=='edit'){
        if(!$_GET['group']){
            echo "<h3>Hmm, iets fout gegaan?</h3>";
        }
    }
    if($_GET['action']&&$_GET['action']!='delete'){


                if($_GET['group']){
                $sql = 'SELECT *
                    FROM netwerk_game_groups where id=' . $_GET['group'] . ';';

                $data = $wpdb -> get_row($sql );
                }

               ?>

                

                <form id="add-rule" method="post" action="<?= get_admin_url(); ?>admin.php?page=page.groups">

                          <table class="form-table" >

                                <tr valign="top">
                                        <th scope="row"><label for="name">Group Name <span style="color:silver">(id:<?=$data->id?>)</span></label></th>
                                        <td>
                                                <input type="text" value="<?=$data->name?>" class="regular-text" id="name" name="name"  />
                                                <span class="description">the name of the group</span>
                                        </td>
                                </tr>

                                <tr valign="top">
                                        <th scope="row"><label for="descr">Description</label></th>
                                        <td>
                                                <textarea id="descr" name="descr" cols="40" rows="5"><?=$data->descr?></textarea>
                                                <span class="description">Group Description</span>
                                        </td>


                                </tr>
                                

                             
                                
                          </table>
                          <input type="hidden" value="<?=$data->id?>" id="id" name="id"  />
                          <input class="button-primary" type="submit" name="submit-group" value="<?=$_GET['action']?>" />
                          <a class="button-secondary" href="?page=page.groups" title="No Groups">Exit</a>

        </form>


        <?
  // einde action
   } else {


            //Our class extends the WP_List_Table class, so we need to make sure that it's there
            if(!class_exists('WP_List_Table')){
                    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
            }
            require_once( dirname(__FILE__) . '/list.groups.php' );

            //Prepare Table of elements
            $wp_group_table = new Group_Table();
            $wp_group_table->prepare_items();

            //Table of elements
            ?>
                

                <form id="group-filter" method="get">
                    <!-- For plugins, we also need to ensure that the form posts back to our current page -->
                    <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
                    <!-- Now we can render the completed list table -->
                    <?php $wp_group_table->display() ?>
                </form>


                <a class="button-secondary" href="?page=page.groups&action=insert" title="New Group">New Group</a>
<?    }// or edit stuff
