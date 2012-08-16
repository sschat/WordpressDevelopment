<?php

class Rule_Table extends WP_List_Table {

   
    function __construct(){
        global $status, $page;

        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'rule',     //singular name of the listed records
            'plural'    => 'rules',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );

    }


    function column_default($item, $column_name){
        switch($column_name){
            case 'ID':
            case 'name':
            case 'descr':
            case 'message':
            case 'group_name':
            case 'moment':
            case 'weight':    
           
                return $item[$column_name];
            default:
                return print_r($item,true); //Show the whole array for troubleshooting purposes
        }
    }

    function column_name($item){

        //Build row actions
        $actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&rule=%s">Edit</a>',$_REQUEST['page'],'edit',$item['ID']),
            'delete'    => sprintf('<a href="?page=%s&action=%s&rule=%s">Delete</a>',$_REQUEST['page'],'delete',$item['ID']),
        );

        //Return the title contents
        return sprintf('%1$s %3$s',
            /*$1%s*/ $item['name'],
            /*$2%s*/ $item['ID'],
            /*$3%s*/ $this->row_actions($actions)
        );
    
        
    }

  
    function column_xx($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item['ID']                //The value of the checkbox should be the record's id
        );
    }



    function get_columns(){
        $columns = array(
            //'ID'        => '<input type="checkbox" />', //Render a checkbox instead of text
            'ID'        => 'ID', 
            'name'     => 'Name',
            'descr'    => 'Description',
            'group_name' => 'Group',
            'moment' => 'Moment',
            'weight' => 'Weight'
        );
        return $columns;
    }

   
    function get_sortable_columns() {
        $sortable_columns = array(
            'ID'  => array('ID', true),
            'name'     => array('name', false),     //true means its already sorted
            'group_name'    => array('group_name',false),
            'moment'  => array('moment',false),
            'weight'  => array('weight',false)
            
        );
        return $sortable_columns;
    }

    function get_bulk_actions() {
        $actions = array(
            'delete'    => 'Delete'
        );
        return false;
        return $actions;
    }

    function prepare_items() {
        global $wpdb;

        $per_page = 99;


        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();


   
        $this->_column_headers = array($columns, $hidden, $sortable);


     
        $sql = 'SELECT r.id as ID, r.name, r.descr , r.message, r.group_id, g.name as group_name, r.moment, r.weight           

                    FROM netwerk_game_rules r
                    left join netwerk_game_groups g on r.group_id = g.id
                    ;';

        $data = $wpdb -> get_results($sql, ARRAY_A );

    
        function usort_reorder($a,$b){
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'ID'; //If no sort, default to title
            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'desc'; //If no order, default to asc
            $result = strcmp($a[$orderby], $b[$orderby]); //Determine sort order
            return ($order==='asc') ? $result : -$result; //Send final sort direction to usort
        }
        usort($data, 'usort_reorder');


        $current_page = $this->get_pagenum();

   
        $total_items = count($data);

       
        $data = array_slice($data,(($current_page-1)*$per_page),$per_page);


        $this->items = $data;

      
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
        ) );
    }

}


