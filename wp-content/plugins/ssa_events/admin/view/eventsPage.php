<?php
/*
 * page used for managing the events
 * what to show
 *
 * table of current events
 * form for creating new or editing selected event
 * button to export attendess of event
 *
 */
?>

<?
/*
 * Get the current events in
 *
 */
global $wpdb;
$strColumns = "id, ename, efrom, till, amount";
$strColumnsArray = array('id', 'Event naam', 'Startdatum', 'Einddatum', 'Max', 'Inschrijvingen' );


$sql = "SELECT $strColumns,(select count(a.user_id) from {$wpdb->prefix}events_attendees a where status=2 and event_id=e.id) as inschrijvingen
FROM {$wpdb->prefix}events e";
$safe_sql = $wpdb -> prepare($sql);
$results = $wpdb -> get_results($safe_sql);

$x = "<table class='wp-list-table widefat '>";
$x .= "<thead><tr>";

foreach ($strColumnsArray as $column) {

    $x .= "<th>" . $column . "</th>";

}

$x .= "</tr></thead><tbody>";
foreach ($results as $row) {

    
    
    $x .= '<tr class="row" id="' . $row->id . '"> 
            <td>' . $row->id . '</td>
            <td>' . $row->ename . '
            <div class="row-actions">
            <span class="inline"><a href="javascript:;" id="' . $row->id . '" name="' . $row->id . '" class="editevent" title="Wijzig Event">Bekijk Event</a> | </span>
            <span class="trash"><a href="javascript:;" id="' . $row->id . '" name="event" class="delete" title="Move this item to the Trash">Delete</a> </span>
            </div>
            </td>
            <td>' . date('d-m-Y H:i', strtotime($row -> efrom)) . '</td>
            <td>' . date('d-m-Y H:i', strtotime($row -> till)) . '</td>
            <td>' . $row->amount . '</td>
            <td>' . $row->inschrijvingen . '</td>
        </tr>';
   

}
$x .= "</tbody></table>";
?>

<style>
#wrap {
width:98%;
}
#add_form_wrap{

background-color: #f9f9f9;
width:94%;
margin:10px;
padding: 0px;
border: 1px solid #d9d9d9;

overflow:hidden;
clear: both;
display: none;

}
#add_form_header, #add_form {

background:  url( <?=EVENTS_URL?>/makeup/btm.png) bottom left repeat-x;
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
float:left;
}

#load {

background: transparent url( <?=EVENTS_URL?>/makeup/load.gif) no-repeat;
height: 100px;
width: 100px;
float: left;
position: fixed;
top: 80%;
left: 50%;
display : none;

}
#helper {
    margin-top:30px;
}
#helper ol li {
    
   
}
</style>
<div id="wrap">
	<div id="load"></div>
	<div style="background:#f9f9f9;border:1px solid #CCC;padding:10px;margin-top:15px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">
		<div id="icon-users" class="icon32">
			<br/>
		</div>
		<h2>Beheer uw Events</h2>
	</div>
	<div id="add_form_wrap">
		<div id="add_form"></div>
	</div>
	<button class='add button' id='newEvent' name='events'>
		Nieuw
	</button>

	<div id="events_table_wrap">
		<div id="events_table">
			<?=$x?>
		</div>
	</div>
	
	<div id="helper">
	    <h3>Events toevoegen in de site</h3>
	    <ol>
	        <li>Creeer een nieuw event. Voeg titel , begin/eind tijd en de max aantal deelnemers toe. Sla op</li>
	        <li>Nieuwe event is zichtbaar in tabel. Noteer hier het id van. Deze gebruiken we in de post.</li>
	        <li>Ga naar de gewenste post of pagina. Vul hier de shortcode toe, en sla op.
	           a<pre>[event id='x']</pre> (waarbij x de id is van stap 2)</li>
	        <li>Event is nu zichtbaar. Gebruikers kunnen zich registreren</li>
	        <li>Bekijk hier de event om de lijst met inschrijvingen in te kijken en te exporteren</li>
	    </ol>
	</div>
</div><!-- wrap -->