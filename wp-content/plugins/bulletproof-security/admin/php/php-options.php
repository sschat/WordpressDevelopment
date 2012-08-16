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

// S-Monitor BPS Security Status for Root and wp-admin (only if problem) - displayed in BPS Only
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

// Obsolete file cleanup / deletion
echo bpsRemoveObs();

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

// Enable PHPINFO Viewer - writes a new denyall htaccess file with the users current IP address
if (isset($_POST['bps-view-phpinfo']) && current_user_can('manage_options')) {
	check_admin_referer( 'bps-view-phpinfo-check' );
	
	$bps_get_IP = $_SERVER['REMOTE_ADDR'];
	$bps_denyall_content = "order deny,allow\ndeny from all\nallow from $bps_get_IP";
	$denyall_htaccess_file = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/php/.htaccess';
	
	if (is_writable($denyall_htaccess_file)) {
	if (!$handle = fopen($denyall_htaccess_file, 'w+b')) {
         _e('<font color="red"><strong>Cannot open file' . "$denyall_htaccess_file" . '</strong></font><br>');
         exit;
    }
    if (fwrite($handle, $bps_denyall_content) === FALSE) {
        _e('<font color="red"><strong>Cannot write to file' . "$denyall_htaccess_file" . '</strong></font><br>');
        exit;
    }
    _e('<font color="green"><strong>PHPINFO File viewing is enabled for your IP address only ===' . "$bps_get_IP. Your PHPINFO file is htaccess protected." .'</strong></font><br>');
    fclose($handle);
	} else {
    _e('<font color="red"><strong>The file ' . "$denyall_htaccess_file" . ' is not writable or does not exist.</strong></font><br><strong>If this is not the problem click <a href="http://www.ait-pro.com/aitpro-blog/2566/bulletproof-security-plugin-support/bulletproof-security-error-messages" target="_blank">here</a> for more help info.</strong><br>');
	}
}

// Create the phpinfo-IP.php file - writes a new phpinfo-IP.php file with the users current IP address
if (isset($_POST['bps-create-phpinfo-multi']) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_phpinfo_create' );
	
$bps_get_IP = $_SERVER['REMOTE_ADDR'];
$phpinfoIPContent = "<?php if ".'($_SERVER['."'".'REMOTE_ADDR'."'".']'." == '$bps_get_IP') { 
phpinfo();
} else {
header(".'"Status: 404 Not Found"'.");
header(".'"HTTP/1.0 404 Not Found"'.");
exit();
}
?>";
	$phpinfoIP = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/php/phpinfo-IP.php';
	
	if (is_writable($phpinfoIP)) {
	if (!$handle = fopen($phpinfoIP, 'w+b')) {
         _e('<font color="red"><strong>Cannot open file' . "$phpinfoIP" . '</strong></font><br>');
         exit;
    }
    if (fwrite($handle, $phpinfoIPContent) === FALSE) {
        _e('<font color="red"><strong>Cannot write to file' . "$phpinfoIP" . '</strong></font><br>');
        exit;
    }
    _e('<font color="green"><strong>The phpinfo-IP.php file was created successfully with your current IP address ===' . "$bps_get_IP.".'</strong></font><br>');
    fclose($handle);
	} else {
    _e('<font color="red"><strong>The file ' . "$phpinfoIP" . ' is not writable or does not exist.</strong></font><br><strong>If this is not the problem click <a href="http://www.ait-pro.com/aitpro-blog/2566/bulletproof-security-plugin-support/bulletproof-security-error-messages" target="_blank">here</a> for more help info.</strong><br>');
	}
}

// Form - Copy the phpinfo file for the PHP Multi Viewer to the folder path and file name specified by user
// Pass user entered URL string to PHPinfo Multi Viewer
if (isset($_POST['bps-copy-phpinfo-multi']) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_phpinfo_copy' );
	
	$phpinfoIP = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/php/phpinfo-IP.php';
	$bpsSaveLocPhpinfo = trim($_POST['bps-phpinfo-save-path']);
	$bpsViewPhpinfo = trim($_POST['bps-phpinfo-url-path']);
	$bpsViewPhpinfoH = $_POST['bpsViewPhpinfoH'];

		copy($phpinfoIP, $bpsSaveLocPhpinfo);
		if (!copy($phpinfoIP, $bpsSaveLocPhpinfo)) {
	_e('<font color="red"><strong>Failed to copy your phpinfo file to '."$bpsSaveLocPhpinfo". '<br>Check that the path you entered is valid and that you have added a valid file name. Minimum folder permissions must be at least 700 in order to copy the file successfully.'.'</strong></font><br>');
   	} else {
	_e('<font color="green"><strong>Your phpinfo file was copied successfully to '."$bpsSaveLocPhpinfo".'</strong></font>');
	}
}

// Form - BPS php.ini Editor
	if (isset($_POST['submit-php2']) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_save_settings_php2' );
	
	$iniFilename = $_POST['iniFilename']; 
	$newcontentphp2 = stripslashes($_POST['newcontentphp2']);
	$options = get_option('bulletproof_security_options');
	$bps_php_ini_file_1 = $options['bpsinifiles_input_1'];
	$bps_php_ini_file_2 = $options['bpsinifiles_input_2'];
	$bps_php_ini_file_3 = $options['bpsinifiles_input_3'];
	$bps_php_ini_file_4 = $options['bpsinifiles_input_4'];
	$bps_php_ini_file_5 = $options['bpsinifiles_input_5'];
	$bps_php_ini_file_6 = $options['bpsinifiles_input_6'];
	$bps_php_ini_file_7 = $options['bpsinifiles_input_7'];
	$bps_php_ini_file_8 = $options['bpsinifiles_input_8'];
	$bps_php_ini_file_9 = $options['bpsinifiles_input_9'];
	$bps_php_ini_file_10 = $options['bpsinifiles_input_10'];
	
	if ($iniFilename == 'saveini1') {
	if ( is_writable($bps_php_ini_file_1) ) {
		$handle = fopen($bps_php_ini_file_1, 'w+b');
		fwrite($handle, $newcontentphp2);
	_e('<font color="green"><strong>Success! Your ' ."$bps_php_ini_file_1". ' file has been updated.</strong></font><br>');	
    fclose($handle);
	}
	}
	
	if ($iniFilename == 'saveini2') {
	if ( is_writable($bps_php_ini_file_2) ) {
		$handle = fopen($bps_php_ini_file_2, 'w+b');
		fwrite($handle, $newcontentphp2);
	_e('<font color="green"><strong>Success! Your ' ."$bps_php_ini_file_2". ' file has been updated.</strong></font><br>');	
    fclose($handle);
	}
	}
	
	if ($iniFilename == 'saveini3') {
	if ( is_writable($bps_php_ini_file_3) ) {
		$handle = fopen($bps_php_ini_file_3, 'w+b');
		fwrite($handle, $newcontentphp2);
	_e('<font color="green"><strong>Success! Your ' ."$bps_php_ini_file_3". ' file has been updated.</strong></font><br>');	
    fclose($handle);
	}
	}
	
	if ($iniFilename == 'saveini4') {
	if ( is_writable($bps_php_ini_file_4) ) {
		$handle = fopen($bps_php_ini_file_4, 'w+b');
		fwrite($handle, $newcontentphp2);
	_e('<font color="green"><strong>Success! Your ' ."$bps_php_ini_file_4". ' file has been updated.</strong></font><br>');	
    fclose($handle);
	}
	}
	
	if ($iniFilename == 'saveini5') {
	if ( is_writable($bps_php_ini_file_5) ) {
		$handle = fopen($bps_php_ini_file_5, 'w+b');
		fwrite($handle, $newcontentphp2);
	_e('<font color="green"><strong>Success! Your ' ."$bps_php_ini_file_5". ' file has been updated.</strong></font><br>');	
    fclose($handle);
	}
	}

	if ($iniFilename == 'saveini6') {
	if ( is_writable($bps_php_ini_file_6) ) {
		$handle = fopen($bps_php_ini_file_6, 'w+b');
		fwrite($handle, $newcontentphp2);
	_e('<font color="green"><strong>Success! Your ' ."$bps_php_ini_file_6". ' file has been updated.</strong></font><br>');	
    fclose($handle);
	}
	}

	if ($iniFilename == 'saveini7') {
	if ( is_writable($bps_php_ini_file_7) ) {
		$handle = fopen($bps_php_ini_file_7, 'w+b');
		fwrite($handle, $newcontentphp2);
	_e('<font color="green"><strong>Success! Your ' ."$bps_php_ini_file_7". ' file has been updated.</strong></font><br>');	
    fclose($handle);
	}
	}

	if ($iniFilename == 'saveini8') {
	if ( is_writable($bps_php_ini_file_8) ) {
		$handle = fopen($bps_php_ini_file_8, 'w+b');
		fwrite($handle, $newcontentphp2);
	_e('<font color="green"><strong>Success! Your ' ."$bps_php_ini_file_8". ' file has been updated.</strong></font><br>');	
    fclose($handle);
	}
	}

	if ($iniFilename == 'saveini9') {
	if ( is_writable($bps_php_ini_file_9) ) {
		$handle = fopen($bps_php_ini_file_9, 'w+b');
		fwrite($handle, $newcontentphp2);
	_e('<font color="green"><strong>Success! Your ' ."$bps_php_ini_file_9". ' file has been updated.</strong></font><br>');	
    fclose($handle);
	}
	}

	if ($iniFilename == 'saveini10') {
	if ( is_writable($bps_php_ini_file_10) ) {
		$handle = fopen($bps_php_ini_file_10, 'w+b');
		fwrite($handle, $newcontentphp2);
	_e('<font color="green"><strong>Success! Your ' ."$bps_php_ini_file_10". ' file has been updated.</strong></font><br>');	
    fclose($handle);
	}
	}
}

// Get BPS PHP error log
function bps_get_php_error_log() {
if (current_user_can('manage_options')) {
	$options = get_option('bulletproof_security_options2');
	$bps_php_error_log = $options['bps_error_log_location'];
	if (file_exists($bps_php_error_log)) {
	$bps_php_error_log = file_get_contents($bps_php_error_log);
	echo $bps_php_error_log;
	} else {
	_e('PHP Error Log File Not Found! Either the PHP Error Log Folder Location has not been set yet or the PHP Error Log Folder Location path that you set is incorrect. Click the Htaccess Protected Secure PHP Error Log Read Me Help button for more info.');
	}
	}
}

// Get Default BPS PHP error log path
function bps_get_default_php_error_log() {
if (current_user_can('manage_options')) {
	$bps_default_php_error_log = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/php/bps_php_error.log';
	if (file_exists($bps_default_php_error_log)) {
	echo $bps_default_php_error_log;
	} else {
	_e('<font color="red"><strong>The '."$bps_default_php_error_log".' was not found. Check that the file exists and is named correctly.</strong></font><br>');
	}
	}
}
	
// Get php.ini file contents from iniSelector selection
function bps_get_php_ini_file() {
if (current_user_can('manage_options')) {	
	$options = get_option('bulletproof_security_options');
	$bps_php_ini_file_1 = $options['bpsinifiles_input_1'];
	$bps_php_ini_file_2 = $options['bpsinifiles_input_2'];
	$bps_php_ini_file_3 = $options['bpsinifiles_input_3'];
	$bps_php_ini_file_4 = $options['bpsinifiles_input_4'];
	$bps_php_ini_file_5 = $options['bpsinifiles_input_5'];
	$bps_php_ini_file_6 = $options['bpsinifiles_input_6'];
	$bps_php_ini_file_7 = $options['bpsinifiles_input_7'];
	$bps_php_ini_file_8 = $options['bpsinifiles_input_8'];
	$bps_php_ini_file_9 = $options['bpsinifiles_input_9'];
	$bps_php_ini_file_10 = $options['bpsinifiles_input_10'];
	
	if (@$_POST['iniSelector'] == array(1)) {
	if (!file_exists($bps_php_ini_file_1)) {
	_e('<font color="red"><strong>The ' ."$bps_php_ini_file_1". ' file either does not exist or is not named correctly. Check that the file really exists, is named correctly and the file path is correct. You can use the Find php.ini Files search tool to find php.ini files or check for the file via FTP.</strong></font>');
	} else {
	$bps_php_ini_file_1 = file_get_contents($bps_php_ini_file_1);
	echo $bps_php_ini_file_1;
	}
	}
	
	if (@$_POST['iniSelector'] == array(2)) {
	if (!file_exists($bps_php_ini_file_2)) {
	_e('<font color="red"><strong>The ' ."$bps_php_ini_file_2". ' file either does not exist or is not named correctly. Check that the file really exists, is named correctly and the file path is correct. You can use the Find php.ini Files search tool to find php.ini files or check for the file via FTP.</strong></font>');
	} else {
	$bps_php_ini_file_2 = file_get_contents($bps_php_ini_file_2);
	echo $bps_php_ini_file_2;
	}
	}
	
	if (@$_POST['iniSelector'] == array(3)) {
	if (!file_exists($bps_php_ini_file_3)) {
	_e('<font color="red"><strong>The ' ."$bps_php_ini_file_3". ' file either does not exist or is not named correctly. Check that the file really exists, is named correctly and the file path is correct. You can use the Find php.ini Files search tool to find php.ini files or check for the file via FTP.</strong></font>');
	} else {
	$bps_php_ini_file_3 = file_get_contents($bps_php_ini_file_3);
	echo $bps_php_ini_file_3;
	}
	}
	
	if (@$_POST['iniSelector'] == array(4)) {
	if (!file_exists($bps_php_ini_file_4)) {
	_e('<font color="red"><strong>The ' ."$bps_php_ini_file_4". ' file either does not exist or is not named correctly. Check that the file really exists, is named correctly and the file path is correct. You can use the Find php.ini Files search tool to find php.ini files or check for the file via FTP.</strong></font>');
	} else {
	$bps_php_ini_file_4 = file_get_contents($bps_php_ini_file_4);
	echo $bps_php_ini_file_4;
	}
	}

	if (@$_POST['iniSelector'] == array(5)) {
	if (!file_exists($bps_php_ini_file_5)) {
	_e('<font color="red"><strong>The ' ."$bps_php_ini_file_5". ' file either does not exist or is not named correctly. Check that the file really exists, is named correctly and the file path is correct. You can use the Find php.ini Files search tool to find php.ini files or check for the file via FTP.</strong></font>');
	} else {
	$bps_php_ini_file_5 = file_get_contents($bps_php_ini_file_5);
	echo $bps_php_ini_file_5;
	}
	}

	if (@$_POST['iniSelector'] == array(6)) {
	if (!file_exists($bps_php_ini_file_6)) {
	_e('<font color="red"><strong>The ' ."$bps_php_ini_file_6". ' file either does not exist or is not named correctly. Check that the file really exists, is named correctly and the file path is correct. You can use the Find php.ini Files search tool to find php.ini files or check for the file via FTP.</strong></font>');
	} else {
	$bps_php_ini_file_6 = file_get_contents($bps_php_ini_file_6);
	echo $bps_php_ini_file_6;
	}
	}

	if (@$_POST['iniSelector'] == array(7)) {
	if (!file_exists($bps_php_ini_file_7)) {
	_e('<font color="red"><strong>The ' ."$bps_php_ini_file_7". ' file either does not exist or is not named correctly. Check that the file really exists, is named correctly and the file path is correct. You can use the Find php.ini Files search tool to find php.ini files or check for the file via FTP.</strong></font>');
	} else {
	$bps_php_ini_file_7 = file_get_contents($bps_php_ini_file_7);
	echo $bps_php_ini_file_7;
	}
	}

	if (@$_POST['iniSelector'] == array(8)) {
	if (!file_exists($bps_php_ini_file_8)) {
	_e('<font color="red"><strong>The ' ."$bps_php_ini_file_8". ' file either does not exist or is not named correctly. Check that the file really exists, is named correctly and the file path is correct. You can use the Find php.ini Files search tool to find php.ini files or check for the file via FTP.</strong></font>');
	} else {
	$bps_php_ini_file_8 = file_get_contents($bps_php_ini_file_8);
	echo $bps_php_ini_file_8;
	}
	}

	if (@$_POST['iniSelector'] == array(9)) {
	if (!file_exists($bps_php_ini_file_9)) {
	_e('<font color="red"><strong>The ' ."$bps_php_ini_file_9". ' file either does not exist or is not named correctly. Check that the file really exists, is named correctly and the file path is correct. You can use the Find php.ini Files search tool to find php.ini files or check for the file via FTP.</strong></font>');
	} else {
	$bps_php_ini_file_9 = file_get_contents($bps_php_ini_file_9);
	echo $bps_php_ini_file_9;
	}
	}

	if (@$_POST['iniSelector'] == array(10)) {
	if (!file_exists($bps_php_ini_file_10)) {
	_e('<font color="red"><strong>The ' ."$bps_php_ini_file_10". ' file either does not exist or is not named correctly. Check that the file really exists, is named correctly and the file path is correct. You can use the Find php.ini Files search tool to find php.ini files or check for the file via FTP.</strong></font>');
	} else {
	$bps_php_ini_file_10 = file_get_contents($bps_php_ini_file_10);
	echo $bps_php_ini_file_10;
	}
	}
}}	

