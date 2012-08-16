<?php

/*
 * define all functions which will be called by ajax
 *
 */

class ssa_events_admin_ajax {

    // some defaults
    private $header = null;
    private $values = array();
    private $results = array();
    private $seats_left = array();
    private $listAttendees = array();
    private $listAttendees_count = null;
    
    public function __construct($values) {

        $this -> values = $values;

        switch ($values['what']) {
            case 'new' :
                $this -> header = '<div id="add_form_header"><h3>Voeg nieuw event toe</h3></div>';
                $this -> getEventForm();
                break;

            case 'save' :
                $this -> saveForm();
                break;

            case 'edit' :
                $this -> getEvent();
                $this -> getEventForm();
                break;

            case 'delete' :
                $this -> deleteEvent();
                break;

            case 'get' :
                $in = $this -> getStatus();
                
                // check if user is aangemeld
                $check = ( $in == 2 ? false : true );
                
                $this -> returnStatus($check);
                
                break;

            case 'set' :
                $this -> setStatus();
                break;

            default :
                break;
        };

    }



    /* the front page */
    /* the front page */
    /* the front page */
   
    private function setStatus() {

        // first check current status
        // then switch it
        // then check again
        $in = $this -> getStatus();

        // if status is "niet aangemeld", // dubble check if still seats availible
        if ($in < 2) {
            $this -> checkSeatsLeft();
            if ($this -> seats_left < 1) {
                echo '99';
                return false;
            }
        }

        $status = ($in < 2 ? 2 : 0);

        $this -> updateStatus($status);

        $out = $this -> getStatus();

        if ($in <> $out)
            $this -> returnStatus(false);

    }

    private function updateStatus($status) {

        global $wpdb;
    
        $cStr = 'user_id, event_id, status, register_page, time_insert, time_update';
        $sStr = '%s, %s, %s, %s, %s, %s';

        $uStr = 'status = %s, register_page = %s';

        $l = array($this -> values['userID'], $this -> values['eventID'], $status, $this -> values['register_post'], current_time('mysql'), current_time('mysql'));
        $l2 = array($status, $this -> values['register_post']);
        $l = array_merge($l, $l2);

        $sql = "INSERT INTO {$wpdb->prefix}events_attendees (" . $cStr . ") VALUES ( " . $sStr . ") ON DUPLICATE KEY UPDATE " . $uStr;

        $wpdb -> query($wpdb -> prepare($sql, $l));
        //echo $wpdb->rows_affected. "<br/>";

    }

    private function getStatus() {
        global $wpdb;

        $sql = "SELECT status FROM {$wpdb->prefix}events_attendees WHERE user_id = %d and event_id = %d";
        $safe_sql = $wpdb -> prepare($sql, $this -> values['userID'], $this -> values['eventID']);
        $this -> results = $wpdb -> get_var($safe_sql);
        return $this -> results;
    }

    private function checkSeatsLeft() {

        global $wpdb;

        // how many seats available
        $sql = "SELECT amount FROM {$wpdb->prefix}events WHERE id = %d";
        $safe_sql = $wpdb -> prepare($sql, $this -> values['eventID']);
        $amount = $wpdb -> get_var($safe_sql);

        // how many are taken
        $sql = "SELECT count(user_id) FROM {$wpdb->prefix}events_attendees WHERE event_id = %d and status=2";
        $safe_sql = $wpdb -> prepare($sql, $this -> values['eventID']);
        $seats_taken = $wpdb -> get_var($safe_sql);

        $this -> seats_left = $amount - $seats_taken;

    }

    private function returnStatus($check) {

        if ($check) {
            $this -> checkSeatsLeft();
            if ($this -> seats_left < 1) {
                echo '99';
                return false;
            }
        }

        echo $this -> results;
        return false;

    }
    
    
    
    /* the admin page */
    /* the admin page */
    /* the admin page */

    private function deleteEvent() {

        global $wpdb;
        $sql = "DELETE from {$wpdb->prefix}events WHERE id = %d";
        $wpdb -> query($wpdb -> prepare($sql, $this -> values['id']));
        echo $wpdb -> rows_affected . "<br/>";

    }

    private function getEvent() {
        global $wpdb;

        $sql = "SELECT id, ename, efrom as event_from, till as event_till, amount, info FROM {$wpdb->prefix}events WHERE id = %d";
        $safe_sql = $wpdb -> prepare($sql, $this -> values['id']);
        $this -> results = $wpdb -> get_row($safe_sql);

    }

    private function saveForm() {

        global $wpdb;

        $cStr = 'id, ename, efrom, till, amount, info';
        $sStr = '%s, %s, %s, %s, %s, %s';
        $uStr = 'ename = %s, efrom = %s, till = %s, amount = %s, info = %s';

        $van = date('Y-m-d H:i', strtotime($this -> values['van']));
        $tot = date('Y-m-d H:i', strtotime($this -> values['tot']));

        $l = array($this -> values['id'], $this -> values['naam'], $van, $tot, $this -> values['aantal'], $this -> values['info']);
        $l2 = array($this -> values['naam'], $van, $tot, $this -> values['aantal'], $this -> values['info']);
        $l = array_merge($l, $l2);

        $sql = "INSERT INTO {$wpdb->prefix}events (" . $cStr . ") VALUES ( " . $sStr . ") ON DUPLICATE KEY UPDATE " . $uStr;

        $wpdb -> query($wpdb -> prepare($sql, $l));

        //print_r($attrs) ;
        //echo $sql . "<br/>";
        //print_r($l) . "<br/>";
        //echo $wpdb->last_error;
        //echo $wpdb->rows_affected;

    }


