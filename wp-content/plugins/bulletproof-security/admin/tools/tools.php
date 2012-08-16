<?php
// Direct calls to this file are Forbidden when core files are not present
if ( !function_exists('add_action') ){
		header('Status: 403 Forbidden');
		header('HTTP/1.1 403 Forbidden');
		exit();
}
 
if ( !current_user_can('manage_options') ){ 
		header('Status: 403 Forbidden');
		header('HTTP/1.1 403 Forbidden');
		exit();
}

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


<div id="message" class="updated" style="border:1px solid #999999; margin-left:104px; margin-top:9px;">
<?php
// S-Monitor First Launch Notification - displayed in BPS Only
echo bps_first_launch_internal_notice();

// S-Monitor HUD - Heads Up Display - Warnings and Error messages - displayed in BPS Only
echo bps_HUD_bps_only();

// S-Monitor Upgrade Notification - displayed in BPS Only
echo bpsPro_update_bps_only_nag();

// S-Monitor BPS Security Status for Root and wp-admin (wp-admin only if problem) - displayed in BPS Only
echo bps_security_status_bps_only();
echo bps_security_status_wpadmin_bps_only();

// S-Monitor PHP Error Log Set Check - displayed in BPS Only
echo bps_smonitor_php_elog_bps();

// S-Monitor PHP Error Log - New PHP Errors - displayed in BPS Only
echo bps_smonitor_ELogModTimeDiff_bps();

// S-Monitor Check if a Php.ini file has been created or added to a File Manager slot - displayed in BPS Only
echo bps_smonitor_phpini_bps();

// S-Monitor F-Lock - Check if F-Lock options saved - F-Lock Lock / Unlock File Status & actual file permissions 404 or 400
echo bps_smonitor_flock_bps();

// Manually runs real-time BPS Pro version update check
echo bpsPro_update_checks();
// Manually runs PHP Error Log cron function - for testing ONLY
// echo bps_smonitor_ELogModTimeDiff_wp_email();

// General all purpose "Settings Saved." message for forms - /includes/class.php
if (current_user_can('manage_options')) {
if (@$_GET['settings-updated'] == true) {
_e('<font color="green"><strong><p>Settings Saved.</p></strong></font>');
	} else {
	_e('<font color="red"><strong>'.bps_cuser_errors().'</strong></font>');
	}
}

// Zip the bps_b64_decode.txt file to the B64.zip Archive file for safe download
if (isset($_POST['submit-bps-zip-b64']) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_zip_b64' );

	$bpsB64TXT = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/tools/bps_b64_decode.txt';
	$bpsB64Zip = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/tools/B64.zip';
	$zipB64 = new ZipArchive;
	
	if ($zipB64->open($bpsB64Zip) === TRUE) {
    $zipB64->addFile($bpsB64TXT, 'bps_b64_decode--'.date("n-d-Y--H:i").'.txt');
    $zipB64->close();
    _e('<font color="green"><strong>The bps_b64_decode.txt file was zipped successfully. Click Download to download the B64.zip Archive file.</strong></font><br>');
	} else {
    _e('<font color="red"><strong>The bps_b64_decode.txt file was not zipped successfully.</strong></font><br>');
	}	
}

// Download the B64.zip Archive file
if (isset($_POST['bps-pro-b64-download']) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_b64_downloader' );

	$bpsB64Zip = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/tools/B64.zip';
	
	if (file_exists($bpsB64Zip)) {
	header('Content-Description: File Transfer');
	header('Content-type: application/force-download');
	header('Content-Disposition: attachment; filename="B64.zip"');
	}
}

?>
</div>

<div class="wrap">
<h2 style="margin-left:104px;"><?php _e('BulletProof Security Pro ~ Pro Tools'); ?></h2>

<!-- jQuery UI Tab Menu -->
<div id="bps-container">
	<div id="bps-tabs" class="bps-menu">
    <div id="bpsHead" style="position:relative; top:0px; left:0px;"><img src="<?php echo get_site_url(); ?>/wp-content/plugins/bulletproof-security/admin/images/bps-pro-logo.png" style="float:left; padding:0px 8px 0px 0px; margin:-68px 0px 0px 0px;" /></div>
		<ul>
			<li><a href="#bps-tabs-1">Online Base64 Decoder</a></li>
            <li><a href="#bps-tabs-2">Offline Base64 Decode/Encode</a></li>
            <li><a href="#bps-tabs-3">Mcrypt Decrypt/Encrypt</a></li>
			<li><a href="#bps-tabs-4">Crypt Encryption</a></li>
			<li><a href="#bps-tabs-5">Scheduled Crons</a></li>
            <li><a href="#bps-tabs-6">String Finder</a></li>
			<li><a href="#bps-tabs-7">String Replacer/Remover</a></li>
			<li><a href="#bps-tabs-8">DB String Finder</a></li>
            <li><a href="#bps-tabs-9">DB Table Cleaner/Remover</a></li>
            <li><a href="#bps-tabs-10">DNS Finder</a></li>
            <li><a href="#bps-tabs-11">Help &amp; FAQ</a></li>
		</ul>
            
<div id="bps-tabs-1" class="bps-tab-page">

<h2><?php _e('Online Safe - Base64 Decode / Decompress'); ?></h2>
<h3><?php _e('base64_decode, gzinflate, gzuncompress, str_rot13, strrev'); ?></h3>

