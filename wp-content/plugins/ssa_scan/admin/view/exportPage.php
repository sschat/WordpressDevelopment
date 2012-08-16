<?php
function cleanData($str)
// clean the data before using it in the export files
  {
    if($str == 't') $str = 'TRUE';
    if($str == 'f') $str = 'FALSE';
    if(preg_match("/^0/", $str) || preg_match("/^\+?\d{8,}$/", $str) || preg_match("/^\d{4}.\d{1,2}.\d{1,2}/", $str)) {
      $str = "'$str";
    }
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
    
    return $str;
  }
  
  
// get the FPDF ready
require (SCAN_DIR . '/fpdf/fpdf.php');

// type tells what export is will be (pdf / comma separeted / excel)
$type = $export;

// used for export as csv
$mailing_list = ''; $excel_list = '';
$options = get_option("event_options");
$delim = $options['export_delim'];
$delim = $delim ? $delim : ', ';
// end csv

//Select the Products you want to show in your PDF file
global $wpdb;

/*
 * get the users list and add them to the export
 *
 */
 



$sql = "select 
user_id, insert_timestamp,
sum(sr.answer) as score
from 
{$wpdb->prefix}scan_types st
join {$wpdb->prefix}scan_types st2
on st.id = st2.parent_id
join {$wpdb->prefix}scan_questions sq
on st2.id = sq.parent_id
join {$wpdb->prefix}scan_results sr
on sq.id = sr.question_id
where
st.type='scan'
and
st.id=%d
group by sr.user_id";

$safe_sql = $wpdb -> prepare($sql, $SCANID);
$listUsers = $wpdb -> get_results($safe_sql);

//$listAttendees_count = $wpdb -> num_rows;
/*
 * get the SCAN details
 *
 */
$sql = "SELECT naam, descr FROM {$wpdb->prefix}scan_types WHERE id = %d";
$safe_sql = $wpdb -> prepare($sql, $SCANID);
$scanDetails = $wpdb -> get_row($safe_sql);

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
$y_axis_initial = 39;
$y_axis = $y_axis_initial;

//print column titles
$pdf -> SetFillColor(243, 243, 243);
$pdf -> SetFont('Arial', 'B', 10);

/*
 * the event HEADER
 *
 */
$pdf -> Cell(105, 10, 'SCAN : ' . $scanDetails -> naam, 1, 0, 'C', 0);


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
$pdf -> SetX(155);
$pdf -> SetFont('Arial', 'b', 8);
$pdf -> Cell(28, 6, 'Export aangemaakt: ', 0, 0, 'L');
$pdf -> SetFont('Arial', '', 8);
$pdf -> Cell(25, 6, date("j M Y"), 0, 1, 'L');

// pdf file details
$pdf -> SetTitle('Scan : ' . $scanDetails -> naam);
$pdf -> SetAuthor($current_user -> display_name);
$pdf -> SetCreator('WP Plugin "Events" on ' . home_url() . ' (by Yeonactivities)');



// column header
$pdf -> SetFont('Arial', 'B', 10);

$pdf -> SetY($y_axis_initial);
$pdf -> SetX(10);
$pdf -> Cell(10, 6, 'NR', 1, 0, 'L', 1);
$pdf -> Cell(80, 6, 'NAAM', 1, 0, 'L', 1);
$pdf -> Cell(20, 6, 'SCORE', 1, 0, 'L', 1);
$pdf -> Cell(50, 6, 'EMAIL', 1, 0, 'R', 1);
$pdf -> Cell(30, 6, 'SCANDATUM', 1, 0, 'R', 1);

//initialize counter
$i = 0;

//Set maximum rows per page
$max = 20;

//Set Row Height
$row_height = 8;

$y_axis = $y_axis + $row_height;

$count = 0;
foreach ($listUsers as $attendee) {

    $user_data = get_userdata($attendee -> user_id);

    $count++;
    //If the current row is the last one, create new page and print column title
    if ($i == $max) {
        $pdf -> AddPage();

        //print column titles for the current page
        $pdf -> SetY($y_axis_initial);
        $pdf -> SetX(10);
        $pdf -> Cell(10, 6, 'NR', 1, 0, 'L', 1);
        $pdf -> Cell(80, 6, 'NAAM', 1, 0, 'L', 1);
        $pdf -> Cell(20, 6, 'SCORE', 1, 0, 'L', 1);
        $pdf -> Cell(50, 6, 'EMAIL', 1, 0, 'R', 1);
        $pdf -> Cell(30, 6, 'SCANDATUM', 1, 0, 'R', 1);

        //Go to next row
        $y_axis = $y_axis + $row_height;

        //Set $i variable to 0 (first row)
        $i = 0;
    }

    $id = $count;
    $name = $user_data -> first_name . " " . $user_data -> last_name;
    $email = $user_data -> user_email;
    $score = $attendee -> score;
    $mailing_list .= $email . $delim;
    
    $date = date('d-m-Y H:i', strtotime($attendee -> insert_timestamp));
    
    $excel_list_raw = $name . "\t" . $email . "\t" . $score . "\t" . $date . "\r\n";
    $excel_list .= cleanData($excel_list_raw); 
    
   
    $pdf -> SetY($y_axis);
    $pdf -> SetX(10);
    $pdf -> Cell(10, 6, $id, 1, 0, 'L', 1);
    $pdf -> Cell(80, 6, $name, 1, 0, 'L', 1);
    $pdf -> Cell(20, 6, $score, 1, 0, 'C', 1);
    $pdf -> Cell(50, 6, $email, 1, 0, 'R', 1);
    $pdf -> Cell(30, 6, $date, 1, 0, 'R', 1);

    //Go to next row
    $y_axis = $y_axis + $row_height;
    $i = $i + 1;
}

if ($type == 'e') {

    $excel_list_header = 'naam' . "\t" . 'email' . "\t" . 'score' . "\t" . 'datum scan' . "\r\n";
   
    $filename = 'export_' . strtolower(urlencode($scanDetails -> naam)) . '.xls';

    
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Content-Type: application/vnd.ms-excel");
  
    print $excel_list_header . $excel_list;

    exit ;

}

if ($type == 'c') {

    $mailing_list = trim($mailing_list, $delim);
   
    $filename = 'maillist_' . strtolower(urlencode($scanDetails -> naam)) . '.csv';

    header("Content-type: application/vnd.ms-excel");
    header("Content-disposition: csv" . date("Y-m-d") . ".csv");
    header("Content-disposition: filename=" . $filename );

    print $mailing_list;

    exit ;

}

if ($type == 'p') {
    //Send file
    //$pdf -> Output();
    $pdf -> Output('export_' . strtolower(urlencode($scanDetails -> naam)) . '.pdf', "D");

}
