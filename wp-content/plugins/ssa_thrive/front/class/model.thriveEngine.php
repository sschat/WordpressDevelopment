<?php

class ThriveEngine {

    private $result = array();
    private $results = array();
    private $user = null;

    private $message = array();
    private $options = array();
    private $minDate = null;
    private $object = null;
    private $object_id = null;
    private $days = null;
    private $failed = false;

    private $used_posts = array();
    private $engine = null;
    private $listPassedRules = array();
    private $listUnlockedPosts = array();
    private $thrive_user_list = array();

    public $message_description = null;

    public function __construct() {

        global $current_user;
        global $wpdb;
        get_currentuserinfo();

        $this -> user = $current_user -> ID;
        $this -> thrive_table = $wpdb -> prefix . "thrive_rules";
        $this -> options = get_option('thrive_options');
        $this -> used_posts = get_option('thrive_used_posts');
        $this -> thrive_user_list = get_option('thrive_user_list');
        
        $this -> dbGate = new dbGateway;
        $this -> dbGate -> setUser($this -> user);
        
    }

    public function getResults() {
        return $this -> results;
    }

    public function getUnlockedPosts() {

        return $this -> listUnlockedPosts;

    }

    public function hasFailed() {
        return $this -> failed;
    }

    /**
     * @parameter $rule_id (array)
     *
     * @value true or false
     *
     */
    public function checkRule($id) {
        global $wpdb;
        
        
        
        
        if ($id == 'all') {

            $where = "";

        } else {

            $where = "where r.rule_id in ($id)";
        }

        $pricerulesSql = "select * from {$this->thrive_table} r $where ";
        $pricerules = $wpdb -> get_results($pricerulesSql);



        foreach ($pricerules as $key => $rule) {

            $id = trim($rule -> rule_id);
            $this -> object = $rule -> object;
            $amount = $rule -> amount;
            $this -> days = $rule -> days;
            $this -> object_id = $rule -> object_id;

            // if days, what is the actual min date the object has to be created
            $this -> minDate = strtotime("-{$this -> days} days", time());

            
            
            $this -> dbGate  -> setDays($this -> days);
            $this -> dbGate  -> setMinDate($this -> minDate);
            $this -> dbGate  -> setObjectId($this -> object_id);
            
            


            // posts
            switch ($this -> object) {

                case 'post' :
                    $count = ($this -> dbGate  -> checkObjectPost());
                    break;

                case 'comment' :
                    $count = $this -> dbGate  -> checkObjectComment();
                    break;

                case 'login' :
                    $count = 0;
                    $amount = 1;
                    // if we find a user, let them pass this rule
                    if ($this -> user > 0) {
                        $amount = -1;
                    }

                    break;

                default :
                // let this rule fail
                    $count = 0;
                    $amount = 10;
                    break;
            }

            //Compare results
            if ($count >= $amount) {

                $passed = true;

            } else {

                $passed = false;
                $this -> failed = true;

            }

            // Build the result block

            $results[$id]['passed'] = $passed;

            $results[$id]['count'] = $count;
            $results[$id]['amount'] = $amount;
            $results[$id]['object'] = $this -> object;
            $results[$id]['object_id'] = $this -> object_id;
            $results[$id]['message'] = $rule -> message;
            $this -> results = $results;

        }// end foreach rule

    }

   

    public function checkUser($cache = true) {

        // check if we already have some data. Use it, if so
        if ($cache && $this -> dbGate ->getUserStatus($this -> user)) {

            $currentStatus = $this -> dbGate ->getUserStatus($this -> user);

            foreach($currentStatus as $row => $value){
                
               $this -> listUnlockedPosts[$value->post_id]['unlocked'] = $value->unlocked;
               
            }
            
           return "cached";

        }

        
        // we didnt find any data or need to refresh it. So lets generate it.
        $this -> checkRule('all');

        // get a list of the PassedRules of this user
        foreach ($this->results as $rule => $value) {
            if ($value['passed'])
                $this -> listPassedRules[] = $rule;
        }



        // compare the list of PassedRules to posts who have a rule
        if($this->used_posts){
            foreach ($this->used_posts as $post => $value) {

                $ruleList = array();
                if ($value)
                    $ruleList = explode(",", $value);

                //all Ids in a ruleList need to be in the PassedRules to see it as unlocked
                $results = array_diff($ruleList, $this -> listPassedRules);

                // save the results to the DB
                $ids = implode(",", $results);

                $this -> dbGate -> setUserStatus($post, $ids);
                $this -> listUnlockedPosts[$post]['unlocked'] = ($ids);

            }
        }
        
       

    }

   

    public function __destruct() {
        foreach ($this as $key => $value) {
            unset($this -> $key);
        }
    }

}