<?php if (!current_user_can('manage_options')) { echo 'Permission Denied'; } else { ?>
<?php
// Enable File Downloading for /tools folder - writes a new denyall htaccess file with the current IP address
function bpsToolsHtaccessIP() {
if (current_user_can('manage_options')) {
	
	$bps_get_IP2 = $_SERVER['REMOTE_ADDR'];
	$denyall_htaccess_file_tools = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/tools/.htaccess';
	$bps_denyall_content_tools = "order deny,allow\ndeny from all\nallow from $bps_get_IP2";
	
	if (is_writable($denyall_htaccess_file_tools)) {
	if (!$handle = fopen($denyall_htaccess_file_tools, 'w+b')) {
         _e('<font color="red"><strong>Cannot open file' . "$denyall_htaccess_file_tools" . '</strong></font>');
         exit;
    }
    if (@fwrite($handle, $bps_denyall_content_tools) === FALSE) {
        _e('<font color="red"><strong>Cannot write to file' . "$denyall_htaccess_file_tools" . '</strong></font>');
        exit;
    }
    _e('<font color="green"><strong>Downloading of the B64.zip Archive file is enabled for your IP address only ===' . "$bps_get_IP2" .'</strong></font>');
    fclose($handle);
	} else {
    $text = '<br><strong>The Tools folder .htaccess file >>> <font color="red"> ' . "$denyall_htaccess_file_tools" . ' </font>does not exist.</strong>';
	_e('<div id="message" class="updated fade" style="border:1px solid #999999; margin-left:104px; margin-top:9px;"><p>'.$text.'</p></div>');
	}
	}
}
// writes current IP Address to .htaccess file on page access / launch
echo bpsToolsHtaccessIP();
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bps-help_faq_table">
  <tr>
    <td class="bps-table_title">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_help">
    <h3><?php _e('Safe Online Decoding Help Info'); ?>  <button id="bps-open-modal1" class="bps-modal-button"><?php _e('Read Me'); ?></button></h3>
    <div id="bps-modal-content1" title="<?php _e('Online Base64 Decoder'); ?>">
	<p><?php _e('<strong>This Read Me Help window is draggable and resizable</strong><br><br><strong>Use this Online Base64 Decoder when decoding known hacker scripts on a Live / Online website - DO NOT USE THE OFFLINE BASE64 DECODER WHEN DECODING KNOWN HACKER SCRIPTS ON A LIVE WEBSITE</strong><br>The Offline base64 decoder can be used safely for decoding hackers base64 code if you have XAMPP or MAMPP installed on your computer and have a local installation of WordPress on your computer.<br><br><strong>How To Use The Online Base64 Decoder</strong><br> -- Paste the base64 code into the <strong>Paste Code Here:</strong> window.<br> -- Select a Decoding Option that matches the type of base64 decode needed to decode the code.<br> -- Click the Decode button - the decoded code is written to the bps_b64_decode.txt text file.<br> -- Click the Zip File button to zip the text file to the B64.zip Archive file.<br> -- Click the Download File button to download the B64.zip Archive file to your computer.<br> -- Unzip the B64.zip file and open the text file safely on your computer to view the decoded base64 code.<br><br><strong>What Is The Difference Between the Online Base64 Decoder And the Offline Base64 Decoder?</strong><br>The Online Base64 Decoder should be used if you are decoding known hackers base64 code on a Live website. The Online Base64 Decoder is not outputting anything to your browser window. The base64 code is decoded and written to this text file /plugins/bulletproof-security/admin/tools/bps_b64_decode.txt instead of being outputted to your browser window. Some portions of hackers base64 scripts may contain php commands and hackers tags that are blocked by browsers and if you have Internet Protection software installed on your computer then it will block the output and alert you that a malicious script has been detected on your website. The browser and your Internet Protection software is seeing the strings and or patterns in the text file even though it is harmless because it is a text file and not a php file. Hackers code cannot be executed or processed from a .txt text file. The Offline Base64 Decoder is outputting the decoded code directly to your browser window to display it. It is not executing or processing that code and just displaying it, but this may trigger your browser or Internet Protection software to alert you that a malicious script has been detected and kick you out of your own website.<br><br><strong>Is It Safe To Use The Online Base64 Decoder On My Live Website?</strong><br>Yes absolutely. The Online Base64 Decoder decodes a hackers script, writes it to a text file then allows you to zip that text file to a zip archive file so that you will then be able to download it and unzip it and view the script safely in Notepad or some other text editor on your computer. The Online Base64 Decoder is less convenient, but absolutely safe to use.<br><br><strong>Safety Tips When Decoding Hackers Base64 Code Scripts</strong><br>You should handle all known hackers scripts just like you would handle a poisonous snake - very carefully and cautiously or not at all. You should never try and view known hackers scripts on a LIve website in a browser window.<br>When in doubt use the Online Base64 Decoder instead of the Offline Base64 Decoder.<br><br><strong>Error: Decode Failed - What Can Cause This?</strong><br>If you try and decode the entire function instead of just the actual encoded code you will see this error. If you have the base64_decode function disabled in your custom php.ini file then you will see this error. If your Web Host has the base64_decode function disabled by default then you will see this error message.<br><br><strong>BPS Pro Video Tutorial links can be found in the Help & FAQ pages.</strong>'); ?></p>
</div>

<?php
// Form - Online Base64 Decode - fwrite decoded code to text file
if(!isset($_POST['SubmitB64-Decoder-Online']))
    $chosen = array(0);
    else
    if (isset($_POST['SubmitB64-Decoder-Online']) && current_user_can('manage_options')) {
	check_admin_referer( 'bpsProB64decoderOnline' );   
	    $chosen = $_POST['bpsB64SelectOnline'];
		
		$bpsB64TextArea = $_POST['bpsB64TextAreaOnline'];
		$bpsNothing = '';
		
		$B64Log = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/tools/bps_b64_decode.txt';
		$timestampB64 = '['.date('m/d/Y g:i A').']';
		
	if ($_POST['bpsB64SelectOnline'] == array(0)) {
		$ouput = @base64_decode($bpsNothing);
	if (@!base64_decode($bpsNothing)) {
		_e('<strong><font color="red">Error: You did not select a Decoding option.</font></strong>');
	} else {	
		_e('<strong><font color="red">Error: You did not select a Decoding option.</font></strong>');	
		}
		}
	
	if ($_POST['bpsB64SelectOnline'] == array(1)) {
		$ouput = @base64_decode($bpsNothing);
	if (@!base64_decode($bpsNothing)) {
		_e('<strong><font color="red">Error: Decode Failed. -- Decoding -- is just a dropdown list spacer.</font></strong>');
	} else {	
		_e('<strong><font color="red">Error: Decode Failed. -- Decoding -- is just a dropdown list spacer.</font></strong>');	
		}
		}
		
	if ($_POST['bpsB64SelectOnline'] == array(2)) {
		$ouput = @base64_decode($bpsB64TextArea);
		$fh = fopen($B64Log, 'a');
 	@fwrite($fh, "\r\n>>>>>>>>>>> Base64 Decode $timestampB64 <<<<<<<<<<<\r\n");
	@fwrite($fh, 'Base64 Decoded Code: '."$ouput\r\n");
	fclose($fh);
	if (@!base64_decode($bpsB64TextArea)) {
		_e('<strong><font color="red">Error: Decode Failed. See the Read Me Help button for possible reasons why.</font></strong>');
   	} else {
		_e('<strong><font color="blue">Decode Successful. View the code in the bps_b64_decode.txt file.</font></strong>');
		}
		}
	
	if ($_POST['bpsB64SelectOnline'] == array(3)) {
		$ouput = @gzinflate(base64_decode($bpsB64TextArea));
		$fh = fopen($B64Log, 'a');
 	@fwrite($fh, "\r\n>>>>>>>>>>> Base64 Decode $timestampB64 <<<<<<<<<<<\r\n");
	@fwrite($fh, 'Base64 Decoded Code: '."$ouput\r\n");
	fclose($fh);
	if (@!gzinflate(base64_decode($bpsB64TextArea))) {
		_e('<strong><font color="red">Error: Decode Failed. See the Read Me Help button for possible reasons why.</font></strong>');
   	} else {
		_e('<strong><font color="blue">Decode Successful. Click Zip File and then click Download File to view the code.</font></strong>');
		}
		}
		
	if ($_POST['bpsB64SelectOnline'] == array(4)) {
		$ouput = @gzinflate(base64_decode(base64_decode(str_rot13($bpsB64TextArea))));
		$fh = fopen($B64Log, 'a');
 	@fwrite($fh, "\r\n>>>>>>>>>>> Base64 Decode $timestampB64 <<<<<<<<<<<\r\n");
	@fwrite($fh, 'Base64 Decoded Code: '."$ouput\r\n");
	fclose($fh);
	if (@!gzinflate(base64_decode(base64_decode(str_rot13($bpsB64TextArea))))) {
		_e('<strong><font color="red">Error: Decode Failed. See the Read Me Help button for possible reasons why.</font></strong>');
   	} else {
		_e('<strong><font color="blue">Decode Successful. Click Zip File and then click Download File to view the code.</font></strong>');
		}
		}
		
	if ($_POST['bpsB64SelectOnline'] == array(5)) {
		$ouput = @gzinflate(base64_decode(strrev(str_rot13($bpsB64TextArea))));
		$fh = fopen($B64Log, 'a');
 	@fwrite($fh, "\r\n>>>>>>>>>>> Base64 Decode $timestampB64 <<<<<<<<<<<\r\n");
	@fwrite($fh, 'Base64 Decoded Code: '."$ouput\r\n");
	fclose($fh);
	if (@!gzinflate(base64_decode(strrev(str_rot13($bpsB64TextArea))))) {
		_e('<strong><font color="red">Error: Decode Failed. See the Read Me Help button for possible reasons why.</font></strong>');
   	} else {
		_e('<strong><font color="blue">Decode Successful. Click Zip File and then click Download File to view the code.</font></strong>');
		}
		}

	if ($_POST['bpsB64SelectOnline'] == array(6)) {
		$ouput = @gzinflate(base64_decode(strrev($bpsB64TextArea)));
		$fh = fopen($B64Log, 'a');
 	@fwrite($fh, "\r\n>>>>>>>>>>> Base64 Decode $timestampB64 <<<<<<<<<<<\r\n");
	@fwrite($fh, 'Base64 Decoded Code: '."$ouput\r\n");
	fclose($fh);
	if (@!gzinflate(base64_decode(strrev($bpsB64TextArea)))) {
		_e('<strong><font color="red">Error: Decode Failed. See the Read Me Help button for possible reasons why.</font></strong>');
   	} else {
		_e('<strong><font color="blue">Decode Successful. Click Zip File and then click Download File to view the code.</font></strong>');
		}
		}

	if ($_POST['bpsB64SelectOnline'] == array(7)) {
		$ouput = @gzinflate(base64_decode(str_rot13($bpsB64TextArea)));
		$fh = fopen($B64Log, 'a');
 	@fwrite($fh, "\r\n>>>>>>>>>>> Base64 Decode $timestampB64 <<<<<<<<<<<\r\n");
	@fwrite($fh, 'Base64 Decoded Code: '."$ouput\r\n");
	fclose($fh);
	if (@!gzinflate(base64_decode(str_rot13($bpsB64TextArea)))) {
		_e('<strong><font color="red">Error: Decode Failed. See the Read Me Help button for possible reasons why.</font></strong>');
   	} else {
		_e('<strong><font color="blue">Decode Successful. Click Zip File and then click Download File to view the code.</font></strong>');
		}
		}
		
	if ($_POST['bpsB64SelectOnline'] == array(8)) {
		$ouput = @gzinflate(base64_decode(str_rot13(strrev($bpsB64TextArea))));
		$fh = fopen($B64Log, 'a');
 	@fwrite($fh, "\r\n>>>>>>>>>>> Base64 Decode $timestampB64 <<<<<<<<<<<\r\n");
	@fwrite($fh, 'Base64 Decoded Code: '."$ouput\r\n");
	fclose($fh);
	if (@!gzinflate(base64_decode(str_rot13(strrev($bpsB64TextArea))))) {
		_e('<strong><font color="red">Error: Decode Failed. See the Read Me Help button for possible reasons why.</font></strong>');
   	} else {
		_e('<strong><font color="blue">Decode Successful. Click Zip File and then click Download File to view the code.</font></strong>');
		}
		}		
		
	if ($_POST['bpsB64SelectOnline'] == array(9)) {
		$ouput = @gzinflate(base64_decode(str_rot13($bpsB64TextArea)));
		$fh = fopen($B64Log, 'a');
 	@fwrite($fh, "\r\n>>>>>>>>>>> Base64 Decode $timestampB64 <<<<<<<<<<<\r\n");
	@fwrite($fh, 'Base64 Decoded Code: '."$ouput\r\n");
	fclose($fh);
	if (@!gzinflate(base64_decode(str_rot13($bpsB64TextArea)))) {
		_e('<strong><font color="red">Error: Decode Failed. See the Read Me Help button for possible reasons why.</font></strong>');
   	} else {
		_e('<strong><font color="blue">Decode Successful. Click Zip File and then click Download File to view the code.</font></strong>');
		}
		}		
		
	if ($_POST['bpsB64SelectOnline'] == array(10)) {
		$ouput = @gzinflate(str_rot13(base64_decode($bpsB64TextArea)));
		$fh = fopen($B64Log, 'a');
 	@fwrite($fh, "\r\n>>>>>>>>>>> Base64 Decode $timestampB64 <<<<<<<<<<<\r\n");
	@fwrite($fh, 'Base64 Decoded Code: '."$ouput\r\n");
	fclose($fh);
	if (@!gzinflate(str_rot13(base64_decode($bpsB64TextArea)))) {
		_e('<strong><font color="red">Error: Decode Failed. See the Read Me Help button for possible reasons why.</font></strong>');
   	} else {
		_e('<strong><font color="blue">Decode Successful. Click Zip File and then click Download File to view the code.</font></strong>');
		}
		}
	
	if ($_POST['bpsB64SelectOnline'] == array(11)) {
		$ouput = @gzuncompress(base64_decode($bpsB64TextArea));
		$fh = fopen($B64Log, 'a');
 	@fwrite($fh, "\r\n>>>>>>>>>>> Base64 Decode $timestampB64 <<<<<<<<<<<\r\n");
	@fwrite($fh, 'Base64 Decoded Code: '."$ouput\r\n");
	fclose($fh);
	if (@!gzuncompress(base64_decode($bpsB64TextArea))) {
		_e('<strong><font color="red">Error: Decode Failed. See the Read Me Help button for possible reasons why.</font></strong>');
   	} else {
		_e('<strong><font color="blue">Decode Successful. Click Zip File and then click Download File to view the code.</font></strong>');
		}
		}		
		
	if ($_POST['bpsB64SelectOnline'] == array(12)) {
		$ouput = @gzuncompress(str_rot13(base64_decode($bpsB64TextArea)));
		$fh = fopen($B64Log, 'a');
 	@fwrite($fh, "\r\n>>>>>>>>>>> Base64 Decode $timestampB64 <<<<<<<<<<<\r\n");
	@fwrite($fh, 'Base64 Decoded Code: '."$ouput\r\n");
	fclose($fh);
	if (@!gzuncompress(str_rot13(base64_decode($bpsB64TextArea)))) {
		_e('<strong><font color="red">Error: Decode Failed. See the Read Me Help button for possible reasons why.</font></strong>');
   	} else {
		_e('<strong><font color="blue">Decode Successful. Click Zip File and then click Download File to view the code.</font></strong>');
		}
		}		

	if ($_POST['bpsB64SelectOnline'] == array(13)) {
		$ouput = @gzuncompress(base64_decode(str_rot13($bpsB64TextArea)));
		$fh = fopen($B64Log, 'a');
 	@fwrite($fh, "\r\n>>>>>>>>>>> Base64 Decode $timestampB64 <<<<<<<<<<<\r\n");
	@fwrite($fh, 'Base64 Decoded Code: '."$ouput\r\n");
	fclose($fh);
	if (@!gzuncompress(base64_decode(str_rot13($bpsB64TextArea)))) {
		_e('<strong><font color="red">Error: Decode Failed. See the Read Me Help button for possible reasons why.</font></strong>');
   	} else {
		_e('<strong><font color="blue">Decode Successful. Click Zip File and then click Download File to view the code.</font></strong>');
		}
		}

	if ($_POST['bpsB64SelectOnline'] == array(14)) {
		$ouput = @str_rot13(gzinflate(base64_decode($bpsB64TextArea)));
		$fh = fopen($B64Log, 'a');
 	@fwrite($fh, "\r\n>>>>>>>>>>> Base64 Decode $timestampB64 <<<<<<<<<<<\r\n");
	@fwrite($fh, 'Base64 Decoded Code: '."$ouput\r\n");
	fclose($fh);
	if (@!str_rot13(gzinflate(base64_decode($bpsB64TextArea)))) {
		_e('<strong><font color="red">Error: Decode Failed. See the Read Me Help button for possible reasons why.</font></strong>');
   	} else {
		_e('<strong><font color="blue">Decode Successful. Click Zip File and then click Download File to view the code.</font></strong>');
		}
		}
}

// Form - Dropdown list array for crypt alogorithms	
function showOptionsDropb64Online($array, $active, $echo=true){
	$string = '';
	foreach($array as $k => $v){
	if(is_array($active))
	$s = (in_array($k, $active))? ' selected="selected"' : '';
	else
	$s = ($active == $k)? ' selected="selected"' : '';
	$string .= '<option value="'.$k.'"'.$s.'>'.$v.'</option>'."\n";
	}
	if($echo)   echo $string;
	else        return $string;
}

$Code = '<strong><font color="blue">'."'".'Code'."'".'</font></strong>';

$bpsB64SelectOnline = array(' Select Decoding Option:', 
' -- Decoding -- ',
 '1. eval(base64_decode('."$Code".'))', 
 '2. eval(gzinflate(base64_decode('."$Code".')))', 
 '3. eval(gzinflate(base64_decode(base64_decode(str_rot13('."$Code".')))))', 
 '4. eval(gzinflate(base64_decode(strrev(str_rot13('."$Code".')))))', 
 '5. eval(gzinflate(base64_decode(strrev('."$Code".'))))', 
 '6. eval(gzinflate(base64_decode(str_rot13('."$Code".'))))', 
 '7. eval(gzinflate(base64_decode(str_rot13(strrev('."$Code".')))))', 
 '8. eval(gzinflate(base64_decode(str_rot13('."$Code".'))))', 
 '9. eval(gzinflate(str_rot13(base64_decode('."$Code".'))))', 
 '10. eval(gzuncompress(base64_decode('."$Code".')))', 
 '11. eval(gzuncompress(str_rot13(base64_decode('."$Code".'))))', 
 '12. eval(gzuncompress(base64_decode(str_rot13('."$Code".'))))', 
 '13. eval(str_rot13(gzinflate(base64_decode('."$Code".'))))'
 );

$scrolltoB64Online = isset($_REQUEST['scrolltoB64Online']) ? (int) $_REQUEST['scrolltoB64Online'] : 0;
?>

<table width="44%" border="0">
  <tr>
    <td width="20%">
<form name="BPS-Zip-B64" action="admin.php?page=bulletproof-security/admin/tools/tools.php" method="post">
<?php wp_nonce_field('bulletproof_security_zip_b64'); ?>
<p class="submit">
<input type="submit" name="submit-bps-zip-b64" class="button" value="<?php esc_attr_e('Zip File') ?>" />
</p>
</form>
</td>
    <td width="80%">
<form name="bps-pro-b64-download" action="<?php echo get_site_url() .'/wp-content/plugins/bulletproof-security/admin/tools/B64.zip'; ?>" method="post" enctype="multipart/form-data">
<?php wp_nonce_field('bulletproof_security_b64_downloader'); ?>
<p class="submit">
<input type="submit" name="bps-pro-b64-download" class="button" value="<?php esc_attr_e('Download File') ?>" onclick="return confirm('<?php _e('Click OK to Download the Base64 Decode Text file now or click Cancel to cancel the file download.'); ?>')" />
</p>
</form>
</td>
  </tr>
</table>

<form name="bpsB64FormOnline" action="admin.php?page=bulletproof-security/admin/tools/tools.php" method="post">
<?php wp_nonce_field('bpsProB64decoderOnline'); ?>
	<strong><label for="bps-B64"><?php _e('Choose Decode Option:'); ?> </label></strong><br />
	<select name="bpsB64SelectOnline[]" id="bpsB64SelectOnline">
	<?php echo showOptionsDropb64Online($bpsB64SelectOnline, $chosen, true); ?>
	</select><br />
    <p class="submit">
	<input type="submit" name="SubmitB64-Decoder-Online" class="button" value="<?php esc_attr_e('Decode') ?>" /></p>
	<label for="bps-B64"><strong><?php _e('Paste Code Here: '); ?></strong></label><br />
    <label for="bps-B64"><strong><?php _e('When decoding - ONLY paste the actual encoded '."$Code".' shown in blue in the example below.<br>Example:  eval(base64_decode('."$Code".'))'); ?></strong></label><br />
    <textarea cols="100" rows="15" name="bpsB64TextAreaOnline" tabindex="1"></textarea><br />
    <input type="hidden" name="scrolltoB64Online" value="<?php echo $scrolltoB64Online; ?>" />
</form><br /><br />
<script type="text/javascript">
/* <![CDATA[ */
jQuery(document).ready(function($){
	$('#bpsB64FormOnline').submit(function(){ $('#scrolltoB64Online').val( $('#bpsB64TextAreaOnline').scrollTop() ); });
	$('#bpsB64TextAreaOnline').scrollTop( $('#scrolltoB64Online').val() ); 
});
/* ]]> */
</script>
</td>
  </tr>
  <tr>
    <td class="bps-table_cell_bottom">&nbsp;</td>
  </tr>
</table>
<?php } ?>
</div>

<div id="bps-tabs-2" class="bps-tab-page">

<h2><?php _e('Offline Safe - Base64 Decode / Encode ~ Compress / Decompress'); ?></h2>
<h3><?php _e('base64_decode, base64_encode, gzinflate, gzdeflate, gzuncompress, gzcompress, str_rot13, strrev'); ?></h3>

