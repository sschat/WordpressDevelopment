<?php

/*
 * class used for the admin result page
 * 
 * 
 * 
 */

class ssa_scan_SqlScanResults {
    
    private $scanList = array();
    private $data = array();
    
        
    public function __construct($data){
        
        include (SCAN_DIR . 'front/class/getScanResults.php');
        
        $this -> data = $data;
        $this -> scanList =  $this -> sqlGetScanList();    
        
        if($data) 
            $this -> controller();
        
        
        
        
    }
    
    public function getScanList(){
    
        return $this -> scanList;
        
    }
    
    public function controller(){
    
        // input is data array
        // what (getUsers)()
        // id   (scanID)()
        switch ($this -> data['what']) {
            case 'getUsers':
                
                
                $this -> getUsers($this -> data['id']);
                
                break;
            
            case 'getResults':
                
                $this -> getResults($this -> data['id']);
                
                break;
            
            case 'getQuestionResults':
                
                $this -> getQuestionResults($this -> data['id']);
                
                break;            
            
            
            default:
                
                break;
        }
        
        
    }
    
    
    
    private function sqlGetScanList(){
        // we get ScanIDs via the results table, so we only show the once with actuall results
                
          global $wpdb;

            $sql = "select
                    t2.naam, t2.id
                    from
                    {$wpdb->prefix}scan_results r
                    left join {$wpdb->prefix}scan_questions q on r.question_id = q.id
                    left join {$wpdb->prefix}scan_types t on q.parent_id = t.id
                    left join {$wpdb->prefix}scan_types t2 on t.parent_id = t2.id
                    where t2.id is not null
                    group by t2.id";

            $safe_sql = $wpdb -> prepare($sql);
            $scanList = $wpdb -> get_results($safe_sql);

        return $scanList;
        
        
    }
    
    
    private function getResults($userID){
        // we do this all in the SAME class as the one we use for the front
        
        // @parms : scan id, user id, admin view (true)
        $showUserResults = new ssa_scan_getScanResults($this -> data['scanID'], $userID, true );
        
        
    }
    
   private function getQuestionResults($themeID){
        // for this themeID, lookup all questions
        // per question, get all answers
        
        echo $this -> data['user1'];

        echo $this -> data['user2'];
     
        
    } 
    private function getUsers($id){
        
        foreach( $this->sqlGetUsers($id) as $user){
         
            $x .= "<option value='{$user->user_id}'>{$user->display_name}</option>";
            
        }
        
        echo $x;
        
    }
    
    
    private function sqlGetUsers($id){
        // we get ScanIDs via the results table, so we only show the once with actuall results
                
          global $wpdb;

            $sql = "select
                    r.user_id, u.display_name
                    from
                    {$wpdb->prefix}scan_results r
                    left join {$wpdb->prefix}scan_questions q on r.question_id = q.id
                    left join {$wpdb->prefix}scan_types t on q.parent_id = t.id
                    left join {$wpdb->prefix}scan_types t2 on t.parent_id = t2.id
                    left join {$wpdb->prefix}users u on r.user_id = u.ID
                    where 
                    t2.id = %d
                    group by r.user_id";

            $safe_sql = $wpdb -> prepare($sql, $id);
            $userList = $wpdb -> get_results($safe_sql);

        return $userList;
        
        
    }
    
    
    
}
