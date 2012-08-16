<?php
global $gameActions;

$gameActions = Array(
    array('moment'=>'view', 'descr'=>'triggered by every page view'), 
    array('moment'=>'publish', 'descr'=>'triggered when user publishes a post'), 
    array('moment'=>'comment', 'descr'=>'triggered when user comments on a post')  
    );

    
    

// Load stylesheet and jscript
add_action('admin_init', 'game_init');

function game_init() {
	
	

}

// Create custom settings menu
add_action('admin_menu', 'game_menu');

function game_menu() {
	//global $themename;
	//create new top-level menu
        add_menu_page( __( 'Gamefication' ), __( 'Gamefication' ), 'manage_options', basename(__FILE__), 'game_main_page' );

        add_submenu_page( basename(__FILE__), 'Groups', 'Groups', 'manage_options', 'page.groups' , 'game_groups_page' );
        add_submenu_page( basename(__FILE__), 'Phases', 'Phases', 'manage_options', 'page.phases' , 'game_phases_page' );
        add_submenu_page( basename(__FILE__), 'Rules', 'Rules', 'manage_options', 'page.rules', 'game_rule_page' );
        add_submenu_page( basename(__FILE__), 'Achievements', 'Achievements', 'manage_options', 'page.achievements', 'game_achievements_page' );
        add_submenu_page( basename(__FILE__), 'Status', 'Status', 'manage_options',  'game-status-page', 'game_status_page' );


}

// Register settings
add_action( 'admin_init', 'register_game_settings' );

function register_game_settings() {
        global $themename, $shortname, $version, $ssa_folls_options, $option_group, $option_name;
        //register our settings
        register_setting( $option_group, $option_name);
}

function game_status_page(){

   
}


function game_groups_page(){
       include ('page.groups.php');
}
function game_phases_page(){
       include ('page.phases.php');
}
function game_rule_page(){
    global $gameActions;
       include ('page.rules.php');
}
function game_achievements_page(){
       include ('page.achievements.php');
}
//function game_status_page(){
//       //include ('game_status_page.php');
//}





// AJAX STUFF
function ajax_insertPoints(){
    
        global $wpdb;
        $rule_id = $_POST['rule_id'];
        $counter = $_POST['counter'];
        $points = $_POST['points'];


        $wpdb->query(  $wpdb->prepare(
        "INSERT INTO netwerk_game_rule_points
        (  rule_id, counter, points )
        VALUES ( %s, %s, %s)",
        $rule_id, $counter, $points   )) ;

        // TODO
        // if false returned, log it to the plugin error log
        echo $wpdb->insert_id;



    die;
}
add_action('wp_ajax_insertPoints', 'ajax_insertPoints');

function ajax_deletePoints(){

        global $wpdb;
        $id = $_POST['id'];

        $x = $wpdb->query( $wpdb->prepare(
	"DELETE FROM netwerk_game_rule_points
	WHERE id = '%s' ",
        $id
        ) );

        // TODO
        // if false returned, log it to the plugin error log
        echo $x;



    die;
}
add_action('wp_ajax_deletePoints', 'ajax_deletePoints');