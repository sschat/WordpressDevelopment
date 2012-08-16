<?php

/*
 * Delete row from SCAN table
 * Delete Scan, theme or question it self
 * 
 * TODO: 
 * delete cascade?
 * add nonce 
 * 
 * this one is called via AJAX on admin screen
 * 
 * 
 * @parm : type
 * $parm : id
 * 
 */


class ssa_scan_DeleteRow {
    
    private $attrs = array();
    
    public function __construct($attrs){
        global $wpdb;
        //what we need to delete?
        // input : 
        // type
        // id
         
        switch ($attrs['type']) {
            case 'scan':
            case 'theme':
                $table = 'scan_types';
                break;
            
            case 'score':
                $table = 'scan_scores';
                break;
            
            case 'qst':
                $table = 'scan_questions';
                break;
            
            default:
                
                break;
        }   
        
        $sql = "DELETE from {$wpdb->prefix}{$table} WHERE id = %d";
        
        $wpdb->query($wpdb->prepare($sql, $attrs['id'] ));
        
        print_r($attrs) ;
        echo $sql . "<br/>";
        //print_r($l) . "<br/>";
        echo $wpdb->last_error. "<br/>";
        echo $wpdb->rows_affected. "<br/>";
        
    }
    
}