<?php if (!current_user_can('manage_options')) { echo 'Permission Denied'; } else { ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bps-help_faq_table">
  <tr>
    <td class="bps-table_title">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_help">
    <h3><?php _e('Safe Offline Decoding &amp; Encoding Help Info'); ?>  <button id="bps-open-modal2" class="bps-modal-button"><?php _e('Read Me'); ?></button></h3>
    <div id="bps-modal-content2" title="<?php _e('Offline Base64 Decoding &amp; Encoding'); ?>">
	<p><?php _e('<strong>This Read Me Help window is draggable and resizable</strong><br><br><strong>Use the Online Base64 Decoder when decoding known hacker scripts on a Live / Online website - DO NOT USE THIS OFFLINE BASE64 DECODER WHEN DECODING KNOWN HACKER SCRIPTS ON A LIVE WEBSITE</strong><br>The Offline base64 decoder can be used safely for decoding hackers base64 code if you have XAMPP or MAMPP installed on your computer and have a local installation of WordPress on your computer. If you are decoding and encoding known good scripts, text or code then the the Offline Base64 Decoder / Encoder is quick, easy and handy tool to decode and encode base64 code.<br><br><strong>How To Use The Offline Base64 Decoder / Encoder</strong><br> -- Paste the base64 code, text or code into the <strong>Paste Code or Text Here:</strong> window.<br> -- Select a Decoding or Encoding Option that matches the type of base64 decode or encode needed to decode or encode the code.<br> -- Click the Decode / Encode button - the decoded or encoded code is displayed in the <strong>Safe Raw Code Output:</strong> window.<br> -- You can copy the decoded or encoded code from the Safe Raw Code Output window and paste it to a text file or php file on your computer.<br> -- The Decoding and Encoding Options are listed in matching numberered counterpart order. Decoding 1. matches Encoding 1., Decoding 2. matches Encoding 2., etc.<br><br><strong>What Is The Difference Between the Online Base64 Decoder And the Offline Base64 Decoder?</strong><br>The Online Base64 Decoder should be used if you are decoding known hackers base64 code on a Live website. The Online Base64 Decoder is not outputting anything to your browser window. The base64 code is decoded and written to this text file /plugins/bulletproof-security/admin/tools/bps_b64_decode.txt instead of being outputted to your browser window. Some portions of hackers base64 scripts may contain php commands and hackers tags that are blocked by browsers and if you have Internet Protection software installed on your computer then it will block the output and alert you that a malicious script has been detected on your website. The browser and your Internet Protection software is seeing the strings and or patterns in the text file even though it is harmless because it is a text file and not a php file. Hackers code cannot be executed or processed from a .txt text file. The Offline Base64 Decoder is outputting the decoded code directly to your browser window to display it. It is not executing or processing that code and just displaying it, but this may trigger your browser or Internet Protection software to alert you that a malicious script has been detected and kick you out of your own website.<br><br><strong>Is It Safe To Use The Online Base64 Decoder On My Live Website?</strong><br>Yes absolutely. The Online Base64 Decoder decodes a hackers script, writes it to a text file then allows you to zip that text file to a zip archive file so that you will then be able to download it and unzip it and view the script safely in Notepad or some other text editor on your computer. The Online Base64 Decoder is less convenient, but absolutely safe to use.<br><br><strong>Safety Tips When Decoding Hackers Base64 Code Scripts</strong><br>You should handle all known hackers scripts just like you would handle a poisonous snake - very carefully and cautiously or not at all. You should never try and view known hackers scripts on a LIve website in a browser window.<br>When in doubt use the Online Base64 Decoder instead of the Offline Base64 Decoder.<br><br><strong>Error: Decode Failed - What Can Cause This?</strong><br>If you try and decode the entire function instead of just the actual encoded code you will see this error. If you have the base64_decode function disabled in your custom php.ini file then you will see this error. If your Web Host has the base64_decode function disabled by default then you will see this error message.<br><br><strong>BPS Pro Video Tutorial links can be found in the Help & FAQ pages.</strong>'); ?></p>
</div>

<?php
// Form - Base64 Decode / Encode
if(!isset($_POST['SubmitB64-Decoder']))
    $chosen = array(0);
    else
    if (isset($_POST['SubmitB64-Decoder']) && current_user_can('manage_options')) {
	check_admin_referer( 'bpsProB64decoder' );   
	    $chosen = $_POST['bpsB64Select'];
		
		$bpsB64TextArea = $_POST['bpsB64TextArea'];
		$bpsNothing = '';
		
	if ($_POST['bpsB64Select'] == array(0)) {
		$ouput = @base64_decode($bpsNothing);
	if (@!base64_decode($bpsNothing)) {
		_e('<strong><font color="red">Error: You did not select a Decoding or Encoding option.</font></strong>');
	} else {	
		_e('<strong><font color="red">Error: You did not select a Decoding or Encoding option.</font></strong>');	
		}
		}
	
	if ($_POST['bpsB64Select'] == array(1)) {
		$ouput = @base64_decode($bpsNothing);
	if (@!base64_decode($bpsNothing)) {
		_e('<strong><font color="red">Error: Decode Failed. -- Decoding -- is just a dropdown list spacer.</font></strong>');
	} else {	
		_e('<strong><font color="red">Error: Decode Failed. -- Decoding -- is just a dropdown list spacer.</font></strong>');	
		}
		}
		
	if ($_POST['bpsB64Select'] == array(2)) {
		$ouput = @base64_decode($bpsB64TextArea);
	if (@!base64_decode($bpsB64TextArea)) {
		_e('<strong><font color="red">Error: Decode Failed. See the Read Me Help button for possible reasons why.</font></strong>');
   	} else {
		_e('<strong><font color="blue">Decode Successful. View the code in the Safe Raw Code Output window below.</font></strong>');
		}
		}
	
	if ($_POST['bpsB64Select'] == array(3)) {
		$ouput = @gzinflate(base64_decode($bpsB64TextArea));
	if (@!gzinflate(base64_decode($bpsB64TextArea))) {
		_e('<strong><font color="red">Error: Decode Failed. See the Read Me Help button for possible reasons why.</font></strong>');
   	} else {
		_e('<strong><font color="blue">Decode Successful. View the code in the Safe Raw Code Output window below.</font></strong>');
		}
		}
		
	if ($_POST['bpsB64Select'] == array(4)) {
		$ouput = @gzinflate(base64_decode(base64_decode(str_rot13($bpsB64TextArea))));
	if (@!gzinflate(base64_decode(base64_decode(str_rot13($bpsB64TextArea))))) {
		_e('<strong><font color="red">Error: Decode Failed. See the Read Me Help button for possible reasons why.</font></strong>');
   	} else {
		_e('<strong><font color="blue">Decode Successful. View the code in the Safe Raw Code Output window below.</font></strong>');
		}
		}
		
	if ($_POST['bpsB64Select'] == array(5)) {
		$ouput = @gzinflate(base64_decode(strrev(str_rot13($bpsB64TextArea))));
	if (@!gzinflate(base64_decode(strrev(str_rot13($bpsB64TextArea))))) {
		_e('<strong><font color="red">Error: Decode Failed. See the Read Me Help button for possible reasons why.</font></strong>');
   	} else {
		_e('<strong><font color="blue">Decode Successful. View the code in the Safe Raw Code Output window below.</font></strong>');
		}
		}

	if ($_POST['bpsB64Select'] == array(6)) {
		$ouput = @gzinflate(base64_decode(strrev($bpsB64TextArea)));
	if (@!gzinflate(base64_decode(strrev($bpsB64TextArea)))) {
		_e('<strong><font color="red">Error: Decode Failed. See the Read Me Help button for possible reasons why.</font></strong>');
   	} else {
		_e('<strong><font color="blue">Decode Successful. View the code in the Safe Raw Code Output window below.</font></strong>');
		}
		}

	if ($_POST['bpsB64Select'] == array(7)) {
		$ouput = @gzinflate(base64_decode(str_rot13($bpsB64TextArea)));
	if (@!gzinflate(base64_decode(str_rot13($bpsB64TextArea)))) {
		_e('<strong><font color="red">Error: Decode Failed. See the Read Me Help button for possible reasons why.</font></strong>');
   	} else {
		_e('<strong><font color="blue">Decode Successful. View the code in the Safe Raw Code Output window below.</font></strong>');
		}
		}
		
	if ($_POST['bpsB64Select'] == array(8)) {
		$ouput = @gzinflate(base64_decode(str_rot13(strrev($bpsB64TextArea))));
	if (@!gzinflate(base64_decode(str_rot13(strrev($bpsB64TextArea))))) {
		_e('<strong><font color="red">Error: Decode Failed. See the Read Me Help button for possible reasons why.</font></strong>');
   	} else {
		_e('<strong><font color="blue">Decode Successful. View the code in the Safe Raw Code Output window below.</font></strong>');
		}
		}		
		
	if ($_POST['bpsB64Select'] == array(9)) {
		$ouput = @gzinflate(base64_decode(str_rot13($bpsB64TextArea)));
	if (@!gzinflate(base64_decode(str_rot13($bpsB64TextArea)))) {
		_e('<strong><font color="red">Error: Decode Failed. See the Read Me Help button for possible reasons why.</font></strong>');
   	} else {
		_e('<strong><font color="blue">Decode Successful. View the code in the Safe Raw Code Output window below.</font></strong>');
		}
		}		
		
	if ($_POST['bpsB64Select'] == array(10)) {
		$ouput = @gzinflate(str_rot13(base64_decode($bpsB64TextArea)));
	if (@!gzinflate(str_rot13(base64_decode($bpsB64TextArea)))) {
		_e('<strong><font color="red">Error: Decode Failed. See the Read Me Help button for possible reasons why.</font></strong>');
   	} else {
		_e('<strong><font color="blue">Decode Successful. View the code in the Safe Raw Code Output window below.</font></strong>');
		}
		}
	
	if ($_POST['bpsB64Select'] == array(11)) {
		$ouput = @gzuncompress(base64_decode($bpsB64TextArea));
	if (@!gzuncompress(base64_decode($bpsB64TextArea))) {
		_e('<strong><font color="red">Error: Decode Failed. See the Read Me Help button for possible reasons why.</font></strong>');
   	} else {
		_e('<strong><font color="blue">Decode Successful. View the code in the Safe Raw Code Output window below.</font></strong>');
		}
		}		
		
	if ($_POST['bpsB64Select'] == array(12)) {
		$ouput = @gzuncompress(str_rot13(base64_decode($bpsB64TextArea)));
	if (@!gzuncompress(str_rot13(base64_decode($bpsB64TextArea)))) {
		_e('<strong><font color="red">Error: Decode Failed. See the Read Me Help button for possible reasons why.</font></strong>');
   	} else {
		_e('<strong><font color="blue">Decode Successful. View the code in the Safe Raw Code Output window below.</font></strong>');
		}
		}		

	if ($_POST['bpsB64Select'] == array(13)) {
		$ouput = @gzuncompress(base64_decode(str_rot13($bpsB64TextArea)));
	if (@!gzuncompress(base64_decode(str_rot13($bpsB64TextArea)))) {
		_e('<strong><font color="red">Error: Decode Failed. See the Read Me Help button for possible reasons why.</font></strong>');
   	} else {
		_e('<strong><font color="blue">Decode Successful. View the code in the Safe Raw Code Output window below.</font></strong>');
		}
		}

	if ($_POST['bpsB64Select'] == array(14)) {
		$ouput = @str_rot13(gzinflate(base64_decode($bpsB64TextArea)));
	if (@!str_rot13(gzinflate(base64_decode($bpsB64TextArea)))) {
		_e('<strong><font color="red">Error: Decode Failed. See the Read Me Help button for possible reasons why.</font></strong>');
   	} else {
		_e('<strong><font color="blue">Decode Successful. View the code in the Safe Raw Code Output window below.</font></strong>');
		}
		}

	if ($_POST['bpsB64Select'] == array(15)) {
		$ouput = @base64_encode($bpsNothing);
	if (@!base64_encode($bpsNothing)) {
		_e('<strong><font color="red">Error: Encode Failed. -- Encoding -- is just a dropdown list spacer.</font></strong>');
	} else {	
		_e('<strong><font color="red">Error: Encode Failed. -- Encoding -- is just a dropdown list spacer.</font></strong>');	
		}
		}	
	
	if ($_POST['bpsB64Select'] == array(16)) {
		$ouput = @base64_encode($bpsB64TextArea);
	if (@!base64_encode($bpsB64TextArea)) {
		_e('<strong><font color="red">Error: Encode Failed. See the Read Me Help button for possible reasons why.</font></strong>');
   	} else {
		_e('<strong><font color="blue">Encode Successful. View the code in the Safe Raw Code Output window below.</font></strong>');
		}
		}
	
	if ($_POST['bpsB64Select'] == array(17)) {
		$ouput = @base64_encode(gzdeflate($bpsB64TextArea));
	if (@!base64_encode(gzdeflate($bpsB64TextArea))) {
		_e('<strong><font color="red">Error: Encode Failed. See the Read Me Help button for possible reasons why.</font></strong>');
   	} else {
		_e('<strong><font color="blue">Encode Successful. View the code in the Safe Raw Code Output window below.</font></strong>');
		}
		}

	if ($_POST['bpsB64Select'] == array(18)) {
		$ouput = @str_rot13(base64_encode(base64_encode(gzdeflate($bpsB64TextArea))));
	if (@!str_rot13(base64_encode(base64_encode(gzdeflate($bpsB64TextArea))))) {
		_e('<strong><font color="red">Error: Encode Failed. See the Read Me Help button for possible reasons why.</font></strong>');
   	} else {
		_e('<strong><font color="blue">Encode Successful. View the code in the Safe Raw Code Output window below.</font></strong>');
		}
		}

	if ($_POST['bpsB64Select'] == array(19)) {
		$ouput = @strrev(str_rot13(base64_encode(gzdeflate($bpsB64TextArea))));
	if (@!strrev(str_rot13(base64_encode(gzdeflate($bpsB64TextArea))))) {
		_e('<strong><font color="red">Error: Encode Failed. See the Read Me Help button for possible reasons why.</font></strong>');
   	} else {
		_e('<strong><font color="blue">Encode Successful. View the code in the Safe Raw Code Output window below.</font></strong>');
		}
		}

	if ($_POST['bpsB64Select'] == array(20)) {
		$ouput = @strrev(base64_encode(gzdeflate($bpsB64TextArea)));
	if (@!strrev(base64_encode(gzdeflate($bpsB64TextArea)))) {
		_e('<strong><font color="red">Error: Encode Failed. See the Read Me Help button for possible reasons why.</font></strong>');
   	} else {
		_e('<strong><font color="blue">Encode Successful. View the code in the Safe Raw Code Output window below.</font></strong>');
		}
		}

	if ($_POST['bpsB64Select'] == array(21)) {
		$ouput = @str_rot13(base64_encode(gzdeflate($bpsB64TextArea)));
	if (@!str_rot13(base64_encode(gzdeflate($bpsB64TextArea)))) {
		_e('<strong><font color="red">Error: Encode Failed. See the Read Me Help button for possible reasons why.</font></strong>');
   	} else {
		_e('<strong><font color="blue">Encode Successful. View the code in the Safe Raw Code Output window below.</font></strong>');
		}
		}

	if ($_POST['bpsB64Select'] == array(22)) {
		$ouput = @str_rot13(strrev(base64_encode(gzdeflate($bpsB64TextArea))));
	if (@!str_rot13(strrev(base64_encode(gzdeflate($bpsB64TextArea))))) {
		_e('<strong><font color="red">Error: Encode Failed. See the Read Me Help button for possible reasons why.</font></strong>');
   	} else {
		_e('<strong><font color="blue">Encode Successful. View the code in the Safe Raw Code Output window below.</font></strong>');
		}
		}
		
	if ($_POST['bpsB64Select'] == array(23)) {
		$ouput = @str_rot13(base64_encode(gzdeflate($bpsB64TextArea)));
	if (@!str_rot13(base64_encode(gzdeflate($bpsB64TextArea)))) {
		_e('<strong><font color="red">Error: Encode Failed. See the Read Me Help button for possible reasons why.</font></strong>');
   	} else {
		_e('<strong><font color="blue">Encode Successful. View the code in the Safe Raw Code Output window below.</font></strong>');
		}
		}

	if ($_POST['bpsB64Select'] == array(24)) {
		$ouput = @base64_encode(str_rot13(gzdeflate($bpsB64TextArea)));
	if (@!base64_encode(str_rot13(gzdeflate($bpsB64TextArea)))) {
		_e('<strong><font color="red">Error: Encode Failed. See the Read Me Help button for possible reasons why.</font></strong>');
   	} else {
		_e('<strong><font color="blue">Encode Successful. View the code in the Safe Raw Code Output window below.</font></strong>');
		}
		}

	if ($_POST['bpsB64Select'] == array(25)) {
		$ouput = @base64_encode(gzcompress($bpsB64TextArea));
	if (@!base64_encode(gzcompress($bpsB64TextArea))) {
		_e('<strong><font color="red">Error: Encode Failed. See the Read Me Help button for possible reasons why.</font></strong>');
   	} else {
		_e('<strong><font color="blue">Encode Successful. View the code in the Safe Raw Code Output window below.</font></strong>');
		}
		}

	if ($_POST['bpsB64Select'] == array(26)) {
		$ouput = @base64_encode(str_rot13(gzcompress($bpsB64TextArea)));
	if (@!base64_encode(str_rot13(gzcompress($bpsB64TextArea)))) {
		_e('<strong><font color="red">Error: Encode Failed. See the Read Me Help button for possible reasons why.</font></strong>');
   	} else {
		_e('<strong><font color="blue">Encode Successful. View the code in the Safe Raw Code Output window below.</font></strong>');
		}
		}
	
	if ($_POST['bpsB64Select'] == array(27)) {
		$ouput = @str_rot13(base64_encode(gzcompress($bpsB64TextArea)));
	if (@!str_rot13(base64_encode(gzcompress($bpsB64TextArea)))) {
		_e('<strong><font color="red">Error: Encode Failed. See the Read Me Help button for possible reasons why.</font></strong>');
   	} else {
		_e('<strong><font color="blue">Encode Successful. View the code in the Safe Raw Code Output window below.</font></strong>');
		}
		}
	
	if ($_POST['bpsB64Select'] == array(28)) {
		$ouput = @base64_encode(gzdeflate(str_rot13($bpsB64TextArea)));
	if (@!base64_encode(gzdeflate(str_rot13($bpsB64TextArea)))) {
		_e('<strong><font color="red">Error: Encode Failed. See the Read Me Help button for possible reasons why.</font></strong>');
   	} else {
		_e('<strong><font color="blue">Encode Successful. View the code in the Safe Raw Code Output window below.</font></strong>');
		}
		}
}

// Form - Dropdown list array for crypt alogorithms	
function showOptionsDropb64($array, $active, $echo=true){
	$string = '';
	foreach($array as $k => $v){
	if(is_array($active))
	$s = (in_array($k, $active))? ' selected="selected"' : '';
	else
	$s = ($active == $k)? ' selected="selected"' : '';
	$string .= '<option value="'.$k.'"'.$s.'>'.$v.'</option>'."\n";
	}
	if($echo)   echo $string;
	else        return $string;
}

$Code = '<strong><font color="blue">'."'".'Code'."'".'</font></strong>';

$bpsB64Select = array(' Select Decoding / Encoding Option:', 
' -- Decoding -- ',
 '1. eval(base64_decode('."$Code".'))', 
 '2. eval(gzinflate(base64_decode('."$Code".')))', 
 '3. eval(gzinflate(base64_decode(base64_decode(str_rot13('."$Code".')))))', 
 '4. eval(gzinflate(base64_decode(strrev(str_rot13('."$Code".')))))', 
 '5. eval(gzinflate(base64_decode(strrev('."$Code".'))))', 
 '6. eval(gzinflate(base64_decode(str_rot13('."$Code".'))))', 
 '7. eval(gzinflate(base64_decode(str_rot13(strrev('."$Code".')))))', 
 '8. eval(gzinflate(base64_decode(str_rot13('."$Code".'))))', 
 '9. eval(gzinflate(str_rot13(base64_decode('."$Code".'))))', 
 '10. eval(gzuncompress(base64_decode('."$Code".')))', 
 '11. eval(gzuncompress(str_rot13(base64_decode('."$Code".'))))', 
 '12. eval(gzuncompress(base64_decode(str_rot13('."$Code".'))))', 
 '13. eval(str_rot13(gzinflate(base64_decode('."$Code".'))))', 
 ' -- Encoding -- ', 
 '1. base64_encode('."$Code".')', 
 '2. base64_encode(gzdeflate('."$Code".'))', 
 '3. str_rot13(base64_encode(base64_encode(gzdeflate('."$Code".'))))', 
 '4. strrev(str_rot13(base64_encode(gzdeflate('."$Code".'))))', 
 '5. strrev(base64_encode(gzdeflate('."$Code".')))', 
 '6. str_rot13(base64_encode(gzdeflate('."$Code".')))', 
 '7. str_rot13(strrev(base64_encode(gzdeflate('."$Code".'))))', 
 '8. str_rot13(base64_encode(gzdeflate('."$Code".')))', 
 '9. base64_encode(str_rot13(gzdeflate('."$Code".')))', 
 '10. base64_encode(gzcompress('."$Code".'))', 
 '11. base64_encode(str_rot13(gzcompress('."$Code".')))', 
 '12. str_rot13(base64_encode(gzcompress('."$Code".')))', 
 '13. base64_encode(gzdeflate(str_rot13('."$Code".')))'
 );

$scrolltoB64 = isset($_REQUEST['scrolltoB64']) ? (int) $_REQUEST['scrolltoB64'] : 0;

?>

<form name="bpsB64Form" action="admin.php?page=bulletproof-security/admin/tools/tools.php#bps-tabs-2" method="post">
<?php wp_nonce_field('bpsProB64decoder'); ?>
	<strong><label for="bps-B64"><?php _e('Choose Encode or Decode Option:'); ?> </label></strong><br />
	<select name="bpsB64Select[]" id="bpsB64Select">
	<?php echo showOptionsDropb64($bpsB64Select, $chosen, true); ?>
	</select><br /><br />
    <p class="submit">
	<input type="submit" name="SubmitB64-Decoder" class="button" value="<?php esc_attr_e('Decode / Encode') ?>" /></p>
	<label for="bps-B64"><strong><?php _e('Paste Code or Text Here: '); ?></strong></label><br />
    <label for="bps-B64"><strong><?php _e('When decoding - ONLY paste the actual encoded '."$Code".' shown in blue in the example below.<br>Example:  eval(base64_decode('."$Code".'))'); ?></strong></label><br />
    <textarea cols="100" rows="15" name="bpsB64TextArea" tabindex="1"></textarea><br />
    <label for="bps-B64"><strong><?php _e('Safe Raw Code Output: '); ?></strong></label><br />
    <textarea cols="100" rows="15" name="bpsB64TextAreaOutput" tabindex="2"><?php echo @stripslashes(htmlspecialchars($ouput)); ?></textarea>
    <input type="hidden" name="scrolltoB64" value="<?php echo $scrolltoB64; ?>" />
</form><br /><br />
<script type="text/javascript">
/* <![CDATA[ */
jQuery(document).ready(function($){
	$('#bpsB64Form').submit(function(){ $('#scrolltoB64').val( $('#bpsB64TextArea').scrollTop() ); });
	$('#bpsB64TextArea').scrollTop( $('#scrolltoB64').val() ); 
});
/* ]]> */
</script>

</td>
  </tr>
  <tr>
    <td class="bps-table_cell_bottom">&nbsp;</td>
  </tr>
</table>
<?php } ?>
</div>
            
