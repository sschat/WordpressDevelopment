<?php
//============================================================+
// File name   : example_011.php
// Begin       : 2008-03-04
// Last Update : 2010-08-08
//
// Description : Example 011 for TCPDF class
//               Colored Table
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com s.r.l.
//               Via Della Pace, 11
//               09044 Quartucciu (CA)
//               ITALY
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Colored Table
 * @author Nicola Asuni
 * @since 2008-03-04
 */

include('../../../../wp-blog-header.php');
require_once('../tcpdf/config/lang/nld.php');
require_once('../tcpdf/tcpdf.php');
//require '../engine/sqls.php';



// extend TCPF with custom functions
class MYPDF extends TCPDF {

	// Load table data from file
	public function LoadData($file) {
		// Read file lines
		$lines = file($file);
		$data = array();
		foreach($lines as $line) {
			$data[] = explode(';', chop($line));
		}
		return $data;
	}

	// Colored table
	public function ColoredTable($header,$return) {
		// Colors, line width and bold font
		$this->SetFillColor(49, 147, 41);
		$this->SetTextColor(255);
		$this->SetDrawColor(128, 0, 0);
		$this->SetLineWidth(0.3);
		$this->SetFont('', 'B');
		// Header
		$w = array(130, 35, 20);
		$num_headers = count($header);

                for($i = 0; $i < $num_headers; ++$i) {
			$this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
		}

		$this->Ln();
		// Color and font restoration
		$this->SetFillColor(224, 235, 255);
		$this->SetTextColor(0);
		$this->SetFont('');
		// Data
		$fill = 0;
		foreach($data as $row) {
			$this->Cell($w[0], 6, $row[0], 'LR', 0, 'L', $fill);
			$this->Cell($w[1], 6, $row[1], 'LR', 0, 'L', $fill);
			$this->Cell($w[2], 6, number_format($row[2]), 'LR', 0, 'R', $fill);

			$this->Ln();
			$fill=!$fill;
		}
		$this->Cell(array_sum($w), 0, '', 'T');
	}
}


// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 011');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 9);


$date_start =  $_POST['date_start'];
$date_end =   $_POST['date_end'];

//============================================================+
// Trainer activiteit: Geplaatste vragen en de reacties
//============================================================+


// add a page
$pdf->AddPage();


// create some HTML content
$html ="
<div>
<h2>Trainers activiteit: Geplaatste berichten</h2>
<p>Periode: $date_start tot $date_end</p>
</div>
";

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');


$results = get_results(3, $date_start, $date_end, 10, true);

$return = '';
foreach ( $results as $row ){

        $print = ($vraag==$row->title . $row->vraag?"":$row->title . $row->vraag);

        $return .= '<tr  >';
        $return .= '<td width="45" >' . $row->trainer . '</td>';
        $return .= '<td width="130" >' . $print . '</td>';
        $return .= '<td width="75" >' . $row->cdate . '</td>';
        $return .= '<td width="75" >' . $row->schrijver . '</td>';
        $return .= '<td width="300" >' . $row->content . '</td>';
        $return .= '<td width="25" >' . $row->love . '</td>';
        $return .= '</tr>';

        $vraag=$row->title . $row->vraag;

}
$tbl = <<<EOD
<table border="1" cellpadding="2" cellspacing="2" align="left">
<tr>
<th style="font-weight:bold" width="45" >Trainer</th>
<th style="font-weight:bold" width="130" >Vraag</th>
<th style="font-weight:bold" width="75" >Datum</th>
<th style="font-weight:bold" width="375" >Reactie</th>
<th style="font-weight:bold" width="25" >+</th>
</tr>
$return
</table>
EOD;


// output the HTML content
$pdf->writeHTML($tbl, true, false, true, false, '');


//============================================================+
// Training activiteit: geplaatste reacties // Disabled for now!
//============================================================+
// add a page
//$pdf->AddPage();

// create some HTML content
$html ="
<div>
<h2>Trainers activiteit: Geplaatste reacties</h2>
<p>Periode $date_start tot $date_end</p>
</div>
";

// output the HTML content
//$pdf->writeHTML($html, true, false, true, false, '');


//Column titles
$header = array('Schrijver', 'Title', 'Datum');


$results = get_results(4, $date_start, $date_end, 10, true);
$return = '';
foreach ( $results as $row ){

        $return .= '<tr  >';
        $return .= '<td width="40" >' . $row->schrijver . '</td>';
        $return .= '<td width="100" >' . $row->title . '</td>';
        $return .= '<td width="450" >' . $row->bericht . '</td>';
        $return .= '<td width="50" >' . $row->date . '</td>';
        $return .= '</tr>';

}
$tbl = <<<EOD
<table border="1" cellpadding="2" cellspacing="2" align="left">
<tr>
<th width="40" style="font-weight:bold">Schrijver</th>
<th width="100" style="font-weight:bold">Titel</th>
<th width="450" style="font-weight:bold">Bericht</th>
<th width="50" style="font-weight:bold">Datum</th>
</tr>
$return
</table>
EOD;

// output the HTML content
//$pdf->writeHTML($tbl, true, false, true, false, '');

//============================================================+
// Berichten met de meeste reacties
//============================================================+

// add a page
$pdf->AddPage();


// create some HTML content
$html ="
<div>
<h2>Meest besproken bericht</h2>
<p>Periode $date_start tot $date_end</p>
</div>
";

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');


//Column titles
$header = array('Title', 'Schrijver', 'bekeken');


$results = get_results(2, $date_start, $date_end, 10, true);
$return = '';
foreach ( $results as $row ){

        $return .= '<tr  >';
        $return .= '<td >' . $row->title . '</td>';
        $return .= '<td >' . $row->schrijver . '</td>';
        $return .= '<td >' . $row->count . '</td>';
        $return .= '</tr>';

}
$tbl = <<<EOD
<table border="1" cellpadding="2" cellspacing="2" align="left">
<tr>
<th style="font-weight:bold">Title</th>
<th style="font-weight:bold">Schrijver</th>
<th style="font-weight:bold">Aantal</th>
</tr>
$return
</table>
EOD;


// output the HTML content
$pdf->writeHTML($tbl, true, false, true, false, '');


//============================================================+
// END OF FILE
//============================================================+
//Close and output PDF document
$pdf->Output('example_011.pdf', 'I');