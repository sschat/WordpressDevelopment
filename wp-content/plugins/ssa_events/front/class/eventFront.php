<?php
/*
 * Called by index.php on front side of WP
 *
 * Shortcode ["event id='x'] is created
 * Javascript and styles are added to header
 *
 * Shortcode takes the event_id, and shows the event and attendees,
 *
 */

class ssa_events_Front {

    private $eventID = null;
    private $event = array();
    private $attendess = array();
    private $event_seats = null;
    private $attendees_nr = null;
    private $options = array();
    
    
    public function __construct() {

        global $current_user;

        get_currentuserinfo();
        $this -> userID = $current_user -> ID;

        // define the shortcode
        add_shortcode('event', array($this, 'event_shortcode'));

        // get your files in
        add_action('wp_enqueue_scripts', array($this, 'head_scripts'));
        
        
        // get the general event options in
        $this -> options = get_option('event_options');

    }

    public function head_scripts() {
        global $post;
        
        wp_deregister_script('jquery');
        wp_register_script('jquery', 'http://code.jquery.com/jquery-latest.min.js');
        wp_enqueue_script('jquery');

        wp_enqueue_style('event_style', EVENTS_URL . 'makeup/event_box.css');
        wp_enqueue_script('event_script', EVENTS_URL . 'front/js/event_script.js');

        wp_enqueue_script('thickbox');
        wp_enqueue_style('thickbox');
       // wp_enqueue_style('login_pop_style', EVENTS_URL . '/makeup/login_popup.css');
    
        // declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
        
        $data = array(
         'ajaxurl' => admin_url('admin-ajax.php'),
         'userID' => $this -> userID,
         'EVENTS_URL' => EVENTS_URL, 
         'register_post' => $post->ID,
         'postCommentNonce' => wp_create_nonce('events-nonce-123')
         );
        
        
        wp_localize_script('event_script', 'AjaxZ', $data);

    }

    public function event_shortcode($atts) {
        extract(shortcode_atts(array('id' => "1"), $atts));

        // this shortcode will lookup the event id and present it
        $this -> eventID = $id;
        return $this -> showEvent();

    }

    private function showEvent() {

        $this -> getEvent();
        $this -> getAttendees();
        
        return $this -> styleEvent();

    }

    private function getEvent() {
        global $wpdb;
        $strColumns = "id, ename, efrom, till, amount, info";
        $sql = "SELECT 
                 $strColumns
                from 
                {$wpdb->prefix}events
                where
                id = %d";

        $safe_sql = $wpdb -> prepare($sql, $this -> eventID);
        $this -> event = $wpdb -> get_row($safe_sql);
        $this -> event_seats = $this -> event -> amount;
        
    }
    
    private function getAttendees() {
        global $wpdb;
        $strColumns = "user_id";
        $sql = "select 
                a.user_id, u.display_name as name
                from {$wpdb->prefix}events_attendees a
                left join {$wpdb->prefix}users u on a.user_id = u.ID
                
                where
                a.event_id = %d
                and a.status = 2
                order by a.time_update DESC";

        $safe_sql = $wpdb -> prepare($sql, $this -> eventID);
        $this -> attendees = $wpdb -> get_results($safe_sql);
        $this -> attendees_nr = $wpdb -> num_rows;
        
    }
    
    private function styleEvent() {
    
        $seats_left = $this -> event_seats - $this -> attendees_nr;
        
        if($seats_left<1){
            
            $button = "<div class='event_button' id='{$this -> eventID}'>VOL</div>";
        } else {
            
            $button = "<div class='event_button' id='{$this -> eventID}'>Aanmelden.</div>";
        }

        
        
        $x = "
            <div class='event_box_wrap' id='{$this -> eventID}'>
            <div class='event_box'>
            
            <div class='event_button_location'><span class='seats_left'>{$seats_left}</span> plaatsen over
            <div class='event_button_wrap' id='{$this -> eventID}' >
            
            <div class='event_button' id='{$this -> eventID}'>JavaScript moet aangezet zijn om van deze functionaliteit gebruik te maken.</div>
            
            </div>
            <span class='afmelden' id='{$this -> eventID}'>afmelden</span>
            
            </div>
            
            <div class='event_title'><span>{$this -> event -> ename }</span></div>
            
            <div class='event_datum'>" . date('d M Y H:i', strtotime($this -> event -> efrom)) .  " - " . date('d M Y H:i', strtotime($this -> event -> till)) . "</div>";
            
            
            
            $x .= $this -> styleAttendees();
            
            
            $x .= "</div></div>";

        return $x;

    }

    private function styleAttendees(){
        $list = "";
        $show =  $this -> options['show_attendees']; // number of visible users. more? we add them in bigger box
        $user_found = false;
        
        foreach ( $this -> attendees as $key => $attendee){
            $count = $key + 1; // key start at 0        
                
            
                
            // add this class if more attendees then we can show    
            $bigList = ( $count<=$show ? "" : "attendeeBigList");
            
            // backup image                    
            $user_photo = get_user_meta($attendee -> user_id, 'user_photo', true);    
            $attendee_image = ($user_photo ? trim($user_photo) : EVENTS_URL . 'makeup/avatar.png');
            
            $list .=  "<div class='attendee $bigList' id='user{$attendee -> user_id}'><a href='" . get_author_posts_url( $attendee -> user_id) . "'><img src='" . EVENTS_URL . "makeup/imumb/imumb.php?src={$attendee_image}&q=75&w=51&h=51' alt='{$attendee -> name}' title='{$attendee -> name}'/></a></div>";
            
            if($attendee -> user_id == $this -> userID) {
                $user_found = true;
            }
        }
        
        // if current user is not foud, we add it "silently". 
       if(!$user_found){
                   
               $user_data = get_userdata( $this -> userID ); 
                // backup image                    
                $user_photo = $user_data -> user_photo;    
                $user_data -> user_photo = ($user_photo ? trim($user_photo) : EVENTS_URL . 'makeup/avatar.png');
            
               if($user_data -> user_photo) {
                 $user_img = "<div class='attendee' id='user{$this -> userID}' style='display:none'><img src='" . EVENTS_URL . "makeup/imumb/imumb.php?src={$user_data -> user_photo}&q=75&w=51&h=51' alt='{$user_data -> display_name}' title='{$user_data -> display_name}'/></div>";
               }
           
       }
        
        $header = "<div class='event_attendees_wrap'><div class='event_attendees'>Aangemelde leden (<span class='seats'>{$this -> attendees_nr}</span>)";
        if($count > $show) {
            $header .= "<span class='show_all' >toon alles</span>";
        }
        $header .= "<div class='list_attendees_wrap'>";
        $footer = "</div></div></div>";
        return $header . $user_img  . $list .  $footer;
    }

  
}
