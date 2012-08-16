<?php
/*
 * 
 * Class for Status Widget 
 * 
 * 
 */
class game_status extends wp_widget {
	function game_status() {
		$this->wp_widget('game_status', 'Game Status');
	}

        
             
        


	function widget($args, $settings) {
		extract($args, EXTR_SKIP);
		
                                    global $wpdb; global $game;

                                    echo $before_widget;

                                    $title = empty($settings['title']) ? '&nbsp;' : apply_filters('widget_title', $settings['title']);

                                    if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };


                                     foreach($game->status as $status){

                                         
                                            echo "<p><strong>" . $status->groupname . " : </strong>" . $status->name  . "</p>";
                                            echo "<p>Credits earned: " . $status->current_count . "</p>";
                                            echo "<img src='" . $status->image . "' />";

                                    }
            

                                                                        
                                    echo $after_widget;

	}

	
}



function register_My_RSS_Widget(){
    
    register_widget('game_status');

}
add_action('init', 'register_My_RSS_Widget', 1);
