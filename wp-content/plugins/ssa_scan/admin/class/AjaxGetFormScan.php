<?php


/*  ALL about FORMS
 *
 *  @parms : Attrs (array)
 *  
 * array:
 *      what   : is it a scan, theme ,question or score
 *      type   : scan / theme
 *      id     : id is filled when edited
 *      pid    : id of the parent type 
 *
 *  called from the DefinePage.php ( for editing current type )
 *  called from the admin page, via ajax
 * 
 */
    
    
class ssa_scan_AjaxGetFormScan {
    
    private $table = array();
    private $attrs = array();
    private $results = array();
    
    private $table_header = 'Voeg nieuwe %s toe';
    
    public function getEditForm($attrs) {
        global $wpdb;
        global $scan_table, $score_table, $question_table;
        
        
        // set the input parms to local class vars
        $this -> attrs = $attrs;
        
        
        
        if($attrs['what'] == "getScore"){
            
            $this -> table = $score_table;
            
             
        }
        
        if($attrs['what'] == "getTable"){
           
           
            $this -> table = $scan_table;
                     
            
        }
       
       
       if($attrs['what'] == "getQuestion"){
       
            $this -> table = $question_table;
            
        }
                
   
   
        foreach ($this->table['columns'] as $key => $field) {

            $x[] = $field['column'];
            
        }
        $strColumns = implode(',', $x);
      
      
      
        // if id is filled up, this is an "edit" form. Else it would be a "create" form (no values)
        if ($attrs['id']>0) {
      
          $this -> table_header = "Wijzig %s";
          $sql = "SELECT $strColumns FROM {$wpdb->prefix}{$this->table['table']} WHERE id = %s";
          $safe_sql = $wpdb -> prepare($sql, $attrs['id']);
          $this -> results = $wpdb -> get_row($safe_sql);
              
    
        }  

        
        
        
       foreach ($this->table['columns'] as $key => $field) {
           
            $fields[] = array("name" => $field['column'], "header" => $field['name'], "value" => $this -> results -> $field['column'], "type" => $field['type'], "descr" => $field['descr']); 
        
       }
        
       return $this -> sendFormFields($fields);

    }

    private function sendFormFields($formfields) {
 
        $x = $this->getTableHeader();
        
        $x .= "<form id='{$this->attrs['what']}' >";
        $x .= "<table class='form-table'>";
        foreach ($formfields as $key => $field) {

            
            switch ($field['type']) {

                case 'id' :
                    $x .= "<input id='{$field['name']}' name='{$field['name']}' type='hidden' value='{$field['value']}'>";
                    break;
                    
                case 'type' :
                    $x .= "<input id='{$field['name']}' name='{$field['name']}' type='hidden' value='{$this -> attrs['type']}'>";
                    break;
                
                case 'parent' :
                    
                    $x .= "<input id='{$field['name']}' name='{$field['name']}' type='hidden' value='{$this -> attrs['pid']}'>";
                    break;    
                                        
                case 'text' :
                    $x .= "<tr valign='top'><th scope='row'><label for='{$field['name']}'>{$field['header']}</label></th><td>";
                    $x .= "<input id='{$field['name']}' name='{$field['name']}' type='text' value='{$field['value']}' size='40'>";
                    $x .= "<span style='margin-left:20px; vertical-align:top;'  class='description'>" . sprintf( $field['descr'] , $this -> attrs['type']) . "</span>";
                    $x .= "</td></tr>";
                    break;
                
                case 'textarea' :
                    $x .= "<tr valign='top' ><th scope='row'><label for='{$field['name']}'>{$field['header']}</label></th><td>";
                    $x .= "<textarea id='{$field['name']}' name='{$field['name']}' rows='7' cols='42'>{$field['value']}</textarea>";
                    $x .= "<span style='margin-left:20px; vertical-align:top;' class='description'>" . sprintf( $field['descr'] , $this -> attrs['type']) . "</span>";
                    
                    
                    $x .= "</td></tr>";
                    break;
                    
                    
                default :
                    break;
            }
            

        }

        $x .= "</table>";
        
        $x .= "<button id='save' name='save'  class='button-primary' >Save</button>";
        $x .= "</form>";
        
        return $x;
  }
  
  private function getTableHeader(){
      
      if($this -> attrs['type'] == "scan"){
              $type = "Scan";
      }
      if($this -> attrs['type'] == "theme"){
          $type = "Thema";
      }
      if($this->attrs['what'] == "getScore"){
              
               $type .= " Score";
          
      }
      if($this -> attrs['what'] == "getQuestion"){
          $type = "Vraag";
      }
      
          
        
      
      return "<div id='add_form_header'><h3>" . sprintf( $this->table_header ,$type) . "</h3></div>";
  }
    
    
}
