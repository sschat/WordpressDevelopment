<?php

/* Class for front 
 * 
 * No input required.
 * Takes current user id
 * reads database values
 * and build up the scan resultpage
 *  
 * todo : 
 * check user status
 * 
 * Is called via ajax on front page, when user reaches last page
 * 
 * 
 */



class ssa_scan_getScanResults {

    private $userID = null;
    private $scanID = null;
    private $theme_scores = array();
    private $adminview = false;
    private $numberOfUsers = 1;
    private $option = array();

    public function __construct($scanID, $userID = false, $admin = false) {

        global $wpdb, $current_user;
        
        if($userID){
            
            $this -> userID = $userID;
            
        } else {
            
            get_currentuserinfo();
            $this -> userID = $current_user -> ID;
            
        }
        $this -> scanID = $scanID;
        $this -> options = get_option('scan_options');
            
            
        if($admin){
                
            $this -> adminview = true;    
            echo "<div id='user_result'>";
            $this -> showScanResults();
            echo "</div>";
         
        }  else { 
            
            
            echo "<div id='user_message'>{$this -> options['pre_eindtekst']}</div>";
             
             echo "<div id='user_result' >";
             $this -> showScanResults();
             
             echo "</div>";
            
            echo "<div id='user_message'>{$this -> options['post_eindtekst']}</div>";
        
        }

    }

    private function showScanResults() {
        
        $scan_total = 0;
        $theme_scores = array();

        // generate the results
        $this -> theme_scores = $this -> getThemeResults();
        
        // based on results, generate the graphs
        $theme_score_image = $this -> makeThemeBarGraphs();

        foreach ($this -> theme_scores as $theme_score) {

            $x .= "<div class='themeScore'>";

            $x .= "<div class='themeHeader' ><h3>{$theme_score['theme_name']}<h3></div>";
            //$x .= "<div class='themeDescr'><i>{$theme_score['theme_descr']}</i></div>";
            $x .= "<div class='themeScore'><strong>Uw score: </strong>{$theme_score['theme_total']}</div>";
            $x .= "<div class='ScoreDescr'><p><strong>Betekenis: </strong>" . utf8_encode($theme_score['score_descr'] ) . " </p></div>";

            $x .= "</div>";

            $scan_total = $scan_total + $theme_score['theme_total'];

        }

        // now generate the OVERALL score
        $scan_score_descr = $this -> getScanResults($scan_total);
        $scan_score_image = $this -> makeScanBarGraphs($scan_total);

        $y = "<div class='scanScore' style='background-color:#{$this -> options['header_color']}; color:#{$this -> options['text_color']}'>";

        $y .= "<div class='scanHeader'><h3 >Totaal Scan Score<h3></div>";
        $y .= "<div class='themeScore'><strong>Uw score: </strong>$scan_total</div>";
        $y .= "<div class='ScoreDescr'><p><strong>Betekenis: </strong>" . utf8_encode($scan_score_descr) . "</p></div>";

        $y .= "</div>";

        if($this -> adminview){
            
                echo $scan_score_image . $theme_score_image;
            
        } else {
            
            echo $scan_score_image . $theme_score_image . $y . $x;
        
        }
    }

    private function getUserCnt(){
        
        global $wpdb;

        $sql = "select 
                sum(r.answer) as theme_sum, t.id as theme_id, t.naam as theme_name, t.descr as theme_descr
                from 
                {$wpdb->prefix}scan_results r
                left join {$wpdb->prefix}scan_questions q on r.`question_id` = q.id
                left join {$wpdb->prefix}scan_types t on q.parent_id = t.id
                left join {$wpdb->prefix}scan_types t2 on t.parent_id = t2.id
                where 
                t2.id = %s
                 
                $user_filter
                
                
                group by t.id
                order by 
                t.id ASC";

        $safe_sql = $wpdb -> prepare($sql, $this -> scanID, $this -> userID);
        $theme_results = $wpdb -> get_results($safe_sql);
        
        
    }    
    private function getThemeResults() {

        global $wpdb;

        
        $user_filter = "and r.user_id = %s";
        $numberUsers = 1;
        if($this->userID=='all'){
        
            // small call to set the number of users who did this test
            $this->sqlGetUsers();
            $user_filter = "";
            $numberUsers = $this -> numberOfUsers;
        }
                
        $sql = "select 
                sum(r.answer) as theme_sum, t.id as theme_id, t.naam as theme_name, t.descr as theme_descr
                from 
                {$wpdb->prefix}scan_results r
                left join {$wpdb->prefix}scan_questions q on r.`question_id` = q.id
                left join {$wpdb->prefix}scan_types t on q.parent_id = t.id
                left join {$wpdb->prefix}scan_types t2 on t.parent_id = t2.id
                where 
                t2.id = %s
                 
                $user_filter
                
                
                group by t.id
                order by 
                t.id ASC";

        $safe_sql = $wpdb -> prepare($sql, $this -> scanID, $this -> userID);
        $theme_results = $wpdb -> get_results($safe_sql);

        // per theme result, lookup the "score" description
        foreach ($theme_results as $theme_result) {

            $sql = "select 
                    CONCAT(s.start, ' - ',  s.end, ' :', s.descr) as descr
                    from
                    {$wpdb->prefix}scan_scores s
                    where 
                    s.parent_id = %d
                    and
                    s.start <= %d
                    and 
                    s.end >= %d";

            $safe_sql = $wpdb -> prepare($sql, $theme_result -> theme_id, $theme_result -> theme_sum, $theme_result -> theme_sum);
            $theme_score_descr = $wpdb -> get_var($safe_sql);

            //set the score as normal or as summary
            $score = $this->userID=='all'?number_format( ($theme_result -> theme_sum / $numberUsers), 1, ',', ' '):$theme_result -> theme_sum ;
            
            
            //compile a results array for showing on screen
            $x = array("theme_id" => $theme_result -> theme_id, "theme_name" => $theme_result -> theme_name, "theme_descr" => $theme_result -> theme_descr, "theme_total" => ( $score  ), "score_descr" => $theme_score_descr, );
            $theme_scores[] = $x;

        }

        return $theme_scores;

    }

