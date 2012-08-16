<?php
/*
Plugin Name: Real time Notifier
Plugin URI: http://www.silverspringactivities.nl/
Description:  Get notified in real time
Version: 1.0
Author: Silverspring activities
Author URI: http://silverspringactivities.nl/
*/

define('PLUGIN_SHORT',   'ssa_tm_');
define('PLUGIN_DIR',   (WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__))) );

// SSA Addons
function ssa_rt_nots_head() {

    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', 'http://code.jquery.com/jquery-latest.min.js');
    wp_enqueue_script( 'jquery' );

    wp_enqueue_script( 'pusher', 'http://js.pusherapp.com/1.9/pusher.min.js');
    wp_enqueue_script( 'pusher_init',  PLUGIN_DIR . 'js/head.js');

     wp_enqueue_style( 'nots_style',  PLUGIN_DIR . 'css/ssa_rt_nots.css');

}

add_action('wp_head', 'ssa_rt_nots_head', 0);

// ADD a SEND request when NEW comment is created
require_once 'lib/Pusher.php';
function ssa_rt_nots_comment($action) {

    $key = '0435991fa523b3b3f04e';
    $secret = '8e5f80a5c0faa0aa8006';
    $app_id = '10756';

    $pusher = new Pusher($key, $secret, $app_id);
    $pusher->trigger('test_channel', 'my_event',  $action . ' added' );


}

add_action('comment_post', "ssa_rt_nots_comment");
add_action('publish_post', 'ssa_rt_nots_comment');

add_action('wp_login', 'ssa_rt_nots_comment');
add_action('wp_logout', 'ssa_rt_nots_comment');
add_action('user_register', 'ssa_rt_nots_comment');