    private function getListAttendees(){
        global $wpdb;


        $sql = "SELECT user_id, time_update FROM {$wpdb->prefix}events_attendees WHERE event_id = %d and status=2 order by time_update DESC ";
        $safe_sql = $wpdb -> prepare($sql, $this -> results -> id);
        $listAttendees = $wpdb -> get_results($safe_sql);
        $this -> listAttendees_count = $wpdb -> num_rows; 
       
        
        foreach($listAttendees as $attendee){
            
            $user_data = get_userdata( $attendee -> user_id ); 
            $this -> listAttendees[$attendee -> user_id]['email'] = $user_data -> user_email;
            $this -> listAttendees[$attendee -> user_id]['photo'] = $user_data -> user_photo;
            $this -> listAttendees[$attendee -> user_id]['time'] = $attendee -> time_update;
            $this -> listAttendees[$attendee -> user_id]['fname'] = $user_data -> first_name;
            $this -> listAttendees[$attendee -> user_id]['lname'] = $user_data -> last_name;
                        
        }
        
       
    }

   private function showListAttendees(){
               
       $max = 100;  $count = 1;
       
       $this -> getListAttendees();
       
       if($this -> listAttendees){
           
           $x = '<div id="listAttendees_wrap"><h3>Inschrijvingen (' . $this -> listAttendees_count . ')</h3>';
           
           $x .= "<div id='actions'><a href='?page=events&t=p&e={$this -> results -> id}' id='{$this -> results -> id}'>Export PDF>></a>  ";
           
           $x .= "<a href='?page=events&t=c&e={$this -> results -> id}' id='{$this -> results -> id}'>Export CSV>></a> </div>";
           
           $x .= '<table id="listAttendees">';
           
           $x .= '<tr><th>Naam</th><th>Email</th><th>Aangemeld</th></tr>';
           
           foreach($this -> listAttendees as $key => $attendee){
                
                $x .= "<tr id='{$key}' class='attendee_wrap'>";
                
              /* 
               *   $x .= "<div class='attendee' id='{$key}'>";
               
              if($attendee['photo']){
                $x .= "<img src='" . EVENTS_URL . "makeup/imumb/imumb.php?src={$attendee['photo']}&q=75&w=41&h=41' alt='" . $attendee['fname'] . " " . $attendee['lname'] . "' title='" . $attendee['fname'] . " " . $attendee['lname'] . "'/>";
               }
                $x .= "</div>";
               */
                $x .= '<td class="user_meta">' .$attendee['fname'] . " " . $attendee['lname'] . "</td>";
                $x .= '<td class="user_meta">' .$attendee['email'] . "</td>";
                $x .= '<td class="user_meta">' . date('d M Y H:i', strtotime($attendee['time'])) . "</td>";
               
                $x .= "</tr>";     
                
                if($count == $max){
                    
                    $x .= '<td class="user_meta">Max ' . $max . ' zichtbaar. </td>';
                    break;
                }       
                $count++;
            }
           
           
           $x .= '</table></div>';
           
       }

       
       echo $x;
        
       
   }
    
    private function getEventForm() {
            
         
        
        if($this -> results -> event_from){
            
           $date_from = date('d-m-Y H:i', strtotime($this -> results -> event_from)); 
           $date_till = date('d-m-Y H:i', strtotime($this -> results -> event_till)); 
        
        } else {
            
           $date_from = $date_till = date('d-m-Y H:i');  
            
        }
        
        $x = '<form>
       <input id="id" name="id" type="hidden" value="' . $this -> results -> id . '" size="40">
       <table class="form-table">
       <tbody>
       <tr valign="top">
       <th scope="row">
       <label for="naam">Naam</label>
       </th>
       <td>
       <input id="naam" name="naam" type="text" value="' . $this -> results -> ename . '" size="40">
       <span style="margin-left:20px; vertical-align:top;" class="description">Omschrijving van het event</span>
       </td>
       </tr>
       
       <tr valign="top">
       <th scope="row">
       <label for="van">Van</label>
       </th>
       <td>
       <input id="van" name="van" class="hasDatepicker" type="text" value="' . $date_from . '" size="40">
       <span style="margin-left:20px; vertical-align:top;" class="description">Start datum / tijd (formaat="dd-mm-jjjj uu:mm)</span>
       </td>
       </tr>

       <tr valign="top">
       <th scope="row">
       <label for="tot">Tot</label>
       </th>
       <td>
       <input id="tot" name="tot" class="hasDatepicker" type="text" value="' . $date_till . '" size="40">
       <span style="margin-left:20px; vertical-align:top;" class="description">Eind datum / tijd (formaat="dd-mm-jjjj uu:mm)</span>
       </td>
       </tr>
       
       <tr valign="top">
       <th scope="row">
       <label for="aantal">Aantal</label>
       </th>
       <td>
       <input id="aantal" name="aantal" type="text" value="' . $this -> results -> amount . '" size="40">
       <span style="margin-left:20px; vertical-align:top;" class="description">Hoeveel aanmeldingen?</span>
       </td>
       </tr>

                     
       <tr valign="top">
       <th scope="row">
       <label for="descr">Info</label>
       </th>
       <td>
       <textarea id="info" name="info" rows="7" cols="42">' . $this -> results -> info . '</textarea>
       <span style="margin-left:20px; vertical-align:top;" class="description">Notitie veld (wordt niet getoond)</span>
       </td>
       </tr>
       
       
       
       </tbody>
       </table>
       <button id="save" name="save"  class="button-primary" >Save</button>
       </form>';
        
               
        echo $this -> header . $x;

        $this -> showListAttendees();
    }

    public function __destruct() {

        foreach ($this as $key => $value) {
            unset($this -> $key);
        }
    }

}
