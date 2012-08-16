<?php

// config the front
function ssa_game_head() {

    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', 'http://code.jquery.com/jquery-latest.min.js');
    wp_enqueue_script( 'jquery' );


   // JAVA SCRIPTS //
    //wp_enqueue_script( 'game_ssa', plugins_url( 'front/game.js' , dirname(__FILE__)  ) );

    // CSS STYLES //
    wp_enqueue_style( 'game_style', plugins_url( 'front/game.css' , dirname(__FILE__)  ) );


    
}
add_action('wp_head', 'ssa_game_head', 0);




function ssa_gamefication_start(){

    global $game, $post;
    $current_user = wp_get_current_user();
    $id = $current_user->ID;
    $game = new gamefication($id);

}
add_action('init', 'ssa_gamefication_start', 0);


function play_view_page(){
    global $game;
    $game->play("view");
        
}
add_action("wp_head", "play_view_page");



function play_publish_page(){
    global $game;
    $game->play("publish");
        
}
add_action("publish_page", "play_publish_page");


function add_game_field(){
    global $game;
    
    echo '<div class="gameField"></div>';
    echo '<script>$(document).ready(function(){
        $(".gameField").html("' . $game->showGameLog() . '").slideDown().delay(5000).slideUp() });
        </script>';

        
}
add_action("wp_footer", "add_game_field");