// Form Create php.ini File - Select, Copy and Rename Host master php.ini file to location entered by user
if(!isset($_POST['Submit-IC']))
    $chosen = array(0);
    else
    if (isset($_POST['Submit-IC']) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_ini_creator' );   
	    $chosen = $_POST['iniHost'];
		
		$oneAndonePhpini = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/php/1and1-phpini.ini';
		$blueHostPhpini = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/php/bluehost-phpini.ini';
		$dreamHostPhpini = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/php/dreamhost-phpini.ini';
		$fastDomainPhpini = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/php/fastdomain-phpini.ini';
		$goDaddyPhpini = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/php/godaddy-phpini5.ini';
		$hostgatorPhpini = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/php/hostgator-phpini.ini';
		$hostMonsterPhpini = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/php/hostmonster-phpini.ini';
		$mediaTemplePhpini = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/php/mediatemple-phpini.ini';
		$netfirmsPhpini = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/php/netfirms-phpini.ini';
		$networksolutionsPhpini = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/php/networksolutions-phpini.ini';
		$standardPhpini = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/php/standard-phpini.ini';
		$bpsSaveLoc = trim($_POST['bpsini-save-path']);
		
		if ($_POST['iniHost'] == array(1)) {
		copy($oneAndonePhpini, $bpsSaveLoc);
		if (!copy($oneAndonePhpini, $bpsSaveLoc)) {
		_e('<font color="red"><strong>Failed to copy and save the 1and1 php.ini file to '."$bpsSaveLoc".'. Either the 1and1-phpini.ini master file does not exist, the destination folder path is invalid or the minimum folder permissions for the destination folder are not set to at least 700.</strong></font><br>');
   		} else {
		_e('<font color="green"><strong>The 1and1 php.ini file was created successfully in folder '."$bpsSaveLoc".'</strong></font>');
		}
		}
		if ($_POST['iniHost'] == array(2)) {
		copy($blueHostPhpini, $bpsSaveLoc);
		if (!copy($blueHostPhpini, $bpsSaveLoc)) {
		_e('<font color="red"><strong>Failed to copy and save the BlueHost php.ini file to '."$bpsSaveLoc".'. Either the bluehost-phpini.ini master file does not exist, the destination folder path is invalid or the minimum folder permissions for the destination folder are not set to at least 700.</strong></font><br>');
   		} else {
		_e('<font color="green"><strong>The BlueHost php.ini file was created successfully in folder '."$bpsSaveLoc".'</strong></font>');
		}
		}   
		if ($_POST['iniHost'] == array(3)) {
		copy($dreamHostPhpini, $bpsSaveLoc);
		if (!copy($dreamHostPhpini, $bpsSaveLoc)) {
		_e('<font color="red"><strong>Failed to copy and save the DreamHost php.ini file to '."$bpsSaveLoc".'. Either the dreamhost-phpini.ini master file does not exist, the destination folder path is invalid or the minimum folder permissions for the destination folder are not set to at least 700.</strong></font><br>');
   		} else {
		_e('<font color="green"><strong>The DreamHost php.ini file was created successfully in folder '."$bpsSaveLoc".'</strong></font>');
		}
		}
		if ($_POST['iniHost'] == array(4)) {
		copy($fastDomainPhpini, $bpsSaveLoc);
		if (!copy($fastDomainPhpini, $bpsSaveLoc)) {
		_e('<font color="red"><strong>Failed to copy and save the FastDomain php.ini file to '."$bpsSaveLoc".'. Either the fastdomain-phpini.ini master file does not exist, the destination folder path is invalid or the minimum folder permissions for the destination folder are not set to at least 700.</strong></font><br>');
   		} else {
		_e('<font color="green"><strong>The FastDomain php.ini file was created successfully in folder '."$bpsSaveLoc".'</strong></font>');
		}
		}
		if ($_POST['iniHost'] == array(5)) {
		copy($goDaddyPhpini, $bpsSaveLoc);
		if (!copy($goDaddyPhpini, $bpsSaveLoc)) {
		_e('<font color="red"><strong>Failed to copy and save the GoDaddy php5.ini file to '."$bpsSaveLoc".'. Either the godaddy-phpini5.ini master file does not exist, the destination folder path is invalid or the minimum folder permissions for the destination folder are not set to at least 700.</strong></font><br>');
   		} else {
		_e('<font color="green"><strong>The GoDaddy php5.ini file was created successfully in folder '."$bpsSaveLoc".'</strong></font>');
		}
		}
		if ($_POST['iniHost'] == array(6)) {
		copy($hostgatorPhpini, $bpsSaveLoc);
		if (!copy($hostgatorPhpini, $bpsSaveLoc)) {
		_e('<font color="red"><strong>Failed to copy and save the HostGator php.ini file to '."$bpsSaveLoc".'. Either the hostgator-phpini.ini master file does not exist, the destination folder path is invalid or the minimum folder permissions for the destination folder are not set to at least 700.</strong></font><br>');
   		} else {
		_e('<font color="green"><strong>The Hostgator php.ini file was created successfully in folder '."$bpsSaveLoc".'</strong></font>');
		}
		}
		if ($_POST['iniHost'] == array(7)) {
		copy($hostMonsterPhpini, $bpsSaveLoc);
		if (!copy($hostMonsterPhpini, $bpsSaveLoc)) {
		_e('<font color="red"><strong>Failed to copy and save the HostMonster php.ini file to '."$bpsSaveLoc".'. Either the hostmonster-phpini.ini master file does not exist, the destination folder path is invalid or the minimum folder permissions for the destination folder are not set to at least 700.</strong></font><br>');
   		} else {
		_e('<font color="green"><strong>The HostMonster php.ini file was created successfully in folder '."$bpsSaveLoc".'</strong></font>');
		}
		}
		if ($_POST['iniHost'] == array(8)) {
		copy($mediaTemplePhpini, $bpsSaveLoc);
		if (!copy($mediaTemplePhpini, $bpsSaveLoc)) {
		_e('<font color="red"><strong>Failed to copy and save the Media Temple php.ini file to '."$bpsSaveLoc".'. Either the mediatemple-phpini.ini master file does not exist, the destination folder path is invalid or the minimum folder permissions for the destination folder are not set to at least 700.</strong></font><br>');
   		} else {
		_e('<font color="green"><strong>The Media Temple php.ini file was created successfully in folder '."$bpsSaveLoc".'</strong></font>');
		}
		}
		if ($_POST['iniHost'] == array(9)) {
		copy($netfirmsPhpini, $bpsSaveLoc);
		if (!copy($netfirmsPhpini, $bpsSaveLoc)) {
		_e('<font color="red"><strong>Failed to copy and save the Netfirms php.ini file to '."$bpsSaveLoc".'. Either the netfirms-phpini.ini master file does not exist, the destination folder path is invalid or the minimum folder permissions for the destination folder are not set to at least 700.</strong></font><br>');
   		} else {
		_e('<font color="green"><strong>The Netfirms php.ini file was created successfully in folder '."$bpsSaveLoc".'</strong></font>');
		}
		}
		if ($_POST['iniHost'] == array(10)) {
		copy($networksolutionsPhpini, $bpsSaveLoc);
		if (!copy($networksolutionsPhpini, $bpsSaveLoc)) {
		_e('<font color="red"><strong>Failed to copy and save the Network Solutions php.ini file to '."$bpsSaveLoc".'. Either the networksolutions-phpini.ini master file does not exist, the destination folder path is invalid or the minimum folder permissions for the destination folder are not set to at least 700.</strong></font><br>');
   		} else {
		_e('<font color="green"><strong>The Network Solutions php.ini file was created successfully in folder '."$bpsSaveLoc".'</strong></font>');
		}
		}
		if ($_POST['iniHost'] == array(11)) {
		copy($standardPhpini, $bpsSaveLoc);
		if (!copy($standardPhpini, $bpsSaveLoc)) {
		_e('<font color="red"><strong>Failed to copy and save the Standard php.ini file to '."$bpsSaveLoc".'. Either the standard-phpini.ini master file does not exist, the destination folder path is invalid or the minimum folder permissions for the destination folder are not set to at least 700.</strong></font><br>');
   		} else {
		_e('<font color="green"><strong>The Standard php.ini file was created successfully in folder '."$bpsSaveLoc".'</strong></font>');
		}
		}
}
// Dropdown list array for iniCreator form - hosts		
function showOptionsDrop($array, $active, $echo=true){
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

$iniHost = array(' Select Host or Standard php.ini File:', '1and1', 'BlueHost', 'DreamHost', 'FastDomain', 'GoDaddy', 'HostGator', 'HostMonster', 'MediaTemple', 'Netfirms', 'Network Solutions', 'Standard php.ini File');

// Form - Select php.ini or other file to DELETE - dropdown list array by file label
// perform open and write test and unlink / delete
if(!isset($_POST['Submit-IDel']))
	$chosen3 = array(0);
	else
	if (isset($_POST['Submit-IDel']) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_ini_deleter' );
	  
		$chosen3 = $_POST['iniDeleter'];
		$options = get_option('bulletproof_security_options');
		$bps_php_ini_file_1D = $options['bpsinifiles_input_1'];
		$bps_php_ini_file_2D = $options['bpsinifiles_input_2'];
		$bps_php_ini_file_3D = $options['bpsinifiles_input_3'];
		$bps_php_ini_file_4D = $options['bpsinifiles_input_4'];
		$bps_php_ini_file_5D = $options['bpsinifiles_input_5'];
		$bps_php_ini_file_6D = $options['bpsinifiles_input_6'];
		$bps_php_ini_file_7D = $options['bpsinifiles_input_7'];
		$bps_php_ini_file_8D = $options['bpsinifiles_input_8'];
		$bps_php_ini_file_9D = $options['bpsinifiles_input_9'];
		$bps_php_ini_file_10D = $options['bpsinifiles_input_10'];
		$write_test = "";
				
	if ($_POST['iniDeleter'] == array(0)) {
	_e('<font color="red"><strong>You did not select a file to delete.</strong></font><br>');	
	}
	
	if ($_POST['iniDeleter'] == array(1)) {
		$ini1D = $options['bpsinifiles_input_1_label'];
	if (!file_exists($bps_php_ini_file_1D)) {
	_e('<font color="red"><strong>The ' . "$bps_php_ini_file_1D" . ' file does not exist or has already been deleted.</strong></font><br>');	
	} else {
		$fh = fopen($bps_php_ini_file_1D, 'a');
		fwrite($fh, '');
		fclose($fh);
		unlink($bps_php_ini_file_1D);
	_e('<font color="green"><strong>The '."$bps_php_ini_file_1D".' file has been deleted.</strong></font><br>');
   	}
	}
	
	if ($_POST['iniDeleter'] == array(2)) {
		$ini2D = $options['bpsinifiles_input_2_label'];
	if (!file_exists($bps_php_ini_file_2D)) {
	_e('<font color="red"><strong>The ' . "$bps_php_ini_file_2D" . ' file does not exist or has already been deleted.</strong></font><br>');	
	} else {	
		$fh = fopen($bps_php_ini_file_2D, 'a');
		fwrite($fh, '');
		fclose($fh);
		unlink($bps_php_ini_file_2D);
	_e('<font color="green"><strong>The '."$bps_php_ini_file_2D".' file has been deleted.</strong></font><br>');
   	}
	}
	
	if ($_POST['iniDeleter'] == array(3)) {
		$ini3D = $options['bpsinifiles_input_3_label'];
	if (!file_exists($bps_php_ini_file_3D)) {
	_e('<font color="red"><strong>The ' . "$bps_php_ini_file_3D" . ' file either does not exist or has already been deleted.</strong></font><br>');
	} else {
		$fh = fopen($bps_php_ini_file_3D, 'a');
		fwrite($fh, '');
		fclose($fh);
		unlink($bps_php_ini_file_3D);
	_e('<font color="green"><strong>The '."$bps_php_ini_file_3D".' file has been deleted.</strong></font><br>');
	}
	}
		
	if ($_POST['iniDeleter'] == array(4)) {
		$ini4D = $options['bpsinifiles_input_4_label'];
	if (!file_exists($bps_php_ini_file_4D)) {
	_e('<font color="red"><strong>The ' . "$bps_php_ini_file_4D" . ' file either does not exist or has already been deleted.</strong></font><br>');
	} else {
		$fh = fopen($bps_php_ini_file_4D, 'a');
		fwrite($fh, '');
		fclose($fh);
		unlink($bps_php_ini_file_4D);
	_e('<font color="green"><strong>The '."$bps_php_ini_file_4D".' file has been deleted.</strong></font><br>');
	}
	}
	
	if ($_POST['iniDeleter'] == array(5)) {
		$ini5D = $options['bpsinifiles_input_5_label'];
	if (!file_exists($bps_php_ini_file_5D)) {
	_e('<font color="red"><strong>The ' . "$bps_php_ini_file_5D" . ' file either does not exist or has already been deleted.</strong></font><br>');
	} else {
		$fh = fopen($bps_php_ini_file_5D, 'a');
		fwrite($fh, '');
		fclose($fh);
		unlink($bps_php_ini_file_5D);
	_e('<font color="green"><strong>The '."$bps_php_ini_file_5D".' file has been deleted.</strong></font><br>');
	}
	}

	if ($_POST['iniDeleter'] == array(6)) {
		$ini6D = $options['bpsinifiles_input_6_label'];
	if (!file_exists($bps_php_ini_file_6D)) {
	_e('<font color="red"><strong>The ' . "$bps_php_ini_file_6D" . ' file either does not exist or has already been deleted.</strong></font><br>');
	} else {
		$fh = fopen($bps_php_ini_file_6D, 'a');
		fwrite($fh, '');
		fclose($fh);
		unlink($bps_php_ini_file_6D);
	_e('<font color="green"><strong>The '."$bps_php_ini_file_6D".' file has been deleted.</strong></font><br>');
	}
	}

	if ($_POST['iniDeleter'] == array(7)) {
		$ini7D = $options['bpsinifiles_input_7_label'];
	if (!file_exists($bps_php_ini_file_7D)) {
	_e('<font color="red"><strong>The ' . "$bps_php_ini_file_7D" . ' file either does not exist or has already been deleted.</strong></font><br>');
	} else {
		$fh = fopen($bps_php_ini_file_7D, 'a');
		fwrite($fh, '');
		fclose($fh);
		unlink($bps_php_ini_file_7D);
	_e('<font color="green"><strong>The '."$bps_php_ini_file_7D".' file has been deleted.</strong></font><br>');
	}
	}
	
	if ($_POST['iniDeleter'] == array(8)) {
		$ini8D = $options['bpsinifiles_input_8_label'];
	if (!file_exists($bps_php_ini_file_8D)) {
	_e('<font color="red"><strong>The ' . "$bps_php_ini_file_8D" . ' file either does not exist or has already been deleted.</strong></font><br>');
	} else {
		$fh = fopen($bps_php_ini_file_8D, 'a');
		fwrite($fh, '');
		fclose($fh);
		unlink($bps_php_ini_file_8D);
	_e('<font color="green"><strong>The '."$bps_php_ini_file_8D".' file has been deleted.</strong></font><br>');
	}
	}

	if ($_POST['iniDeleter'] == array(9)) {
		$ini9D = $options['bpsinifiles_input_9_label'];
	if (!file_exists($bps_php_ini_file_9D)) {
	_e('<font color="red"><strong>The ' . "$bps_php_ini_file_9D" . ' file either does not exist or has already been deleted.</strong></font><br>');
	} else {
		$fh = fopen($bps_php_ini_file_9D, 'a');
		fwrite($fh, '');
		fclose($fh);
		unlink($bps_php_ini_file_9D);
	_e('<font color="green"><strong>The '."$bps_php_ini_file_9D".' file has been deleted.</strong></font><br>');
	}
	}
	
	if ($_POST['iniDeleter'] == array(10)) {
		$ini10D = $options['bpsinifiles_input_10_label'];
	if (!file_exists($bps_php_ini_file_10D)) {
	_e('<font color="red"><strong>The ' . "$bps_php_ini_file_10D" . ' file either does not exist or has already been deleted.</strong></font><br>');
	} else {
		$fh = fopen($bps_php_ini_file_10D, 'a');
		fwrite($fh, '');
		fclose($fh);
		unlink($bps_php_ini_file_10D);
	_e('<font color="green"><strong>The '."$bps_php_ini_file_10D".' file has been deleted.</strong></font><br>');
	}
	}
}

// Dropdown list array for iniDeleter form	
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

// iniDeleter Array keys - Labels shown in dropdown list
if (current_user_can('manage_options')) {
$options = get_option('bulletproof_security_options');
$ini1D = $options['bpsinifiles_input_1_label'];
$ini2D = $options['bpsinifiles_input_2_label'];
$ini3D = $options['bpsinifiles_input_3_label'];
$ini4D = $options['bpsinifiles_input_4_label'];
$ini5D = $options['bpsinifiles_input_5_label'];
$ini6D = $options['bpsinifiles_input_6_label'];
$ini7D = $options['bpsinifiles_input_7_label'];
$ini8D = $options['bpsinifiles_input_8_label'];
$ini9D = $options['bpsinifiles_input_9_label'];
$ini10D = $options['bpsinifiles_input_10_label'];
}    

$iniDeleter = array(' Select File to Delete:', "$ini1D", "$ini2D", "$ini3D", "$ini4D", "$ini5D", "$ini6D", "$ini7D", "$ini8D", "$ini9D", "$ini10D");

// Form - Select php.ini file to edit - dropdown list array by file label
// return string for template2 form hidden input field and perform open and write test
if(!isset($_POST['Submit-IS']))
	$chosen2 = array(0);
	else
	if (isset($_POST['Submit-IS']) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_ini_selector' );
	  
	    $chosen2 = $_POST['iniSelector'];
		$options = get_option('bulletproof_security_options');
		$bps_php_ini_file_1 = $options['bpsinifiles_input_1'];
		$bps_php_ini_file_2 = $options['bpsinifiles_input_2'];
		$bps_php_ini_file_3 = $options['bpsinifiles_input_3'];
		$bps_php_ini_file_4 = $options['bpsinifiles_input_4'];
		$bps_php_ini_file_5 = $options['bpsinifiles_input_5'];
		$bps_php_ini_file_6 = $options['bpsinifiles_input_6'];
		$bps_php_ini_file_7 = $options['bpsinifiles_input_7'];
		$bps_php_ini_file_8 = $options['bpsinifiles_input_8'];
		$bps_php_ini_file_9 = $options['bpsinifiles_input_9'];
		$bps_php_ini_file_10 = $options['bpsinifiles_input_10'];
		$write_test = "";
		
	if ($_POST['iniSelector'] == array(1)) {
		$iniFilename = 'saveini1';
		$ini1 = $options['bpsinifiles_input_1_label'];
	if (is_writable($bps_php_ini_file_1)) {
    if (!$handle = fopen($bps_php_ini_file_1, 'a+b')) {
	_e('<font color="red"><strong>Cannot open file' . "$bps_php_ini_file_1" . '</strong></font><br>');
    exit;
    }
    if (fwrite($handle, $write_test) === FALSE) {
    _e('<font color="red"><strong>Cannot write to file' . "$bps_php_ini_file_1" . '</strong></font><br>');
	exit;
    }
	_e('<font color="green"><strong>File Open and Write test successful! Your '."$bps_php_ini_file_1".' file is writable.</strong></font><br>');
	}
	}
	if ($_POST['iniSelector'] == array(2)) {
		$iniFilename = 'saveini2';
		$ini2 = $options['bpsinifiles_input_2_label'];
	if (is_writable($bps_php_ini_file_2)) {
    if (!$handle = fopen($bps_php_ini_file_2, 'a+b')) {
	_e('<font color="red"><strong>Cannot open file' . "$bps_php_ini_file_2" . '</strong></font><br>');
    exit;
    }
    if (fwrite($handle, $write_test) === FALSE) {
    _e('<font color="red"><strong>Cannot write to file' . "$bps_php_ini_file_2" . '</strong></font><br>');
	exit;
    }
	_e('<font color="green"><strong>File Open and Write test successful! Your '."$bps_php_ini_file_2".' file is writable.</strong></font><br>');
	}
	}
	if ($_POST['iniSelector'] == array(3)) {
		$iniFilename = 'saveini3';
		$ini3 = $options['bpsinifiles_input_3_label'];
	if (is_writable($bps_php_ini_file_3)) {
    if (!$handle = fopen($bps_php_ini_file_3, 'a+b')) {
	_e('<font color="red"><strong>Cannot open file' . "$bps_php_ini_file_3" . '</strong></font><br>');
    exit;
    }
    if (fwrite($handle, $write_test) === FALSE) {
    _e('<font color="red"><strong>Cannot write to file' . "$bps_php_ini_file_3" . '</strong></font><br>');
	exit;
    }
	_e('<font color="green"><strong>File Open and Write test successful! Your '."$bps_php_ini_file_3".' file is writable.</strong></font><br>');
	}
	}    
	if ($_POST['iniSelector'] == array(4)) {
		$iniFilename = 'saveini4';
		$ini4 = $options['bpsinifiles_input_4_label'];
	if (is_writable($bps_php_ini_file_4)) {
    if (!$handle = fopen($bps_php_ini_file_4, 'a+b')) {
	_e('<font color="red"><strong>Cannot open file' . "$bps_php_ini_file_4" . '</strong></font><br>');
    exit;
    }
    if (fwrite($handle, $write_test) === FALSE) {
    _e('<font color="red"><strong>Cannot write to file' . "$bps_php_ini_file_4" . '</strong></font><br>');
	exit;
    }
	_e('<font color="green"><strong>File Open and Write test successful! Your '."$bps_php_ini_file_4".' file is writable.</strong></font><br>');
	}
	}
	if ($_POST['iniSelector'] == array(5)) {
		$iniFilename = 'saveini5';
		$ini5 = $options['bpsinifiles_input_5_label'];
	if (is_writable($bps_php_ini_file_5)) {
    if (!$handle = fopen($bps_php_ini_file_5, 'a+b')) {
	_e('<font color="red"><strong>Cannot open file' . "$bps_php_ini_file_5" . '</strong></font><br>');
    exit;
    }
    if (fwrite($handle, $write_test) === FALSE) {
    _e('<font color="red"><strong>Cannot write to file' . "$bps_php_ini_file_5" . '</strong></font><br>');
	exit;
    }
	_e('<font color="green"><strong>File Open and Write test successful! Your '."$bps_php_ini_file_5".' file is writable.</strong></font><br>');
	}
	}
	if ($_POST['iniSelector'] == array(6)) {
		$iniFilename = 'saveini6';
		$ini6 = $options['bpsinifiles_input_6_label'];
	if (is_writable($bps_php_ini_file_6)) {
    if (!$handle = fopen($bps_php_ini_file_6, 'a+b')) {
	_e('<font color="red"><strong>Cannot open file' . "$bps_php_ini_file_6" . '</strong></font><br>');
    exit;
    }
    if (fwrite($handle, $write_test) === FALSE) {
    _e('<font color="red"><strong>Cannot write to file' . "$bps_php_ini_file_6" . '</strong></font><br>');
	exit;
    }
	_e('<font color="green"><strong>File Open and Write test successful! Your '."$bps_php_ini_file_6".' file is writable.</strong></font><br>');
	}
	}
	if ($_POST['iniSelector'] == array(7)) {
		$iniFilename = 'saveini7';
		$ini7 = $options['bpsinifiles_input_7_label'];
	if (is_writable($bps_php_ini_file_7)) {
    if (!$handle = fopen($bps_php_ini_file_7, 'a+b')) {
	_e('<font color="red"><strong>Cannot open file' . "$bps_php_ini_file_7" . '</strong></font><br>');
    exit;
    }
    if (fwrite($handle, $write_test) === FALSE) {
    _e('<font color="red"><strong>Cannot write to file' . "$bps_php_ini_file_7" . '</strong></font><br>');
	exit;
    }
	_e('<font color="green"><strong>File Open and Write test successful! Your '."$bps_php_ini_file_7".' file is writable.</strong></font><br>');
	}
	}
	if ($_POST['iniSelector'] == array(8)) {
		$iniFilename = 'saveini8';
		$ini8 = $options['bpsinifiles_input_8_label'];
	if (is_writable($bps_php_ini_file_8)) {
    if (!$handle = fopen($bps_php_ini_file_8, 'a+b')) {
	_e('<font color="red"><strong>Cannot open file' . "$bps_php_ini_file_8" . '</strong></font><br>');
    exit;
    }
    if (fwrite($handle, $write_test) === FALSE) {
    _e('<font color="red"><strong>Cannot write to file' . "$bps_php_ini_file_8" . '</strong></font><br>');
	exit;
    }
	_e('<font color="green"><strong>File Open and Write test successful! Your '."$bps_php_ini_file_8".' file is writable.</strong></font><br>');
	}
	}
	if ($_POST['iniSelector'] == array(9)) {
		$iniFilename = 'saveini9';
		$ini9 = $options['bpsinifiles_input_9_label'];
	if (is_writable($bps_php_ini_file_9)) {
    if (!$handle = fopen($bps_php_ini_file_9, 'a+b')) {
	_e('<font color="red"><strong>Cannot open file' . "$bps_php_ini_file_9" . '</strong></font><br>');
    exit;
    }
    if (fwrite($handle, $write_test) === FALSE) {
    _e('<font color="red"><strong>Cannot write to file' . "$bps_php_ini_file_9" . '</strong></font><br>');
	exit;
    }
	_e('<font color="green"><strong>File Open and Write test successful! Your '."$bps_php_ini_file_9".' file is writable.</strong></font><br>');
	}
	}
	if ($_POST['iniSelector'] == array(10)) {
		$iniFilename = 'saveini10';
		$ini10 = $options['bpsinifiles_input_10_label'];
	if (is_writable($bps_php_ini_file_10)) {
    if (!$handle = fopen($bps_php_ini_file_10, 'a+b')) {
	_e('<font color="red"><strong>Cannot open file' . "$bps_php_ini_file_10" . '</strong></font><br>');
    exit;
    }
    if (fwrite($handle, $write_test) === FALSE) {
    _e('<font color="red"><strong>Cannot write to file' . "$bps_php_ini_file_10" . '</strong></font><br>');
	exit;
    }
	_e('<font color="green"><strong>File Open and Write test successful! Your '."$bps_php_ini_file_10".' file is writable.</strong></font><br>');
	}
	}
}

// Dropdown list array for iniSelector form	
function showOptionsDrop2($array, $active, $echo=true){
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

// iniSelector Array keys - Lables shown in dropdown list
if (current_user_can('manage_options')) {
$options = get_option('bulletproof_security_options');
$ini1 = $options['bpsinifiles_input_1_label'];
$ini2 = $options['bpsinifiles_input_2_label'];
$ini3 = $options['bpsinifiles_input_3_label'];
$ini4 = $options['bpsinifiles_input_4_label'];
$ini5 = $options['bpsinifiles_input_5_label'];
$ini6 = $options['bpsinifiles_input_6_label'];
$ini7 = $options['bpsinifiles_input_7_label'];
$ini8 = $options['bpsinifiles_input_8_label'];
$ini9 = $options['bpsinifiles_input_9_label'];
$ini10 = $options['bpsinifiles_input_10_label'];
}    

$iniSelector = array(' Select php.ini File to Edit:', "$ini1", "$ini2", "$ini3", "$ini4", "$ini5", "$ini6", "$ini7", "$ini8", "$ini9", "$ini10");

$scrolltophp1 = isset($_REQUEST['scrolltophp1']) ? (int) $_REQUEST['scrolltophp1'] : 0;
$scrolltophp2 = isset($_REQUEST['scrolltophp2']) ? (int) $_REQUEST['scrolltophp2'] : 0;

// INI Finder - Finds all files with .ini extension in case php.ini file has been renamed
// will result in additional .ini files being displayed but this is a better option
function bpsiniFinder() {
if (isset($_POST['bps-ini-find-submit']) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_find_ini_files' );
	
$path = $_SERVER['DOCUMENT_ROOT'] . '/'; // use ABSPATH instead if you only want to search the current site
$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);
	foreach ($objects as $inifile) {
	if ($inifile->isFile()) {
	$ext = strtolower(substr($inifile,strlen($inifile)-3));
	if ($ext == 'ini') {
	//$fileinfo->getFilename(); // get file name without path
	$fullpath = $inifile->getPathname(); // get the full path
	echo "<div class=\"iniFinderStyle\">$fullpath</div>";
	}
}}}}

// Form - php.ini disable_functions Bad JuJu Test
if (isset($_POST['phpiniTest']) && current_user_can('manage_options')) {
	check_admin_referer( 'bps-phpini-test' );
	_e('<font color="green"><strong>Php.ini File Test Performed.  You should now see === PHP Warning:  show_source() has been disabled for security reasons === in your PHP error log if your php.ini file is set up correctly.</strong></font><br>');
	}
?>
</div>

<div class="wrap">
<h2 style="margin-left:104px;"><?php _e('P-Security ~ php.ini Security &amp; Performance'); ?></h2>

<!-- jQuery UI Tab Menu -->
<div id="bps-container">
	<div id="bps-tabs" class="bps-menu">
    <div id="bpsHead" style="position:relative; top:0px; left:0px;"><img src="<?php echo get_site_url(); ?>/wp-content/plugins/bulletproof-security/admin/images/bps-pro-logo.png" style="float:left; padding:0px 8px 0px 0px; margin:-68px 0px 0px 0px;" /></div>
	<ul>
		<li><a href="#bps-tabs-1">PHP.ini Options</a></li>
        <li><a href="#bps-tabs-2">PHP.ini File Editor</a></li>
		<li><a href="#bps-tabs-3">PHP Error Log</a></li>
		<li><a href="#bps-tabs-4">PHP Info Viewer</a></li>
        <li><a href="#bps-tabs-5">Php.ini Security Status</a></li>
        <li><a href="#bps-tabs-6">Help &amp; FAQ</a></li>
	</ul>
            
<div id="bps-tabs-1" class="bps-tab-page">
<h2><?php _e('PHP.ini Options'); ?></h2>

<div id="iniOverview" style="border-top:1px solid #999999;">
<h3><?php _e('BPS PHP.ini Overview'); ?>  <button id="bps-open-modal1" class="bps-modal-button"><?php _e('Read Me'); ?></button></h3>
<div id="bps-modal-content1" title="<?php _e('PHP.ini Overview'); ?>">
	<p><?php _e('<strong>This Read Me Help window is draggable and resizable</strong><br><br><strong>Adding a new custom php.ini file or changes made in your existing php.ini files may not show up in your PHP Server configuration file (phpinfo) for up to 15 minutes.</strong> Usually new changes or modifications to your php.ini file will be displayed in phpinfo within 5 minutes.<br><br><strong>Custom php.ini file creation is a one time thing that never needs to be done again.</strong> You only need to create a single custom php.ini file once for a Hosting account to protect all of your websites under your Hosting account. You do not need to create separate custom php.ini files for each of your websites under your Hosting account.<br><br><strong>BPS Pro php.ini Options Overview - php.ini Master Files</strong><br>The BPS Pro php.ini Master files have already been pre-made for  the 10 most commonly used web hosts. The 10 web hosts listed in the Php.in File Creator dropdown list provide web hosting for the majority of websites worldwide. We have also provided a Standard php.ini Master file that contains the same optimum security and performance settings as the 10 hosts listed in the dropdown list.  The Master php.ini files contain optimum security and perfomance settings specific to WordPress and also in general for all types of websites. Some manual editing of the Master php.ini files is required in this version of BPS Pro. Eventually custom php.ini file creation will be completed automated. Please click on the <strong>General php.ini Info and Host Specific php.ini Information</strong> link on the Help &amp; FAQ page for custom php.ini set up by Host and for Standard php.ini set up information.<br><br><strong>Php.ini AutoMagic - custom php.ini handler .htaccess code</strong><br>BPS automatically detects your Web Host and will automatically write custom php.ini handler .htaccess code to your root .htaccess file if your web host requires this code.  Please click on the <strong>AutoMagic php.ini Handler Web Hosts List</strong> link on the Help &amp; FAQ page to see a list of web hosts that BPS is automatically writing the custom php.ini handler code for. If you do not see your web host listed then please send an email to info@ait-pro.com and we will add your web hosts custom php.ini handler code to php.ini AutoMagic in BPS.<br><br><strong>php.ini  file settings are  called php.ini directives</strong><br>The technical term for php.ini file settings are called php.ini directive settings.<br><br><strong>A typo or invalid entry in your custom php.ini file will cause a 500 Internal Server Error and your website will not be viewable. Be very careful when editing your custom php.ini files not to make any typos or add invalid text.</strong> If you do see a 500 Internal Server Error then you will either need to FTP to your website or login to your web host Control Panel to manually correct the php.ini file or just delete the php.ini file and create a new one with the Php.ini File Creator.<br><br><strong>Whats going on here?</strong><br><strong>Php.ini File Finder: </strong>Search for existing php.ini files or get the path to the BPS Standard php.ini Master file, which you can then copy to any available empty slot in the php.ini File Manager.<br><br><strong>Php.ini File Creator: </strong>Create a new custom php.ini file for your website. Please click on the <strong>General php.ini Info and Host Specific php.ini Information</strong> link on the Help &amp; FAQ page for custom php.ini set up by Host and for Standard php.ini set up information. Add and save the folder path to your custom php.ini file to any available empty slot in the Php.ini File Manager.<br><br><strong>Php.ini File Manager - All Purpose File Manager: </strong>Copy and paste or type in the path to any file in any available empty slot in the File Manager to able to open and edit the file using the PHP.ini File Editor - All Purpose File Editor. Adding and saving a file path to your php.ini files or other files will allow you to edit the files using the PHP.ini File Editor - All Purpose File Editor. Add and save your own custom Label or Description. Your custom Label will be displayed in the <strong>Select php.ini file to edit:</strong> dropdown list for the PHP.ini File Editor. The BPS php.ini File Editor enables you to make any additional changes or modifications that you would like without having to use FTP or your web host Control Panel.<br><br><strong>BPS Pro Video Tutorial links can be found in the Help & FAQ pages.</strong>'); ?></p>
</div>

<h3><?php _e('Adding A Custom php.ini File Increases Website Security and Improves Website Performance'); ?></h3>
</div>
<?php if (!current_user_can('manage_options')) { echo 'Permission Denied'; } else { ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bps-help_faq_table">
  <tr>
    <td class="bps-table_title">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_help">
<h3><?php _e('Php.ini File Finder'); ?>  <button id="bps-open-modal2" class="bps-modal-button"><?php _e('Read Me'); ?></button></h3>
<div id="bps-modal-content2" title="<?php _e('Php.ini File Finder'); ?>">
	<p><?php _e('<strong>This Read Me Help window is draggable and resizable</strong><br><br><strong>Find Existing php.ini Files</strong><br>The best use of this tool is to find file paths quickly so you can then add those file paths to the php.ini File Manager. If you already have an existing php.ini file that you would like to search for and add to the Php.ini File Manager then click the Find php.ini Files button. The php.ini file finder will search for all files with a .ini file extension. This means that files other than just php.ini files may also be listed in the returned search results. The BPS host Master php.ini files will also be returned in your search. These master files are master files that your custom php.ini file is created from.  You can add the file path to a master file to the File Manager so that you can open and edit it with PHP.ini File Editor. The search could take as little as 5 seconds or as long as several minutes depending on the amount of files to search. Add and save your own custom Label or Description for each php.ini file path that you add. Your Label will be displayed in the <strong>Select php.ini file to edit:</strong> dropdown list for the PHP.ini File Editor.<br><br><strong>BPS Pro Video Tutorial links can be found in the Help & FAQ pages.</strong>'); ?></p>
</div>

<form name="bpsiniFinder" action="admin.php?page=bulletproof-security/admin/php/php-options.php" method="post">
<?php wp_nonce_field('bulletproof_security_find_ini_files'); ?>
<strong><label for="bps-ini-file-finder"><?php _e('Search for existing php.ini files by clicking the Find php.ini Files button:'); ?> </label></strong><br />
<label for="bps-ini-file-finder"><?php _e(''); ?></label>
<?php echo bpsiniFinder(); ?>
<input type="hidden" name="bpsIF" value="bps-ini-finder" />
<p class="submit">
<input type="submit" name="bps-ini-find-submit" class="button" value="<?php esc_attr_e('Find php.ini Files') ?>" /></p>
</form>

<div id="inicreator" style="border-bottom:1px solid #999999;border-top:1px solid #999999;">
<h3><?php _e('Php.ini File Creator'); ?>  <button id="bps-open-modal3" class="bps-modal-button"><?php _e('Read Me'); ?></button></h3>
<div id="bps-modal-content3" title="<?php _e('Php.ini File Creator'); ?>">
	<p><?php _e('<strong>This Read Me Help window is draggable and resizable</strong><br><br><strong>Adding a new custom php.ini file or changes made in your existing php.ini files may not show up in your PHP Server configuration file (phpinfo) for up to 15 minutes.</strong> Usually new changes or modifications to your custom php.ini file will be displayed in phpinfo within 5 minutes.<br><br><strong>Custom php.ini file creation is a one time thing that never needs to be done again.</strong> You only need to create a single custom php.ini file once for a Hosting account to protect all of your websites under your Hosting account. You do not need to create separate custom php.ini files for each of your websites under your Hosting account. If you have several different Hosting Accounts (not different websites) then ONLY 1 custom php.ini file needs to be created per Hosting Account.<br><br><strong>Quick Setup Instructions By Web Host Name</strong> (if your Web Host is not listed here see <strong>Choosing the Standard php.ini Master File</strong>)<br>BPS detects your Web Host and will automatically write your Web Hosts php.ini handler code (if your Web Host requires this code) to your Root .htaccess file when you click the AutoMagic buttons in B-Core. The php.ini handler code will be created with AutoMagic <strong>ONLY IF YOUR WEB HOST HAS BEEN ADDED TO THE BPS WEB HOST LIST.</strong> To see the complete BPS Web Host list go to the Help &amp; FAQ page and click on the <strong>AutoMagic php.ini Handler Web Hosts List</strong> link. For detailed Host specific custom php.ini set up steps go to the Help &amp; FAQ page and click on the <strong>General php.ini Info and Host Specific php.ini Information</strong> link.<br><br><strong>Example folder path (replace the x'."'".'s with your actual account path info) where your custom php.ini file should be created if your Web Host is:</strong><br><strong>1&amp;1:</strong>  /kunden/homepages/xxxxx/htdocs/php.ini<br><strong>BlueHost:</strong>/home1/xxxxx/public_html/php.ini - Also requires additional cPanel PHP Configuration.<br><strong>Compitent Web Hosting:</strong> /home/xxxxx/public_html/php.ini<br><strong>DreamHost:</strong> /.php/5.3/phprc - Requires additional Control Panel Configuration.<br><strong>FastDomain:</strong> /home1/xxxxx/public_html/php.ini - Also requires additional cPanel PHP Configuration.<br><strong>GoDaddy:</strong> /home/content/xx/xxxxx/html/php5.ini<br><strong>Host The Name:</strong> /home/xxxxx/php.ini<br><strong>HostGator:</strong> /home/xxxxx/public_html/php.ini<br><strong>HostMonster:</strong> /home1/xxxxx/public_html/php.ini - Also requires additional cPanel PHP Configuration.<br><strong>IX Web Hosting:</strong> Required: Your Host MUST create the initial php.ini file for you. You can then edit it.<br><strong>MediaTemple:</strong> /nfs/xxx/mnt/xxxx/etc/php.ini<br><strong>Netfirms:</strong> /mnt/web_b/d07/xxx/xxxx/www/php.ini<br><strong>Network Solutions:</strong> /data/21/2/75/152/xxxxx/user/xxxxx/cgi-bin/php.ini<br><strong>pair Networks Web Hosting:</strong> /usr/www/users/xxxxx/cgi-bin/php.ini<br><strong>ServInt Web Hosting:</strong> /home/xxxxx/public_html/php.ini<br><strong>SKGOLD Web Hosting:</strong> /home/xxxxx/php.ini<br><strong>WiredTree Web Hosting:</strong> /home/xxxxx/public_html/php.ini<br><br><strong>Choosing the Standard php.ini Master File</strong><br>If your Web Host is not listed here then you will be choosing the BPS Standard php.ini File to create your new custom php.ini file from. For step by step custom php.ini set up instructions, go to the Help & FAQ page and click on the <strong>General php.ini Info and Host Specific php.ini Information</strong> link. Let us know what your Web Host name is and we will add it to the BPS Web Hosts list. If your Web Host is not list and you would like for us to set up your custom php.ini file send an email to info@ait-pro.com. Also if your Web Host requires php.ini handler code for your .htaccess file we can also add that to AutoMagic so that you will not have to manually add that code to your Root .htaccess file.<br><br><strong>Additional Info About Web Hosts and Custom php.ini Files</strong><br>Most Web Hosts require that you create a single custom php.ini file in your Document Root folder. Some Web Hosts require that your custom php.ini file is created in a folder that they specify. Some Web Hosts have additional Control Panel or cPanel setting requirements. Some Web Hosts require that additional custom php.ini handler .htaccess code is added to your root .htaccess file. If you Web Host is on the list of BPS Web Hosts detected then your custom php.ini handler will automatically be created for you with AutoMagic. If your Web Host is not on the list send us an email and we will add your Web Hosts custom php.ini handler code to AutoMagic.<br><br><strong>Editing a BPS Master php.ini Files First Before Creating Your Custom php.ini File From It</strong><br>Use the Php.ini File Finder to search for all php.ini files. Copy the path to the BPS Master php.ini file that you want to edit to any available empty slot in the File Manager and edit it with the PHP.ini File Editor first before using the Php.ini File Creator to create your custom php.ini file from it. See the File Manager Read Me button for more information.<br><br><strong>Additional General Info About Creating New Custom php.ini Files</strong><br>Use the Php.ini File Creator to create your custom php.ini file from the BPS Master php.ini files. Choose your Host from the dropdown Host select list or choose the Standard php.ini file and then add the folder path where you want your new php.ini file created and saved too. Before clicking the Create php.ini File button copy the folder path you just added and then go ahead and click the Create php.ini File button. Now go to the Php.ini File Manager and paste the php.ini folder path you just copied, to any available text boxes in the Php.ini File Manager, type in a Label or Description for this php.ini files folder path in the <strong>Add Your Label / Description</strong> text box and click the Save Changes button to save your new php.ini folder path and Label. Your personal Label or Description will be displayed in the <strong>Select php.ini file to edit:</strong> dropdown list for the PHP.ini File Editor. You can change your labels at any time. The labels are just labels. Example: Label 1 will open whatever php.ini file that is shown in the text box to the right of the Label 1, etc.<br><br><strong>General Info About Choosing Your Host</strong><br>If your host is listed in the <strong>Choose your Host...</strong> dropdown select list then you can find out more information about what your specific host allows, disallows or requires regarding using custom php.ini files by clicking on the Help and FAQ tab. If your host is not listed in the dropdown list then choose the Standard php.ini file Master template to create your new custom php.ini file from. To see the complete BPS Web Host list go to the Help &amp; FAQ page and click on the <strong>AutoMagic php.ini Handler Web Hosts List</strong> link. For detailed Host specific custom php.ini set up steps go to the Help &amp; FAQ page and click on the <strong>General php.ini Info and Host Specific php.ini Information</strong> link.<br><br><strong>All Web Hosts require that your custom php.ini file is named php.ini except for GoDaddy,</strong> which requires that your custom php.ini file is named php5.ini. All lowercase letters must be used when naming your php.ini or php5.ini file.<br><br><strong>BPS Pro allows you to open and view your default web host master php.ini configuration file.</strong> If your host is not listed in the dropdown list then this allows you to view or copy your web hosts master php.ini file to the your own custom php.ini file that you created using the Standard php.ini master file. What works the best is to copy your host master php.ini file to your computer and then change all the directive settings in that file with the BPS Pro optimum security and performance directive settings from the Standard php.ini file and then copy and paste the new combined file that you created into your custom php.ini file. Please watch the P-Security Video Tutorial for step by step instructions on how to create a custom php.ini file for your website.<br><br><strong>BPS Pro Video Tutorial links can be found in the Help & FAQ pages.</strong>
'); ?></p>
</div>

<form action="admin.php?page=bulletproof-security/admin/php/php-options.php" method="post">
<?php wp_nonce_field('bulletproof_security_ini_creator'); ?>
	<strong><label for="bps-ini-file-creator"><?php _e('<font color="blue">View the Read Me button above FIRST<br>before creating your custom php.ini file</font><br>'); ?></label></strong><br />
    <strong><label for="bps-ini-file-creator"><?php _e('Choose your Host or the Standard php.ini file:'); ?> </label></strong><br />
	<select name="iniHost[]" id="iniHost">
	<?php echo showOptionsDrop($iniHost, $chosen, true); ?>
	</select><br /><br />
	<label for="bps-ini-file-creator"><strong><?php _e('Your Website folder path is: '); ?></strong><?php echo ABSPATH; ?></label><br />
    <label for="bps-ini-file-creator"><strong><?php _e('Your Document Root folder path is: '); ?></strong><?php echo $_SERVER['DOCUMENT_ROOT']; ?></label><br /><br />
    <strong><label for="bps-ini-file-creator"><?php _e('Add the path where your custom php.ini file will be created:'); ?></label></strong><br />
    <input type="text" name="bpsini-save-path" value="" class="regular-text-medium" />
    <p class="submit">
    <input type="submit" name="Submit-IC" value="<?php esc_attr_e('Create php.ini File') ?>" class="button" onClick="return confirm('<?php _e('Have you already clicked on the Php.ini File Creator Read Me button to view custom php.ini file set up instructions?\n\nClick OK to create a custom php.ini file or click Cancel.'); ?>')" />
    
    </p>
</form>
</div>

<div id="bpsLogFiles" style="border-bottom:1px solid #999999;">
<h3><?php _e('Additional Log Files That You Can Add To The File Manager'); ?>  <button id="bps-open-modal4" class="bps-modal-button"><?php _e('Read Me'); ?></button></h3>
<div id="bps-modal-content4" title="<?php _e(''); ?>">
	<p><?php _e('<strong>This Read Me Help window is draggable and resizable</strong><br><br><strong>Adding Log Files To The File Manager</strong><br>Copy the file path to a log file that is displayed here to any available empty slot in the File Manager, Add you label and click the Save Changes button. You can now view the log files using the Php.ini File Editor<br><br><strong>HTTP Error Log</strong><br>Add this log file path to any available empty slot in the File Manager and use the Php.ini File Editor to open and view your HTTP Error Log. The HTTP Error Log file contains 400, 403 and 404 (404 error logging requires an additional set up step) errors that occur on your website. Typically 403 errors occur when hackers are running hacking scripts against your website in an attempt to hack it.<br><br><strong>String Replacer / Remover Log</strong><br>Add this log file path to your File Manager to view string replacements or removals performed when using the Pro-Tools String Replacer / Remover in Write Mode.<br><br><strong>BPS Pro Video Tutorial links can be found in the Help & FAQ pages.</strong>'); ?></p>
</div>

<?php _e('<strong>HTTP Error Log Path:  </strong>'); echo WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/htaccess/http_error_log.txt<br>';
_e('<strong>String Replacer / Remover Log Path:  </strong>'); echo WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/tools/string_replacer_log.txt<br>';
?>
<br />
</div>

<div id="iniDeleter" style="float:right; margin:20px 20px 0px 0px;">
<form name="iniDeleterForm" action="admin.php?page=bulletproof-security/admin/php/php-options.php" method="post">
<?php wp_nonce_field('bulletproof_security_ini_deleter'); ?>
	<strong><label for="bps-ini-file-deleter"><?php _e('Delete Files:'); ?> </label></strong>
	<select name="iniDeleter[]" id="iniDeleter">
	<?php echo showOptionsDrop3($iniDeleter, $chosen3, true); ?>
	</select>
	<input type="submit" name="Submit-IDel" value="<?php _e('Delete'); ?>" class="button" onClick="return confirm('<?php _e('Are you sure?\n\nClick OK to delete the file or click Cancel.\n\nAfter deleting the file delete the Label and File Path from the File Manager. '); ?>')" />
</form>
</div>

<h3><?php _e('Php.ini File Manager - All Purpose File Manager'); ?>  <button id="bps-open-modal5" class="bps-modal-button"><?php _e('Read Me'); ?></button></h3>
<div id="bps-modal-content5" title="<?php _e('Php.ini / All Purpose File Manager'); ?>">
	<p><?php _e('<strong>This Read Me Help window is draggable and resizable</strong><br><br>
<strong>All Purpose File Manager Explained</strong><br>You can open, edit and delete any type of file located anywhere under your hosting account. The File Manager and the File Editor work together. Paths to files that you add to the File Manager are files that you can now open using the File Editor. You can also open and view Server protected files that would normally require using SSH to open and view them. For example  you can open and view your web hosts Master default php.ini file, log files, additional .htaccess files, php files, .htpasswd files and any other types of files. If the file is editable you can edit it using the Php.ini File Editor. <br>
<br>The php.ini File Manager allows you to save the folder path to your existing or newly created custom php.ini files so that you can edit your custom php.ini files using the PHP.ini File Editor. The folder path to your php.ini files and your Label or Description is saved to your WordPress Database so that your saved information is saved permanently until you delete it. Your saved information will also remain saved to the database when upgrading BPS Pro. Your personal label or description will be displayed in the <strong>Select php.ini file to edit:</strong> dropdown list for the PHP.ini File Editor - All Purpose File Editor.<br><br>The php.ini File Manager also allows you to add the path to a BPS Master php.ini file so that you can view and edit the Master php.ini file first before using the Php.ini File Creator to create your custom php.ini file. Example path to the GoDaddy Master php.ini file: /home/content/xx/xxxx/html/wp-content/plugins/bulletproof-security/admin/php/godaddy-phpini5.ini. All the host php.ini Master files are located in this folder /wp-content/plugins/bulletproof-security/admin/php/. Use the phpinfo viewer to get your web hosts master php.ini file path. You can add this path to any available empty slot in the File Manager and open it with the File Editor to view your host’s master php.ini file to get your correct specific Zend directives or any other specific directives for your web host / website. Watch the P-Security Video Tutorial for a step by step example.<br><br><strong>Most Web Hosts  Allow Only One Custom php.ini File Per Hosting Account</strong><br>If your web host requires or only allows you to use 1 php.ini file for your entire website hosting account then you will only need to use one text box to save your php.ini file folder path.<br><br><strong>Web Hosts That  Require or Allow Multiple Custom php.ini File Per Hosting Account</strong><br>Some Web Hosts (very few) require that you use multiple php.ini files, one php.ini file for each specific folder or website folder under your website domain and hosting account where you want to control your PHP configuration. If your host requires or allows that you create multiple php.ini files then you can add up to 10 php.ini files / file paths to manage using the Php.ini File Manager. More slots will be added in a later  BPS Pro version.<br><br><strong>XAMPP or MAMPP Info</strong><br>If you are using XAMPP or MAMPP you will need to manually enter double backslashes in your file paths in order for the file path to be seen correctly. Example: Double backslashes after C:, double backslashes after xampp and double backslashes after htdocs.<br><br><strong>BPS Pro Video Tutorial links can be found in the Help & FAQ pages.</strong>'); ?></p>
</div>

<div style="float:left;font-weight:bold;"><?php _e('Add Your Label / Description'); ?></div>
<div style="float:right; margin-right:400px;font-weight:bold;"><?php _e('Add The File Path To Your php.ini File or Other Type of File'); ?></div>
<form name="bps-ini-options" action="options.php" method="post">
			<?php settings_fields('bulletproof_security_options'); ?>
			<?php $options = get_option('bulletproof_security_options'); ?>
            <table class="form-table">
            <tr valign="top"><th scope="row"><input type="text" name="bulletproof_security_options[bpsinifiles_input_1_label]" value="<?php echo $options['bpsinifiles_input_1_label']; ?>" class="regular-text-label" /></th>
					<td><input type="text" name="bulletproof_security_options[bpsinifiles_input_1]" value="<?php echo $options['bpsinifiles_input_1']; ?>" class="regular-text-wide" /></td>
			  </tr>
                <tr valign="top"><th scope="row"><input type="text" name="bulletproof_security_options[bpsinifiles_input_2_label]" value="<?php echo $options['bpsinifiles_input_2_label']; ?>" class="regular-text-label" /></th>
					<td><input type="text" name="bulletproof_security_options[bpsinifiles_input_2]" value="<?php echo $options['bpsinifiles_input_2']; ?>" class="regular-text-wide" /></td>
				</tr>
                <tr valign="top"><th scope="row"><input type="text" name="bulletproof_security_options[bpsinifiles_input_3_label]" value="<?php echo $options['bpsinifiles_input_3_label']; ?>" class="regular-text-label" /></th>
					<td><input type="text" name="bulletproof_security_options[bpsinifiles_input_3]" value="<?php echo $options['bpsinifiles_input_3']; ?>" class="regular-text-wide" /></td>
				</tr>
                <tr valign="top"><th scope="row"><input type="text" name="bulletproof_security_options[bpsinifiles_input_4_label]" value="<?php echo $options['bpsinifiles_input_4_label']; ?>" class="regular-text-label" /></th>
					<td><input type="text" name="bulletproof_security_options[bpsinifiles_input_4]" value="<?php echo $options['bpsinifiles_input_4']; ?>" class="regular-text-wide" /></td>
				</tr>
                <tr valign="top"><th scope="row"><input type="text" name="bulletproof_security_options[bpsinifiles_input_5_label]" value="<?php echo $options['bpsinifiles_input_5_label']; ?>" class="regular-text-label" /></th>
					<td><input type="text" name="bulletproof_security_options[bpsinifiles_input_5]" value="<?php echo $options['bpsinifiles_input_5']; ?>" class="regular-text-wide" /></td>
				</tr>
                <tr valign="top"><th scope="row"><input type="text" name="bulletproof_security_options[bpsinifiles_input_6_label]" value="<?php echo $options['bpsinifiles_input_6_label']; ?>" class="regular-text-label" /></th>
					<td><input type="text" name="bulletproof_security_options[bpsinifiles_input_6]" value="<?php echo $options['bpsinifiles_input_6']; ?>" class="regular-text-wide" /></td>
				</tr>
                <tr valign="top"><th scope="row"><input type="text" name="bulletproof_security_options[bpsinifiles_input_7_label]" value="<?php echo $options['bpsinifiles_input_7_label']; ?>" class="regular-text-label" /></th>
					<td><input type="text" name="bulletproof_security_options[bpsinifiles_input_7]" value="<?php echo $options['bpsinifiles_input_7']; ?>" class="regular-text-wide" /></td>
				</tr>
                <tr valign="top"><th scope="row"><input type="text" name="bulletproof_security_options[bpsinifiles_input_8_label]" value="<?php echo $options['bpsinifiles_input_8_label']; ?>" class="regular-text-label" /></th>
					<td><input type="text" name="bulletproof_security_options[bpsinifiles_input_8]" value="<?php echo $options['bpsinifiles_input_8']; ?>" class="regular-text-wide" /></td>
				</tr>
                <tr valign="top"><th scope="row"><input type="text" name="bulletproof_security_options[bpsinifiles_input_9_label]" value="<?php echo $options['bpsinifiles_input_9_label']; ?>" class="regular-text-label" /></th>
					<td><input type="text" name="bulletproof_security_options[bpsinifiles_input_9]" value="<?php echo $options['bpsinifiles_input_9']; ?>" class="regular-text-wide" /></td>
				</tr>
                <tr valign="top"><th scope="row"><input type="text" name="bulletproof_security_options[bpsinifiles_input_10_label]" value="<?php echo $options['bpsinifiles_input_10_label']; ?>" class="regular-text-label" /></th>
					<td><input type="text" name="bulletproof_security_options[bpsinifiles_input_10]" value="<?php echo $options['bpsinifiles_input_10']; ?>" class="regular-text-wide" /></td>
				</tr>
			</table>
			<p class="submit">
			<input type="submit" name="bps-ini-options" class="button" value="<?php esc_attr_e('Save Changes') ?>" />
			</p>
	  </form></td>
  </tr>
  <tr>
    <td class="bps-table_cell_bottom">&nbsp;</td>
  </tr>
</table>
<?php } ?>
</div>
            
<div id="bps-tabs-2" class="bps-tab-page">
<h2><?php _e('PHP.ini Editor - All Purpose File Editor'); ?></h2>

<div id="phpiniEditor" style="border-top:1px solid #999999;">
<h3><?php _e('Editing Php.ini Files'); ?>  <button id="bps-open-modal6" class="bps-modal-button"><?php _e('Read Me'); ?></button></h3>
<div id="bps-modal-content6" title="<?php _e('PHP.ini / All Purpose File Editor'); ?>">
	<p><?php _e('<strong>This Read Me Help window is draggable and resizable</strong><br><br><strong>Adding new php.ini files or changes made in your existing php.ini files may not show up in your PHP server configuration file for up to 15 minutes.</strong> Usually new changes or modifications to your php.ini file will be displayed in phpinfo within 5 minutes.<br><br>The All Purpose File Editor will allow you to open and edit any type of file under your entire hosting account. You can open and view your Web Hosts Master default php.ini file and other configuration files, but most of these files are not editable because they are in Server Protected folders.<br><br><strong>Editing Php.ini Files</strong><br>Use the <strong>Select php.ini file to edit:</strong> dropdown list to select the php.ini file that you want to edit.  If the dropdown list does not show any php.ini files to edit then you have not added any php.ini files to manage with the Php.ini File Manager yet.  Go to the PHP.ini Options page and add the folder path to your php.ini file and your personal Label or Description in the Php.ini File Manager text boxes for each php.ini file that you want to add for editing.  Your Label / Description will be now be shown in the <strong>Select php.ini file to edit:</strong> dropdown list.<br><br><strong>Php.ini File Editing Overview</strong><br>Be very careful not to make any typos when editing your php.ini files.  <strong>Typos in your php.ini file will cause 500 Internal Server Errors and your site will not load.</strong>  If you see a 500 Internal Server Error you will either need to FTP to your website or use your web host Control Panel and edit or delete the php.ini file that has the mistake in it.  The BPS master php.ini files have been pre-made and pre-tested to ensure that there are no mistakes or typos in them. If your host does not allow a particular php.ini directive to be changed or set to a setting that they do not allow then the directive will be ignored and not cause any problems with your website.<br><br><strong>Testing Your php.ini File - disable_functions Test</strong><br>This test will not work by default for the 1and1, DreamHost and MediaTemple Master php.ini files due to additional php.ini modifications required first before using this test. In order for this php.ini test to work correctly you must have either already created a php.ini file for this website or you have an existing php.ini file with the show_source function listed as one of the functions to disable in the disable_functions php.ini directive in your php.ini file. If your host allows or requires that you have custom php.ini files created for each of your websites or specific folders then you must have a php.ini file added for this specific website in order for the php.ini disable_functions test to work correctly and display a php error in your php error log. When you click the Test php.ini File button a popup window will tell you whether or not the php.ini disable_functions test was successful or unsuccessful and the main window will redirect to your PHP Error Log page.<br><br><strong>BPS Pro Video Tutorial links can be found in the Help & FAQ pages.</strong>'); ?></p>
</div>

<?php if (!current_user_can('manage_options')) { echo 'Permission Denied'; } else { ?>
<form name="phpiniTest" method="POST" action="admin.php?page=bulletproof-security/admin/php/php-options.php#bps-tabs-3" target="" onSubmit="window.open('<?php echo get_site_url(); ?>/wp-content/plugins/bulletproof-security/admin/test/bad-juju-test.php','','scrollbars=yes,menubar=yes,width=800,height=600,resizable=yes,status=yes,toolbar=yes')">
<?php wp_nonce_field('bps-phpini-test'); ?>
<input type="hidden" name="inifilename" value="bps-test-phpini-file" />
<p class="submit">
<input type="submit" name="phpiniTest" class="button" value="<?php esc_attr_e('Test php.ini File') ?>" /></p>
</form>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bps-help_faq_table">
  <tr>
    <td class="bps-table_title">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_help">
    <div id="iniselector">
<form name="iniSelectorForm" action="admin.php?page=bulletproof-security/admin/php/php-options.php#bps-tabs-2" method="post">
<?php wp_nonce_field('bulletproof_security_ini_selector'); ?>
		<strong><label for="bps-ini-file-selector"><?php _e('Select php.ini file to edit:'); ?> </label></strong>
        <select name="iniSelector[]" id="iniSelector">
       <?php echo showOptionsDrop2($iniSelector, $chosen2, true); ?>
		</select>
		<?php submit_button( __( 'Select' ), 'button', 'Submit-IS', false ); ?>
	</form>
</div>
</td>
  </tr>
  <td class="bps-table_cell_help">
<form name="templatephp2" id="templatephp2" action="admin.php?page=bulletproof-security/admin/php/php-options.php#bps-tabs-2" method="post">
<?php wp_nonce_field('bulletproof_security_save_settings_php2'); ?>
<div id="bpsPhpIniEditor">
    <textarea cols="130" rows="27" name="newcontentphp2" id="newcontentphp2" tabindex="1"><?php echo bps_get_php_ini_file(); ?></textarea>
	<input type="hidden" name="iniFilename" value="<?php echo @$iniFilename; ?>" />
    <input type="hidden" name="scrolltophp2" id="scrolltophp2" value="<?php echo $scrolltophp2; ?>" />
    <p class="submit">
	<input type="submit" name="submit-php2" class="button" value="<?php esc_attr_e('Update File') ?>" /></p>
</div>
</form>
<script type="text/javascript">
/* <![CDATA[ */
jQuery(document).ready(function($){
	$('#templatephp2').submit(function(){ $('#scrolltophp2').val( $('#newcontentphp2').scrollTop() ); });
	$('#newcontentphp2').scrollTop( $('#scrolltophp2').val() ); 
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
</div>
            
<div id="bps-tabs-3" class="bps-tab-page">
<h2><?php _e('PHP Error Log'); ?></h2>

<div id="errorLogGeneral" style="border-top:1px solid #999999;">
<h3><?php _e('Htaccess Protected Secure PHP Error Log'); ?>  <button id="bps-open-modal7" class="bps-modal-button"><?php _e('Read Me'); ?></button></h3>
<div id="bps-modal-content7" title="<?php _e('PHP Error Log'); ?>">
	<p><?php _e('<strong>This Read Me Help window is draggable and resizable</strong><br><br><strong>BPS Pro Htaccess Protected Secure PHP Error Log</strong><br><br><strong>Setting Up Your PHP Error Log to Display PHP Errors</strong><br>When you first view the BPS PHP Error Log page you will see a PHP Error Log File Not Found! error message displayed in the PHP Error Log window. If this is a new First Time installation of BPS then copy the <strong>Default BPS Error Log Location:</strong> folder path to the <strong>PHP Error Log Location Set To:</strong> text box window and click the Set Error Log Location button. You should now see a blank PHP error log displayed with BPS Pro htaccess Protected Secure PHP Error Log at the top of the php error log file. A File Open and Write Test successful message should also be displayed above your PHP error log window. If you have not already set up the path to your error log in your custom php.ini file then this is the step that you need to do next.<br><br>If you have several websites under your hosting account then you will want to use one folder location on one website for your one PHP Error log. For all of your other websites under your hosting account you will copy and paste the <strong>Error Log Path Seen by Server:</strong> folder path to the <strong>PHP Error Log Location Set To:</strong> text box. This means that all of your websites will be logging PHP errors to this one PHP Error log.  Most hosts do not allow multiple PHP Error logs to be used or multiple PHP Error logs will not work period.<br><br><strong>Adding your PHP Error Log Path to Your Custom php.ini File</strong><br>Copy the <strong>PHP Error Log Location Set To:</strong> folder path that you just added to set the location of your php error log, click the Php.ini File Editor menu tab, open your custom php.ini file to edit it, paste the folder path for your PHP Error Log to the <strong>error_log = </strong> Directive in your custom php.ini file and click the Update File button to save your changes. See the example below. If you do not see any php.ini files listed in the Php.ini File Editor <strong>Select php.ini file to edit:</strong> dropdown list then you will need to add your custom php.ini file path to the Php.ini File Manager to be able to see it listed in the Php.ini File Editor dropdown list.<br><br><strong>Example: Adding the php error log folder path to your custom php.ini error_log Directive:</strong><br>In your custom php.ini file you will see an example error log path that looks like this example path below. You will be overwriting this example error log path in your custom php.ini file with your actual PHP error log path that BPS is displaying to you.<br>error_log = /home/content/xx/xxxxxxxx/html/wp-content/plugins/bulletproof-security/admin/php/bps_php_error.log<br><br><strong>Troubleshooting and Possible Problems</strong><br>If you have done everything correctly then the <strong>Error Log Path Seen by Server:</strong> should display the same exact path that you see in the <strong>PHP Error Log Location Set To:</strong> window. BPS will display an error warning message if a valid path to your BPS PHP error log has not been added to your custom php.ini file by comparing the <strong>PHP Error Log Location Set To:</strong> path to the <strong>Error Log Path Seen by Server:</strong> path. If your custom php.ini file is not really being seen by your Web Host Server then you will see that the <strong>Error Log Path Seen by Server:</strong> path is either blank or you may see <strong>error_log</strong> or you may see a completely different folder path then the path in the <strong>PHP Error Log Location Set To:</strong> window. A custom php.ini help link is provided on the P-Security Help and FAQ page - General php.ini Info and Host Specific php.ini Information. This link contains specific custom php.ini set up steps by web host.<br><br><strong>Testing Your PHP Error Log</strong><br>To make sure your PHP Error Log is set up correctly click the Test Error Log button. You should see a 500 Server error in the pop up window, an Error Log Test Performed message and a PHP Parse error should be logged in your PHP error log if it is set up correctly. This test can also be used to test the BPS PHP Error Log Last Modified Time and S-Monitor new PHP Error Alerts.<br><br><strong>Multiple Websites and / or Using Multiple PHP Error Logs</strong><br>If you have multiple websites under one hosting account with BPS Pro installed then you would typically want the PHP Error Log file path set up for just one central folder location in your php.ini file.  If you have a host that allows or requires that multiple php.ini files be used then I recommend that you use just one central folder location for your PHP Error Log.  Some hosts do allow you to set up multiple error log files, but this is probably unnecessary as your one error log file will display errors for all of your websites under your one hosting account with the full path to whichever file is generating a PHP error. In other words, the path displayed in the php error will clearly show you which site the php error is occurring on.<br><br><strong>BPS Pro Video Tutorial links can be found in the Help & FAQ pages.</strong>'); ?></p>
</div>
</div>

<?php if (!current_user_can('manage_options')) { echo 'Permission Denied'; } else { ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bps-help_faq_table">
  <tr>
    <td class="bps-table_title">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_help">
<h3><?php _e('PHP Error Log Location'); ?></h3>

    <form name="phpErrorLogSetLocation" action="options.php" method="post">
	<?php settings_fields('bulletproof_security_options2'); ?>
	<?php $options = get_option('bulletproof_security_options2'); ?>
	<label for="phpErrorLog"><strong><?php _e('Default BPS Error Log Location: '); ?></strong><?php echo bps_get_default_php_error_log(); ?></label><br />
    <label for="phpErrorLog"><strong><?php _e('PHP Error Log Location Set To: '); ?></strong></label>
    <input type="text" name="bulletproof_security_options2[bps_error_log_location]" value="<?php echo $options['bps_error_log_location']; ?>" class="regular-text-save-path" /><br />
    <label for="phpErrorLog"><strong><?php _e('Error Log Path Seen by Server: '); ?></strong><?php echo ini_get('error_log'); ?></label><br />
    <p class="submit">
	<input type="submit" name="Submit-PELL" class="button" value="<?php esc_attr_e('Set Error Log Location') ?>" /></p>
</form>

<form name="phpErrorLogTest" method="POST" action="admin.php?page=bulletproof-security/admin/php/php-options.php#bps-tabs-3" target="" onSubmit="window.open('<?php echo get_site_url(); ?>/wp-content/plugins/bulletproof-security/admin/test/bps-error-log-test.php','','scrollbars=yes,menubar=yes,width=800,height=600,resizable=yes,status=yes,toolbar=yes')">
<?php wp_nonce_field('bps-error-log-test'); ?>
<input type="hidden" name="filename" value="bps-test-error-log" />
<p class="submit">
<input type="submit" name="phpErrorLogTest" class="button" value="<?php esc_attr_e('Test Error Log') ?>" /></p>
</form>

<div id="errorLogGeneral2" style="border-top:1px solid #999999;">
<h3><?php _e('PHP Error Log Last Modified Time'); ?>  <button id="bps-open-modal8" class="bps-modal-button"><?php _e('Read Me'); ?></button></h3>
<div id="bps-modal-content8" title="<?php _e('PHP Error Log Last Modified Time'); ?>">
	<p><?php _e('<strong>This Read Me Help window is draggable and resizable</strong><br><br><strong>PHP Error Log Last Modified Time Explained</strong><br>The <strong>Error Log Last Modified Time in File:</strong> text window displays the actual current last modified time for your php error log file. The <strong>Error Log Last Modified Time in DB:</strong> label displays a database saved Timestamp of the last modified time for your php error log file. Clicking the <strong>Reset Last Modified Time in DB</strong> button saves a new Timestamp to your database. When the <strong>Error Log Last Modified Time in DB:</strong> Timestamp does not match the actual last modified time of your error log file the label is displayed in red font for easier quick visual identification. When the <strong>Error Log Last Modified Time in DB:</strong> Timestamp matches the actual last modified time of your error log file the label is displayed in green font.<br><br>Besides just having a quick visual check to see if any new php errors are occurring on your website and in your PHP error log you can set up the BPS S-Monitor to alert you when new PHP Errors occur. The Alerts can be displayed in BPS pages only or in your WordPress Dashboard or turned off. Example Scenario: You set up the BPS S-Monitor to display BPS alerts in your WordPress Dashboard when new PHP errors are logged to your PHP Error log. The BPS PHP Error Log Alert contains a link to the PHP Error Log page. When you are done checking your PHP Error log you would click the <strong>Reset Last Modified Time in DB</strong> button to save a new last modified file time to your WordPress Database. This will also remove the BPS PHP Error Log Alert from your WordPress Dashboard. When a new PHP error occurs and is logged to your PHP Error log file you will be alerted again.<br><br><strong>Hacking Attempts, Hacker Recon and PHP Errors</strong><br>PHP errors are generated any time a php script fails to execute correctly or as expected. There are different levels of php errors: Notice, Warning and Fatal php errors. Notices and Warnings are not that important - Fatal php errors are important and you should find out why they are occurring.<br><br>Hackers create and send automated bot programs out searching for code vulnerabilities primarily in forms since forms allow data input. This is referred to as Hacker Recon. If you see php errors in your log for a form script or form page on your site then most likely this is a hacker bot searching for a vulnerability or exploit in the form. A simple way to see if there is an actual problem with a form on your site is to submit a test form. If you do not see an error message then there is nothing wrong with your actual form and this means that the error was caused by a hacker bot looking for a vulnerability in your form.<br><br> Something about the coding in that form is attracting the bots to it in the first place so it is a good idea to look at the php error and find out what the error message means so you can determine what about your form coding is attracting the hacker bot. A common search parameter in hacker bots would be to search for the php function mysql_pconnect in a form. If a form contains this dangerous php function it is an easy target for hackers to compromise because of the nature of this particular php function. It allows persistent connections to your MySQL database, which allows a hacker to make a brute force connection to your WordPress database. None of your plugins should be using this function. You can use the BPS String Finder to check all of your plugins for the mysql_pconnect function. If you find the function mysql_connect it is perfectly safe and does not allow persistent connections to your WP Database.<br>
<br><strong>BPS Pro Video Tutorial links can be found in the Help & FAQ pages.</strong>'); ?></p>
</div>

<?php
// Get the Last Modifed Date of the PHP Error Log File
function getPhpELogLastMod() {
$options = get_option('bulletproof_security_options2');
$filename = $options['bps_error_log_location'];
if (file_exists($filename)) {
	$last_modified = date ("F d Y H:i:s.", filemtime($filename));
	return $last_modified;
	}
}

// String comparison of DB Last Modified Time and Actual File Last Modified Time
function ELogModTimeDiff() {
$options = get_option('bulletproof_security_options_elog');
$last_modified_time = getPhpELogLastMod();
$last_modified_time_db = $options['bps_error_log_date_mod'];
	if (strcmp($last_modified_time, $last_modified_time_db) == 0) { // 0 is equal
	_e('<font color="green" style="padding-right:5px;"><strong>Error Log Last Modified Time in DB: </strong></font>');
	} else {
	_e('<font color="red" style="padding-right:5px;"><strong>Error Log Last Modified Time in DB: </strong></font>');
	}
}
?> 

<form name="phpErrorLogModDate" action="options.php" method="post">
	<?php settings_fields('bulletproof_security_options_elog'); ?> 
	<?php $options = get_option('bulletproof_security_options_elog'); ?>
	<label for="phpErrorLog"><strong><?php echo ELogModTimeDiff(); ?></strong><?php echo $options['bps_error_log_date_mod']; ?></label><br />
	<label for="phpErrorLog"><strong><?php _e('Error Log Last Modified Time in File: '); ?></strong></label>
    <input type="text" name="bulletproof_security_options_elog[bps_error_log_date_mod]" class="regular-text-really-short" value="<?php echo getPhpELogLastMod(); ?>" />
    <p class="submit">
	<input type="submit" name="Submit-PELMD" class="button" value="<?php esc_attr_e('Reset Last Modified Time in DB') ?>" /></p>
</form>
</div>

<div id="messageinner" class="updatedinner" style="width:665px;">
<?php
// Form - php.ini Error Log - Perform File Open and Write test - If append write test is successful write to file
if (current_user_can('manage_options')) {
$options = get_option('bulletproof_security_options2');
$php_error_log = $options['bps_error_log_location'];
$write_test = "";
	if (is_writable($php_error_log)) {
    if (!$handle = fopen($php_error_log, 'a+b')) {
	_e('<font color="red"><strong>Cannot open file' . "$php_error_log" . '</strong></font><br>');
    exit;
    }
    if (fwrite($handle, $write_test) === FALSE) {
    _e('<font color="red"><strong>Cannot write to file' . "$php_error_log" . '</strong></font><br>');
	exit;
    }
	_e('<font color="green"><strong>File Open and Write test successful! Your PHP Error Log file is writable.</strong></font><br>');
	}
	}
	
	if (isset($_POST['submit-php1']) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_save_settings_php1' );
	$newcontentphp1 = stripslashes($_POST['newcontentphp1']);
	if ( is_writable($php_error_log) ) {
		$handle = fopen($php_error_log, 'w+b');
		fwrite($handle, $newcontentphp1);
	_e('<font color="green"><strong>Success! Your PHP Error Log file has been updated.</strong></font><br>');	
    fclose($handle);
	}
}

// Form - PHP Error Log Test
if (isset($_POST['phpErrorLogTest']) && current_user_can('manage_options')) {
	check_admin_referer( 'bps-error-log-test' );
	_e('<font color="green"><strong>Error Log Test Performed. You should now see a PHP Parse error in your PHP error log if it is set up correctly.</strong></font><br>');
	}
?>
</div>

<div id="phpErrorLogEditor">
<form name="templatephp1" id="templatephp1" action="admin.php?page=bulletproof-security/admin/php/php-options.php#bps-tabs-3" method="post">
<?php wp_nonce_field('bulletproof_security_save_settings_php1'); ?>
<div id="bpsPhpErrorLog">
    <textarea cols="130" rows="27" name="newcontentphp1" id="newcontentphp1" tabindex="1"><?php echo bps_get_php_error_log(); ?></textarea>
	<input type="hidden" name="scrolltophp1" id="scrolltophp1" value="<?php echo $scrolltophp1; ?>" />
    <p class="submit">
	<input type="submit" name="submit-php1" class="button" value="<?php esc_attr_e('Update File') ?>" /></p>
</div>
</form>
<script type="text/javascript">
/* <![CDATA[ */
jQuery(document).ready(function($){
	$('#templatephp1').submit(function(){ $('#scrolltophp1').val( $('#newcontentphp1').scrollTop() ); });
	$('#newcontentphp1').scrollTop( $('#scrolltophp1').val() ); 
});
/* ]]> */
</script>
</div>
</td>
  </tr>
  <tr>
    <td class="bps-table_cell_bottom">&nbsp;</td>
  </tr>
</table>
<?php } ?>
</div>
            
<div id="bps-tabs-4" class="bps-tab-page">
<h2><?php _e('PHP Info Viewer'); ?></h2>

<div id="phpViewer" style="border-top:1px solid #999999;border-bottom:1px solid #999999;">
<h3><?php _e('View PHP Server Configuration Information Safely And Securely With htaccess Protected phpinfo()'); ?></h3>
</div>
<?php if (!current_user_can('manage_options')) { echo 'Permission Denied'; } else { ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bps-help_faq_table">
  <tr>
    <td class="bps-table_title">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_help">
<h3><?php _e('Phpinfo Viewer'); ?>  <button id="bps-open-modal9" class="bps-modal-button"><?php _e('Read Me'); ?></button></h3>
<div id="bps-modal-content9" title="<?php _e('Phpinfo Viewer'); ?>">
	<p><?php _e('<strong>This Read Me Help window is draggable and resizable</strong><br><br>Your BPS Pro PHP folder is already htaccess self protected. The PHP Viewer cannot be accessed or viewed by anyone else except for you. When you click the View PHPINFO button below your current Public IP address will be written to the htaccess file that protects the BPS PHP Info viewer file. When you add, edit or make changes to your php.ini files you can check the results of those changes by clicking the View PHPINFO button. If you see a 403 or 404 error in the pop up window when clicking the View PHPINFO button then refresh the pop up window or just close it and click the View PHPINFO button again.<br><br><strong>NOTE: </strong>Adding new php.ini files or changes made in your existing php.ini files may not show up in your PHP server configuration file for up to 15 minutes. Usually new changes or modifications to your php.ini file will be displayed in phpinfo within 5 minutes.<br><br><strong>BPS Pro Video Tutorial links can be found in the Help & FAQ pages.</strong>'); ?></p>
</div>
    
<form name="bps-view-phpinfo" method="POST" action="admin.php?page=bulletproof-security/admin/php/php-options.php#bps-tabs-4" target="" onSubmit="window.open('<?php echo get_site_url(); ?>/wp-content/plugins/bulletproof-security/admin/php/bps-phpinfo.php','','scrollbars=yes,menubar=yes,width=800,height=600,resizable=yes,status=yes,toolbar=yes')">
<?php wp_nonce_field('bps-view-phpinfo-check'); ?>
<input type="hidden" name="filename" value="bps-view-phpinfo-secure" />
<p class="submit">
<input type="submit" name="bps-view-phpinfo" class="button" value="<?php esc_attr_e('View PHPINFO') ?>" /></p>
</form>

<h2><?php _e('PHP Info Multi Viewer'); ?></h2>
<div id="phpMultiViewerGeneral" style="border-top:1px solid #999999;border-bottom:1px solid #999999;">

<h3><?php _e('Phpinfo Multi Viewer'); ?>  <button id="bps-open-modal10" class="bps-modal-button"><?php _e('Read Me'); ?></button></h3>
<div id="bps-modal-content10" title="<?php _e('Phpinfo Multi Viewer'); ?>">
	<p><?php _e('<strong>This Read Me Help window is draggable and resizable</strong><br><br>Some web hosts allow or may require that you add php.ini files to specific folders where you want the php.ini configuration settings to be applied for only those specific folders. The PHP Info Multi Viewer allows you to create and add phpinfo files for those specific folders to view the PHP configuration changes for only those specific folders. The phpinfo file that you create by clicking the Create Phpinfo File button is protected by adding your current IP address to the phpinfo file when it is created.<br>1. Click the Create Phpinfo File button to create your secure phpinfo file.<br>2. Add the folder path where you want the phpinfo file to be copied and saved too.<br>3. Add the URL path to your new phpinfo file and then click the Save Phpinfo File button.<br>4. Click the View PHPINFO Multi button to view your phpinfo information.<br><strong>NOTE: </strong>Adding new php.ini files or changes made in your existing php.ini files may not show up in your PHP server configuration file for up to 15 minutes. Usually new changes or modifications to your php.ini file will be displayed in phpinfo within 5 minutes.<br><br><strong>Another Example of How the Multi Viewer Could Be Used</strong><br>This is example is referring to GoDaddy hosting specifically for viewing the phpinfo file for specific website folders. GoDaddy allows you to add a php handler to your .htaccess files in the root of each website to designate what version of PHP to run the websites php files under.  So in this example I have added this PHP5.3.x handler - <strong>AddHandler x-httpd-php5-3 .php</strong> - to the .htaccess file that is in the root folder for /website1.  This particular handler says to run PHP5.3.x for this specific website in this specific website folder.  Now lets say I have another website and the .htaccess file for that website is in the root folder for the /website2 folder.  For that websites htaccess file I have added the PHP handler to run PHP5.2.x on that specific website - <strong>AddHandler x-httpd-php5 .php</strong>.  Now if I create a phpinfo file for the /website1 folder and view the phpinfo file I will see this at the top of the phpinfo page - <strong>PHP Version 5.2.17</strong>, indicating that PHP5.2.x is being run on the PHP files in this specific website folder.  If I create a phpinfo file for the /website2 folder and view the phpinfo file I will see this at the top of the phpinfo page - <strong>PHP Version 5.3.6</strong>, indicating that PHP5.3.x is being run on the PHP files in this specific website folder.  The basic idea here is that if your host allows you to apply different PHP settings for different websites or website folders then you can create phpinfo files to view your PHP information for those specific website folders using the Multi Viewer. Now if you added the PHP5.3.x handler to your root website htaccess file in your hosting account then if you do not have a different PHP handlers in your child website htaccess files then all of your sites below the parent site will have PHP5.3.x handler applied and run on them. In other words if you have several websites under one hosting account and you want all of them to be running PHP5.3.x then you only need to add the PHP handler to your root website .htaccess file. The Multi Viewer allows you to create and view individual phpinfo files anywhere under your entire hosting account.<br><br><strong>IMPORTANT!!!:  If you have Grid / Shared hosting on GoDaddy and force 5.3.x to run on your websites then the Zend Optimizer code in the pre-made GoDaddy master php.ini file will not work. The Zend Optimizer code in the php.ini master file only works for PHP 5.2.x. This example was purely for example sake. Do not force PHP 5.3.x to run on your websites if you have GoDaddy Grid / Shared hosting - use 5.2.17 until GoDaddy is fully ready to offer and support the Zend Optimizer for 5.3.x on Grid / Shared hosting.</strong><br><br><strong>BPS Pro Video Tutorial links can be found in the Help & FAQ pages.</strong>'); ?></p>
</div>

<h3><?php _e('View your PHP server information safely and securely for specific folders'); ?></h3>
</div>

<div id="phpInfoCreator" style="border-bottom:1px solid #999999;">
<h3><?php _e('Phpinfo Master File Creator'); ?>  <button id="bps-open-modal11" class="bps-modal-button"><?php _e('Read Me'); ?></button></h3>
<div id="bps-modal-content11" title="<?php _e('Phpinfo Master File Creator'); ?>">
	<p><?php _e('<strong>This Read Me Help window is draggable and resizable</strong><br><br>Click the Create Phpinfo File button to create your new secure phpinfo file. This creates a new Master phpinfo-IP.php file in the BPS /php folder with your current IP address written to the file. <strong>Phpinfo File:</strong> will display the path to the Master phpinfo-IP.php file if the file was written too successfully.<br><br><strong>BPS Pro Video Tutorial links can be found in the Help & FAQ pages.</strong>'); ?></p>
</div>

<form name="bps-create-phpinfo-multi" action="admin.php?page=bulletproof-security/admin/php/php-options.php#bps-tabs-4" method="post">
<?php wp_nonce_field('bulletproof_security_phpinfo_create'); ?>
<strong><label for="bps-ini-file-creator"><?php _e('Click the Create Phpinfo File button to create your new secure phpinfo file:'); ?></label></strong><br />
<label for="bps-ini-file-creator"><strong><?php _e('Phpinfo File: '); ?></strong><span style="color:green; font-weight:bold;"><?php echo @$phpinfoIP; ?></span></label><br />
<input type="hidden" name="bcpms1" value="bps-create-phpinfo-multi-create" />
<p class="submit">
<input type="submit" name="bps-create-phpinfo-multi" class="button" value="<?php esc_attr_e('Create Phpinfo File') ?>" /></p>
</form>
</div>

<div id="phpInfoCreatorCopy" style="border-bottom:1px solid #999999;">
<h3><?php _e('Phpinfo File Creator / Copier'); ?>  <button id="bps-open-modal12" class="bps-modal-button"><?php _e('Read Me'); ?></button></h3>
<div id="bps-modal-content12" title="<?php _e('Phpinfo File Creator / Copier'); ?>">
	<p><?php _e('<strong>This Read Me Help window is draggable and resizable</strong><br><br>Add the folder path where you want your new phpinfo file saved. Name your phpinfo file with a .php file extension. Example: myphpinfo.php. <strong>File saved to folder:</strong> will display the folder path you entered. <strong>File saved to URL:</strong> will display the URL path you entered.<br><br>The phpinfo file that you create is automatically deleted when you click the <strong>View PHPINFO Multi</strong> button. Your phpinfo file exists just long enough to output your PHP Server configuration information and is automatically deleted to avoid unnecessary file clutter.<br><br><strong>BPS Pro Video Tutorial links can be found in the Help & FAQ pages.</strong>'); ?></p>
</div>

<form name="bps-copy-phpinfo-multi" action="admin.php?page=bulletproof-security/admin/php/php-options.php#bps-tabs-4" method="post">
<?php wp_nonce_field('bulletproof_security_phpinfo_copy'); ?>
	<strong><label for="bps-ini-file-creator"><?php _e('Add the folder path where you want your new phpinfo file saved:'); ?></label></strong><br />
    <label for="bps-ini-file-creator"><strong><?php _e('Name your phpinfo file with a .php file extension. Example:'); ?></strong><?php _e(' myphpinfo.php'); ?></label><br />
    <label for="bps-ini-file-creator"><strong><?php _e('Your Website root folder path is: '); ?></strong><?php echo ABSPATH; ?></label><br />
    <label for="bps-ini-file-creator"><strong><?php _e('File saved to folder: '); ?></strong><span style="color:green; font-weight:bold;"><?php echo @$bpsSaveLocPhpinfo; ?></span></label><br />
    <input type="text" name="bps-phpinfo-save-path" value="Example: <?php echo ABSPATH; ?>some-folder-name/some-unique-file-name.php" class="regular-text-mycrypt" /><br /><br />
    <strong><label for="bps-ini-file-creator"><?php _e('Add the URL path to your new phpinfo file:'); ?></label></strong><br />
    <label for="bps-ini-file-creator"><strong><?php _e('Hint: '); ?></strong><?php _e('Copy a portion of the folder path above to your URL window. Example: /some-folder-name/some-unique-file-name.php'); ?></label><br />
 <label for="bps-ini-file-creator"><strong><?php _e('Your Website URL is: '); ?></strong><?php echo get_site_url(); ?></label><br />
 <label for="bps-ini-file-creator"><strong><?php _e('File saved to URL: '); ?></strong><span style="color:green; font-weight:bold;"><?php echo @$bpsViewPhpinfo; ?></span></label><br />
     <input type="text" name="bps-phpinfo-url-path" value="Example: <?php echo get_site_url(); ?>/some-folder-name/some-unique-file-name.php" class="regular-text-mycrypt" />
    <input type="hidden" name="bcpms2" value="bps-create-phpinfo-multi-copy" />
    <p class="submit">
	<input type="submit" name="bps-copy-phpinfo-multi" class="button" value="<?php esc_attr_e('Save Phpinfo File') ?>" /></p>
</form>
</div>

<div id="phpMultiViewer">
<h3><?php _e('Phpinfo Multi Viewer'); ?>  <button id="bps-open-modal13" class="bps-modal-button"><?php _e('Read Me'); ?></button></h3>
<div id="bps-modal-content13" title="<?php _e('Phpinfo Multi Viewer'); ?>">
	<p><?php _e('<strong>This Read Me Help window is draggable and resizable</strong><br><br>Click View PHPINFO Multi button to view PHP configuration information for the specific folder where you created and saved your custom phpinfo file too. If you see a blank popup window after clicking the View PHPINFO Multi button then you did not enter a valid URL and / or file name when you created your custom phpinfo file using the Phpinfo File Creator / Copier.<br><br>The phpinfo file that you create is automatically deleted when you click the <strong>View PHPINFO Multi</strong> button. Your phpinfo file exists just long enough to output your PHP Server configuration information and is automatically deleted to avoid unnecessary file clutter.<br><br><strong>NOTE: </strong>Adding new php.ini files or changes made in your existing php.ini files may not show up in your PHP server configuration file for up to 15 minutes. Usually new changes or modifications to your php.ini file will be displayed in phpinfo within 5 minutes.<br><br><strong>BPS Pro Video Tutorial links can be found in the Help & FAQ pages.</strong>'); ?></p>
</div>

<?php
// Delete the phpinfo file at the same time it is opened
if (isset($_POST['bps-view-phpinfo-multi']) && current_user_can('manage_options')) {
	check_admin_referer( 'bps-view-phpinfo-multi' );
	$bpsSaveLocPhpinfo = $_POST['bpsUnlink'];
	
	if (file_exists($bpsSaveLocPhpinfo)) {
	$fh = fopen($bpsSaveLocPhpinfo, 'a');
	fwrite($fh, '<h1>Hello world!</h1>');
	fclose($fh);
	unlink($bpsSaveLocPhpinfo);
	}
}
?>

<form name="bps-view-phpinfo-multi" method="POST" action="admin.php?page=bulletproof-security/admin/php/php-options.php#bps-tabs-4" target="" onSubmit="window.open('<?php echo @$bpsViewPhpinfo; ?>','','scrollbars=yes,menubar=yes,width=800,height=600,resizable=yes,status=yes,toolbar=yes')">
<?php wp_nonce_field('bps-view-phpinfo-multi'); ?>
 <label for="bps-ini-file-creator"><?php _e('If you see a blank popup window after clicking the View PHPINFO Multi button you have not entered a valid URL and / or file name.'); ?></label><br />
 <input type="hidden" name="bpsUnlink" value="<?php echo @$bpsSaveLocPhpinfo; ?>" />
 <label for="bps-ini-file-creator"><strong><?php _e('Click the View button to View: '); ?></strong><span style="color:green; font-weight:bold;"><?php echo @$bpsViewPhpinfo; ?></span></label>
<p class="submit">
<input type="submit" name="bps-view-phpinfo-multi" class="button" value="<?php esc_attr_e('View PHPINFO Multi') ?>" /></p>
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

<div id="bps-tabs-5" class="bps-tab-page">
<h2><?php _e('Php.ini Security Status'); ?></h2>

<div id="phpSecurityStatus" style="border-top:1px solid #999999;border-bottom:1px solid #999999;">
<h3><?php _e('Displays The Primary Security &amp; Performance Features Added By Your BPS Pro Custom php.ini File'); ?>  <button id="bps-open-modal14" class="bps-modal-button"><?php _e('Read Me'); ?></button></h3>
<div id="bps-modal-content14" title="<?php _e('Security &amp; Performance Features'); ?>">
	<p><?php _e('<strong>This Read Me Help window is draggable and resizable</strong><br><br><strong>To view all of your PHP Server Configuration information use the PHP Info Viewer.</strong><br><br><strong>Is My Custom phpini File Set up Correctly? Is It Being Seen By My Server?</strong><br>The Status column should display all Green On or Off Status settings. If you see Red On or Off Status settings displayed then your Custom php.ini file is probably not set up correctly and / or is not being seen as the Loaded Configuration php.ini file for your website.<br><br>It is possible that you may see a couple of Red On or Off Status settings if your Web Host does not allow you to override their default php.ini directive settings for some php.ini directives. This would mean that your custom php.ini file is set up correctly, but you are not allowed to change a couple of directive settings on your Host Server.<br><br>You can check the <strong>Loaded Configuration File</strong> file path in the PHP Info Viewer to ensure that it is showing the path to your custom php.ini file and not your Servers default php.ini file. Some Web Hosts like Network Solutions will still show the Loaded Configuration File path for their default php.ini file, but you will see that your custom php.ini directives settings are being applied to your website and you should see all Green On or Off Status settings or mostly Green On or Off Status settings. For additional help information go to the <strong>Help & FAQ</strong> Menu Tab and click on the <strong>General php.ini Info and Host Specific php.ini Information</strong> link to view possible reasons why your custom php.ini file is not being seen as the Loaded Configuration php.ini file for your website.'); ?></p>
</div>
</div>

<?php if (!current_user_can('manage_options')) { echo 'Permission Denied'; } else { ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bps-help_faq_table">
  <tr>
    <td class="bps-table_title">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_help"> 

    <table width="100%" class="widefat" style="margin-bottom:15px;">
<thead>
	<tr>
	<th scope="col" style="width:50px;"><strong><?php _e('php.ini Directive')?></strong></th>
	<th scope="col" style="width:250px;"><strong><?php _e('PHP Functions Disabled')?></strong></th>
    <th scope="col" style="width:300px;"><strong><?php _e('Description')?></strong></th>
	</tr>
</thead>
<tbody>
<tr>
	<th scope="row"><?php 
	
	$disable_functions = ini_get('disable_functions');
	$suhosin_functions = ini_get('suhosin.executor.func.blacklist');
	
	if ( !extension_loaded( 'suhosin' ) ) { // if the suhosin extension is not loaded
	if(ini_get('disable_functions') != '' && ini_get('disable_functions') != false) { // is enabled and has functions listed
	_e('disable_functions');
	}
	if(ini_get('disable_functions') == false) {
	_e('disable_functions');
	}
	}
	elseif ( extension_loaded( 'suhosin' ) ) { // if the suhosing extension is loaded
	if (ini_get('suhosin.executor.func.blacklist') != '' && ini_get('suhosin.executor.func.blacklist') != false) {
	_e('suhosin.executor.func.blacklist');
	}
	if(ini_get('suhosin.executor.func.blacklist') == false) {
	_e('suhosin.executor.func.blacklist');
	} else {
	_e('');
	}
	}
	?>
    </th>
	<td>
	<?php 
	if ( !extension_loaded( 'suhosin' ) ) {
	if(ini_get('disable_functions') != '' && ini_get('disable_functions') != false) {
	echo $disable_functions;
	}
	if(ini_get('disable_functions') == false) {
	_e('<font color="red"><strong>The recommended PHP functions are not disabled.</strong></font>');
	}
	}
	elseif ( extension_loaded( 'suhosin' ) ) {
	if (ini_get('suhosin.executor.func.blacklist') != '' && ini_get('suhosin.executor.func.blacklist') != false) {
	echo $suhosin_functions;
	}
	if(ini_get('suhosin.executor.func.blacklist') == false) {
	_e('<font color="red"><strong>The recommended PHP functions are not disabled.</strong></font>'); 
	} else { 
	_e('');
	}
	}
	?>
    </td>
    <td>
	<?php 
	if ( !extension_loaded( 'suhosin' ) ) {
	if(ini_get('disable_functions') != '' && ini_get('disable_functions') != false) {
	_e('This disable_functions directive allows you to add PHP functions that you want to disable on your website. By default BPS is disabling several dangerous PHP functions that are commonly used by hackers.');
	}
	if(ini_get('disable_functions') == false) {
	_e('The disable_functions directive is not in use or is commented out in your php.ini file. Uncomment the disable_functions directive by removing the semi-colon from in front of it.');
	}
	}
	elseif ( extension_loaded( 'suhosin' ) ) {
	if (ini_get('suhosin.executor.func.blacklist') != '' && ini_get('suhosin.executor.func.blacklist') != false) {
	_e('The Suhosin suhosin.executor.func.blacklist directive allows you to add PHP functions that you want to disable on your website. BPS has detected that your Web Host is using Suhosin. If you do not see any functions listed under PHP Functions Disabled then comment out the disable_functions directive in your custom php.ini file and add those functions to the suhosin.executor.func.blacklist directive.');
	}
	if(ini_get('suhosin.executor.func.blacklist') == false) {
	_e('BPS has detected that your Web Host is using Suhosin, but the suhosin.executor.func.blacklist directive was not found in your custom php.ini file. Add the suhosin.executor.func.blacklist directive to your custom php.ini file and comment out the disable_functions directive in your custom php.ini file and then copy the functions shown in the disable_functions directive to your new suhosin.executor.func.blacklist directive that you just added.'); 
	} else {
	_e('');
	}
	}
	?>
    </td>
	</tr>
</tbody>
</table>

    <table width="100%" class="widefat" style="margin-bottom:20px;">
<thead>
	<tr>
	<th scope="col" style="width:40px;"><strong><?php _e('php.ini Directive')?></strong></th>
	<th scope="col" style="width:20px;"><strong><?php _e('Status')?></strong></th>
    <th scope="col" style="width:500px;"><strong><?php _e('Description')?></strong></th>
	</tr>
</thead>
<tbody>
<tr>
	<th scope="row"><?php _e('asp_tags')?></th>
	<td><?php if(ini_get('asp_tags') == 1) { 
	_e('<font color="red"><strong>On</strong></font>'); 
	} else { 
	_e('<font color="green"><strong>Off</strong></font>'); } ?></td>
    <td><?php _e('<strong>Clean Code:</strong> Allow or Disallow ASP-style <% %> tags instead of the standard PHP tags. Not a critical requirement, performance issue or security problem. This is a standard php.ini setting to avoid code compatibility issues.'); ?></td>
	</tr>
    <tr>
	<th scope="row"><?php _e('allow_call_time_pass_reference')?></th>
	<td><?php if(ini_get('allow_call_time_pass_reference') == 1) { 
	_e('<font color="red"><strong>On</strong></font>'); 
	} else { 
	_e('<font color="green"><strong>Off</strong></font>'); } ?></td>
    <td><?php _e('<strong>Security:</strong> Allow or Disallow warnings which PHP will issue if you pass a value by reference at function call time. The acceptable method for passing a value by reference to a function is by declaring the reference in the functions definition, not at call time. These warnings should only be enabled in development environments.'); ?></td>
	</tr>
    <tr>
	<th scope="row"><?php _e('allow_url_fopen')?></th>
	<td><?php if(ini_get('allow_url_fopen') == 1) { 
	_e('<font color="red"><strong>On</strong></font>'); 
	} else { 
	_e('<font color="green"><strong>Off</strong></font>'); } ?></td>
    <td><?php _e('<strong>Security:</strong> Allow or Disallow the treatment of URLs like (http:// or ftp://) as files. Allow or Disallow PHP file functions such as file_get_contents() and the include and require statements to be able to retrieve data from remote locations.'); ?></td>
	</tr>
    <tr>
	<th scope="row"><?php _e('allow_url_include')?></th>
	<td><?php if(ini_get('allow_url_include') == 1) { 
	_e('<font color="red"><strong>On</strong></font>'); 
	} else { 
	_e('<font color="green"><strong>Off</strong></font>'); } ?></td>
    <td><?php _e('<strong>Security:</strong> Allow or Disallow include and require statements to open URLs like (http:// or ftp://) as files. Allow or Disallow remote file access via the include and require statements. Include and require are the most common attack points for code injection attempts. Does not affect the remote file access capabilities of the standard file functions.'); ?></td>
	</tr>
    <tr>
	<th scope="row"><?php _e('define_syslog_variables')?></th>
	<td><?php if(ini_get('define_syslog_variables') == 1) { 
	_e('<font color="red"><strong>On</strong></font>'); 
	} else { 
	_e('<font color="green"><strong>Off</strong></font>'); } ?></td>
    <td><?php _e('<strong>Performance:</strong> Define or not Define the various syslog variables (e.g. $LOG_PID, $LOG_CRON, etc.). Increased performance by turning this directive off. In runtime, you can define these variables by calling define_syslog_variables()'); ?></td>
	</tr>
    <tr>
    <th scope="row"><?php _e('display_errors')?></th>
	<td><?php if(ini_get('display_errors') == 1) { 
	_e('<font color="red"><strong>On</strong></font>'); 
	} else { 
	_e('<font color="green"><strong>Off</strong></font>'); } ?></td>
    <td><?php _e('<strong>Security:</strong> Allow or Disallow PHP output errors, notices and warnings to remote users. The error message content may expose information about your script, web server or database server that may be exploitable for hacking. Sensitive information such as database usernames and passwords could also be leaked out. BPS logs php errors instead of displaying them to remote users. The BPS php error log is .htaccess protected.'); ?></td>
	</tr>
    <tr>
	<th scope="row"><?php _e('display_startup_errors')?></th>
	<td><?php if(ini_get('display_startup_errors') == 1) { 
	_e('<font color="red"><strong>On</strong></font>'); 
	} else { 
	_e('<font color="green"><strong>Off</strong></font>'); } ?></td>
    <td><?php _e('<strong>Security:</strong> Allow or Disallow the display of errors which occur during PHPs startup sequence. Handled separately from the display_errors directive. Useful in debugging configuration problems in a development environment, but should never be set to On for production servers.'); ?></td>
	</tr>
    <tr>
    <th scope="row"><?php _e('expose_php')?></th>
	<td><?php if(ini_get('expose_php') == 1) { 
	_e('<font color="red"><strong>On</strong></font>'); 
	} else { 
	_e('<font color="green"><strong>Off</strong></font>'); } ?></td>
    <td><?php _e('<strong>Standard non-Security:</strong> Allow or Disallow PHP to expose that it is installed on the server by adding its signature to the Web server header. Not a security threat in any way, but it makes it possible to determine whether you use PHP on your server or not.'); ?></td>
	</tr>
    <tr>
	<th scope="row"><?php _e('implicit_flush')?></th>
	<td><?php if(ini_get('implicit_flush') == 1) { 
	_e('<font color="red"><strong>On</strong></font>'); 
	} else { 
	_e('<font color="green"><strong>Off</strong></font>'); } ?></td>
    <td><?php _e('<strong>Performance:</strong> Allow or Disallow PHP to tell the output layer to flush itself automatically after every output block. This is equivalent to calling the PHP function flush() after each and every call to print() or echo() and each and every HTML block. Turning this option on has serious performance implications and is generally recommended for debugging purposes only.'); ?></td>
	</tr>
    <tr>
    <th scope="row"><?php _e('magic_quotes_gpc')?></th>
	<td><?php if(ini_get('magic_quotes_gpc') == 1) { 
	_e('<font color="red"><strong>On</strong></font>'); 
	} else { 
	_e('<font color="green"><strong>Off</strong></font>'); } ?></td>
    <td><?php _e('<strong>Performance:</strong> Allow or Disallow magic quotes. A preprocessing feature of PHP where PHP will attempt to escape (slashes) any character sequences in GET, POST, COOKIE and ENV data which might otherwise corrupt data being placed in resources such as databases before making that data available to you. This feature has been deprecated as of PHP 5.3.0 and is scheduled for removal in PHP 6.'); ?></td>
	</tr>
    <tr>
	<th scope="row"><?php _e('magic_quotes_runtime')?></th>
	<td><?php if(ini_get('magic_quotes_runtime') == 1) { 
	_e('<font color="red"><strong>On</strong></font>'); 
	} else { 
	_e('<font color="green"><strong>Off</strong></font>'); } ?></td>
    <td><?php _e('<strong>Performance:</strong> Allow or Disallow magic quotes for runtime-generated data, e.g. data from SQL, from exec(), etc.'); ?></td>
	</tr>
    <tr>
	<th scope="row"><?php _e('mysql.allow_persistent')?></th>
	<td><?php if(ini_get('mysql.allow_persistent') == 1) { 
	_e('<font color="red"><strong>On</strong></font>'); 
	} else { 
	_e('<font color="green"><strong>Off</strong></font>'); } ?></td>
    <td><?php _e('<strong>Security:</strong> Allow or Disallow persistent MySQL database connections. The mysql_pconnect() function might be used in a WordPress plugin on your site, but it is unlikely. There are Pros and Cons to this directive setting, but without explaining what those Pros and Cons are, we log hackers attempting to exploit the mysql_pconnect() function on a regular basis in the AITpro HoneyPots.'); ?></td>
	</tr>
    <tr>
	<th scope="row"><?php _e('output_buffering')?></th>
	<td><?php 
	$output_buffering = ini_get('output_buffering');
	if(ini_get('output_buffering') != 0) { 
	_e('<font color="red"><strong>'.$output_buffering.'</strong></font>');
	} else { 
	_e('<font color="green"><strong>'.$output_buffering.'</strong></font>'); } ?></td>
    <td><?php _e('<strong>Performance WP Specific:</strong> Allow or Disallow output buffering. Output buffering is a mechanism for controlling how much output data (excluding headers and cookies) PHP should keep internally before pushing that data to the client. Output buffering does not work well on WordPress sites and causes slower performance. For other types of sites that are NOT WordPress the recommended output buffering setting is: output_buffering = 4096. The ouput buffering setting for WordPress should be: output_buffering = 0. Meaning 0 bytes - the value is an integer not an On / Off value.'); ?></td>
	</tr>
    <tr>
	<th scope="row"><?php _e('register_globals')?></th>
	<td><?php if(ini_get('register_globals') == 1) { 
	_e('<font color="red"><strong>On</strong></font>'); 
	} else { 
	_e('<font color="green"><strong>Off</strong></font>'); } ?></td>
    <td><?php _e('<strong>Security &amp; Performance:</strong> Allow or Disallow the EGPCS variables, input data (POST, GET, cookies, environment and other server variables), to be registered as global variables. Perfomance increase by avoiding global scope script clutter with user data. Allowing register_globals will register form variables as globals and can lead to possible security problems.'); ?></td>
	</tr>
    <tr>
    <th scope="row"><?php _e('register_long_arrays')?></th>
	<td><?php if(ini_get('register_long_arrays') == 1) { 
	_e('<font color="red"><strong>On</strong></font>'); 
	} else { 
	_e('<font color="green"><strong>Off</strong></font>'); } ?></td>
    <td><?php _e('<strong>Performance:</strong> Allow or Disallow the deprecated long $HTTP_*_VARS type predefined variables to be registered by PHP. As this is deprecated and superglobals have been introduced as of PHP 4.1.0 this should never be turned On / Allowed.'); ?></td>
	</tr>
    <tr>
	<th scope="row"><?php _e('register_argc_argv')?></th>
	<td><?php if(ini_get('register_argc_argv') == 1) { 
	_e('<font color="red"><strong>On</strong></font>'); 
	} else { 
	_e('<font color="green"><strong>Off</strong></font>'); } ?></td>
    <td><?php _e('<strong>Performance:</strong> Allow or Disallow whether PHP registers $argv & $argc each time it runs. $argv contains an array of all the arguments passed to PHP when a script is invoked. $argc contains an integer representing the number of arguments that were passed when the script was invoked. Registering these variables consumes CPU cycles and memory each time a script is executed. For performance reasons, this feature should be disabled on production servers.'); ?></td>
	</tr>
    <tr>
    <th scope="row"><?php _e('report_memleaks')?></th>
	<td><?php if(ini_get('report_memleaks') == 1) { 
	_e('<font color="green"><strong>On</strong></font>'); 
	} else { 
	_e('<font color="red"><strong>Off</strong></font>'); } ?></td>
    <td><?php _e('<strong>Standard Debug Error Logging:</strong> Allow or Disallow memory leaks to be logged. This directive only applies to debugging and will only effect a debug compile if error reporting includes E_WARNING in the allowed list'); ?></td>
	</tr>
    <tr>
	<th scope="row"><?php _e('safe_mode')?></th>
	<td><?php if(ini_get('safe_mode') == 1) { 
	_e('<font color="red"><strong>On</strong></font>'); 
	} else { 
	_e('<font color="green"><strong>Off</strong></font>'); } ?></td>
    <td><?php _e('<strong>Obsolete Deprecated:</strong> Allow or Disallow whether to enable PHPs safe mode if PHP is compiled with --enable-safe-mode. This feature has been DEPRECATED as of PHP 5.3.0.'); ?></td>
	</tr>
    <tr>
    <th scope="row"><?php _e('sql.safe_mode')?></th>
	<td><?php if(ini_get('sql.safe_mode') == 1) { 
	_e('<font color="red"><strong>On</strong></font>'); 
	} else { 
	_e('<font color="green"><strong>Off</strong></font>'); } ?></td>
    <td><?php _e('<strong>Security:</strong> Allow or Disallow database connect functions to use default values (host, user and password) in place of supplied arguments.'); ?></td>
	</tr>
    <tr>
    <th scope="row"><?php _e('variables_order')?></th>
	<td>
	<?php 
	$variables_order = ini_get('variables_order'); 
	if(ini_get('variables_order') == 'GPCS') {
	 _e('<font color="green"><strong>GPCS</strong></font>');
	 }
	if(ini_get('variables_order') == 'EGPCS') { 
	 _e('<font color="red"><strong>EGPCS</strong></font>'); 
	 } 
	 ?>
     </td>
    <td><?php _e('<strong>Performance:</strong> GPCS is the optimum performance variables order. This directive determines which super global arrays are registered when PHP starts up. By default BPS has register_globals set to Off / Disallowed so that the environment variables are not hashed into the $_ENV. Variables: G,P,C,E & S (super globals: GET, POST, COOKIE, ENV and SERVER). Using EGPCS variables order will cause a performance decrease. If you see EGPCS instead of GPCS displayed here then remove the E from your variables_order directive in your php.ini file.'); ?></td>
	</tr>
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
    <td class="bps-table_cell_help"><a href="http://www.ait-pro.com/aitpro-blog/2851/bulletproof-security-pro/bulletproof-security-pro-p-security-php-ini-video-tutorial/" target="_blank"><?php _e('P-Security Video Tutorial'); ?></a></td>
    <td class="bps-table_cell_help"><a href="http://www.ait-pro.com/aitpro-blog/2837/bulletproof-security-pro/bulletproof-security-pro-screenshots/" target="_blank"><?php _e('BPS Pro Screenshots'); ?></a></td>
  </tr>
  <tr>
    <td class="bps-table_cell_help"><a href="http://www.ait-pro.com/aitpro-blog/2845/bulletproof-security-pro/bulletproof-security-pro-hover-tooltips/" target="_blank"><?php _e('Read Me Help Buttons Posted As Text For Language Translation'); ?></a></td>
    <td class="bps-table_cell_help"><a href="http://www.ait-pro.com/aitpro-blog/2843/bulletproof-security-pro/bulletproof-security-pro-questions-and-comments/" target="_blank"><?php _e('Post Questions and Comments For Assistance'); ?></a></td>
  </tr>
  <tr>
    <td class="bps-table_cell_help"><a href="http://www.ait-pro.com/aitpro-blog/2853/bulletproof-security-pro/php-ini-general-and-host-specific-information/" target="_blank"><?php _e('General php.ini Info and Host Specific php.ini Information'); ?></a></td>
    <td class="bps-table_cell_help">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_help"><a href="http://www.ait-pro.com/aitpro-blog/3576/bulletproof-security-pro/custom-php-ini-faq/" target="_blank"><?php _e('Custom php.ini and PHP Errors FAQ'); ?></a></td>
    <td class="bps-table_cell_help">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_help"><a href="http://www.ait-pro.com/aitpro-blog/3576/bulletproof-security-pro/custom-php-ini-faq#web-hosts-list" target="_blank"><?php _e('AutoMagic php.ini Handler Web Hosts List'); ?></a></td>
    <td class="bps-table_cell_help">&nbsp;</td>
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
   
<div id="AITpro-link">BulletProof Security Pro <?php echo BULLETPROOF_VERSION; ?> Plugin by <a href="http://www.ait-pro.com/" target="_blank" title="AITpro Website Design">AITpro Website Design</a>
</div>
</div>
</div>