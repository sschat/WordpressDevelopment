<?php

// get the FPDF ready
require (EVENTS_DIR . '/fpdf/fpdf.php');

$eventID = absint($_GET['e']);

// used for export as csv
$mailing_list = ''; 
$options = get_option("event_options");
$delim = $options['export_delim'];
$delim = $delim ? $delim : ', ';
// end csv

//Select the Products you want to show in your PDF file
global $wpdb;

/*
 * get the attendees list and add them to the export
 *
 */
$sql = "SELECT user_id, time_update FROM {$wpdb->prefix}events_attendees WHERE event_id = %d and status=2 order by time_update ASC";
$safe_sql = $wpdb -> prepare($sql, $eventID);
$listAttendees = $wpdb -> get_results($safe_sql);
$listAttendees_count = $wpdb -> num_rows;
/*
 * get the event details
 *
 */
$sql = "SELECT id, ename, efrom as event_from, till as event_till, amount, info FROM {$wpdb->prefix}events WHERE id = %d";
$safe_sql = $wpdb -> prepare($sql, $eventID);
$eventDetails = $wpdb -> get_row($safe_sql);

/*
 * get the current user details
 *
 */
global $current_user;
get_currentuserinfo();

//Create new pdf file
$pdf = new FPDF();

//Disable automatic page break
$pdf -> SetAutoPageBreak(false);

//Add first page
$pdf -> AddPage();

//set initial y axis position per page
$y_axis_initial = 45;
$y_axis = $y_axis_initial;

//print column titles
$pdf -> SetFillColor(243, 243, 243);
$pdf -> SetFont('Arial', 'B', 10);

/*
 * the event HEADER
 *
 */
$pdf -> Cell(105, 10, 'INSCHRIJVINGEN : ' . $eventDetails -> ename, 1, 0, 'C', 1);

$pdf -> SetFont('Arial', 'i', 9);
$pdf -> Cell(40, 10, 'Van : ' . date('d M Y H:i', strtotime($eventDetails -> event_from)), 0, 0, 'C');
$pdf -> Cell(40, 10, 'Tot : ' . date('d M Y H:i', strtotime($eventDetails -> event_till)), 0, 1, 'C');

// print line
$pdf -> Line(10, 25, 201, 25);

// export details
$pdf -> SetXY(10, 26);
$pdf -> SetFont('Arial', 'b', 8);
$pdf -> Cell(18, 6, 'Export voor: ', 0, 0, 'L');
$pdf -> SetFont('Arial', '', 8);
$pdf -> Cell(35, 6, get_bloginfo(), 0, 0, 'L');
$pdf -> SetFont('Arial', 'b', 8);
$pdf -> Cell(18, 6, 'Export door: ', 0, 0, 'L');
$pdf -> SetFont('Arial', '', 8);
$pdf -> Cell(35, 6, $current_user -> display_name, 0, 0, 'L');
$pdf -> SetXY(155, 26);
$pdf -> SetFont('Arial', 'b', 8);
$pdf -> Cell(28, 6, 'Export aangemaakt: ', 0, 0, 'L');
$pdf -> SetFont('Arial', '', 8);
$pdf -> Cell(25, 6, date("j M Y"), 0, 1, 'L');

// pdf file details
$pdf -> SetTitle('Inschrijvingen : ' . $eventDetails -> ename);
$pdf -> SetAuthor($current_user -> display_name);
$pdf -> SetCreator('WP Plugin "Events" on ' . home_url() . ' (by Yeonactivities)');

//table header
$pdf -> SetY($y_axis_initial - 8);
$pdf -> SetFont('Arial', '', 9);
$pdf -> Cell(30, 6, 'Inschrijvingen : ' . $listAttendees_count, 0, 0, 'L');
$pdf -> Cell(30, 6, 'Max: ' . $eventDetails -> amount, 0, 1, 'L');

// column header
$pdf -> SetFont('Arial', 'B', 10);

$pdf -> SetY($y_axis_initial);
$pdf -> SetX(10);
$pdf -> Cell(10, 6, 'NR', 1, 0, 'L', 1);
$pdf -> Cell(90, 6, 'NAAM', 1, 0, 'L', 1);
$pdf -> Cell(60, 6, 'EMAIL', 1, 0, 'R', 1);
$pdf -> Cell(30, 6, 'DATUM', 1, 0, 'R', 1);

//initialize counter
$i = 0;

//Set maximum rows per page
$max = 20;

//Set Row Height
$row_height = 8;

$y_axis = $y_axis + $row_height;

$count = 0;
foreach ($listAttendees as $attendee) {

    $user_data = get_userdata($attendee -> user_id);

    $count++;
    //If the current row is the last one, create new page and print column title
    if ($i == $max) {
        $pdf -> AddPage();

        //print column titles for the current page
        $pdf -> SetY($y_axis_initial);
        $pdf -> SetX(10);
        $pdf -> Cell(10, 6, 'NR', 1, 0, 'L', 1);
        $pdf -> Cell(90, 6, 'NAAM', 1, 0, 'L', 1);
        $pdf -> Cell(60, 6, 'EMAIL', 1, 0, 'R', 1);
        $pdf -> Cell(30, 6, 'DATUM', 1, 0, 'R', 1);

        //Go to next row
        $y_axis = $y_axis + $row_height;

        //Set $i variable to 0 (first row)
        $i = 0;
    }

    $id = $count;
    $name = $user_data -> first_name . " " . $user_data -> last_name;
    $email = $user_data -> user_email;
    
    $mailing_list .= $email . $delim;
    
    $date = date('d-m-Y H:i', strtotime($attendee -> time_update));

    $pdf -> SetY($y_axis);
    $pdf -> SetX(10);
    $pdf -> Cell(10, 6, $id, 1, 0, 'L', 1);
    $pdf -> Cell(90, 6, $name, 1, 0, 'L', 1);
    $pdf -> Cell(60, 6, $email, 1, 0, 'R', 1);
    $pdf -> Cell(30, 6, $date, 1, 0, 'R', 1);

    //Go to next row
    $y_axis = $y_axis + $row_height;
    $i = $i + 1;
}

if ($type == 'c') {

    $mailing_list = trim($mailing_list, $delim);
   
    $filename = 'export_' . strtolower(urlencode($eventDetails -> ename)) . '.csv';

    header("Content-type: application/vnd.ms-excel");
    header("Content-disposition: csv" . date("Y-m-d") . ".csv");
    header("Content-disposition: filename=" . $filename );

    print $mailing_list;

    exit ;

}

if ($type == 'p') {
    //Send file
    //$pdf -> Output();
    $pdf -> Output('export_' . strtolower(urlencode($eventDetails -> ename)) . '.pdf', "D");

}
