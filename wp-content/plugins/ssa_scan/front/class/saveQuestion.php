<?php
/*
 * Called via ajax
 * 
 * Input : Attrs (array)
 *  - id : question id
 * - value : question value
 * 
 * @return rows affected (0 on error)
 * 
 */
class ssa_scan_saveQuestion{
    
    public function __construct($attrs){
        
        

        global $wpdb, $current_user;

        get_currentuserinfo();
        $this -> user = $current_user -> ID;

        $sql = "INSERT INTO {$wpdb->prefix}scan_results ( user_id, question_id, answer, qst_explain, insert_timestamp) VALUES ( %d, %d, %d, %s, %s ) ON DUPLICATE KEY UPDATE answer = %s, qst_explain = %s, insert_timestamp = %s";

        $l = array($this -> user, $attrs['id'] , $attrs['value'] , "-" , current_time('mysql'), $attrs['value'] , "-" , current_time('mysql') );

        $wpdb -> query($wpdb -> prepare($sql, $l));

        //print_r($l) ;
        //echo $sql . "<br/>";
        //print_r($l) . "<br/>";
        //echo $wpdb -> last_error ;
        
        
        // return this number so we know if it was succesfull
        echo $wpdb -> rows_affected;

    }

    
}
