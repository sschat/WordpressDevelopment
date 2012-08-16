<?php

/* Saving a new added row
 * Return : none
 * 
 * @parms : data (array)
 *  
 * 'data' array:
 *      what    : is it a scan, theme ,question or score
 *      columns : all fields from the given form
 *              (this includes parent_id, type and "id" only edit )
 *
 *  called from the admin page via ajax
 *
 */
class ssa_scan_AjaxSaveScan {
    
    private $table = array();
    private $results = array(); 
    private $attrs = array();
    
   
    
    public function saveForm($attrs){
        global $wpdb;
        global $scan_table, $score_table, $question_table;    
            
        // set the input parms to local class vars
        $this -> $attrs = $attrs;
        
        
        if($attrs['what'] == "getScore"){
            
            $this -> table = $score_table;
            
             
        }
        
        
        if($attrs['what'] == "getTable"){
            $this -> table = $scan_table;
            
        }
        
        if($attrs['what'] == "getQuestion"){
               $this -> table = $question_table;
         
        }
        
        
        $count = 1; $l = array();
        foreach($this->table['columns'] as $key => $field){
            
            // all columns
            $c[] = $field['column'];
            
            
            // all %s strings
            $s[] = "%s";
            
            // all columns on update (exept the ID, always col no.1 )
            if($count>1)
            $u[] = $field['column'] . "=%s";
            
            // all column values
            $l[] = ($attrs[$field['column']]?$attrs[$field['column']]: "0") ;  
            
            // all column values on update (exept the ID, always col no.1 )
            if($count>1)
            $l2[] = ($attrs[$field['column']]?$attrs[$field['column']]: "") ;  
            
            $count++;
            
        }
        // turn all arrays in to strings
        $cStr = implode(',', $c); $sStr = implode(',', $s); $uStr = implode(',', $u); $l = array_merge($l, $l2);
        
        $sStr = $this -> clearData( $sStr  );

                
        
        $sql = "INSERT INTO {$wpdb->prefix}{$this->table['table']} (" . $cStr . ") VALUES ( " . $sStr . ") ON DUPLICATE KEY UPDATE " . $uStr ;
        
        
        $wpdb->query($wpdb->prepare($sql, $l ));
        
        
        
        //print_r($attrs) ;
        //echo $sql . "<br/>";
        //print_r($l) . "<br/>";
        //echo $wpdb->last_error. "<br/>";
        echo $wpdb->rows_affected. "<br/>";

        
        
    }
    
    public function clearData($str){
        
        return  mysql_real_escape_string($str);

    }
}