    private function getScanResults($scan_total) {

        global $wpdb;

            $sql = "select 
                    CONCAT(s.start, ' - ',  s.end, ' :', s.descr) as descr
                    from
                    {$wpdb->prefix}scan_scores s
                    where 
                    s.parent_id = %d
                    and
                    s.start <= %d
                    and 
                    s.end >= %d";

            $safe_sql = $wpdb -> prepare($sql, $this -> scanID, $scan_total, $scan_total);
            $scan_score_descr = $wpdb -> get_var($safe_sql);


        return $scan_score_descr;

    }


    private function makeThemeBarGraphs(){
        global $wpdb;
        
        // for this we include an extra file to generate our color bar
        include_once('csscolor.php');
        $base = new CSS_Color('C9E3A6');
        
        foreach ($this -> theme_scores as $theme_score) {
                
                $zIndex=1;
                // first we check the theme score table. Based on the values we generate a nice step-graph
                $sql = "select 
                        s.start, s.end
                        from 
                        {$wpdb->prefix}scan_scores s
                        where parent_id =  %d
                        order by s.end DESC"; 
                
                 $safe_sql = $wpdb -> prepare($sql, $theme_score['theme_id']);
                 $theme_steps = $wpdb -> get_results($safe_sql);
                

                
                
                // build a div pattern. Start with the largest number. This will be the under layer
                // each score step, smaller, will be placed on top of it
                
                // divide by ratio
                // since we ordered the list by "end" DESC, we know the first row is the largest value -> this is 100%
                $ratio = (100 / $theme_steps[0]->end );
                
                $x .= "<div style='clear:both'></div>";
                $x .= "<div class='themeBarLabel'>";
                
                $x .= "<span>" . $theme_score['theme_name']. "</span><br/>";
                
                
                $x .= "</div>";
                $x .= "<div class='themeBarPlaceholder' id='themeBarPlaceholder_{$theme_score['theme_id']}'>";
                
                foreach($theme_steps as $theme_step){
                    
                            $x .= "<div class='themeBarStep' id='{$theme_step->end}' style='z-index:{$zIndex}; background-color: #" . $base->bg['-'.$zIndex] . "; width: " . ($theme_step->end * $ratio) . "%;'><span>{$theme_step->end}</span></div>";
                            $zIndex++;
                }
                // now we add the score into it
                $x .= "<div class='themeBarScore' id='{$theme_score['theme_total']}' style='z-index:{$zIndex}; background-color: #" . $base->bg['+'.$zIndex] . "; width: " . ($theme_score['theme_total'] * $ratio) . "%;'><span>{$theme_score['theme_total']}</span></div>";
                           
                // just a visual add
                $x .= "<div class='themeBarPlaceholderBtm'></div>";
                $x .= "</div>";
        
        }
        
        $x .= "<div style='clear:both'></div>";
        return $x;
    }

    
    
    
   private function makeScanBarGraphs($scan_total){
        global $wpdb;
        
        // for this we include an extra file to generate our color bar
        $base = new CSS_Color('aebdd0');
        
      
        
        $zIndex=1;
        // first we check the theme score table. Based on the values we generate a nice step-graph
        $sql = "select 
                s.start, s.end
                from 
                {$wpdb->prefix}scan_scores s
                where parent_id =  %d
                order by s.end DESC"; 
        
         $safe_sql = $wpdb -> prepare($sql, $this -> scanID);
         $scan_steps = $wpdb -> get_results($safe_sql);
        

        // build a div pattern. Start with the largest number. This will be the under layer
        // each score step, smaller, will be placed on top of it
        
        // divide by ratio
        // since we ordered the list by "end" DESC, we know the first row is the largest value -> this is 100%
        $ratio = (100 / $scan_steps[0]->end );
        
        $x .= "<div style='clear:both'></div>";
        $x .= "<div class='themeBarLabel'>";
        
        
        $x .= "<span>Scan Score:</span>";
        
        $x .= "</div>";
        $x .= "<div class='themeBarPlaceholder'>";
        
        foreach($scan_steps as $scan_step){
            
                    $x .= "<div class='themeBarStep' id='{$scan_step->end}' style='z-index:{$zIndex}; background-color: #" . $base->bg['-'.$zIndex] . "; width: " . ($scan_step->end * $ratio) . "%;'><span>{$scan_step->end}</span></div>";
                    $zIndex++;
        }
        // now we add the score into it
        $x .= "<div class='themeBarScore' id='{$scan_total}' style='z-index:{$zIndex}; background-color: #" . $base->bg['+'.$zIndex] . "; width: " . ($scan_total * $ratio) . "%;'><span>{$scan_total}</span></div>";
                   
        // just a visual add
        $x .= "<div class='themeBarPlaceholderBtm'></div>";
        $x .= "</div>";
        
        
        
        $x .= "<div style='clear:both'></div>";
        return $x;
    }

    
    
    
    /*
     * this function only used for admin, when selecting the scan id
     * 
     */
     private function sqlGetUsers(){
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

            $safe_sql = $wpdb -> prepare($sql, $this -> scanID);
            $userList = $wpdb -> get_results($safe_sql);
            
            $this -> numberOfUsers = $wpdb -> num_rows;

    
        return $userList;
        
        
    }
     
     

}
