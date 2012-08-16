<?php

/* ALL about TABLES
 * Return a table layout defined by the paramters
 *
 * @parms : attrs (array)
 *  
 * array:
 *      what   : is it a scan, theme ,question or score
 *      type   : scan / theme
 *      pid    : id of the parent (table rows belonging to only current page)
 *
 *  called from the DefinePage.php ()
 *
 */
    
    
class ssa_scan_AjaxGetTablesScan {


    
    
    private $table = array();
    private $attrs = array();
    private $headernames = array();
    
    public function getRecords($attrs) {
        global $wpdb;
        global $scan_table, $score_table, $question_table;
        
       
        
        // set the input parms to local class vars
        $this -> attrs = $attrs;

        
        if($attrs['what'] == "getScore"){
            
            $this -> table = $score_table;
             $sql_addon = ' type = %s';
            if($this -> attrs['pid']){
                
                $sql_addon .= " AND parent_id = %s";
                
            }
            $sql_addon .= " ORDER BY start";
            $sql_values = array ($this -> attrs['type'],  $this -> attrs['pid']);
             
        }
        
        if($attrs['what'] == "getTable"){
            
            $this -> table = $scan_table;
            
            $sql_addon = ' type = %s';
            if($this -> attrs['pid']){
                
                $sql_addon .= " AND parent_id = %s";
                
            }
            $sql_values = array ($this -> attrs['type'],  $this -> attrs['pid']);
            
        }
        
        if($attrs['what'] == "getQuestion"){
            
            $this -> table = $question_table;
            
            
            if($this -> attrs['pid']){
                
                $sql_addon = "  parent_id = %s";
                
            }
            $sql_values = array ($this -> attrs['pid']);
            
        }
        
                
        // generate a custom column list. (hide some of them)
        foreach ($this->table['columns'] as $key => $field) {

            if( ! in_array( $field['type'] , array( 'hide' , 'type', 'parent') ) )        
                $x[] = $field['column'];
                
                
                // used for the header 
                 if( ! in_array( $field['column'] , array( 'parent_id' , 'type', 'options') ) )      
                $this -> headernames[] = $field['name'];
        
        
        }
        $strColumns = implode(',', $x);

               
        // Get the result in from sql
        $sql = "SELECT $strColumns FROM {$wpdb->prefix}{$this->table['table']} WHERE " . $sql_addon;
        
        
       // prepare and execute        
        $safe_sql = $wpdb -> prepare($sql, $sql_values);
        $this -> results = $wpdb -> get_results($safe_sql);
        

        // send the results to format a table / or present an input form
        if($this -> results) {
            
            return $this -> sendTableResults();
        
        } else {
                
            //$getForm = new AjaxGetFormScan();
            //$attrs['id'] = null;
            //return $getForm -> getEditForm( $attrs );            
            
        }
        
        
        
        
    }

    private function sendTableResults() {

        global $wpdb;
        // in de page we build a table layout, so return table rows
        $x = "<table class='wp-list-table widefat '>";

        $x .= "<thead><tr>";
                
        foreach($this -> headernames as $column){
        
            $x .= "<th>" . $column . "</th>";
        
        }
        
        $x .= "</tr></thead>";

        $x .= "<tfoot><tr>";
                
        foreach($wpdb -> get_col_info('name') as $column){
        
            $x .= "<th>" . $column . "</th>";
        
        }
        
        $x .= "</tr></tfoot>";
        $this -> headernames = array();


        foreach ($this->results as $row) {

            $x .= "<tr class='row' id='$row->id'> ";

            foreach($row as $column){
                
               $x .= $this->makeTableRow( $column, $row->id );
            
            }
            
            
            //reset counter for the column row
            $this -> columnCount = 0;

            $x .= "</tr>";

        }
        $x .= "</table>";
    
    return $x;
    }

  
    private function makeTableRow($column, $id) {

        $x = "<td>" . $column;

        if ($this -> columnCount == 1) {
            
            if( $this->attrs['what']== 'getScore') {
                
                $x .= "<div class='row-actions'>";
                $x .= "<span class='inline'><a href='javascript:;' id='getScore' name='{$this -> attrs['type']}' class='editscore' title='Edit Score'>Wijzig Score</a> | </span>";
                
                $x .= "<span class='trash'><a href='javascript:;' id='$id'  name='score' class='delete' title='Move this item to the Trash'>Delete</a> </span>";
                $x .= "</div>";
                
                
            } else {
                    
                    if($this -> attrs['type'] =='scan'){
                        
                        $x .= "<div class='row-actions'>";
                        $x .= "<span class='inline'><a href='?page=scans&scan={$id}' class='editscan' title='Edit this item inline'>Wijzig Scan</a> | </span>";
                        
                        $x .= "<span class='trash'><a href='javascript:;' id='$id'  name='scan' class='delete' title='Move this item to the Trash'>Delete</a> </span>";
                        $x .= "</div>";
                        
                    }
                    
                    if($this -> attrs['type'] =='theme'){
                        $x .= "<div class='row-actions'>";
                        $x .= "<span class='inline'><a href='?page=scans&scan={$this -> attrs['pid']}&theme=$id' class='edittheme' title='Edit this item inline'>Wijzig Thema</a> | </span>";
                        
                        $x .= "<span class='trash'><a href='javascript:;' id='$id'  name='theme' class='delete' title='Move this item to the Trash'>Delete</a> </span>";
                        $x .= "</div>";
                    }
                    
                     if($this -> attrs['type'] =='qst'){
                        $x .= "<div class='row-actions'>";
                        $x .= "<span class='inline'><a href='javascript:;' id='getQuestion'  class='editqst' title='Edit Score'>Wijzig Vraag</a> | </span>";
                        
                        $x .= "<span class='trash'><a href='javascript:;' id='$id'  name='qst' class='delete' title='Move this item to the Trash'>Delete</a> </span>";
                        $x .= "</div>";
                    }
            
            } // getScore
            
            
        }
        $x .= "</td>";

        $this -> columnCount++;
        
        return $x;
    }

}
