<?php
global $wpdb; global $ssa_nots_db_version; global $table_name;
$ssa_nots_db_version = "1.0";
$table_name = $wpdb->prefix . "ssa_notifications";

function ssa_nots_install() {
   global $wpdb;
   global $ssa_nots_db_version;
   global $table_name;

   $charset_collate = '';


    if (!empty($wpdb->charset))
            $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
    if (!empty($wpdb->collate))
            $charset_collate .= " COLLATE $wpdb->collate";

    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {


           $sql = "CREATE TABLE " . $table_name . " ( "
                  . "id mediumint(9) NOT NULL AUTO_INCREMENT, "
                  . "user_id mediumint(9), "
                  . "object VARCHAR(12), "
                  . "object_id mediumint(9), "
                  . "action_type varchar(127), "
                  . "action varchar(127), "
                  . "insert_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL, "
                   . "UNIQUE KEY id (id)) $charset_collate;";
           
           require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
           dbDelta($sql);

            $sql = "CREATE INDEX notifcations ON $table_name (user_id(9))";
            $wpdb->query($sql);

    }

    add_option("ssa_nots_db_version", $ssa_nots_db_version);

}



function ssa_nots_remove_table(){

    //remove table?

}



// ADD THE NOTIFACTIONS TO TABLE
function ssa_nots_add($to, $object, $object_id){

    // receving the values and add it to the table
    global $table_name;  global $wpdb;

    $wpdb->query( $wpdb->prepare(
	"
        INSERT INTO $table_name
        ( user_id, object, object_id, insert_time )
        VALUES ( %s, %s, %s, %s )
	",
        $to,
	$object,
	$object_id,
        current_time('mysql')

    ) );

    // TODO
    // if false returned, log it to the plugin error log
    return $wpdb->insert_id;

}




// TRIGGER FOR COMMENTS
function ssa_nots_comments($comID,$action="add"){
     global $wpdb;
     
    // Find the POST of this comment
    $cpostID = $wpdb->get_var("SELECT comment_post_ID FROM $wpdb->comments WHERE comment_ID='$comID' AND comment_approved='1' ");
    // Find the author of this POST
    $to = $wpdb->get_var("SELECT post_author FROM $wpdb->posts WHERE ID='$cpostID' ");

    // Notify the author of new comment
    ssa_nots_add($to, 'comment', $comID);

}
// TRIGGER FOR NEW POSTS
function ssa_nots_new_post($ID){
     global $wpdb;

    // Find the author of this POST
    $to = $wpdb->get_var("SELECT post_author FROM $wpdb->posts WHERE ID='$ID' ");

    // Notify the author of new post
    ssa_nots_add($to, 'newpost', $ID);

}
// TRIGGER FOR UPDATE POSTS
function ssa_nots_update_post($ID){
     global $wpdb;

    // Find the author of this POST
    $to = $wpdb->get_var("SELECT post_author FROM $wpdb->posts WHERE ID='$ID' ");

    // Notify the author of new post
    ssa_nots_add($to, 'updatepost', $ID);

}



