<?php
// The bps-maintenance-values.php file contains the actual data that is echoed here
include 'bps-maintenance-values.php';
header('HTTP/1.1 503 Service Temporarily Unavailable',true,503);
header('Status: 503 Service Temporarily Unavailable');
header('Retry-After:' . "$bps_retry_after" .''); 	
// header Retry After conversion times in seconds/hrs/days
// 3600=1hr 7200-2hrs 43200=12hrs 86400-24hrs 172800-48hrs 259200-72hrs 604800-168hrs-7days 2419200-672hrs-28days
// Retry After time is for telling the Search Engines when to return not to set the countdown timer display

/*	The Copyright, AITpro Software Products License Information and Credit Where Credit Is Due information below must remain
	intact or all BulletProof Security Pro warranties, guarantees, liabilities are void.
	
	Copyright (C) 2011 Edward Alexander, AIT-pro.com. All rights reserved.

	AITpro Software Products License Information:
	BY DOWNLOADING, INSTALLING, COPYING, ACCESSING, OR USING BulletProof Security Pro YOU AGREE TO THE TERMS OF THIS AGREEMENT. 
	IF YOU 	ARE ACCEPTING THESE TERMS ON BEHALF OF ANOTHER PERSON OR A COMPANY OR OTHER LEGAL ENTITY, YOU REPRESENT AND WARRANT
	THAT YOU HAVE FULL AUTHORITY TO BIND THAT PERSON, COMPANY, OR LEGAL ENTITY TO THESE TERMS. IF YOU DO NOT AGREE TO THESE TERMS,
	* DO NOT DOWNLOAD, INSTALL, COPY, ACCESS, OR USE BulletProof Security Pro; AND
	* PROMPTLY RETURN BulletProof Security Pro TO THE PARTY FROM WHOM YOU ACQUIRED IT. IF YOU DOWNLOADED BulletProof Security Pro
	FROM THE AITPRO WEBSITE, CONTACT AITPRO FOR A REFUND IF APPLICABLE.
	
	AITpro Software Products License Information continued:
	You agree to keep the AITpro Software Products License for BulletProof Security Pro, unmodified or altered in any way,
	with the original copy of BulletProof Security Pro that you have and any and all copies or partial copies of BulletProof
	Security Pro that You make. 

	Credit Where Credit Is Due:
	Bonus Code:
	The following bonus code scripts, snippets or example code do not make up the core coding of BulletProof Security Pro 
	and are not included in the price of BulletProof Security Pro as they are free code scripts, snippets or example code 
	and are added as Bonus Code features to BulletProof Security Pro. Bonus Code has been adapted, modified and recoded 
	to work for WordPress and BPS Pro where necessary.
	Maintenance Mode countdown timer code - Dynamic Countdown script - © Dynamic Drive
	DB String Finder code - AnyWhereInDB - author Nafis Ahmad
	DB Table Cleaner/Remover code - Copyright (c) 2009 Lester "GaMerZ" Chan
*/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Language" content="en-us">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<!-- <meta name="robots" content="noindex,nofollow"> ONLY use noindex with a 503
If this is a brand new website that has not been indexed before - I recommend that 
you never use noindex even if this is a for a brand new website --> 
<!-- ########################################################################## -->
<!-- ################ Adds Title to top browser window ######################## -->
<title><?php echo $bps_site_title; ?></title>
<!-- the CSS style must remain inline - an external stylesheet rel link will not work -->
<!-- using divs with positioning to allow each object to be positioned independently -->
<style type="text/css">
<!--
body { 
	font-family: Verdana, Arial, Helvetica, sans-serif;
	line-height: normal;
	background-color:#FFF;
}

p { font-family: Verdana, Arial, Helvetica, sans-serif; }

#countdowncontainer {
	position:relative; top:0px; left:0px;
}

#countdowncontainer2 {
	position:relative; top:0px; left:0px;
}

#website_domain_name {
	font-weight:bold;
	font-size:18px;
	position:relative; top:0px; left:0px;
}

