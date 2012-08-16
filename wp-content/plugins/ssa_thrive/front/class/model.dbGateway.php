<?php

class dbGateway {

    private $user = null;
    private $days = null;
    private $minDate = null;
    private $object_id = null;
    private $options = array();

    public function __construct() {

        $this -> options = get_option('thrive_options');

    }

    public function setUser($in) {
        $this -> user = $in;
    }

    public function setDays($in) {
        $this -> days = $in;
    }

    public function setMinDate($in) {
        $this -> minDate = $in;
    }

    public function setObjectId($in) {
        $this -> object_id = $in;
    }

    public function getUserStatus($id) {

        return $this -> _getUserData($id);

    }

    public function setUserStatus($post, $count) {

        
       return $this -> _setUserData($post,$count);

    }



    public function checkObjectPost() {
        global $wpdb;

        $sql = 'SELECT 
                           count(id) 
                           FROM ' . $wpdb -> posts . '
                           where post_author="' . $this -> user . '" 
                           and post_status in ("publish", "pending") 
                           and post_type="post" ';

        if ($this -> days)
            $sql .= 'and  date_format(post_date, "%Y-%m-%d") >= "' . date('Y-m-j', $this -> minDate) . '"';

        if ($this -> options['min_words'])
            $sql .= ' and LENGTH(post_content) - LENGTH(REPLACE(post_content, " ", ""))+1 > ' . $this -> options['min_words'];

        if ($this -> options['approved'])
            $sql .= ' and post_status = "publish" ';

        // Get results
        return $wpdb -> get_var($sql);

    }

    public function checkObjectComment() {
        global $wpdb;

        $sql = 'SELECT count(comment_id) as count
                       FROM ' . $wpdb -> comments . '
                       where user_id="' . $this -> user . '" 
                       and comment_approved in("1", "0") 
                       ';

        if ($this -> days)
            $sql .= 'and  date_format(comment_date, "%Y-%m-%d") >= "' . date('Y-m-j', $this -> minDate) . '"';

        if ($this -> options['min_words'])
            $sql .= ' and LENGTH(comment_content) - LENGTH(REPLACE(comment_content, " ", ""))+1 > ' . $this -> options['min_words'];

        if ($this -> options['approved'])
            $sql .= ' and comment_approved = 1';

        if ($this -> object_id)
            $sql .= ' and comment_post_ID = ' . $this -> object_id;

        // Get results
        return $wpdb -> get_var($sql);

    }

    private function _getUserData($id) {
        global $wpdb;

        $sql = 'SELECT *
               FROM ' . $wpdb -> prefix . 'thrive_users
               where user_id="' . $id . '" 
               ';

        // Get results
        return $wpdb -> get_results($sql);

    }

    private function _setUserData($post, $count) {
        global $wpdb;
      
        $wpdb->query($wpdb->prepare(
            "INSERT INTO {$wpdb->prefix}thrive_users
            (  user_id, post_id, unlocked )
            VALUES ( %s, %s, %s)
            ON DUPLICATE KEY
            UPDATE
            
            user_id=%s,
            post_id=%s,
            unlocked=%s
            "
            , $this -> user, $post, $count
            , $this -> user, $post, $count
            
            ));

        return true;
    }

    public function __destruct() {
        foreach ($this as $key => $value) {
            unset($this -> $key);
        }
    }

}
