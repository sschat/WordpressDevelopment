<?php
/*
Plugin Name: ssa_gamefication
Plugin URI: http://www.silverspringactivities.nl/
Description: More interaction with your users
Version: 0.1
Author: Silverspring activities
Author URI: http://silverspringactivities.nl/
*/


/*
 * Purpose:

 *
 */

// security check
if( !defined( 'WP_PLUGIN_URL' ) )
{
	die( "There is nothing to see here!" );
}


include('admin/page.main.php');
include('game/class.gamefication.php');
include('game/actions.php');
include('game/widgets.php');



        
        
        
function ssa_install(){
    include('game/install.php');
    ssa_game_install();
}

register_activation_hook(__FILE__,'ssa_install');
register_deactivation_hook(__FILE__,'ssa_game_remove_table');






