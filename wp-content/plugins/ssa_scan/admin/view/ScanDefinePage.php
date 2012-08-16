<?php

/*
 * Showing the Define page, it ALL happens here
 *
 * lets go crazy on the AJAX stuff
 * show them!! oke, my bad... lets be normal again
 *
 */

/* what to do
 *
 * when main page:
 *
 *   show list of current scans
 *   add buttons: add new, edit, delete
 *
 *
 * when scan page:
 *
 *   show two lists:
 *   one list: defines scores
 *   add buttons: add new, edit, delete
 *
 *   one list: show themes
 *   add buttons: add new, edit, delete
 *
 *
 * when theme page:
 *
 *   one list: defines scores
 *   add buttons: add new, edit, delete
 *
 *   one list: show questions
 *   add buttons: add new, edit, delete
 *
 *
 * when question page:
 *
 *   one list: defines questions
 *   add buttons: add new, edit, delete
 *
 *
 */

// start up the engines
$getTable = new ssa_scan_AjaxGetTablesScan();
$getForm = new ssa_scan_AjaxGetFormScan();




// some defaults;
$basix_url = "?page=scans";
$scan_url = $basix_url . "&scan=" . $this -> input['scan'];
$theme_url = $scan_url . "&theme=" . $this -> input['theme'];
$qst_url = $theme_url . "&qst=" . $this -> input['qst'];
$breadcrum = "<a href='$basix_url'>Home</a>";
$main_page = false;
//Form header 
//$add_form_header = "<h3>Voeg toe of wijzig</h3>";


// check what page we need:
if ($this -> input['qst']) {

    // question id is filled
    // so it is a question page
    $attrs = array("what" => "getQuestion", "type" => "qst", "id" => $this -> input['qst'], "pid" => $this -> input['theme']);
    $child_header = "<h2>Vraag</h2>";
    $child_table = $getForm -> getEditForm($attrs);

    $breadcrum .= " > <a href='$scan_url'>scan</a>";
    $breadcrum .= " > <a href='$theme_url'>theme</a>";
    $breadcrum .= " > <a href='$qst_url'>vraag</a>";
    
       

} else if ($this -> input['theme']) {

    // theme id is filled
    // so it is a theme page
    
    //show the scan details
    $attrs = array("what" => "getTable", "type" => "theme", "id" => $this -> input['theme'],  "pid" => $this -> input['scan']);
    $details_header = "<h2>Thema details</h2>";
    $details_table = $getForm -> getEditForm($attrs);
    
    //show the theme questions
    $attrs = array("what" => "getQuestion", "type" => "qst", "id" => false, "pid" => $this -> input['theme']);
    $child_header = "<h2>Vragen binnen dit thema</h2>";
    $child_add = "<button class='add button' id='getQuestion' name='qst'>Nieuw</button>";
    $child_table = $getTable -> getRecords($attrs);

    //show the theme scores
    $attrs = array("what" => "getScore", "type" => "theme", "id" => false, "pid" => $this -> input['theme']);
    $score_header = "<h2>Theme score definities</h2>";
    $score_add = "<button class='add button' id='getScore' name='theme'>Nieuw</button>";
    $score_table = $getTable -> getRecords($attrs);

    $breadcrum .= " > <a href='$scan_url'>scan</a>";
    $breadcrum .= " > <a href='$theme_url'>theme</a>";
    
    

} else if ($this -> input['scan']) {

    // scan id is filled
    // so it is a scan page

    //show the scan details
    $attrs = array("what" => "getTable", "type" => "scan", "id" => $this -> input['scan'], "pid" => null);
    $details_header = "<h2>Scan details</h2>";
    $details_table = $getForm -> getEditForm($attrs);
    
    
    //show the scan themes
    $attrs = array("what" => "getTable", "type" => "theme", "id" => false, "pid" => $this -> input['scan']);
    $child_header = "<h2>Thema's van deze scan</h2>";
    $child_add = "<button class='add button' id='getTable' name='theme'>Nieuw</button>";
    $child_table = $getTable -> getRecords($attrs);

    // show the scan scores
    $attrs = array("what" => "getScore", "type" => "scan", "id" => false, "pid" => $this -> input['scan']);
    $score_header = "<h2>Scan score definities</h2>";
    $score_add = "<button class='add button' id='getScore' name='scan'>Nieuw</button>";
    $score_table = $getTable -> getRecords($attrs);

    $breadcrum .= " > <a href='$scan_url'>scan</a>";
    
    

} else {

    // no ids filled
    // so it is a "main" page
    // get scan table in
    $attrs = array("what" => "getTable", "type" => "scan", "id" => null);
    $child_header = "<h2>Uw scans</h2>";
    $child_add = "<button class='add button' id='getTable' name='scan'>Nieuw</button>";
    $child_table = $getTable -> getRecords($attrs);
    $main_page = true;
}
?>

<style>
#wrap {
    width:98%;
}
#details_wrap, #child_wrap, #score_wrap, #add_form_wrap
{
    width:45%; margin:10px; padding: 10px; float:left;
    background: #f9f9f9 ;
    border: 1px solid #999;
    border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;
}
#score_wrap {
    width:94%;
}

#add_form_wrap{
    
    background-color: #f9f9f9;
    width:94%; 
    margin:10px;
    padding: 0px;
    
    overflow:hidden;
    clear: both;
    display: none;

    _float: left;
    _position: fixed;
    _top: 16%;
    _left: 16%;
    
}
#add_form_header, #add_form {

    background:  url( <?=SCAN_URL?>/makeup/btm.png) bottom left repeat-x;
    overflow:hidden;
    padding:10px;
    width:100%;
    
}
#add_form_header {
    border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;
    background-color: #5a759b;
    color: white;
    width: 94%;
}

button {
    
    margin:20px;
    float:right;
}

#load {

    background: transparent url( <?=SCAN_URL?>/makeup/load.gif) no-repeat;
    height: 100px;
    width: 100px;
    float: left;
    position: fixed;
    top: 80%;
    left: 50%;
    display : none;
    
}
</style>
<div id="wrap">
	<div id="load"></div>
	<div style="background:#f9f9f9;border:1px solid #CCC;padding:10px;margin-top:15px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">
		<div id="icon-users" class="icon32">
			<br/>
		</div>
		<h2> Beheer uw scans</h2>
	</div>
	<div id="bread_crumb">
		<?=$breadcrum
		?>
	</div>
	
	 <div id="add_form_wrap">
    
    <div id="add_form"></div>
    </div> 
    
    
    <?if(!$main_page){?>
    <div id="details_wrap" >
        <?=$details_header
        ?>
        
        <?=$details_table
        ?>
    </div>
    <?}?>
    
    
	<div id="child_wrap" >
		<?=$child_add
        ?>
        <?=$child_header
		?>
		
		<?=$child_table
		?>
	</div>
	
	
	 <?if(!$main_page){?>
	<div id="score_wrap">
		<?=$score_add
        ?>
        <?=$score_header
		?>
		
		<?=$score_table
		?>
	</div>
	<?}?>
	
   
</div>