<div id="bps-tabs-3" class="bps-tab-page">
<h2><?php _e('Mycrypt ~ Decrypt / Encrypt'); ?></h2>
<?php if (!current_user_can('manage_options')) { echo 'Permission Denied'; } else { ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bps-help_faq_table">
  <tr>
    <td class="bps-table_title">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_help">
    <h3><?php _e('Mcrypt ~ Decrypt'); ?>  <button id="bps-open-modal3" class="bps-modal-button"><?php _e('Read Me'); ?></button></h3>
    <div id="bps-modal-content3" title="<?php _e('Mcrypt ~ Decrypt Encryption'); ?>">
	<p><?php _e('<strong>This Read Me Help window is draggable and resizable</strong><br><br><strong>mcrypt_encrypt and mcrypt_decrypt functions</strong><br><br><strong>Mcrypt Cipher:</strong> MCRYPT_RIJNDAEL_256<br><strong>Block Algorithm / Cipher Mode:</strong> MCRYPT_MODE_CBC<br><strong>Salt and String:</strong> md5 hashed and base64 encoded / decoded<br><br>To decrypt paste or type the salt into the salt window and paste mcrypt encrypted code into the Decrypt window and click the Decrypt button to decrypt it. To encrypt text or code paste or type the salt into the salt window and paste or type text or code into the Encrypt window and click the Encrypt button.<br><br><strong>BPS Pro Video Tutorial links can be found in the Help & FAQ pages.</strong>'); ?></p>
</div>

<?php
// Form - mcrypt_decrypt
if (isset($_POST['Submit-Mcrypt-Decrypt']) && current_user_can('manage_options')) {
	check_admin_referer( 'bpsProMcryptDecrypt' );
	$bpsMcryptSaltPassD = $_POST['bpsMcryptSaltPassD'];
	$bpsMcryptDecryptString = $_POST['bpsMcryptDecryptString'];
	$bpsMcryptDecrypt = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($bpsMcryptSaltPassD), base64_decode($bpsMcryptDecryptString), MCRYPT_MODE_CBC, md5(md5($bpsMcryptSaltPassD))), "\0");
	//echo '<strong>Mycrypt_decrypt:</strong> '. $bpsMcryptDecrypt;
}

// Form - mcrypt_encrypt
if (isset($_POST['Submit-Mcrypt-Encrypt']) && current_user_can('manage_options')) {
	check_admin_referer( 'bpsProMcryptEncrypt' );
	$bpsMcryptSaltPassE = $_POST['bpsMcryptSaltPassE'];
	$bpsMcryptEncryptString = $_POST['bpsMcryptEncryptString'];
	$bpsMcryptEncrypt = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($bpsMcryptSaltPassE), $bpsMcryptEncryptString, MCRYPT_MODE_CBC, md5(md5($bpsMcryptSaltPassE))));
	//echo '<strong>Mycrypt_encrypt:</strong> '. $bpsMcryptEncrypt;
}
$scrolltoMcryptE = isset($_REQUEST['scrolltoMcryptE']) ? (int) $_REQUEST['scrolltoMcryptE'] : 0;
$scrolltoMcryptD = isset($_REQUEST['scrolltoMcryptD']) ? (int) $_REQUEST['scrolltoMcryptD'] : 0;
?>

<form name="bpsMcryptDecryptForm" action="admin.php?page=bulletproof-security/admin/tools/tools.php#bps-tabs-3" method="post">
<?php wp_nonce_field('bpsProMcryptDecrypt'); ?>
<div><label for="bpsMcrypt"><strong><?php _e('Salt / Password: '); ?></strong></label><br />
    <input type="text" name="bpsMcryptSaltPassD" value="" size="98"/><br />
    <label for="bpsMcrypt"><strong><?php _e('Paste Text or Code to Decrypt Here: '); ?></strong></label><br />
    <textarea cols="100" rows="15" name="bpsMcryptDecryptString" tabindex="1"></textarea>
	<input type="hidden" name="scrolltoMcryptD" value="<?php echo $scrolltoMcryptD; ?>" />
    <p class="submit">
	<input type="submit" name="Submit-Mcrypt-Decrypt" class="button" value="<?php esc_attr_e('Decrypt') ?>" /></p>
</div>
</form>
<script type="text/javascript">
/* <![CDATA[ */
jQuery(document).ready(function($){
	$('#bpsMcryptDecryptForm').submit(function(){ $('#scrolltoMcryptD').val( $('#bpsMcryptDecryptString').scrollTop() ); });
	$('#bpsMcryptDecryptString').scrollTop( $('#scrolltoMcryptD').val() ); 
});
/* ]]> */
</script>

<div id="bpsCodeView">

<table width="100%" border="0" class="widefat fixed">
  <tr>
    <td><?php echo '<strong><font color="blue">Decrypted (Safe Raw Code Output):</font> '. @stripslashes(htmlspecialchars($bpsMcryptDecrypt)) .'</strong>'; ?></td>
  </tr>
  <tr>
    <td><?php echo '<strong><font color="blue">Encrypted Output:</font>  '. @$bpsMcryptEncrypt .'</strong>'; ?></td>
  </tr>
</table>

</div>

<h3><?php _e('Mcrypt ~ Encrypt'); ?></h3>
<form name="bpsMcryptEncryptForm" action="admin.php?page=bulletproof-security/admin/tools/tools.php#bps-tabs-3" method="post">
<?php wp_nonce_field('bpsProMcryptEncrypt'); ?>
<div><label for="bpsMcrypt"><strong><?php _e('Salt / Password: '); ?></strong></label><br />
    <input type="text" name="bpsMcryptSaltPassE" value="" size="98"/><br />
    <label for="bpsMcrypt"><strong><?php _e('Paste Text or Code to Encrypt Here: '); ?></strong></label><br />
    <textarea cols="100" rows="15" name="bpsMcryptEncryptString" tabindex="2"></textarea>
	<input type="hidden" name="scrolltoMcryptE" value="<?php echo $scrolltoMcryptE; ?>" />
    <p class="submit">
	<input type="submit" name="Submit-Mcrypt-Encrypt" class="button" value="<?php esc_attr_e('Encrypt') ?>" /></p>
</div>
</form>
<script type="text/javascript">
/* <![CDATA[ */
jQuery(document).ready(function($){
	$('#bpsMcryptEncryptForm').submit(function(){ $('#scrolltoMcryptE').val( $('#bpsMcryptEncryptString').scrollTop() ); });
	$('#bpsMcryptEncryptString').scrollTop( $('#scrolltoMcryptE').val() ); 
});
/* ]]> */
</script>
</td>
  </tr>
  <tr>
    <td class="bps-table_cell_bottom">&nbsp;</td>
  </tr>
</table>
<?php } ?>
</div>
            
