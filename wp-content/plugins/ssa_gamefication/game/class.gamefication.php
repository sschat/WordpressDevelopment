<?php

class gamefication {

    public $player;
    protected $_counter; public $status;
    protected $_logger = array();

    
    
    public function __construct($id) {

       global $wpdb;
       $this->player = $id;
       
       $this->_setStatus();

    }


    public function play($moment){

     
        // First find the rules for this moment
        $rule_list = $this->_set_rules($moment);
                
        // For every rule update the counter
        foreach($rule_list as $rule){
            
            $this->_log("INFO", "Updating rule : " . $rule->id );
            $this->_update_counter($rule->id, $rule->weight);
        
            //When the counters are updated, let do some status checking and updating
            // With the new counters lets check if a new phase has been reached
            $this->_update_status($rule->group_id);
            
        }

    }

    
    
    
    
    public  function showGameLog(){
        return $this->_showLog(true);
    }




    /* --- */
    /* --- BELOW THIS LINE IS LIKE UNDER THA HOOD --- */
    /* --- */

    protected function _setStatus(){
         global $wpdb;
         $sql = 'SELECT g.id, g.name as groupname, s.phase_id, p.name, p.image
                    ,(select 
                    sum(c.counter)
                    from netwerk_game_rules r
                    left join netwerk_game_counter c on r.id = c.rule_id
                    where 
                    r.group_id=g.id) as current_count
                    FROM netwerk_game_status s 
                     left join netwerk_game_phases p on s.phase_id = p.id 
                     left join netwerk_game_groups g on s.group_id = g.id
                    where s.user_id="' . $this->player . '"';
         
         $this->status = $wpdb->get_results( $wpdb->prepare( $sql ));
        
    }


    protected function _set_rules($moment){
        global $wpdb;
        $sql = 'SELECT id, group_id, weight FROM netwerk_game_rules where moment="' . $moment . '";';
        return $wpdb->get_results($sql );
        
    }
    
    
    protected function _update_counter($rule_id, $weight){
            global $wpdb;
            // this function looks up the current counter and insert/updates the counter

            $this->_counter = false;
            $sql = 'SELECT counter FROM netwerk_game_counter where user_id="' . $this->player . '" and rule_id="' . $rule_id . '"';
            $counter = $wpdb->get_var( $wpdb->prepare( $sql ));
            
            $amount = 1 * $weight;
            
            if($counter) {
                // Counter found, so PLUSONE
                $wpdb->query(  $wpdb->prepare(
                'UPDATE netwerk_game_counter set counter = %s where user_id="' . $this->player . '" and rule_id="' . $rule_id . '"' , 
                $counter + $amount
                ));
              
                $this->_counter=$counter + $amount;
                         
            } else {
                // Counter not found, so insert to 1
                  $wpdb->query(  $wpdb->prepare(
                "INSERT INTO netwerk_game_counter
                (  user_id, rule_id, counter )
                VALUES ( %s, %s, %s )" , 
                $this->player, $rule_id, $amount
                ));
                  
               $this->_counter= $amount;
                
          }
          
          return true;
    }
    
    
    protected function _update_status($group_id){
             global $wpdb;
            // this function looks up the current phase and insert/updates the phase of this group 

             
             // get current phase of this group
            $sql = 'SELECT phase_id FROM netwerk_game_status where user_id="' . $this->player . '" and group_id="' . $group_id . '"';
            $current_phase = $wpdb->get_var( $wpdb->prepare( $sql ));
          
            
            
            
            // get the "calculated" phase of this group with the amount of points
            $sql = 'SELECT 
                        p1.id
                        FROM netwerk_game_phases p1
                        where
                        p1.points = 
                        (select 
                        max(points)
                        FROM netwerk_game_phases p2
                        where group_id="' . $group_id . '" and   points < ' . $this->_counter . ')  ';
            $cal_phase = $wpdb->get_var( $wpdb->prepare( $sql ));
           
             if($cal_phase === $current_phase) {
                 
                 $this->_log("INFO", "CURRENT PHASE (" . $current_phase . ") is same as CALCULATE PHASE (" . $cal_phase . ")" ); 
                 
                 // nothing to do
                 return true;
                 
             } 
             
             
             
          // hooray, a new phase has been reached
          if($current_phase){
                $wpdb->query(  $wpdb->prepare(
                "UPDATE netwerk_game_status 
                SET phase_id = %s
                where user_id = %s
                and group_id = %s;"
                ,
                $cal_phase, $this->player, $group_id
                )) ;
                
                $this->_log("INFO", "YOUR GROUP PHASE HASE BEEN UPDATED (old:" . $current_phase . "|new:" . $cal_phase .")" ); 
                
                
          } else {
                $wpdb->query(  $wpdb->prepare(
                "INSERT INTO netwerk_game_status
                (  user_id, group_id, phase_id )
                VALUES ( %s, %s, %s )" , 
                $this->player, $group_id, $cal_phase
                ));
              
              $this->_log("INFO", "YOUR GROUP PHASE HASE BEEN INSERTED (old:" . $current_phase . "|new:" . $cal_phase  .")" ); 
          }

           //todo send notification
          
          
          
          

    }

    // Some logging makes it easier
    protected function _log($type, $message){
        
        $this->_logger[$type][]=$message;
        
    }

    protected function _showlog($on){
        if($on){
            $return = '';
            
            foreach($this->_logger as $type => $row){

                foreach($row as $message){
                    
                    $return .= "<p>" . $type . " : " . $message . "</p>";
                    
                }
                
            }
                       
        }
        return $return;
   }

    public function __destruct() {
        foreach ($this as $key => $value) {
            unset($this->$key);
        }
    }


    

}
