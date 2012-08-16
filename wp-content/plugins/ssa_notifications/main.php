<?php
/*
Plugin Name: ssa_notifications
Plugin URI: http://www.silverspringactivities.nl/
Description: Gets all the notifications in place for an author / user
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


include('admin/nots_admin.php');
include('nots/functions.php');
include('nots/class.notification.php');

register_activation_hook(__FILE__,'ssa_nots_install');
register_deactivation_hook(__FILE__,'ssa_nots_remove_table');


function ssa_notification_start(){

    global $notify, $post;
    $current_user = wp_get_current_user();
    $id = $current_user->ID;
    $notify = new notification($id);

}
add_action('init', 'ssa_notification_start', 0);

// TODO
// Add the actions only when checked in the admin panel
//add_action('comment_post', 'ssa_nots_comments');
//add_action('publish_post', 'ssa_nots_new_post');
// check the correct action of "updating post" : add_action('edit_post', 'ssa_nots_update_post');