<div id="bps-tabs-4" class="bps-tab-page">
<h2><?php _e('Crypt'); ?></h2>
<?php if (!current_user_can('manage_options')) { echo 'Permission Denied'; } else { ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bps-help_faq_table">
  <tr>
    <td class="bps-table_title">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_help">
    <h3><?php _e('Crypt one-way string hashing'); ?>  <button id="bps-open-modal4" class="bps-modal-button"><?php _e('Read Me'); ?></button></h3>
    <div id="bps-modal-content4" title="<?php _e('Crypt one-way string hashing'); ?>">
	<p><?php _e('<strong>This Read Me Help window is draggable and resizable</strong><br><br><strong>The crypt function</strong><br><br>The crypt function will return a hashed string using the algorithms listed in the Choose Encryption Algorithm drop down list. Your system may not support all of the available Algorithms. You will see an error message if your system does not support a particular Algorithm that you have selected. There is no decrypt function for the crypt function. The crypt() function uses a one-way algorithm. Practical uses for crypt would be hashing / encrypting sensitive information like passwords, credit card numbers or creating a key that cannot be decrypted. The hash string can be accessible / viewable publicly and the hashed / encrypted string will match data stored in a private database that is not publicly accessible. Example: When a user creates a password and the password is encrypted with the crypt() function, the encrypted version of this password is saved to the database. The next time the user logs in their password is encrypted again and compared against the already saved (encrypted) password in the database. If the encrypted password is some how intercepted it will be the encrypted version of the password instead of the actual password. The encrypted password will not work to log in with because it will be encrypted again and will not match any encrypted passwords stored in the database.<br><br><strong>BPS Pro Video Tutorial links can be found in the Help & FAQ pages.</strong>'); ?></p>
</div>

<strong><?php _e('Hashed / Encrypted String:'); ?></strong>
<div id="bpsCodeWhite" style="border:1px solid #999999; background-color: #fff; width:100%; padding:0px 0px 0px 5px; margin:0px 0px 20px 0px;">

<?php
// Form - Encrypt using crypt function with selected Alogorithm
if(!isset($_POST['Submit-Crypt']))
    $chosen = array(0);
    else
    if (isset($_POST['Submit-Crypt']) && current_user_can('manage_options')) {
	check_admin_referer( 'bpsProCryptEncrypt' );   
	    $chosen = $_POST['bpsCryptAlgo'];
		
		$bpsCryptEncryptSalt = $_POST['bpsCryptEncryptSalt'];
		$bpsCryptEncryptString = $_POST['bpsCryptEncryptString'];
		$replacement = '';
		
		if ($_POST['bpsCryptAlgo'] == array(1)) {
		if (CRYPT_STD_DES == 1) {
	$filter = $bpsCryptEncryptSalt;
	$string = crypt($bpsCryptEncryptString, $bpsCryptEncryptSalt) . "\n";
	$key = str_replace($filter, $replacement, $string);
	echo '<strong><font color="blue">Standard DES Hashed String:</font> '.$key.'</strong>';
   		} else {
		_e('<font color="red"><strong>Your system and / or server does not support CRYPT_STD_DES. Select a different encryption algorithm.</strong></font>');
		}
		}
		if ($_POST['bpsCryptAlgo'] == array(2)) {
		if (CRYPT_EXT_DES == 1) {
	$filter = '_'.$bpsCryptEncryptSalt;
	$string = crypt($bpsCryptEncryptString, '_'.$bpsCryptEncryptSalt) . "\n";
	$key = str_replace($filter, $replacement, $string);
	echo '<strong><font color="blue">Extended DES Hashed String:</font> '.$key.'</strong>';
   		} else {
		_e('<font color="red"><strong>Your system and / or server does not support CRYPT_EXT_DES. Select a different encryption algorithm.</strong></font>');
		}
		}   
		if ($_POST['bpsCryptAlgo'] == array(3)) {
		if (CRYPT_MD5 == 1) {
	$filter = '$1$'.$bpsCryptEncryptSalt.'$';
	$string = crypt($bpsCryptEncryptString, '$1$'.$bpsCryptEncryptSalt.'$') . "\n";
	$key = str_replace($filter, $replacement, $string);
	echo '<strong><font color="blue">MD5 Hashed String:</font> '.$key.'</strong>';
   		} else {
		_e('<font color="red"><strong>Your system and / or server does not support CRYPT_MD5. Select a different encryption algorithm.</strong></font>');
		}
		}
		if ($_POST['bpsCryptAlgo'] == array(4)) {
		if (CRYPT_BLOWFISH == 1) {
	$filter = '$2a$09$'.$bpsCryptEncryptSalt.'$';
	$string = crypt($bpsCryptEncryptString, '$2a$09$'.$bpsCryptEncryptSalt.'$') . "\n";
	$key = str_replace($filter, $replacement, $string);
	echo '<strong><font color="blue">Blowfish Hashed String:</font> '.$key.'</strong>';
   		} else {
		_e('<font color="red"><strong>Your system and / or server does not support CRYPT_BLOWFISH. Select a different encryption algorithm.</strong></font>');
		}
		}
		if ($_POST['bpsCryptAlgo'] == array(5)) {
		if (CRYPT_SHA256 == 1) {
	$filter = '$5$rounds=5000$'.$bpsCryptEncryptSalt.'$';
	$string = crypt($bpsCryptEncryptString, '$5$rounds=5000$'.$bpsCryptEncryptSalt.'$') . "\n";
	$key = str_replace($filter, $replacement, $string);
	echo '<strong><font color="blue">SHA-256 Hashed String:</font> '.$key.'</strong>';
   		} else {
		_e('<font color="red"><strong>Your system and / or server does not support CRYPT_SHA256. Select a different encryption algorithm.</strong></font>');
		}
		}
		if ($_POST['bpsCryptAlgo'] == array(6)) {
		if (CRYPT_SHA512 == 1) {
	$filter = '$6$rounds=5000$'.$bpsCryptEncryptSalt.'$';
	$string = crypt($bpsCryptEncryptString, '$6$rounds=5000$'.$bpsCryptEncryptSalt.'$') . "\n";
	$key = str_replace($filter, $replacement, $string);
	echo '<strong><font color="blue">SHA-512 Hashed String:</font> '.$key.'</strong>';
   		} else {
		_e('<font color="red"><strong>Your system and / or server does not support CRYPT_SHA512. Select a different encryption algorithm.</strong></font>');
		}
		}
}

// Form - Dropdown list array for crypt alogorithms	
function showOptionsDrop3($array, $active, $echo=true){
	$string = '';
	foreach($array as $k => $v){
	if(is_array($active))
	$s = (in_array($k, $active))? ' selected="selected"' : '';
	else
	$s = ($active == $k)? ' selected="selected"' : '';
	$string .= '<option value="'.$k.'"'.$s.'>'.$v.'</option>'."\n";
	}
	if($echo)   echo $string;
	else        return $string;
}

$bpsCryptAlgo = array(' Select Encryption Algorithm:', 'CRYPT_STD_DES', 'CRYPT_EXT_DES', 'CRYPT_MD5', 'CRYPT_BLOWFISH', 'CRYPT_SHA256', 'CRYPT_SHA512');

?>
</div>

<form name="bpsCryptEncryptForm" action="admin.php?page=bulletproof-security/admin/tools/tools.php#bps-tabs-4" method="post">
<?php wp_nonce_field('bpsProCryptEncrypt'); ?>
	<strong><label for="bps-crypt"><?php _e('Choose the Encryption Algorithm:'); ?> </label></strong><br />
	<select name="bpsCryptAlgo[]" id="bpsCryptAlgo">
	<?php echo showOptionsDrop3($bpsCryptAlgo, $chosen, true); ?>
	</select><br /><br />
    <label for="bps-crypt"><strong><?php _e('Salt / Password: '); ?></strong></label><br />
    <input type="text" name="bpsCryptEncryptSalt" value="" size="100"/><br />
	<label for="bps-crypt"><strong><?php _e('String / Text to Hash / Encrypt: '); ?></strong></label><br />
    <input type="text" name="bpsCryptEncryptString" value="" size="100"/>
    <p class="submit">
	<input type="submit" name="Submit-Crypt" class="button" value="<?php esc_attr_e('Create Hash / Encrypt') ?>" /></p>
</form>


<div id="bpsCodeWhite2" style="border:1px solid #999999; background-color: #fff; width:100%; padding:0px 0px 0px 5px; margin:0px 10px 20px 0px;">
<p>&#9632; <strong>CRYPT_STD_DES - </strong>Use a 2 character salt using only characters A-Z and 0-9. Using invalid characters in the salt will cause crypt() to fail.</p> 
<p>&#9632; <strong>CRYPT_EXT_DES - </strong>Use an 8 character salt using only characters A-Z and 0-9. Using invalid characters in the salt will cause crypt() to fail. A valid Extended DES salt requires a 9 character salt starting with an underscore. The underscore salt prefix required for Extended DES hashing has already been included in the BPS form function.</p>
<p>&#9632; <strong>CRYPT_MD5 - </strong>Use an 8 character salt using characters A-Z and 0-9 as well any special characters. A valid MD5 salt requires a 12 character salt starting with $1$. The $1$ salt prefix required for MD5 hashing has already been included in the form function and a trailing $ salt character has also been added. </p> 
<p>&#9632; <strong>CRYPT_BLOWFISH - </strong>Use a 20 character salt using only characters A-Z and 0-9. Using characters outside of this range in the salt will cause crypt() to return a zero-length string. A valid Blowfish salt requires a 22 character salt starting with $2a$ followed by a two digit cost parameter and another $ sign. The BPS form function already contains this Blowfish salt prefix: $2a$09$ and a trailing $ salt character has also been added. The two digit cost parameter is the base-2 logarithm of the iteration count for the underlying Blowfish-based hashing algorithm and must be in range 04-31 (09 was chosen randomly for this BPS form function), values outside this range will cause crypt() to fail.</p> 
<p>&#9632; <strong>CRYPT_SHA256 - </strong>Use a 16 character salt using characters A-Z and 0-9 as well any special characters. A valid SHA-256 salt requires a 16 character salt starting with $5$. The $5$ salt prefix required for SHA-256 hashing and the default of 5000 rounds (rounds=&lt;N&gt;$) prefix and a trailing $ salt character have already been included in the BPS form function. The numeric value of N is used to indicate how many times the hashing loop should be executed, much like the cost parameter on Blowfish. The default number of rounds is 5000, there is a minimum of 1000 and a maximum of 999,999,999. Any selection of N outside this range will be truncated to the nearest limit.</p> 
<p>&#9632; <strong>CRYPT_SHA512 - </strong>Use a 16 character salt using characters A-Z and 0-9 as well any special characters. A valid SHA-512 salt requires a 16 character salt starting with $6$. The $6$ salt prefix required for SHA-512 hashing and the default of 5000 rounds (rounds=&lt;N&gt;$) prefix and a trailing $ salt character have already been included in the BPS form function. The numeric value of N is used to indicate how many times the hashing loop should be executed, much like the cost parameter on Blowfish. The default number of rounds is 5000, there is a minimum of 1000 and a maximum of 999,999,999. Any selection of N outside this range will be truncated to the nearest limit.</p>
</div>
</td>
  </tr>
  <tr>
    <td class="bps-table_cell_bottom">&nbsp;</td>
  </tr>
</table>
<?php } ?>
</div>
           