/* if you want the table to be positioned in absolute center uncomment this CSS style */
/* be sure to comment out the duplicated .maintenance_table CSS class and #bps_mtable_div styles below */
/* .maintenance_table {
	width:500px;
	height:300px;
	border: solid #999999 2px;
	position:absolute;
	top:50%;
	left:50%;
	margin-top:-150px;
	margin-left:-250px;
	padding:10px;
	background-color: #E9E9E9;
}
*/

/* move the entire maintenance table to the static position you want */
/* by adding pixels to top: and left: Example top:100px left:100px   */
#bps_mtable_div {
	position:relative; top:0px; left:0px;
	margin:0 auto;
	width:100%;
}

.maintenance_table {
	width:500px;
	height:300px;
	border: solid #999999 2px;
	position:absolute;
	top:50px;
	left:50px;
	margin:0 auto;
	padding:10px;
	background-color: #E9E9E9;
}

#online_text1 {
	font-family:Verdana, Arial, Helvetica, sans-serif;
	position:relative; top:0px; left:0px;
}
#online_text2 {
	font-family:Verdana, Arial, Helvetica, sans-serif;
	position:relative; top:0px; left:0px;
}

#lcdstyle{ /*Example CSS to create LCD countdown look*/
	background-color:black;
	color: #00FF00;
	font: bold 18px MS Sans Serif;
	padding: 3px;
	position:relative; top:0px; left:0px;
}

#lcdstyle sup{ /*Example CSS to create LCD countdown look*/
	font-size: 80%
}
-->
</style>

<!-- Credit Where Credit Is Due:
Bonus Code:
The following Dynamic Countdown script bonus code does not make up the core coding of BulletProof Security Pro 
and is not included in the price of BulletProof Security Pro as this is a free code script, snippet or example 
code and is added as a bonus feature to BulletProof Security Pro.
Adapted, modified and recoded to work for WordPress and BPS Pro -->

<script type="text/javascript">
/***********************************************
* Dynamic Countdown script- © Dynamic Drive (http://www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit http://www.dynamicdrive.com/ for this script and 100s more.
***********************************************/

function cdtime(container, targetdate){
if (!document.getElementById || !document.getElementById(container)) return
this.container=document.getElementById(container)
this.currentTime=new Date()
this.targetdate=new Date(targetdate)
this.timesup=false
this.updateTime()
}

cdtime.prototype.updateTime=function(){
var thisobj=this
this.currentTime.setSeconds(this.currentTime.getSeconds()+1)
setTimeout(function(){thisobj.updateTime()}, 1000) //update time every second
}

cdtime.prototype.displaycountdown=function(baseunit, functionref){
this.baseunit=baseunit
this.formatresults=functionref
this.showresults()
}

cdtime.prototype.showresults=function(){
var thisobj=this

var timediff=(this.targetdate-this.currentTime)/1000 //difference btw target date and current date, in seconds
if (timediff<0){ //if time is up
this.timesup=true
this.container.innerHTML=this.formatresults()
return
}
var oneMinute=60 //minute unit in seconds
var oneHour=60*60 //hour unit in seconds
var oneDay=60*60*24 //day unit in seconds
var dayfield=Math.floor(timediff/oneDay)
var hourfield=Math.floor((timediff-dayfield*oneDay)/oneHour)
var minutefield=Math.floor((timediff-dayfield*oneDay-hourfield*oneHour)/oneMinute)
var secondfield=Math.floor((timediff-dayfield*oneDay-hourfield*oneHour-minutefield*oneMinute))
if (this.baseunit=="hours"){ //if base unit is hours, set "hourfield" to be topmost level
hourfield=dayfield*24+hourfield
dayfield="n/a"
}
else if (this.baseunit=="minutes"){ //if base unit is minutes, set "minutefield" to be topmost level
minutefield=dayfield*24*60+hourfield*60+minutefield
dayfield=hourfield="n/a"
}
else if (this.baseunit=="seconds"){ //if base unit is seconds, set "secondfield" to be topmost level
var secondfield=timediff
dayfield=hourfield=minutefield="n/a"
}
this.container.innerHTML=this.formatresults(dayfield, hourfield, minutefield, secondfield)
setTimeout(function(){thisobj.showresults()}, 1000) //update results every second
}

