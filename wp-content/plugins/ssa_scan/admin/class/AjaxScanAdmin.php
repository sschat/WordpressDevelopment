<?php

/*
 * define all functions which will be called by ajax
 *
 */

class ssa_scan_AjaxScanAdmin {

    // some defaults
    private $what = null;
    private $type = null;
    private $id = null;
    private $results = array();

    private $columnCount = 0;



    public function __construct() {
        

    }

 

    public function setInfo($attrs) {

        $this -> what = $attrs['what'];
        $this -> type = $attrs['type'];
        $this -> id = $attrs['id'];

    }

  


    public function __destruct() {
        
        foreach ($this as $key => $value) {
            unset($this -> $key);
        }
    }

}
