<?php
/*
Plugin Name: ssa_followication
Plugin URI: http://www.silverspringactivities.nl/
Description: Users can follow any object they like
Version: 0.1
Author: Silverspring activities
Author URI: http://silverspringactivities.nl/
*/


/*
 * Purpose:
 * Build up a list of events of what happens on the site
 *
 * Each event will be shown to user when logged in again
 * Filter can be "new since logged in" "all events / paginated"
 *
 * What to do?
 * When plugin is activated:
 * build database table
 *
 *
 *
 * When plugin is deleted:
 * ask for remove table
 *
 */

// security check
if( !defined( 'WP_PLUGIN_URL' ) )
{
	die( "There is nothing to see here!" );
}


include('admin/folls_admin.php');
include('folls/functions.php');
include('folls/class.followication.php');

register_activation_hook(__FILE__,'ssa_folls_install');
register_deactivation_hook(__FILE__,'ssa_folls_remove_table');


// config the front
function ssa_folls_head() {

    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', 'http://code.jquery.com/jquery-latest.min.js');
    wp_enqueue_script( 'jquery' );


   // JAVA SCRIPTS //
    wp_enqueue_script( 'folls_ssa', WP_PLUGIN_URL . '/' . plugin_basename( dirname( __FILE__ ) ) .'/front/folls.js');

    // CSS STYLES //
    wp_enqueue_style( 'folls_style', WP_PLUGIN_URL . '/' . plugin_basename( dirname( __FILE__ ) ) .'/front/folls.css');


    global $follower, $post;
    $object = is_author()?"author":$post->post_type;
    $object_id = is_author()?get_the_author_meta( 'ID' ):$post->ID;
    $x = $follower->set_object($object_id, $object);

}
add_action('wp_head', 'ssa_folls_head', 0);

function ssa_followication_start(){

    global $follower, $post;
    $current_user = wp_get_current_user();
    $id = $current_user->ID;
    $follower = new followication($id);

}
add_action('init', 'ssa_followication_start', 0);


// add the button to the content
function add_btn_content($content) {
        global $current_user;
        global $post;
        global $wpdb;
        global $follower;
               
        $current_user = wp_get_current_user();
        $id = $current_user->ID;
        $object = is_author()?"author":$post->post_type;
        $object_id = is_author()?get_the_author_meta( 'ID' ):$post->ID;

        //see if this user if following this object and set the status
        //$s = $wpdb->get_var("SELECT id FROM netwerk_ssa_followications WHERE follower_id = '$id' and object='$post->post_type' AND object_id='$post->ID' ");
        $status =$follower->status();

        $x = $content;
        $content = "<div class='hiddenvalues' style='display:none'>" ;
        $content .= "<input type='text' id='follower_id' name='follower_id' value='" .  $id . "'/> ";
        $content .= "<input type='text' id='object' name='object' value='" . $object . "'/> ";
        $content .= "<input type='text' id='object_id' name='object_id' value='" . $object_id . "'/> ";
        $content .= "<input type='text' id='status' name='status' value='" . $status . "'/> ";
        $content .= "</div>";
        $content .= "<div class='followbtn' style='float:right'><img src='" .  WP_PLUGIN_URL . '/' . plugin_basename( dirname( __FILE__ ) ) .'/front/ajax-loader.gif' . "' id='followload' style='display:none' width='16'  height='16'/><a id='followbtn' href='#' alt='#'><span>loading</span></a></div>";
        $content .= $x;

        // otherwise returns the database content

        return $content;
}

add_filter( 'the_content', 'add_btn_content' );

