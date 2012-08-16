<?php

/*
 * Defining the widgets for the EVENT plugin
 * 
 */

class ssa_event_attendees extends wp_widget {
     
    function ssa_event_attendees() {
        $this->wp_widget('ssa_event_attendees', 'Event Inschrijvingen');
    }

    function form($settings) {
        $settings = wp_parse_args((array) $settings, array('title' => '', 'post_title' => '', 'comments_title' => ''));
        
        $header = strip_tags($settings['header']);
        $events = strip_tags($settings['events']);
        $count = strip_tags($settings['count']);
        
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('header'); ?>">Titel</label>
            <input class='widefat' id="<?php echo $this->get_field_id('header'); ?>" name="<?php echo $this->get_field_name('header'); ?>" type="text" value="<?php echo attribute_escape($header); ?>" />
            
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('events'); ?>">Event ids</label>
            <input class='widefat' id="<?php echo $this->get_field_id('events'); ?>" name="<?php echo $this->get_field_name('events'); ?>" type="text" value="<?php echo attribute_escape($events); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('count'); ?>">Show</label>
            <input size='3' id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo attribute_escape($count); ?>" />
            
        </p>
       
        <?
    }

    function widget($args, $settings) {
        extract($args, EXTR_SKIP);

        global $wpdb;

        echo $before_widget;

        $header = empty($settings['header']) ? '&nbsp;' : apply_filters('widget_title', $settings['header']);
        $events = $settings['events'];
        $count  = $settings['count'];
        
        if (!empty($header)) {
            echo $before_title . $header . $after_title;
        };

        // get the events (or just on) limit by the count
        $sqlEvents = empty($events) ? "" : ' and event_id in (' . $events . ') ';
        $sqlLimit = empty($count) ? " 10 " : " $count ";
         
        
        $sql = "SELECT 
        a.event_id, 
        a.user_id, 
        a.time_update,
        a.register_page as post_id,
        e.ename 
        FROM {$wpdb->prefix}events_attendees a
        inner join {$wpdb->prefix}events e on a.event_id = e.id 
        WHERE status=2 $sqlEvents 
        order by time_update DESC 
        LIMIT $sqlLimit";
        
        
        $safe_sql = $wpdb -> prepare($sql);
        
        $listAttendees = $wpdb -> get_results($safe_sql);
        
        $x .= "<div id='event_widget'>";
        
        foreach($listAttendees as $attendee){
            
            $x .= "<div class='ew_attendee'>";
            $user_data = get_userdata( $attendee -> user_id ); 
            //$listAttendees[$attendee -> user_id]['email'] = $user_data -> user_email;
            //$listAttendees[$attendee -> user_id]['photo'] = $user_data -> user_photo;
            //$listAttendees[$attendee -> user_id]['time'] = $attendee -> time_update;
            //$listAttendees[$attendee -> user_id]['fname'] = $user_data -> first_name;
            //$listAttendees[$attendee -> user_id]['lname'] = $user_data -> last_name;
            $name = $user_data -> first_name . " " . $user_data -> last_name;
            $register_page = get_page($attendee -> post_id); 
            $page_link = get_permalink( $attendee -> post_id );
            
            // backup image
            $attendee_image = ($user_data -> user_photo) ? trim($user_data -> user_photo) : EVENTS_URL . 'makeup/avatar.png';
            
             
            $x .= "<a href='" . get_author_posts_url( $attendee -> user_id) . "'><img class='ew_attendee_image' src='" . EVENTS_URL . "makeup/imumb/imumb.php?src={$attendee_image}&q=60&w=35&h=35' alt='" . $name . "' title='" . $name . "'/></a>";
            
             
            $x .=   "<div class='ew_attendee_name'>$name heeft zich ingeschreven voor:</div>";
            $x .=   "<div class='ew_attendee_page'><a href='$page_link' title='Schrijf u ook in op {$register_page -> post_title}'/>{$register_page -> post_title}</a> </div>";
            
            $x .= "</div>";            
                        
        }
    
        $x .= "</div>";
         echo $x;
        

        echo $after_widget;
    }

    function update($new_settings, $old_settings) {
        $settings = $old_settings;
        $settings['header'] = strip_tags($new_settings['header']);
        $settings['events'] = strip_tags($new_settings['events']);
        $settings['count'] = strip_tags($new_settings['count']);
        
        return $settings;
    }
     
     
     
 }