<div id="bps-tabs-5" class="bps-tab-page">
<h2><?php _e('Scheduled Cron Jobs'); ?></h2>
<?php if (!current_user_can('manage_options')) { echo 'Permission Denied'; } else { ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bps-help_faq_table">
  <tr>
    <td class="bps-table_title">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_help">
    <h3><?php _e('Cron jobs that are scheduled to run on your website.'); ?></h3>
<table class="widefat fixed" style="margin-bottom:20px;">
<thead>
	<tr>
	<th scope="col"><?php _e('Next Run Date')?></th>
	<th scope="col"><?php _e('Frequency')?></th>
	<th scope="col"><?php _e('Hook Name')?></th>
	</tr>
</thead>
<tbody>
<?php
	$cron = _get_cron_array();
	$schedules = wp_get_schedules();
	$date_format = 'M j, Y @ G:i';
	foreach ( $cron as $timestamp => $cronhooks ) {
	foreach ( (array) $cronhooks as $hook => $events ) {
	foreach ( (array) $events as $key => $event ) {
	$cron[ $timestamp ][ $hook ][ $key ][ 'date' ] = date_i18n( $date_format, $timestamp );
	}
	}
}
?>
<?php foreach ( $cron as $timestamp => $cronhooks ) { ?>
<?php foreach ( (array) $cronhooks as $hook => $events ) { ?>
<?php foreach ( (array) $events as $event ) { ?>
<tr>
	<th scope="row"><?php echo $event[ 'date' ]; ?></th>
	<td>
<?php 
	if ( $event[ 'schedule' ] ) {
	echo $schedules [ $event[ 'schedule' ] ][ 'display' ]; 
	} else {
	?><em><?php _e('One-off event')?></em><?php
	}
	?>
	</td>
	<td><?php echo $hook; ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php } ?>
</tbody>
</table>
</td>
  </tr>
  <tr>
    <td class="bps-table_cell_bottom">&nbsp;</td>
  </tr>
</table>
<?php } ?>
</div>
        
<div id="bps-tabs-6" class="bps-tab-page">
<h2><?php _e('String / Function Finder'); ?></h2>
<?php if (!current_user_can('manage_options')) { echo 'Permission Denied'; } else { ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bps-help_faq_table">
  <tr>
    <td class="bps-table_title">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_help">
    <h3><?php _e('String / Function Finder Usage'); ?>  <button id="bps-open-modal5" class="bps-modal-button"><?php _e('Read Me'); ?></button></h3>
    <div id="bps-modal-content5" title="<?php _e('String / Function Finder Usage'); ?>">
	<p><?php _e('<strong>This Read Me Help window is draggable and resizable</strong><br><br><strong>Overview of Hackers Methods</strong><br>A lot of people go looking for and find BPS after their websites have already been hacked. If you are installing BPS Pro after your website has already been hacked then the Finder tool will help you find the hackers code very quickly. Once you have located the hackers code you can use the Replacer / Remover Tool to replace or remove the hackers code. There is no magic function that will automatically fix your website after is has been hacked because the functions that the hackers use are legitmate internal PHP functions. With that said there are functions that hackers are more likely to use such as <strong>base64_encode, base64_decode</strong> and PHP functions that deal with or perform remote execution of scripts. Hackers will usually try and place backdoor scripts on your site and hide those scripts by encoding and decoding them with base64 code. The most ideal scenario that a hacker will try to achieve is to be able to remotely control or automate script execution on your website with a backdoor script. This means that there is going to be a string that you can search for that will give away their script. So finding the right string to search for is the key and is something that cannot be magically determined and requires that you use logic to figure out the string to search for. You will need to know the approximate time that your website was hacked so that you can check your server logs for clues. All you need is one string to search for and it will be found in your server log. That string will lead to all of the hackers script or scripts. You will need to have a basic understanding of PHP coding when you find the hackers script to determine exactly what the script is doing. The primary hacking script may be auto-generating or auto-creating additional scripts on your site. If your site was hacked using SQL Injection then a traceable log entry may not show up in your logs depending on how the SQL Injection was achieved on your WordPress Database.<br><br><strong>Practical Uses For The String / Function Finder</strong><br>The Finder will find any string anywhere within your hosting account. The Finder will search starting from the folder path you enter and search all subfolders of that folder path. You can search for PHP function names or any string pattern. The Finder is not searching your WordPress Database. Use the DB String Finder if you want to search your database instead of your files. The Finder can search within all of your files on your host server under your hosting account and will display the full paths and the code line numbers where the string you are searching for was found. The string search term is highlighted in the returned search results. The string search is case sensitive so the string you enter must match exactly. Capital and lowercase letters must match exactly. A string search could contain several words in the string you are searching for, but the Finder is not designed to search for different instances of strings such as a search for and/or string searches, it is designed to find an exact string match, whether the string is one word or several words or HTML characters or PHP code or whatever else you are searching for. The Finder is looking for an exact match for whatever string search term you enter into the Search String window. If the search term you enter is part of a word or a longer string then the entire word or string is returned in the search results with your exact string search term highlighted.<br><br><strong>Examples:</strong><br>&bull; Search for the <strong>base64_encode and base64_decode</strong> functions commonly used by hacking scripts to find any instances of these functions in your files. If you find some base64 code that you would like to decode then use the BPS Base64 Decoder to decode it.<br>&bull; Search in your WordPress Plugins folder for functions that your plugins may be using to determine if the function is being used by a plugin or not and can be added to your custom php.ini file under the disable_functions Directive. If a plugin is using a PHP function that is considered dangerous then you will have to make a decision whether or not to block this function. This may cause the plugin to stop working altogether or it may just block a particular function that the plugin is performing, but the plugin itself will continue to work<br>&bull; Another example of searching for a php function that is considered very dangerous and a commonly targeted php function by hackers. Do a search of your Plugins folder for the <strong>mysql_pconnect</strong> function. You should hopefully see <strong>No Results Returned</strong> instead of finding this function in any of your plugins. It is very bad news and hackers specifically look for this function to exploit it. If you do find this function in any of your plugins then I recommend that you remove the plugin immediately and delete all the plugin files from your website.<br>&bull; Search for any string pattern such as portions of a known hacking string or identifying word used in mass site hacking injections<br>&bull; As a test, search for the word <strong>viagra</strong> in your WP Plugins folder and you will find a BPS test file that contains the word viagra and this help info will also be displayed in your search results. An example use for this particular type of search would be if your site was hacked prior to finding and installing BPS and the hacker has added viagra links to your site then the Finder will display all the files and code line numbers where those links have been added. If no instances of the search term viagra was found in any of your files then this would mean that either code is being coded using base64 to hide it or the links are dynamically being pulled from your WordPress Database indicating that the hacker has used SQL Injection to inject the hacking code into your database. You can use the DB String Finder Tool to search your database for strings.<br><br><strong>BPS Pro Video Tutorial links can be found in the Help & FAQ pages.</strong>'); ?></p>
</div>

<?php
// String Finder
class FileSystemStringSearch 
{ 
    var $_searchPath; 
    var $_searchString; 
    var $_searchResults; 

function FileSystemStringSearch($searchPath, $searchString) { 
	$this->_searchPath = $searchPath; 
	$this->_searchString = $searchString; 
	$this->_searchResults = array(); 
} 

function isValidPath() { 
	if(file_exists($this->_searchPath)) { 
	return true; 
	} else { 
	return false; 
	} 
} 

function searchPathIsFile() { 
	if(substr($this->_searchPath, -1, 1)=='/' || substr($this->_searchPath, -1, 1)=='\\') { 
	return false; 
	} else { 
	return true; 
	} 
} 

function searchFileForString($file) { 
$fileLines = file($file); 
$lineNumber = 1; 
	foreach($fileLines as $line) { 
	$searchCount = substr_count($line, $this->_searchString); 
	if($searchCount > 0) { 
	$this->addResult($file, $line, $lineNumber, $searchCount); 
	} 
	$lineNumber++; 
	} 
} 

function addResult($filePath, $lineContents, $lineNumber, $searchCount) { 
	$this->_searchResults[] = array('filePath' => $filePath, 'lineContents' => $lineContents, 'lineNumber' => $lineNumber, 'searchCount' => $searchCount); 
    } 

function highlightSearchTerm($string) { 
	return str_replace($this->_searchString, '<span style="background-color:#FFFF99"><strong>'.$this->_searchString.'</strong></span>', $string); 
} 

function scanDirectoryForString($dir) { 
	$subDirs = array(); 
	$dirFiles = array(); 
         
	$dh = opendir($dir); 
	while(($node = readdir($dh)) !== false) { 
	if(!($node=='.' || $node=='..')) { 
	if(is_dir($dir.$node)) { 
	$subDirs[] = $dir.$node.'/'; 
	} else { 
	$dirFiles[] = $dir.$node; 
	} 
	} 
} 

foreach($dirFiles as $file) { 
	$this->searchFileForString($file); 
} 

	if(count($subDirs) > 0) { 
	foreach($subDirs as $subDir) { 
	$this->scanDirectoryForString($subDir); 
	} 
	} 
} 

function run() { 
	if($this->isValidPath()) { 
	if($this->searchPathIsFile()) { 
	$this->searchFileForString($this->_searchPath); 
	} else { 
	$this->scanDirectoryForString($this->_searchPath); 
	} 
          
	} else { 
	die('FileSystemStringSearch Error: File/Directory does not exist'); 
	} 
} 

function getResults() { 
	 return $this->_searchResults; 
} 
     
function getResultCount() { 
	$count = 0; 
	foreach($this->_searchResults as $result) { 
	$count += $result['searchCount']; 
 	} 
	return $count; 
} 
     
function getSearchPath() { 
	return $this->_searchPath; 
} 
     
function getSearchString() { 
	return $this->_searchString; 
    } 
}  

if (!isset($_POST['bpsStringFinder'])) {
$bps_max_execution_time = ini_get('max_execution_time'); 
	_e('<strong>Current Max Script Execution Time: </strong>'.$bps_max_execution_time.' seconds<br>');
	_e('<strong>String Search Max Script Execution Time: </strong> Set to a 280 second Maximum<br><br>');
	}

// Find strings based on user input
function bpsStringFinder() {
if (isset($_POST['bpsStringFinder']) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_string_finder' );
	set_time_limit(250); // 250 + 30 = 280 leaving a 20 second buffer

$bpsStringPath = $_POST['bpsStringPath'];
$bpsString = $_POST['bpsString'];
$searcher = new FileSystemStringSearch("$bpsStringPath", "$bpsString"); 
$searcher->run(); 

if($searcher->getResultCount() > 0) { 
    echo('<p>Searched "'.$searcher->getSearchPath().'" for string <strong>"'.$searcher->getSearchString().'":</strong></p>'); 
    echo('<p>Search string found <strong>'.$searcher->getResultCount().' times.</strong></p>'); 
    echo('<div class="StringFinder">');
	echo('<ul>'); 
        foreach($searcher->getResults() as $result) { 
            echo('<li><font color="blue"><em>'.$result['filePath'].', line '.$result['lineNumber'].'</em></font><br />'.$searcher->highlightSearchTerm(htmlspecialchars($result['lineContents'])).'</li>'); 
        } 
    echo('</ul>');
	echo('</div>'); 
	} else { 
    echo('<p>Searched "'.$searcher->getSearchPath().'" for string <strong>"'.$searcher->getSearchString().'":</strong></p>'); 
    echo('<p>No results returned</p>'); 
	}
	}
}
?>

<?php echo('<strong>Website Root Path: </strong>'. ABSPATH); ?><br />
<?php echo('<strong>Document Root Path: </strong>'. $_SERVER['DOCUMENT_ROOT']); ?>
<br /><br />
<form name="bpsStringFinder" action="admin.php?page=bulletproof-security/admin/tools/tools.php#bps-tabs-6" method="post">
<?php wp_nonce_field('bulletproof_security_string_finder'); ?>
<strong><label for="bps-string-finder"><?php _e('Find Strings / Functions:'); ?> </label></strong><br />
<label for="bps-string-finder"><strong><?php _e('Search String: '); ?></strong></label><br />
<input type="text" name="bpsString" value="" size="100"/><br />
<label for="bps-string-finder"><strong><?php _e('Search Path: '); ?></strong></label><br />
<input type="text" name="bpsStringPath" value="" size="100"/>
<input type="hidden" name="bpsIF3" value="bps-string-finder3" />
<p class="submit">
<input type="submit" name="bpsStringFinder" class="button" value="<?php esc_attr_e('Find String') ?>" /></p>
<?php echo bpsStringFinder(); ?>
</form>
</td>
  </tr>
  <tr>
    <td class="bps-table_cell_bottom">&nbsp;</td>
  </tr>
</table>
<?php } ?>
</div>

<div id="bps-tabs-7" class="bps-tab-page">
<h2><?php _e('String Replacer / Remover ~ Preview Mode &amp; Write Mode'); ?></h2>
<?php if (!current_user_can('manage_options')) { echo 'Permission Denied'; } else { ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bps-help_faq_table">
  <tr>
    <td class="bps-table_title">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_help">
    <h3><?php _e('Preview Mode'); ?>  <button id="bps-open-modal6" class="bps-modal-button"><?php _e('Read Me'); ?></button></h3>
    <div id="bps-modal-content6" title="<?php _e('BPS String Replacer Preview Mode'); ?>">
	<p><?php _e('<strong>This Read Me Help window is draggable and resizable</strong><br><br><strong>BPS String Replacer / Remover ~ Preview Mode</strong><br><br>The String Replacer / Remover Preview Mode allows you to preview the string replacement or string removal you want to perform before you use the Replacer / Remover ~ Write Mode to actually write the new string or remove the string. The string replacement that is performed in Preview Mode is only visually replacing the string and is not actually changing or writing a new string.<br><br><strong>Using the Replacer / Remover ~ Preview Mode</strong><br>&bull; Enter the string you want to search for in the <strong>Search String:</strong> window. The string search is case sensitive so the string you enter must match exactly. Capital and lowercase letters must match exactly.<br>&bull; Enter the string you want to replace your Search String with in the <strong>Replacement String:</strong> window.<br>&bull; Enter the folder path where you want to search in the <strong>Search Path:</strong> window. This will search all files in the folder path that you add including all subfolders of this folder path.<br><br>There is a test file in the BPS /test folder that you can use for both the Preview Mode and the Write Mode String Replacement testing. Enter <strong>bpsBogusString</strong> in the <strong>Search String:</strong> window - The string search is case sensitive so the string you enter must match exactly. Capital and lowercase letters must match exactly, Enter <strong>TestString</strong> in the <strong>Replacement String:</strong> window, enter the folder path to your WordPress Plugins folder in the <strong>Search Path:</strong> window and click the Replace Strings button. You should see that the search string <strong>bpsBogusString</strong> was found and replaced 9 times with <strong>TestString</strong> and that will aslo include this help file as well because the string exists in this help file.<br><br><strong>BPS Pro Video Tutorial links can be found in the Help & FAQ pages.</strong>'); ?></p>
</div>

<?php
if (!isset($_POST['bpsStringReplacerFP'])) {
$bps_max_execution_time = ini_get('max_execution_time'); 
	_e('<strong>Current Max Script Execution Time: </strong>'.$bps_max_execution_time.' seconds<br>');
	_e('<strong>String Replacer Max Script Execution Time: </strong> Set to a 280 second Maximum<br><br>');
	}

// PREVIEW MODE - Replace all instances of search string with user input string
function bpsStringReplacer() {
if (isset($_POST['bpsStringReplacer']) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_string_replacer' );
	set_time_limit(250); // 250 + 30 = 280 leaving a 20 second buffer
	
$bpsStringPath = $_POST['bpsStringPath'];
$bpsString = $_POST['bpsString'];
$bpsStringReplacement = $_POST['bpsStringReplacement'];
$searcher = new FileSystemStringSearch("$bpsStringPath", "$bpsString");
$searcher->run(); 

if($searcher->getResultCount() > 0) { 
    echo('<p>Searched "'.$searcher->getSearchPath().'" for string <strong>"'.$searcher->getSearchString().'":</strong></p>'); 
    echo('<p>Search string found and replaced <strong>'.$searcher->getResultCount().' times.</strong></p>'); 
    echo('<div class="StringFinder">');
	echo('<ul>'); 
        foreach($searcher->getResults() as $result) { 
			echo('<li><font color="blue"><em>'.$result['filePath'].', line '.$result['lineNumber'].'</em></font><br />'.$searcher->highlightSearchTerm(stripslashes(str_replace($bpsString, '<span style=\"background-color:#FFFF99\"><strong>'.htmlspecialchars($bpsStringReplacement).'</strong></span>', htmlspecialchars($result['lineContents'])))).'</li>');
        } 
    echo('</ul>');
	echo('</div>'); 
	} else { 
    echo('<p>Searched "'.$searcher->getSearchPath().'" for string <strong>"'.$searcher->getSearchString().'":</strong></p>'); 
    echo('<p>No results returned</p>'); 
	}
	}
}
?>

<?php echo('<strong>Website Root Path: </strong>'. ABSPATH); ?><br />
<?php echo('<strong>Document Root Path: </strong>'. $_SERVER['DOCUMENT_ROOT']); ?>
<br /><br />
<form name="bpsStringReplacer" action="admin.php?page=bulletproof-security/admin/tools/tools.php#bps-tabs-7" method="post">
<?php wp_nonce_field('bulletproof_security_string_replacer'); ?>
<strong><label for="bps-string-replacer"><?php _e('Find Strings / Functions:'); ?> </label></strong><br />
<label for="bps-string-replacer"><strong><?php _e('Search String: '); ?></strong></label><br />
<input type="text" name="bpsString" value="" size="100"/><br />
<label for="bps-string-replacer"><strong><?php _e('Replacement String: '); ?></strong></label><br />
<input type="text" name="bpsStringReplacement" value="" size="100"/><br />
<label for="bps-string-replacer"><strong><?php _e('Search Path: '); ?></strong></label><br />
<input type="text" name="bpsStringPath" value="" size="100"/>
<input type="hidden" name="bpsIF3" value="bps-string-finder3" />
<p class="submit">
<input type="submit" name="bpsStringReplacer" class="button" value="<?php esc_attr_e('Replace String') ?>" /></p>
<?php echo bpsStringReplacer(); ?>
</form>

<?php
// REPLACE / WRITE MODE - Replace all instances of search string with user input string
function bpsStringReplacerFP() {
if (isset($_POST['bpsStringReplacerFP']) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_string_replacerFP' );

$bpsStringPathFP = $_POST['bpsStringPathFP'];
$bpsStringFP = $_POST['bpsStringFP'];
$bpsStringReplacementFP = $_POST['bpsStringReplacementFP'];
$searcher = new FileSystemStringSearch("$bpsStringPathFP", "$bpsStringFP");
$searcher->run(); 

if($searcher->getResultCount() > 0) { 
    echo('<p>Searched "'.$searcher->getSearchPath().'" for string <strong>"'.$searcher->getSearchString().'":</strong></p>'); 
    echo('<p>Search string found and replaced <strong>'.$searcher->getResultCount().' times.</strong></p>'); 
    echo('<div class="StringFinder">');
	echo('<ul>'); 
        foreach($searcher->getResults() as $result) { 
			echo('<li><font color="blue"><em>'.$result['filePath'].', line '.$result['lineNumber'].'</em></font><br />'.$searcher->highlightSearchTerm(stripslashes(str_replace($bpsStringFP, '<span style=\"background-color:#FFFF99\"><strong>'.htmlspecialchars($bpsStringReplacementFP).'</strong></span>', htmlspecialchars($result['lineContents'])))).'</li>');
			$content = file_get_contents($result['filePath']); 
			$content = str_replace($bpsStringFP, stripslashes($bpsStringReplacementFP), $content); 
			file_put_contents($result['filePath'],$content); 
        
	$bpsStringReplaceLog = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/tools/string_replacer_log.txt';
	$timestamp = '['.date('m/d/Y g:i A').']'; 
	$fh = fopen($bpsStringReplaceLog, 'a');
 	fwrite($fh, "\r\n*********** BPS Log Entry $timestamp ************\r\n");
 	fwrite($fh, 'Search Path: '.$bpsStringPathFP."\r\n");
 	fwrite($fh, 'Search String: '.$bpsStringFP.' --- Replacement String: '.$bpsStringReplacementFP."\r\n");
 	fwrite($fh, 'Original Content: '.$result['lineContents']."\r\n");
 	fwrite($fh, 'File Path and Code Line: '.$result['filePath'].', line '.$result['lineNumber']."\r\n");
 	fclose($fh);
		} 
    echo('</ul>');
	echo('</div>');
	} else { 
    echo('<p>Searched "'.$searcher->getSearchPath().'" for string <strong>"'.$searcher->getSearchString().'":</strong></p>'); 
    echo('<p>No results returned</p>'); 
	}
	}
}
?>

<h2><?php _e('String Replacer / Remover ~ Write Mode'); ?></h2>

<div id="bpsReplacer" style="border-top:1px solid #999999;">
<h3><?php _e('Write Mode'); ?>  <button id="bps-open-modal7" class="bps-modal-button"><?php _e('Read Me'); ?></button></h3>
<div id="bps-modal-content7" title="<?php _e('BPS String Replacer Write Mode'); ?>">
	<p><?php _e('<strong>This Read Me Help window is draggable and resizable</strong><br><br><strong>BPS String Replacer / Remover ~ Write Mode</strong><br><br>The String Replacer / Remover Write Mode allows you to search and replace or remove strings throughout all of your files. Use Preview Mode first to make sure the string replacement you want to do is exactly what you want. The string replacement that is performed in Write Mode is permanent. You could of course reverse the process if you are doing a string replacement, but if you are doing a string removal and you replace a string with a blank space then you will not be able to reverse the string replacement. A log file entry is written to the String Replacer Log file each time you perform a string replacement or removal. See below for more information.<br><br><strong>Using the Replacer / Remover ~ Write Mode</strong><br>&bull; Enter the string you want to search for in the <strong>Search String:</strong> window. The string search is case sensitive so the string you enter must match exactly. Capital and lowercase letters must match exactly.<br>&bull; Enter the string you want to replace your Search String with in the <strong>Replacement String:</strong> window. If you want to remove or permanently delete a string then leave the <strong>Replacement String:</strong> window blank.<br>&bull; Enter the folder path where you want to search in the <strong>Search Path:</strong> window. This will search all files in the folder path that you add including all subfolders of this folder path.<br><br>There is a test file in the BPS /test folder that you can use for both the Preview Mode and the Write Mode String Replacement testing. Enter <strong>bpsBogusString</strong> in the <strong>Search String:</strong> window - The string search is case sensitive so the string you enter must match exactly. Capital and lowercase letters must match exactly, Enter <strong>TestString</strong> in the <strong>Replacement String:</strong> window, enter full path to the BPS Test folder /your-website-root-path-goes-here/wp-content/plugins/bulletproof-security/admin/test/ in the <strong>Search Path:</strong> window and click the Replace Strings button. You should see that the search string <strong>bpsBogusString</strong> was found and replaced 4 times with <strong>TestString</strong>. In Write Mode these strings have been permanently replaced so this test will only work once unless you reverse the process and replace the string <strong>TestString</strong> with <strong>bpsBogusString</strong>.<br><br><strong>BPS Pro String Replacer / Remover Log</strong><br>Each time you perform a string replacement or removal a log entry is made into the BPS Pro String Replacer Log file. The log file is here /wp-content/plugins/bulletproof-security/admin/tools/string_replacer_log.txt. The log file entry adds a timestamp, the Search Path, the Search String, the Replacement String, the Original Content before being modified and the File Path and Code Line that was modified. You can add the path to the Replacer Log file to an available slot in the Php.ini File Manager and use the Php.ini Editor to view the Log file online or you can download it via FTP. Adding the Log file path to the Php.ini File Manager will allow you to view the Replacer Log file at any time. The Log file is htaccess protected and cannot be viewed using a browser unless you open the file with your browser via FTP.<br><br><strong>BPS Pro Video Tutorial links can be found in the Help & FAQ pages.</strong>'); ?></p>
</div>

<form name="bpsStringReplacerFP" action="admin.php?page=bulletproof-security/admin/tools/tools.php#bps-tabs-7" method="post">
<?php wp_nonce_field('bulletproof_security_string_replacerFP'); ?>
<strong><label for="bps-string-replacer"><?php _e('Find Strings / Functions:'); ?> </label></strong><br />
<label for="bps-string-replacer"><strong><?php _e('Search String: '); ?></strong></label><br />
<input type="text" name="bpsStringFP" value="" size="100"/><br />
<label for="bps-string-replacer"><strong><?php _e('Replacement String: '); ?></strong></label><br />
<input type="text" name="bpsStringReplacementFP" value="" size="100"/><br />
<label for="bps-string-replacer"><strong><?php _e('Search Path: '); ?></strong></label><br />
<input type="text" name="bpsStringPathFP" value="" size="100"/>
<input type="hidden" name="bpsIF4" value="bps-string-finder4" />
<p class="submit">
<input type="submit" name="bpsStringReplacerFP" class="button" value="<?php esc_attr_e('Replace / Remove String') ?>" /></p>
<?php echo bpsStringReplacerFP(); ?>
</form>
</div>
</td>
  </tr>
  <tr>
    <td class="bps-table_cell_bottom">&nbsp;</td>
  </tr>
</table>
<?php } ?>
</div>

<div id="bps-tabs-8" class="bps-tab-page">

<h2><?php _e('WordPress Database String Finder'); ?></h2>

<div id="bpsDBFinder" style="border-top:1px solid #999999;">

<h3><?php _e('DB String Finder'); ?>  <button id="bps-open-modal8" class="bps-modal-button"><?php _e('Read Me'); ?></button></h3>
<div id="bps-modal-content8" title="<?php _e('DB String Finder'); ?>">
	<p><?php _e('<strong>This Read Me Help window is draggable and resizable</strong><br><br><strong>BPS DB String Finder Primary Purpose and Usage</strong><br>If your site has already been hacked prior to installing BPS Pro and you suspect or know that the hack was performed by injecting code into your database you can use the DB String Finder Tool to quickly search your database for all or part of the hackers code or a string. There is no magic function or tool that can automatically figure out what is legitimate data and what is not. You must have a starting point in your search and that starting point is an identifying string or word or pattern that the hacker has used and that you can search for. You should check your Server log files around the time your site was hacked for a starting point to look for the string to search for. Also the output of the hackers code gives you a starting point to search for the string that is outputted. Example: If a viagra link is being dynamically generated from your database and the link is not physically located in a file then you would search your WP DB for the search term viagra. If a link or whatever other code a hacker has placed on your site is located physically in files then use the file String Finder Tool to find the files where the hackers code is located. The DB String Finder searches your entire WordPress Database for the string search term you enter in the <strong>DB Search String:</strong> window. The DB String Finder is not an attempt to replace the phpMyAdmin tool that all web hosts offer and have installed. Instead it is a tool to quickly determine if you need to access and utilize phpMyAdmin to remove any hackers code. Since this DB String Finder tool searches your entire database all in one shot very quickly without having to access phpMyAdmin you will have a guide to follow that will point to exactly what needs to be changed or removed in your database using phpMyAdmin or if nothing is found then you have eliminated that code was injected into your database. A DB String Replacer Tool may be added in a later version of BPS after further debate and testing.<br><br><strong>BPS DB String Finder Explained</strong><br>The DB String search searches all DB tables, columns and rows for a string match. The string search is not case sensitive. The string search will return search results for all or part of the search term you enter. If a large amount of data is returned in your search results use the horizontal scroller located at the bottom of the search results window to scroll and view all the table data.<br><br><strong>Examples: </strong>If you searched for bulletproof_security you would get search results with instances of the string bulletproof_security highlighted and on a separate line using a line break to isolate only your string search term for easier visual identification. If you searched for Bulletproof_security with a capital B then you would get the same exact search results. If you searched for bullet you would get search results with instances of the string bullet highlighted and on a separate line for easier visual identification.<br><br>Running a DB search may generate this php warning message in your php error log <strong>PHP Warning:  Unknown: 1 result set(s) not freed. Use mysql_free_result to free result sets which were requested using mysql_query() in Unknown on line 0.</strong> You can disregard this warning. It is a known issue that is common when performing this method of searching your database.<br><br><strong>BPS Pro Video Tutorial links can be found in the Help & FAQ pages.</strong>'); ?></p>
</div>

<?php
// Credit Where Credit Is Due - Bonus Code:
// The following AnyWhereInDB bonus code does not make up the core coding of BulletProof Security Pro and is not 
// included in the price of BulletProof Security Pro as this is a free code script, snippet or example code and
// is added as a bonus feature to BulletProof Security Pro.
// Adapted, modified and recoded to work for WordPress and BPS Pro
/***********************************************************************
* @name  AnyWhereInDB
* @author Nafis Ahmad 
* @abstract This project is to find out a part of string from anywhere in database
* @version 0.22  
* @package anywhereindb
*************************************************************************/

if (!isset($_POST['bpsDBStringFinder9'])) {
$bps_max_execution_time = ini_get('max_execution_time'); 
	_e('<strong>Current Max Script Execution Time: </strong>'.$bps_max_execution_time.' seconds<br>');
	_e('<strong>DB String Finder Max Script Execution Time: </strong> Set to a 280 second Maximum<br><br>');
	}

if ( is_admin() ) {
if (current_user_can('manage_options')) {
	global $wpdb;
	$server = DB_HOST;
	$dbuser = DB_USER;
	$dbpass = DB_PASSWORD;
	$dbname = DB_NAME;
	
	// @name Databse Connection 
	// @abstract  connected with database. and without showing any error ... 
	
	$link = @mysql_connect($server, $dbuser, $dbpass);
	// Remove @ sign for testing
	//if ($link) {  
	//echo 'Connected to DB Server';
	//}
	if (!$link) {  
	echo 'Cannot Connect to DB Server';
	}
	if(!@mysql_select_db($dbname, $link)){ 
	echo 'Database Not Found';
	}
	///@endof Databse Connection  
	
	html_header();	 //  @link  html_head will printed here!! 
	
	// @abstract  Show the html search Form !!
	//endof html search form

if (isset($_POST['bpsDBStringFinder9']) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_db_string_finder9' );
	set_time_limit(250); // 250 + 30 = 280 leaving a 20 second buffer	
	
	if(!empty($_POST['search_text']))
	 // @abstract for each Search Text we seach in the database
	{	

		$search_text = mysql_real_escape_string($_POST['search_text']);
		$result_in_tables = 0;
		
		echo '<a href="javascript:hide_all()">Collapse All Result</a> 
			 <a href="javascript:show_all()">Expand All Result</a>';
		echo "<h4>DB String Search Results for: <i>". $search_text.'</i></h4>';
		
		// @abstract  table count in the database
		$sql= 'show tables';
		$res = mysql_query($sql);
		//@abstract  get all table information in row tables
		$tables = fetch_array($res);
        //$tables = array(array('album'));
		//endof table count
	
	   for($i=0;$i<sizeof($tables);$i++)
	   // @abstract  for each table of the db seaching text
	   {
			//@abstract querry bliding of each table
			$sql = 'select count(*) from '.$tables[$i]['Tables_in_'.$dbname];
			$res = mysql_query($sql);
			
			if(mysql_num_rows($res)>0)
			//@abstract Buliding search Querry, search
			{
				//@abstract taking the table data type information
				$sql = 'desc '.$tables[$i]['Tables_in_'.$dbname]; 
				$res = mysql_query($sql);
				$collum = fetch_array($res);
				
				$search_sql = 'select * from '.$tables[$i]['Tables_in_'.$dbname].' where ';
				$no_varchar_field = 0;
				
				for($j=0;$j<sizeof($collum);$j++)
				// @abstract only finding each row information
				{
						## we are searching all the fields in this table
						
						//if(substr($collum[$j]['Type'],0,7)=='varchar'|| substr($collum[$j]['Type'],0,7)=='text')
						// @abstractonly type selection part of query buliding
						// @todo seach all field in the data base put a 1 in if(1)
						// @example if(1) 
						//{
							//echo $collum[$j]->Field .'<br />';
							if($no_varchar_field!=0){$search_sql .= ' or ' ;}
							$search_sql .= '`'.$collum[$j]['Field'] .'` like \'%'.$search_text.'%\' ';			
							$no_varchar_field++;
						//} // endof type selection part of query bulidingtype selection part
						
				}//@endof for |buliding search query
				
				if($no_varchar_field>0)
				// @abstract only main searching part showing the data
				{
					$res = mysql_query($search_sql);
					$search_result = fetch_array($res);
					if(sizeof($search_result))
					// @abstract found search data showing it! 
					{
						$result_in_tables++;
						
	echo '<div class="table_name">&nbsp;&nbsp; Table : ' . $tables[$i]['Tables_in_'.$dbname] .' &nbsp;&nbsp;</div> 
		  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.
		'<span class="number_result"> Total DB String Search Results for <i>"'.$search_text .'"</i>: '.mysql_affected_rows().'</span>
		<br/>
		<div class="link_wrapper"><a href="javascript:toggle(\''.$tables[$i]['Tables_in_'.$dbname].'_sql'.'\')">SQL</a></div>
		<div id="'.$tables[$i]['Tables_in_'.$dbname].'_sql" class="sql keys"><i>'.$search_sql.'</i	></div>
		<div class="link_wrapper"><a href="javascript:toggle(\''.$tables[$i]['Tables_in_'.$dbname].'_wrapper'.'\')">Result</a></div>
		<script language="JavaScript">
		table_id.push("'.$tables[$i]['Tables_in_'.$dbname].'_wrapper");
		</script>
		<div class="wrapper" id="'.$tables[$i]['Tables_in_'.$dbname].'_wrapper">';
						
						table_arrange($search_result);
						echo '</div><br/><br/>';
					}// @endof showing found search  
					
				}//@endof  main searching 
			}//@endof  querry building and searching 

				
	   }
	   
	   if(!$result_in_tables)
	   // @abstract if result is not found
	   {
			echo '<p style="color:red;">Search String <i>'.$search_text.'</i> was not found in your WordPress Database ('.$dbname.') !</p>';
	   }
} // @endof if user can manage options for DB connection
} // @endof if isset / check admin referrer / form
	mysql_close($link); 
	}
}// @endof is_admin()
// @abstract common footer
?>

<div id="bpsDBSFForm">
<form action="admin.php?page=bulletproof-security/admin/tools/tools.php#bps-tabs-8" method="POST">
    <?php wp_nonce_field('bulletproof_security_db_string_finder9'); ?>
	<label for="search_text"> Searching Database <strong>'<?php echo $dbname ?>'</strong></label><br /><br />
    <label for="search_text"><strong><?php _e('DB Search String: '); ?></strong></label><br />
	<input type="text" name="search_text" size="100" <?php if(!empty($_POST['search_text'])) echo 'value="'.$_POST['search_text'].'"';  ?> />
	<p class="submit">
<input type="submit" name="bpsDBStringFinder9" class="button" value="<?php esc_attr_e('Find DB String') ?>" /></p>
</form>
</div>
</div>

<?php
echo '</body>';
echo '</html>';
//@endof common footer

//*********************
//* PHP functions 
//*********************
function fetch_array($res)
// @method    fetch_array
// @abstract taking the mySQL $resource id and fetch and return the result array
// @param   string| MySQL resouser 
// @return  array  
{
	   $data = array();	
	while ($row = mysql_fetch_assoc($res)) 
	{
		$data[] = $row;
	}
	return $data;
} //@endof  function fetch_array


function table_arrange($array)
// @method  table_arrange
// @abstract taking the mySQL the result array and return html Table in a string. showing the search content in a diffrent css class.
// @param  array 
// @post_data  search_text
// @return  string | html table 
{
	
	$table_data = ''; // @abstract  returning table
	
	$max =0; // @abstract  max lenth of a row
	
	$max_i =0; // @abstract  number of the row which is maximum max lenth of a row
	
	$search_text = $_POST["search_text"];
	
	for($i=0;$i<sizeof($array);$i++)
	{
		//@abstract table row 
		$table_data .= '<tr class='.(($i&1)?'"odd_row"':'"even_row"') .' >';
		//
		$j=0;
		
		foreach($array[$i] as $key => $data) 
		{
			
			//@abstract a class around the search text 
			$data = preg_replace("|($search_text)|Ui" , "<pre class=\"search_text\"><b>$1</b></pre>" , htmlspecialchars($data));
			
			$table_data .= '<td>'. $data .' &nbsp;</td>';
			
			$j++;
		}
		
		if($max<$j)
		{
			$max = $j;
			$max_i = $i;
		}
		$table_data .= '</tr>'."\n";
	}
	$table_data .= '</table></div>';
	unset($data);
	// @endof html table
	
	//@abstract populating the table head
	
	// @varname $data_a
	//@abstract  taking the highest sized array and printing the key name.
	$data_a = $array[$max_i];
	
	
	$table_head =  '<tr>';
		foreach($data_a as $key => $value) 
		{
			$table_head .= '<td class="keys">'. $key.'</td>';
		}
			
	$table_head .= '</tr>'."\n";
	//@endof populating the table head
	
	// @abstract printing the table data
	echo '<div class="table_bor">
					<table cellspacing="0" cellpadding="3" border="0" class="data_table">'.$table_head.$table_data;
}//@endof  function table_arrange

function html_header()
// @method  html_header
// @abstract showing the html header of the instance. 
// @result prints the html header
{

?>

<?php  
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
echo '<html xmlns="http://www.w3.org/1999/xhtml">';
echo '<head>'; 
?>
<!--
//----------------------------------------------------------------------------------------------------------------------//
* @name  AnyWhereInDB
* @author Nafis Ahmad happy56[at]gmail.com // http://twitter.com/happy56
* @abstract This project is to find out a part of string from anywhere in database
* @version 0.22
* @package anywhereindb
//----------------------------------------------------------------------------------------------------------------------//
-->
<!-- <title>Any where In DB || AnyWhereInDB.php</title> -->
<!-- <script language="JavaScript"> -->
<script type="text/javascript">
/* <![CDATA[ */
	//@var array| initilazed 
	var table_id =new Array();

	function hide_all()
	// @method  hide_all
	// @abstract hideing all the result tables
	
	{
		for(i=0;i<table_id.length;i++){
			document.getElementById(table_id[i]).style.display = 'none';
		}
	} //@endof function hide_all
	
	function show_all()
	// @method  show_all
	// @abstract showing all the result tables
	
	{
		for(i=0;i<table_id.length;i++){
			document.getElementById(table_id[i]).style.display = 'block';
		}
	}//@endof function show_all
	
	function toggle(id)
	// @method  toggle
	// @abstract hide/showing a html contianer 
	
	{
		
		if(get_style(id,'display') =='block')
		{
			document.getElementById(id).style.display = 'none';
		}else {

			document.getElementById(id).style.display = 'block';
		
		}
		
	}//@endof function show_all
	
	function get_style(el,styleProp)
	// @method  get_style
	// @abstract making life easier to Get CSS properties from that table.  
	{
		var x = document.getElementById(el);
		if (x.currentStyle)
			var y = x.currentStyle[styleProp];
		else if (window.getComputedStyle)
			var y = document.defaultView.getComputedStyle(x,null).getPropertyValue(styleProp);
		return y;
	}// @endof function get_style
/* ]]> */
</script>

<style>
<!--
/*
* @style by farnar.net
* @author Nurul Amin Russel 
*
*/
#bpsDBFinder h1{color:  #233E99;}
#bpsDBFinder td{ font-size:11px; font-family:arial;vertical-align:top;border:1px solid #fff;}
#bpsDBFinder a{font-size:11px; font-family:arial;}
#bpsDBFinder .table_name{background: #233E99 none repeat scroll 0% 0%;display:inline;font-size: 18px;color: rgb(255, 255, 255);border-bottom: 4px solid rgb(35, 62, 153);margin-top: 20px;}
#bpsDBFinder .wrapper{width:95%; overflow:scroll; overflow-y:hidden; margin-bottom:10px; padding:10px}
#bpsDBFinder .number_result{font-size:13px;color: #233E99;}
#bpsDBFinder .search_text{background: #FFFF99;}
#bpsDBFinder .table_bor{margin: 0pt auto;}
#bpsDBFinder .data_table{text-align: center;width:680px;cellspacing:0;cellpadding:10px;border:0;}
#bpsDBFinder .keys{background-color:#cccccc;font-size:11px; font-family:arial;}
#bpsDBFinder .odd_row{background-color:#E5E5E5 ;}
#bpsDBFinder .even_row{background-color:#f5f5f5;}
#bpsDBFinder .sql{display:none;width:680px;padding:10px;border:0;}
#bpsDBFinder .link_wrapper{margin-top:10px;} 
#bpsDBFinder .me{font-size:11px; font-family:arial;color:#333;}
-->
</style>

<?php 
echo '</head>';
echo '<body>'; 

}// @endof  function html_head
//---------------------------//
// @endof file anywhereindb.php
// @note if you have querry, need, idea, help; fell free to contact happy56[at]gmail.com
?>
</div>

<div id="bps-tabs-9" class="bps-tab-page">
<h2><?php _e('WordPress Database Table Cleaner/Remover'); ?></h2>
<?php if (!current_user_can('manage_options')) { echo 'Permission Denied'; } else { ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bps-help_faq_table">
  <tr>
    <td class="bps-table_title">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_help">
    <h3><?php _e('DB Table Cleaner / Remover'); ?>  <button id="bps-open-modal9" class="bps-modal-button"><?php _e('Read Me'); ?></button></h3>
    <div id="bps-modal-content9" title="<?php _e('DB Table Cleaner / Remover'); ?>">
	<p><?php _e('<strong>This Read Me Help window is draggable and resizable</strong><br><br><strong>BPS DB Table Cleaner / Remover</strong><br><br>EMPTYING a table means all the rows in the table will be deleted. <strong>CAUTION!!! THIS ACTION IS NOT REVERSIBLE.</strong><br><br>DROPPING a table means deleting the table.<strong>CAUTION!!! THIS ACTION IS NOT REVERSIBLE.</strong><br><br><strong>BPS Pro Video Tutorial links can be found in the Help & FAQ pages.</strong>'); ?></p>
</div>

<?php
// Credit Where Credit Is Due - Bonus Code:
// The following DB Table Cleaner / Remover bonus code does not make up the core coding of BulletProof Security Pro and is 
// not included in the price of BulletProof Security Pro as this is a free code script, snippet or example code and is added
// as a bonus feature to BulletProof Security Pro.
// Code Written By:																	
// - Lester "GaMerZ" Chan															
// - http://lesterchan.net															
### Check Whether User Can Manage Database
//if(!current_user_can('manage_options')) {
//	die('Access Denied');
//}

### Form Processing 
if(@$_POST['bpsDBCleaner-Submit']) {
	// Lets Prepare The Variables
	$emptydrop = $_POST['emptydrop'];

	// Decide What To Do
	switch($_POST['bpsDBCleaner-Submit']) {
		case __('Empty/Drop'):
			check_admin_referer('bulletproof_security_db_cleaner');
			$empty_tables = array();
			if(!empty($emptydrop)) {
				foreach($emptydrop as $key => $value) {
					if($value == 'empty') {
						$empty_tables[] = $key;
					} elseif($value == 'drop') {
						$drop_tables .=  ', '.$key;
					}
				}
			} else {
				$text = '<font color="red">'.__('No Tables Selected.').'</font>';
			}
			$drop_tables = substr($drop_tables, 2);
			if(!empty($empty_tables)) {
				foreach($empty_tables as $empty_table) {
					$empty_query = $wpdb->query("TRUNCATE $empty_table");
					$text .= '<font color="green">'.sprintf(__('Table \'%s\' Emptied'), $empty_table).'</font><br />';
				}
			}
			if(!empty($drop_tables)) {
				$drop_query = $wpdb->query("DROP TABLE $drop_tables");
				$text = '<font color="green">'.sprintf(__('Table(s) \'%s\' Dropped'), $drop_tables).'</font>';
			}
			break;
	}
}

$tables = $wpdb->get_col("SHOW TABLES");
?>

<?php if(!empty($text)) { echo '<!-- Last Action --><div id="message" class="updated fade" style="border:1px solid #999999; margin-left:104px; margin-top:9px;"><p>'.$text.'</p></div>'; } ?>

<form name="bpsDBCleaner" action="admin.php?page=bulletproof-security/admin/tools/tools.php#bps-tabs-9" method="post">
<?php wp_nonce_field('bulletproof_security_db_cleaner'); ?>
	<h2><?php _e('Empty/Drop Tables'); ?></h2>	
	<br style="clear" />
<table class="widefat">
	<thead>
		<tr>
		<th><?php _e('Tables'); ?></th>
		<th><?php _e('Empty'); ?> <sup><?php _e('1'); ?></sup></th>
		<th><?php _e('Drop'); ?> <sup><?php _e('2'); ?></sup></th>
		</tr>
	</thead>
<?php
	foreach($tables as $table_name) {
			if(@$no%2 == 0) {
				$style = '';							
			} else {
				$style = ' class="alternate"';
			}
			@$no++;
	echo "<tr $style><th align=\"left\" scope=\"row\">$table_name</th>\n";
	echo "<td><input type=\"radio\" id=\"$table_name-empty\" name=\"emptydrop[$table_name]\" value=\"empty\" />&nbsp;<label for=\"$table_name-empty\">".__('Empty').'</label></td>';
	echo "<td><input type=\"radio\" id=\"$table_name-drop\" name=\"emptydrop[$table_name]\" value=\"drop\" />&nbsp;<label for=\"$table_name-drop\">".__('Drop').'</label></td></tr>';
					}
				?>
	<tr>
		<td colspan="3">
		<?php _e('1. EMPTYING a table means all the rows in the table will be deleted. <strong>CAUTION!!! THIS ACTION IS NOT REVERSIBLE.</strong>'); ?>
		<br />
		<?php _e('2. DROPPING a table means deleting the table. <strong>CAUTION!!! THIS ACTION IS NOT REVERSIBLE.</strong>'); ?>				
	</td>
	</tr>
	<tr>
	<td colspan="3" align="center"><input type="submit" name="bpsDBCleaner-Submit" value="<?php _e('Empty/Drop'); ?>" class="button" onClick="return confirm('<?php _e('You Are About To Empty Or Drop The Selected Database Tables.\nThis Action Is Not Reversible.\n\n Choose [Cancel] to stop, [Ok] to delete.'); ?>')" />&nbsp;&nbsp;<input type="button" name="cancel" value="<?php _e('Cancel'); ?>" class="button" onClick="javascript:history.go(-1)" /></td>
	</tr>
</table>
</form>
</td>
  </tr>
  <tr>
    <td class="bps-table_cell_bottom">&nbsp;</td>
  </tr>
</table>
<?php } ?>
</div>

<div id="bps-tabs-10" class="bps-tab-page">
<h2><?php _e('Find DNS Records By Domain Name'); ?></h2>
<?php if (!current_user_can('manage_options')) { echo 'Permission Denied'; } else { ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bps-help_faq_table">
  <tr>
    <td class="bps-table_title">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_help">
<h3><?php _e('Get DNS Records with DNS_ALL'); ?></h3>
<form name="bpsNetwork" action="admin.php?page=bulletproof-security/admin/tools/tools.php#bps-tabs-10" method="post">
<?php wp_nonce_field('bpsNetworkTools'); ?>
<div><label for="bpsNetwork"><strong><?php _e('Enter Domain Name - Example: ait-pro.com: '); ?></strong></label><br />
    <input type="text" name="bpsNetworkHost" value="" size="50"/> <br />
    <p class="submit">
	<input type="submit" name="Submit-Network" class="button" value="<?php esc_attr_e('Get Info') ?>" /></p>
</div>
</form>

<?php 
// Request "ALL" DNS records - create $authns and $addtl arrays
if (isset($_POST['Submit-Network']) && current_user_can('manage_options')) {
	check_admin_referer( 'bpsNetworkTools' );
	$site = trim($_POST['bpsNetworkHost']);
	$result = dns_get_record($site, DNS_ALL, $authns, $addtl);

	echo "<div class=\"iniFinderStyle\">";
	echo "<pre>";
	echo "Result = ";
	print_r($result);
	echo "Auth NS = ";
	print_r($authns);
	echo "Additional = ";
	print_r($addtl);
	echo "</pre>";
	echo "</div>";
}
?>
<h3><?php _e('Get DNS Records with DNS_ANY'); ?></h3>
<form name="bpsNetworkAny" action="admin.php?page=bulletproof-security/admin/tools/tools.php#bps-tabs-10" method="post">
<?php wp_nonce_field('bpsNetworkToolsAny'); ?>
<div><label for="bpsNetwork"><strong><?php _e('Enter Domain Name - Example: ait-pro.com: '); ?></strong></label><br />
    <input type="text" name="bpsNetworkHostAny" value="" size="50"/> <br />
    <p class="submit">
	<input type="submit" name="Submit-Network-Any" class="button" value="<?php esc_attr_e('Get Info') ?>" /></p>
</div>
</form>

<?php 
// Request "ANY" DNS records - create $authns and $addtl arrays
if (isset($_POST['Submit-Network-Any']) && current_user_can('manage_options')) {
	check_admin_referer( 'bpsNetworkToolsAny' );
	$site = trim($_POST['bpsNetworkHostAny']);
	$result = dns_get_record($site, DNS_ANY, $authns, $addtl);

	echo "<div class=\"iniFinderStyle\">";
	echo "<pre>";
	echo "Result = ";
	print_r($result);
	echo "Auth NS = ";
	print_r($authns);
	echo "Additional = ";
	print_r($addtl);
	echo "</pre>";
	echo "</div>";
}
?>
</td>
  </tr>
  <tr>
    <td class="bps-table_cell_bottom">&nbsp;</td>
  </tr>
</table>
<?php } ?>
</div>

<div id="bps-tabs-11" class="bps-tab-page">
<div id="bps_tools_help_table">
<h2><?php _e('Help &amp; FAQ'); ?></h2>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bps-help_faq_table">
  <tr>
    <td colspan="2" class="bps-table_title">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_help"><a href="http://www.ait-pro.com/aitpro-blog/2841/bulletproof-security-pro/bulletproof-security-pro-overview-video-tutorial/" target="_blank"><?php _e('BPS Pro Overview Video Tutorial'); ?></a></td>
    <td class="bps-table_cell_help"><a href="http://www.ait-pro.com/aitpro-blog/2835/bulletproof-security-pro/bulletproof-security-pro-features/" target="_blank"><?php _e('BPS Pro Features'); ?></a></td>
  </tr>
  <tr>
    <td class="bps-table_cell_help"><a href="http://www.ait-pro.com/aitpro-blog/2855/bulletproof-security-pro/bulletproof-security-pro-pro-tools-video-tutorial/" target="_blank"><?php _e('Pro-Tools Video Tutorial'); ?></a></td>
    <td class="bps-table_cell_help"><a href="http://www.ait-pro.com/aitpro-blog/2837/bulletproof-security-pro/bulletproof-security-pro-screenshots/" target="_blank"><?php _e('BPS Pro Screenshots'); ?></a></td>
  </tr>
  <tr>
    <td class="bps-table_cell_help"><a href="http://www.ait-pro.com/aitpro-blog/2845/bulletproof-security-pro/bulletproof-security-pro-hover-tooltips/" target="_blank"><?php _e('Read Me Help Buttons Posted As Text For Language Translation'); ?></a></td>
    <td class="bps-table_cell_help"><a href="http://www.ait-pro.com/aitpro-blog/2843/bulletproof-security-pro/bulletproof-security-pro-questions-and-comments/" target="_blank"><?php _e('Post Questions and Comments For Assistance'); ?></a></td>
  </tr>
  <tr>
    <td class="bps-table_cell_help">&nbsp;</td>
    <td class="bps-table_cell_help">&nbsp;</td>
  </tr>
   <tr>
    <td colspan="2" class="bps-table_cell_bottom">&nbsp;</td>
  </tr>
</table>
</div>
</div>

<div id="AITpro-link">BulletProof Security Pro <?php echo BULLETPROOF_VERSION; ?> Plugin by <a href="http://www.ait-pro.com/" target="_blank" title="AITpro Website Design">AITpro Website Design</a>
</div>
</div>
</div>