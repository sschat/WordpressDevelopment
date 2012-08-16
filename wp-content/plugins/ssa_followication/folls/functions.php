<?php
global $wpdb; global $ssa_folls_db_version; global $foll_table_name;
$ssa_folls_db_version = "1.0";
$foll_table_name = $wpdb->prefix . "ssa_followications";

function ssa_folls_install() {
   global $wpdb;
   global $ssa_folls_db_version;
   global $foll_table_name;

   $charset_collate = '';


           $sql = "CREATE TABLE IF NOT EXISTS `$foll_table_name` (
          `id` mediumint(9) NOT NULL AUTO_INCREMENT,
          `object` varchar(12) DEFAULT NULL,
          `object_id` mediumint(9) DEFAULT NULL,
          `follower_id` mediumint(9) DEFAULT NULL,
          `insert_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
          UNIQUE KEY `id` (`id`),
          UNIQUE KEY `unique` (`object`,`object_id`,`follower_id`));" ;


           
           
           require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
           dbDelta($sql);

           //TODO: create index on the object/object_id
            $sql = "CREATE INDEX IF NOT EXISTS notifcations ON $foll_table_name (user_id(9))";
            $wpdb->query($sql);



    add_option("ssa_folls_db_version", $ssa_folls_db_version);

}



function ssa_folls_remove_table(){

    //remove table?

}







// AJAX the requests
function implement_ajax() {

        global $follower;    

        
        $object =     $_POST['object'];
        $object_id =$_POST['object_id'];
        $follower_id =   $_POST['follower_id'];
        $status     = $_POST['s'];

        //$object = is_author()?"author":$post->post_type;
        //$object_id = is_author()?get_the_author_meta( 'ID' ):$post->ID;
        $x = $follower->set_object($object_id, $object);


        $return = "E";
        
        if($status=='follow')
        $return = $follower->follow();


        if($status=='following')
        $return = $follower->unfollow();


        
        echo $return;
        
        die();


}
add_action('wp_ajax_followicate', 'implement_ajax');
add_action('wp_ajax_nopriv_followicate', 'implement_ajax');//for users that are not logged in.