/////CUSTOM FORMAT OUTPUT FUNCTIONS BELOW//////////////////////////////

//Create your own custom format function to pass into cdtime.displaycountdown()
//Use arguments[0] to access "Days" left
//Use arguments[1] to access "Hours" left
//Use arguments[2] to access "Minutes" left
//Use arguments[3] to access "Seconds" left

//The values of these arguments may change depending on the "baseunit" parameter of cdtime.displaycountdown()
//For example, if "baseunit" is set to "hours", arguments[0] becomes meaningless and contains "n/a"
//For example, if "baseunit" is set to "minutes", arguments[0] and arguments[1] become meaningless etc

function formatresults(){
if (this.timesup==false){//if target date/time not yet met
var displaystring=arguments[0]+" days "+arguments[1]+" hours "+arguments[2]+" minutes "+arguments[3]+" seconds left until April 22, 2010 04:25:00"
} // else if target date/time met
else{ // ##################### Message 2 - ...will be online in... #########################
var displaystring="<div id='online_text2'><?php echo "$bps_message2"; ?></div>"
}
return displaystring
}

function formatresults2(){
if (this.timesup==false){ //if target date/time not yet met
var displaystring="<div id='lcdstyle'>"+arguments[0]+" <sup>days</sup> "+arguments[1]+" <sup>hours</sup> "+arguments[2]+" <sup>minutes</sup> "+arguments[3]+" <sup>seconds</sup></div>"
}
else{ // else if target date/time met
// #########################################################################################
var displaystring=""// Don't display any text #############################################
alert("<?php echo "$bps_countdown_completed_popup"; ?>")// Instead, perform a custom pop up message alert
}
return displaystring
}
</script>
</head>

<body background="<?php echo "$bps_body_background_image"; ?>">
<div id="bps_mtable_div">
<table border="2" cellpadding="10" cellspacing="0" class="maintenance_table">
  <tr>
    <td>
<?php
// #################### The www prefix of your domain name is not displayed #################
// #################### to Display www comment out $bps-hostname = str_replace ##############
$bps_hostname = $_SERVER['SERVER_NAME']; 
$bps_hostname = str_replace('www.', '', $bps_hostname); ?>
<div id="website_domain_name"><?php echo $bps_hostname; ?></div>

<!-- ########################################################################################
     ############ Message 1 - ...is performing maintenance... ############################### -->
<p><?php echo "<div id=\"online_text1\">" . "$bps_message1" . "</div><br>"; ?></p>

<div id="countdowncontainer"></div>
<br />
<div id="countdowncontainer2"></div>
<!-- ########################################################################################
     ############ Echos your current public IP address ###################################### -->
<p>Your IP Address is: <?php echo $_SERVER['REMOTE_ADDR']; ?></p>

<script type="text/javascript">
/* <![CDATA[ */
// ##########################################################################################
// ############## the date and time that your website started maintenance ###################
// ############## Time is entered in Military time ##########################################
var futuredate=new cdtime("countdowncontainer", "<?php echo "$bps_start_maintenance_date".' '."$bps_start_maintenance_time"; ?>")
//var futuredate=new cdtime("countdowncontainer", "January 25, 2011 16:25:00")
futuredate.displaycountdown("days", formatresults)

var currentyear=new Date().getFullYear()
//dynamically get this Christmas' year value. If Christmas already passed, then year=current year+1
var thischristmasyear=(new Date().getMonth()>=11 && new Date().getDate()>25)? currentyear+1 : currentyear
// ###########################################################################################
// ############## the date and time that your website maintenance will end maintenance #######
// ############## year is precalculated by +thischristmasyear+ ###############################
var christmas=new cdtime("countdowncontainer2", "<?php echo "$bps_end_maintenance_date".', '; ?>"+thischristmasyear+"<?php echo ' '."$bps_end_maintenance_time"; ?>")
christmas.displaycountdown("days", formatresults2)
/* ]]> */
</script>
</td>
</tr>
</table>
</div>
</body>
</html>