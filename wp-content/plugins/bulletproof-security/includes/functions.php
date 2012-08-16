<?php
// Direct calls to this file are Forbidden when core files are not present
if (!function_exists ('add_action')) {
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

// Obsolete file cleanup / deletion - these main pages only - options, php-options, monitor and flock
// also include a one time refresh notification - remove this after 2 versions
function bpsRemoveObs() {
	$bpsObs1 = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/js/tip_centerwindow.js';
	$bpsObs2 = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/js/tip_followscroll.js';
	$bpsObs3 = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/js/wz_tooltip.js';
	$bpsObs4 = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/license/bpspro-license.jar';
	
	$deleteMe = array("$bpsObs1", "$bpsObs2", "$bpsObs3", "$bpsObs4");
	foreach($deleteMe as $deleted){
	if (file_exists($deleted)) {
	unlink($deleted);
	//$text = 'Refresh your Browser Window to make this one time message go away.';
	//_e('<div id="message" class="updated fade" style="border:1px solid #999999; margin-left:104px; margin-top:9px;"><p>'.$text.'</p></div>');
	}
	}
}

// First Launch Admin Notice - BPS Pro Activation
function bps_first_launch_admin_notice_act() {
$options = get_option('bulletproof_security_options_activation');
	if (!get_option('bulletproof_security_options_activation')) {
	_e('<div class="update-nag"><strong>BPS Pro Activation Notification</strong><br>Please Activate BPS Pro first before doing anything else. BPS Pro will not function correctly until Activation has been completed.<br><strong><a href="admin.php?page=bulletproof-security/admin/activation/activation.php">Click Here</a></strong> to go to the Activation page.</div>');
	} else { 
	echo '';
	}
}
add_action('admin_notices', 'bps_first_launch_admin_notice_act');

// First Launch Admin Notice - S-Monitor
function bps_first_launch_admin_notice() {
$options = get_option('bulletproof_security_options_monitor');
	if (!$options['bps_first_launch']) { 
	_e('<div class="update-nag"><strong>BPS Pro First Install / Launch Notification</strong><br><strong>Please Activate BPS Pro first before doing anything else</strong><br>BPS Pro will not function correctly until Activation has been completed. <strong><a href="admin.php?page=bulletproof-security/admin/activation/activation.php">Click Here</a></strong> to go to the Activation page.<br>To turn this alert off after Activating BPS and to turn on all other BPS Alerts <strong><a href="admin.php?page=bulletproof-security/admin/monitor/monitor.php">Click Here</a></strong> to go to the S-Monitor Monitoring and Alerting page.<br>BPS Alerts will not be displayed until you click the Save Options button the first time you install BPS. Check the S-Monitor Whats New page when installing BPS for the first time and after upgrading BPS for tips and info on what has changed or been added in each new version of BPS.</div>');
	}
	if ($options['bps_first_launch'] == 'wpOn') { 
	_e('<div class="update-nag"><strong>BPS Pro First Install / Launch Notification</strong><br><strong>Please Activate BPS Pro first before doing anything else</strong><br>BPS Pro will not function correctly until Activation has been completed. <strong><a href="admin.php?page=bulletproof-security/admin/activation/activation.php">Click Here</a></strong> to go to the Activation page.<br>To turn this alert off after Activating BPS and to turn on all other BPS Alerts <strong><a href="admin.php?page=bulletproof-security/admin/monitor/monitor.php">Click Here</a></strong> to go to the S-Monitor Monitoring and Alerting page.<br>BPS Alerts will not be displayed until you click the Save Options button the first time you install BPS. Check the S-Monitor Whats New page when installing BPS for the first time and after upgrading BPS for tips and info on what has changed or been added in each new version of BPS.</div>');
	} else { 
	echo '';
}
}
add_action('admin_notices', 'bps_first_launch_admin_notice');

// BPS First Launch S-Monitor notification - Display in BPS Only
function bps_first_launch_internal_notice() {
$options = get_option('bulletproof_security_options_monitor');
	if ($options['bps_first_launch'] == 'bpsOn') { 
	_e('<br><strong>BPS Pro First Install / Launch Notification</strong><br><strong>Please Activate BPS Pro first before doing anything else</strong><br>BPS Pro will not function correctly until Activation has been completed. <strong><a href="admin.php?page=bulletproof-security/admin/activation/activation.php">Click Here</a></strong> to go to the Activation page.<br>To turn this alert off after Activating BPS and to turn on all other BPS Alerts <strong><a href="admin.php?page=bulletproof-security/admin/monitor/monitor.php">Click Here</a></strong> to go to the S-Monitor Monitoring and Alerting page.<br>BPS Alerts will not be displayed until you click the Save Options button the first time you install BPS. Check the S-Monitor Whats New page when installing BPS for the first time and after upgrading BPS for tips and info on what has changed or been added in each new version of BPS.<br><br>');
	} else { 
	echo '';
	}
}

// S-Monitor Display HUD Alerts in WP Dashboard Only if wpOn
function bps_HUD_WP_Dashboard() {
$options = get_option('bulletproof_security_options_monitor');
	if ($options['bps_HUD_alerts'] == 'wpOn') { 
	echo bps_check_php_version_error();
	echo bps_check_safemode();
	echo bps_check_permalinks_error();
	echo bps_check_iis_supports_permalinks();
	echo bps_hud_check_bpsbackup();
	echo bps_hud_check_bpsbackup_master();
	echo @bps_w3tc_htaccess_check($plugin_var);
	echo @bps_wpsc_htaccess_check($plugin_var);
	} else { 
	echo '';
	}
}
add_action('admin_notices', 'bps_HUD_WP_Dashboard');

// S-Monitor Display HUD Alerts in BPS Only if bpsOn
function bps_HUD_bps_only() {
$options = get_option('bulletproof_security_options_monitor');
	if ($options['bps_HUD_alerts'] == 'bpsOn') { 
	echo bps_check_php_version_error();
	echo bps_check_safemode();
	echo bps_check_permalinks_error();
	echo bps_check_iis_supports_permalinks();
	echo bps_hud_check_bpsbackup();
	echo bps_hud_check_bpsbackup_master();
	echo @bps_w3tc_htaccess_check($plugin_var);
	echo @bps_wpsc_htaccess_check($plugin_var);
	//}
	//elseif ($options['bps_HUD_alerts'] == 'Off') { 
	//_e('just for testing the off option');
	} else { 
	echo '';
	}
}

// S-Monitor - Check if PHP Error Log Location has been Set - WP Only
function bps_smonitor_php_elog_wp() {
$options2 = get_option('bulletproof_security_options2');
$options = get_option('bulletproof_security_options_monitor');	
	if ($options2['bps_error_log_location'] == '' ) { 
	if ($options['bps_PHP_ELogLoc_set'] == 'wpOn') { 
	_e('<div class="update-nag"><strong>The PHP Error Log Location has not been set yet</strong><br>This is a general check just to remind you to add your PHP Error Log file path in the <strong>PHP Error Log Location Set To:</strong> text box.<br><strong><a href="admin.php?page=bulletproof-security/admin/php/php-options.php#bps-tabs-3">Click Here</a></strong> to go to the P-Security PHP Error Log page. View the Htaccess Protected Secure PHP Error Log Read Me Help button for </div>');
	}
	elseif ($options['bps_PHP_ELogLoc_set'] == 'Off') { 
	_e('');
	} else {
	_e('');
	}
	}
}
add_action('admin_notices', 'bps_smonitor_php_elog_wp');

// S-Monitor - Check if PHP Error Log Location has been Set - BPS Only
function bps_smonitor_php_elog_bps() {
$options2 = get_option('bulletproof_security_options2');
$options = get_option('bulletproof_security_options_monitor');	
	if ($options2['bps_error_log_location'] == '' ) { 
	if ($options['bps_PHP_ELogLoc_set'] == 'bpsOn') { 
	_e('<br><strong>The PHP Error Log Location has not been set yet</strong><br>To set your PHP Error Log location <strong><a href="admin.php?page=bulletproof-security/admin/php/php-options.php#bps-tabs-3">Click Here</a></strong> to go to the P-Security PHP Error Log page.<br>');
	} else { 
	echo '';
	}
	}
}

// S-Monitor - PHP Error Log new errors - Get the Last Modifed Date of the PHP Error Log File - actual file
function getPhpELogLastMod_smonitor() {
$options = get_option('bulletproof_security_options2');
$filename = $options['bps_error_log_location'];
if (file_exists($filename)) {
	$last_modified = date ("F d Y H:i:s.", filemtime($filename));
	return $last_modified;
	}
}

// S-Monitor - PHP Error Log new errors - String comparison of DB Last Modified Time and Actual File Last Modified Time - WP Only
function bps_smonitor_ELogModTimeDiff_wp() {
$options2 = get_option('bulletproof_security_options_elog');
$options = get_option('bulletproof_security_options_monitor');
$last_modified_time = getPhpELogLastMod_smonitor();
$last_modified_time_db = $options2['bps_error_log_date_mod'];
	if (strcmp($last_modified_time, $last_modified_time_db) != 0) { // 0 is equal strings
	if ($options['bps_PHP_ELog_error'] == 'wpOn') {
	_e('<div class="update-nag"><font color="red"><strong>A PHP Error has been logged in your PHP Error Log</strong></font><br><strong>To go to the P-Security PHP Error Log page <a href="admin.php?page=bulletproof-security/admin/php/php-options.php#bps-tabs-3">Click Here</a></strong></div>');
	}
	elseif ($options['bps_PHP_ELog_error'] == 'Off') { 
	_e('');
	} else {
	_e('');
	}
	}
}
add_action('admin_notices', 'bps_smonitor_ELogModTimeDiff_wp');

// Add Action for the PHP Error Log Cron job to check for new php errors
add_action('bpsPro_php_elog_check', 'bps_smonitor_ELogModTimeDiff_wp_email');

// The Scheduled Cron Job to check the PHP Error Log hourly
function bpsPro_schedule_Elog_check() {
	if ( !wp_next_scheduled( 'bpsPro_php_elog_check' ) ) {
		wp_schedule_event(time(), 'hourly', 'bpsPro_php_elog_check');
	}
}
add_action('init', 'bpsPro_schedule_Elog_check');

// Add ELog Cron to cron_schedules
function bpsPro_add_hourly_elog_cron( $schedules ) {
	$schedules['hourly'] = array('interval' => 3600, 'display' => __('Once Hourly'));
	return $schedules;
}
add_filter('cron_schedules', 'bpsPro_add_hourly_elog_cron');

// S-Monitor Email Alert - The scheduled Cron function to check the PHP Error Log new errors
// String comparison of DB Last Modified Time and Actual File Last Modified Time
function bps_smonitor_ELogModTimeDiff_wp_email() {
$options2 = get_option('bulletproof_security_options_elog');
$options = get_option('bulletproof_security_options_email');
$last_modified_time = getPhpELogLastMod_smonitor();
$last_modified_time_db = $options2['bps_error_log_date_mod'];
	if (strcmp($last_modified_time, $last_modified_time_db) != 0) { // 0 is equal strings
	if ($options['bps_error_log_email'] == 'yes') {
	
	$bps_email = $options['bps_send_email_to'];
	$bps_email_from = $options['bps_send_email_from'];
	$bps_email_cc = $options['bps_send_email_cc'];
	$bps_email_bcc = $options['bps_send_email_bcc'];
	$justUrl = get_site_url();
	$mail_To = "$bps_email";
	$headers = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	$headers .= "From: $bps_email_from" . "\r\n";
	$headers .= "Cc: $bps_email_cc" . "\r\n";
	$headers .= "Bcc: $bps_email_bcc" . "\r\n";
	$mail_Subject = " BPS Pro Alert: A PHP Error has been logged ";

$mail_message = '<p><font color="red"><strong>A PHP Error has been logged in your PHP Error Log File.</strong></font></p>';
$mail_message .= '<p>Site: '."$justUrl".'</p>'; 
$mail_message .= '<p>To view the php error go to the BPS P-Security PHP Error Log page.</p>';
mail($mail_To, $mail_Subject, $mail_message, $headers);
	}
	elseif ($options['bps_error_log_email'] == 'no') { 
	wp_clear_scheduled_hook('bpsPro_php_elog_check');
	// may need to use this 
	// $timestamp = wp_next_scheduled( 'my_schedule_hook' );    
	// wp_unschedule_event($timestamp, 'my_schedule_hook', original_args );
	_e('Cron Job unscheduled');
	} else {
	_e('');
	}
	}
}

// S-Monitor - PHP Error Log new errors - String comparison of DB Last Modified Time and Actual File Last Modified Time - BPS Only
function bps_smonitor_ELogModTimeDiff_bps() {
$options2 = get_option('bulletproof_security_options_elog');
$options = get_option('bulletproof_security_options_monitor');
$last_modified_time = getPhpELogLastMod_smonitor();
$last_modified_time_db = $options2['bps_error_log_date_mod'];
	if (strcmp($last_modified_time, $last_modified_time_db) != 0) { // 0 is equal strings
	if ($options['bps_PHP_ELog_error'] == 'bpsOn') {
	_e('<br><font color="red"><strong>A PHP Error has been logged in your PHP Error Log</strong></font><br><strong>To go to the P-Security PHP Error Log page <a href="admin.php?page=bulletproof-security/admin/php/php-options.php#bps-tabs-3">Click Here</a></strong><br>');
	} else { 
	echo '';
	}
	}
}

// S-Monitor - Check if a Php.ini file has been created or added to a File Manager slot - WP Only
// Check if the php error log path is valid and if the custom php.ini file is being recognized / seen by the Server
function bps_smonitor_phpini_wp() {
$options = get_option('bulletproof_security_options_monitor');
$options2 = get_option('bulletproof_security_options');
$options3 = get_option('bulletproof_security_options2');
$ElogPathServer = ini_get('error_log');
$ElogPathSet = $options3['bps_error_log_location'];	
	if ($options['bps_phpini_created'] == 'wpOn') { 
	if ($options2['bpsinifiles_input_1'] == '' && $options2['bpsinifiles_input_2'] == '' && $options2['bpsinifiles_input_3'] == '' && $options2['bpsinifiles_input_4'] == '' && $options2['bpsinifiles_input_5'] == '' && $options2['bpsinifiles_input_6'] == '' && $options2['bpsinifiles_input_7'] == '' && $options2['bpsinifiles_input_8'] == '' && $options2['bpsinifiles_input_9'] == '' && $options2['bpsinifiles_input_10'] == '') {
	_e('<div class="update-nag"><strong>Php.ini Options and File Manager General Check</strong><br>This is just a very general check designed to make you aware of the php.ini Options page, the File Manager and general info about creating a custom php.ini file. To make this general displayed message go away copy the <strong>HTTP Error Log Path:</strong> that is displayed on the php.ini Options page to any available empty slot in the File Manager. When you create a new custom php.ini file for your website you will add the folder path to that new custom php.ini file that you create to any available empty slot in the File Manager. Custom php.ini file creation is a one time thing and only one custom php.ini file needs to be created per Hosting account to protect all of your websites under your Hosting account. <strong>Optional:</strong> You can add your custom php.ini file path in the File Manager for all of your other websites under your Hosting account, but this is completely optional and is not required.<strong><a href="admin.php?page=bulletproof-security/admin/php/php-options.php">Click Here</a></strong> to go to the P-Security PHP.ini Options page.</div>');
	}
	elseif ($options2['bpsinifiles_input_1'] != '' || $options2['bpsinifiles_input_2'] != '' || $options2['bpsinifiles_input_3'] != '' || $options2['bpsinifiles_input_4'] != '' || $options2['bpsinifiles_input_5'] != '' || $options2['bpsinifiles_input_6'] != '' || $options2['bpsinifiles_input_7'] != '' || $options2['bpsinifiles_input_8'] != '' || $options2['bpsinifiles_input_9'] != '' || $options2['bpsinifiles_input_10'] != '') {
	if (strcmp($ElogPathServer, $ElogPathSet) != 0 && $ElogPathSet != '') { // 0 is equal - != 0 means that the paths do not match
	_e('<div class="update-nag"><font color="red"><strong>Custom php.ini File Error - PHP Error Log Path Does Not Match</strong></font><br>The <strong>PHP Error Log Location Set To:</strong> folder path does not match the <strong>Error Log Path Seen by Server:</strong> folder path.<br>If your site is in Maintenance Mode you can disregard this error message. <strong><a href="admin.php?page=bulletproof-security/admin/php/php-options.php#bps-tabs-3">Click Here</a></strong> to go to the PHP Error Log page and click the <strong>Htaccess Protected Secure PHP Error Log Read Me</strong> button for troubleshooting steps.</div>');
	}
	}
	}
	elseif ($options['bps_phpini_created'] == 'Off') { 
	_e('');
	} else {
	_e('');
	}
}
add_action('admin_notices', 'bps_smonitor_phpini_wp');

// S-Monitor - Check if a Php.ini file has been created or added to a File Manager slot - BPS Only
// Check if the php error log path is valid and if the custom php.ini file is being recognized / seen by the Server
function bps_smonitor_phpini_bps() {
$options = get_option('bulletproof_security_options_monitor');
$options2 = get_option('bulletproof_security_options');
$options3 = get_option('bulletproof_security_options2');
$ElogPathServer = ini_get('error_log');
$ElogPathSet = $options3['bps_error_log_location'];
	if ($options['bps_phpini_created'] == 'bpsOn') { 
	if ($options2['bpsinifiles_input_1'] == '' && $options2['bpsinifiles_input_2'] == '' && $options2['bpsinifiles_input_3'] == '' && $options2['bpsinifiles_input_4'] == '' && $options2['bpsinifiles_input_5'] == '' && $options2['bpsinifiles_input_6'] == '' && $options2['bpsinifiles_input_7'] == '' && $options2['bpsinifiles_input_8'] == '' && $options2['bpsinifiles_input_9'] == '' && $options2['bpsinifiles_input_10'] == '') {
	_e('<br><strong>Php.ini Options and File Manager General Check</strong><br>This is just a very general check designed to make you aware of the php.ini Options page, the File Manager and general info about creating a custom php.ini file. To make this general displayed message go away copy the <strong>HTTP Error Log Path:</strong> that is displayed on the php.ini Options page to any available empty slot in the File Manager. When you create a new custom php.ini file for your website you will add the folder path to that new custom php.ini file that you create to any available empty slot in the File Manager. Custom php.ini file creation is a one time thing and only one custom php.ini file needs to be created per Hosting account to protect all of your websites under your Hosting account. <strong>Optional:</strong> You can add your custom php.ini file path in the File Manager for all of your other websites under your Hosting account, but this is completely optional and is not required.<strong><a href="admin.php?page=bulletproof-security/admin/php/php-options.php">Click Here</a></strong> to go to the P-Security PHP.ini Options page.<br>');
	}
	elseif ($options2['bpsinifiles_input_1'] != '' || $options2['bpsinifiles_input_2'] != '' || $options2['bpsinifiles_input_3'] != '' || $options2['bpsinifiles_input_4'] != '' || $options2['bpsinifiles_input_5'] != '' || $options2['bpsinifiles_input_6'] != '' || $options2['bpsinifiles_input_7'] != '' || $options2['bpsinifiles_input_8'] != '' || $options2['bpsinifiles_input_9'] != '' || $options2['bpsinifiles_input_10'] != '') {
	if (strcmp($ElogPathServer, $ElogPathSet) != 0) { // 0 is equal - != 0 means that the paths do not match
	_e('<br><font color="red"><strong>Custom php.ini File Error - PHP Error Log Path Does Not Match</strong></font><br>The <strong>PHP Error Log Location Set To:</strong> folder path does not match the <strong>Error Log Path Seen by Server:</strong> folder path.<br>If your site is in Maintenance Mode you can disregard this error message. <strong><a href="admin.php?page=bulletproof-security/admin/php/php-options.php#bps-tabs-3">Click Here</a></strong> to go to the PHP Error Log page and click the <strong>Htaccess Protected Secure PHP Error Log Read Me</strong> button for troubleshooting steps.<br>');
	}
	}
	}
	elseif ($options['bps_phpini_created'] == 'Off') { 
	_e('');
	} else {
	_e('');
	}
}

// BPS Master htaccess File Editing - file checks and get contents for editor
function get_secure_htaccess() {
	$secure_htaccess_file = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/htaccess/secure.htaccess';
	if (file_exists($secure_htaccess_file)) {
	$bpsString = file_get_contents($secure_htaccess_file);
	echo $bpsString;
	} else {
	_e('The secure.htaccess file either does not exist or is not named correctly. Check the /wp-content/plugins/bulletproof-security/admin/htaccess/ folder to make sure the secure.htaccess file exists and is named secure.htaccess.');
	}
}

function get_default_htaccess() {
	$default_htaccess_file = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/htaccess/default.htaccess';
	if (file_exists($default_htaccess_file)) {
	$bpsString = file_get_contents($default_htaccess_file);
	echo $bpsString;
	} else {
	_e('The default.htaccess file either does not exist or is not named correctly. Check the /wp-content/plugins/bulletproof-security/admin/htaccess/ folder to make sure the default.htaccess file exists and is named default.htaccess.');
	}
}

function get_maintenance_htaccess() {
	$maintenance_htaccess_file = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/htaccess/maintenance.htaccess';
	if (file_exists($maintenance_htaccess_file)) {
	$bpsString = file_get_contents($maintenance_htaccess_file);
	echo $bpsString;
	} else {
	_e('The maintenance.htaccess file either does not exist or is not named correctly. Check the /wp-content/plugins/bulletproof-security/admin/htaccess/ folder to make sure the maintenance.htaccess file exists and is named maintenance.htaccess.');
	}
}

function get_wpadmin_htaccess() {
	$wpadmin_htaccess_file = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/htaccess/wpadmin-secure.htaccess';
	if (file_exists($wpadmin_htaccess_file)) {
	$bpsString = file_get_contents($wpadmin_htaccess_file);
	echo $bpsString;
	} else {
	_e('The wpadmin-secure.htaccess file either does not exist or is not named correctly. Check the /wp-content/plugins/bulletproof-security/admin/htaccess/ folder to make sure the wpadmin-secure.htaccess file exists and is named wpadmin-secure.htaccess.');
	}
}

// The current active root htaccess file - file check
function get_root_htaccess() {
	$root_htaccess_file = ABSPATH . '/.htaccess';
	if (file_exists($root_htaccess_file)) {
	$bpsString = file_get_contents($root_htaccess_file);
	echo $bpsString;
	} else {
	_e('An .htaccess file was not found in your website root folder.');
	}
}

// The current active wp-admin htaccess file - file check
function get_current_wpadmin_htaccess_file() {
	$current_wpadmin_htaccess_file = ABSPATH . '/wp-admin/.htaccess';
	if (file_exists($current_wpadmin_htaccess_file)) {
	$bpsString = file_get_contents($current_wpadmin_htaccess_file);
	echo $bpsString;
	} else {
	_e('An .htaccess file was not found in your wp-admin folder.');
	}
}

// File write checks for editor
function secure_htaccess_file_check() {
$secure_htaccess_file = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/htaccess/secure.htaccess';
	if (!is_writable($secure_htaccess_file)) {
 		_e('<font color="red"><strong>Cannot write to the secure.htaccess file. Minimum file permission required is 600.</strong></font><br>');
	    } else {
	_e('');
}
}

// File write checks for editor
function default_htaccess_file_check() {
$default_htaccess_file = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/htaccess/default.htaccess';
	if (!is_writable($default_htaccess_file)) {
 		_e('<font color="red"><strong>Cannot write to the default.htaccess file. Minimum file permission required is 600.</strong></font><br>');
	    } else {
	_e('');
}
}
// File write checks for editor
function maintenance_htaccess_file_check() {
$maintenance_htaccess_file = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/htaccess/maintenance.htaccess';
	if (!is_writable($maintenance_htaccess_file)) {
 		_e('<font color="red"><strong>Cannot write to the maintenance.htaccess file. Minimum file permission required is 600.</strong></font><br>');
	    } else {
	_e('');
}
}
// File write checks for editor
function wpadmin_htaccess_file_check() {
$wpadmin_htaccess_file = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/htaccess/wpadmin-secure.htaccess';
	if (!is_writable($wpadmin_htaccess_file)) {
 		_e('<font color="red"><strong>Cannot write to the wpadmin-secure.htaccess file. Minimum file permission required is 600.</strong></font><br>');
	    } else {
	_e('');
}
}
// File write checks for editor
function root_htaccess_file_check() {
$root_htaccess_file = ABSPATH . '/.htaccess';
	if (!is_writable($root_htaccess_file)) {
 		_e('<font color="red"><strong>Cannot write to the root .htaccess file. Minimum file permission required is 600.</strong></font><br>');
	    } else {
	_e('');
}
}
// File write checks for editor
function current_wpadmin_htaccess_file_check() {
$current_wpadmin_htaccess_file = ABSPATH . '/wp-admin/.htaccess';
	if (!is_writable($current_wpadmin_htaccess_file)) {
 		_e('<font color="red"><strong>Cannot write to the wp-admin .htaccess file. Minimum file permission required is 600.</strong></font><br>');
	    } else {
	_e('');
}
}
		
// S-Monitor Display - Root htaccess - BPS Status and Alerts in WP Dashboard Only if wpOn
function bps_status_WP_Dashboard() {
$options = get_option('bulletproof_security_options_monitor');
	$filename = ABSPATH . '.htaccess';
	$section = @file_get_contents($filename, NULL, NULL, 3, 46);
	$check_stringBPSQSE = @file_get_contents($filename);	
	$check_string = @strpos($section, "1");	
	if ($options['bps_security_status'] == 'wpOn') { 
	if ( !file_exists($filename)) {
	_e('<div class="update-nag"><font color="red"><strong>BPS Pro Alert! An .htaccess file was NOT found in your root folder. Check the BPS Pro <a href="admin.php?page=bulletproof-security/admin/options.php#bps-tabs-2">Security Status page</a> for more specific information.</strong></font></div>');
	} else {
	if (file_exists($filename)) {
		if ($check_string == "19" && strpos($check_stringBPSQSE, "BPSQSE")) {
		print($section);
	} else {
	_e('<div class="update-nag"><font color="red"><strong>BPS Pro Alert! Your site does not appear to be protected by BulletProof Security.</strong></font><br><strong>If you are Upgrading BPS your site is still protected, but you will need to create new Master .htaccess files with the AutoMagic buttons and Activate them.<br>If your site is in Maintenance Mode your site is protected by BPS and this Alert will remain to remind you to put your site back in BulletProof Mode again.<br>If your site is in Default Mode then it is not protected by BulletProof Security. Check the BPS Pro <a href="admin.php?page=bulletproof-security/admin/options.php#bps-tabs-2">Security Status page</a> to view your BPS Security Status information.</strong></div>');
}}}}}
add_action('admin_notices', 'bps_status_WP_Dashboard');

// S-Monitor Display - Root htaccess - BPS Status and Alerts in BPS Only if bpsOn
function bps_security_status_bps_only() {
$options = get_option('bulletproof_security_options_monitor');
	$filename = ABSPATH . '.htaccess';
	$section = @file_get_contents($filename, NULL, NULL, 3, 46);
	$check_stringBPSQSE = @file_get_contents($filename);		
	$check_string = @strpos($section, "1");	
	if ($options['bps_security_status'] == 'bpsOn') { 
	if ( !file_exists($filename)) {
	_e('<br><font color="red"><strong>BPS Pro Alert! An .htaccess file was NOT found in your root folder. Check the BPS Pro <a href="admin.php?page=bulletproof-security/admin/options.php#bps-tabs-2">Security Status page</a> for more specific information.</strong></font><br>');
	} else {
	if (file_exists($filename)) {
		if ($check_string == "19" && strpos($check_stringBPSQSE, "BPSQSE")) {
		print($section);
	} else {
	_e('<br><font color="red"><strong>BPS Pro Alert! Your site does not appear to be protected by BulletProof Security.</strong></font><br><strong>If you are Upgrading BPS your site is still protected, but you will need to create new Master .htaccess files with the AutoMagic buttons and Activate them.<br>If your site is in Maintenance Mode your site is protected by BPS and this Alert will remain to remind you to put your site back in BulletProof Mode again.<br>If your site is in Default Mode then it is not protected by BulletProof Security. Check the BPS Pro <a href="admin.php?page=bulletproof-security/admin/options.php#bps-tabs-2">Security Status page</a> to view your BPS Security Status information.</strong><br>');
}}}}}

// S-Monitor Display - wp-admin htaccess - BPS Status and Alerts in WP Dashboard Only if wpOn
function bps_status_wpadmin_WP_Dashboard() {
$options = get_option('bulletproof_security_options_monitor');
	$filename = ABSPATH . 'wp-admin/.htaccess';
	$section = @file_get_contents($filename, NULL, NULL, 3, 46);
	$check_stringBPSQSE = @file_get_contents($filename);	
	$check_string = @strpos($section, "1");	
	if ($options['bps_security_status'] == 'wpOn') { 
	if ( !file_exists($filename)) {
	_e('<div class="update-nag"><font color="red"><strong>BPS Pro Alert! An .htaccess file was NOT found in your wp-admin folder. Check the BPS Pro <a href="admin.php?page=bulletproof-security/admin/options.php#bps-tabs-2">Security Status page</a> for more specific information.</strong></font></div>');
	} else {
	if (file_exists($filename)) {
		if ($check_string == "19" && strpos($check_stringBPSQSE, "BPSQSE")) {
		//print($section);
		echo '';
	} else {
	_e('<div class="update-nag"><font color="red"><strong>BPS Pro Alert! A valid BPS Pro .htaccess file was NOT found in your wp-admin folder.</strong></font><br><strong>Either you have not activated BulletProof Mode for your wp-admin folder yet or if you are Upgrading<br>then the version of the wp-admin htaccess file that you are using is not the most current version. Activate BulletProof Mode for your wp-admin folder.<br>BulletProof Mode for the wp-admin folder MUST also be activated when you have BulletProof Mode activated for the Root folder.<br>Check the BPS Pro <a href="admin.php?page=bulletproof-security/admin/options.php#bps-tabs-2">Security Status page</a> to view your BPS Security Status information.</strong></div>');
}}}}}
add_action('admin_notices', 'bps_status_wpadmin_WP_Dashboard');

// S-Monitor Display - wp-admin htaccess - BPS Status and Alerts in BPS Only if bpsOn
function bps_security_status_wpadmin_bps_only() {
$options = get_option('bulletproof_security_options_monitor');
	$filename = ABSPATH . 'wp-admin/.htaccess';
	$section = @file_get_contents($filename, NULL, NULL, 3, 46);
	$check_stringBPSQSE = @file_get_contents($filename);		
	$check_string = @strpos($section, "1");	
	if ($options['bps_security_status'] == 'bpsOn') { 
	if ( !file_exists($filename)) {
	_e('<br><font color="red"><strong>BPS Pro Alert! An .htaccess file was NOT found in your wp-admin folder. Check the BPS Pro <a href="admin.php?page=bulletproof-security/admin/options.php#bps-tabs-2">Security Status page</a> for more specific information.</strong></font><br>');
	} else {
	if (file_exists($filename)) {
		if ($check_string == "19" && strpos($check_stringBPSQSE, "BPSQSE")) {
		//print($section);
		echo '';
	} else {
	_e('<br><font color="red"><strong>BPS Pro Alert! A valid BPS Pro .htaccess file was NOT found in your wp-admin folder.</strong></font><br><strong>Either you have not activated BulletProof Mode for your wp-admin folder yet or if you are Upgrading then the version of the wp-admin htaccess file that you are using is not the most current version. Activate BulletProof Mode for your wp-admin folder.<br>BulletProof Mode for the wp-admin folder MUST also be activated when you have BulletProof Mode activated for the Root folder.<br>Check the BPS Pro <a href="admin.php?page=bulletproof-security/admin/options.php#bps-tabs-2">Security Status page</a> to view your BPS Security Status information.</strong><br>');
}}}}}

// F-Lock - Check if F-Lock options saved - F-Lock Lock / Unlock File Status & actual file permissions 404 or 400 - WP Only
function bps_smonitor_flock_wp() {
clearstatcache();

$fileRH = ABSPATH . '.htaccess';
$fileWPC = ABSPATH . 'wp-config.php';
$fileRI = ABSPATH . 'index.php';
$fileBH = ABSPATH . 'wp-blog-header.php';
$fileDRH = $_SERVER['DOCUMENT_ROOT'] . '/.htaccess';
$fileDRI = $_SERVER['DOCUMENT_ROOT'] . '/index.php';
$fileHGWIOD = dirname(ABSPATH) . '/.htaccess';
$fileIGWIOD = dirname(ABSPATH) . '/index.php';

$permsRH = @substr(sprintf(".%o.", fileperms($fileRH)), -4);
$permsWPC = @substr(sprintf(".%o.", fileperms($fileWPC)), -4);
$permsRI = @substr(sprintf(".%o.", fileperms($fileRI)), -4);
$permsBH = @substr(sprintf(".%o.", fileperms($fileBH)), -4);
$permsDRH = @substr(sprintf(".%o.", fileperms($fileDRH)), -4);
$permsDRI = @substr(sprintf(".%o.", fileperms($fileDRI)), -4);
$permsHGWIOD = @substr(sprintf(".%o.", fileperms($fileHGWIOD)), -4);
$permsIGWIOD = @substr(sprintf(".%o.", fileperms($fileIGWIOD)), -4);

$options2 = get_option('bulletproof_security_options_flock'); 
$options = get_option('bulletproof_security_options_monitor');
	if (!get_option('bulletproof_security_options_flock')) {
	_e('<div class="update-nag"><strong>BPS Pro F-Lock Notification</strong><br>F-Lock options have not been saved yet.<br><strong><a href="admin.php?page=bulletproof-security/admin/lock/flock.php">Click Here</a></strong> to go to the F-Lock page to choose and save your File Lock / Unlock options.</div>');
	}
	if (@$options['bps_flock_status'] == '') {
	_e('<div class="update-nag"><strong>BPS Pro S-Monitor Notification</strong><br>New S-Monitor option <strong>F-Lock: Check File Lock / Unlock Status</strong> needs to be saved. <br><strong><a href="admin.php?page=bulletproof-security/admin/monitor/monitor.php">Click Here</a></strong> to go to the S-Monitor page to choose how you want <strong>F-Lock: Check File Lock / Unlock Status</strong> messages and warnings to be displayed to you and click the Save Options button.</div>');
	}
	if (@$options['bps_flock_status'] == 'wpOn') { 
	if ($options2['bps_lock_root_htaccess'] == 'off') { 
	_e('');
	}	
	elseif ($options2['bps_lock_root_htaccess'] == 'no' || $permsRH != '404.') {
	_e('<div class="update-nag"><font color="red"><strong>Your Root .htaccess File is not Locked</strong></font><br>To Lock your Root .htaccess file <strong><a href="admin.php?page=bulletproof-security/admin/lock/flock.php">Click Here</a></strong>.</div>');
	}
	if ($options2['bps_lock_wpconfig'] == 'off') { 
	_e('');
	}
	elseif ($options2['bps_lock_wpconfig'] == 'no' || $permsWPC != '400.' && file_exists($fileWPC)) { 
	_e('<div class="update-nag"><font color="red"><strong>Your wp-config.php File is not Locked</strong></font><br><strong><a href="admin.php?page=bulletproof-security/admin/lock/flock.php">Click Here</a></strong> To Lock your wp-config.php file.</div>');
	}
	if ($options2['bps_lock_index_php'] == 'off') { 
	_e('');
	}	
	elseif ($options2['bps_lock_index_php'] == 'no' || $permsRI != '400.') { 
	_e('<div class="update-nag"><font color="red"><strong>Your WP Root index.php File is not Locked</strong></font><br><strong><a href="admin.php?page=bulletproof-security/admin/lock/flock.php">Click Here</a></strong> To Lock your WP Root index.php file.</div>');
	}
	if ($options2['bps_lock_wpblog_header'] == 'off') { 
	_e('');
	}
	elseif ($options2['bps_lock_wpblog_header'] == 'no' || $permsBH != '400.') { 
	_e('<div class="update-nag"><font color="red"><strong>Your wp-blog-header.php File is not Locked</strong></font><br><strong><a href="admin.php?page=bulletproof-security/admin/lock/flock.php">Click Here</a></strong> To Lock your wp-blog-header.php file.</div>');
	}
	if ($options2['bps_lock_root_htaccess_dr'] == 'off') { 
	_e('');
	}
	elseif ($options2['bps_lock_root_htaccess_dr'] == 'no' || $permsDRH != '404.' && file_exists($fileDRH)) { 
	_e('<div class="update-nag"><font color="red"><strong>Your DR Root .htaccess File is not Locked</strong></font><br><strong><a href="admin.php?page=bulletproof-security/admin/lock/flock.php">Click Here</a></strong> To Lock your DR Root .htaccess file.</div>');
	}
	if ($options2['bps_lock_index_php_dr'] == 'off') { 
	_e('');
	}
	elseif ($options2['bps_lock_index_php_dr'] == 'no' || $permsDRI != '400.' && file_exists($fileDRI)) { 
	_e('<div class="update-nag"><font color="red"><strong>Your DR WP index.php File is not Locked</strong></font><br><strong><a href="admin.php?page=bulletproof-security/admin/lock/flock.php">Click Here</a></strong> To Lock your DR WP index.php file.</div>');
	}
	if ($options2['bps_lock_root_htaccess_gwiod'] == 'off') { 
	_e('');
	}
	elseif ($options2['bps_lock_root_htaccess_gwiod'] == 'no' || $permsHGWIOD != '404.' && file_exists($fileHGWIOD)) { 
	_e('<div class="update-nag"><font color="red"><strong>Your GWIOD Root .htaccess File is not Locked</strong></font><br><strong><a href="admin.php?page=bulletproof-security/admin/lock/flock.php">Click Here</a></strong> To Lock your GWIOD Root .htaccess file.</div>');
	}
	if ($options2['bps_lock_index_php_gwiod'] == 'off') { 
	_e('');
	}
	elseif ($options2['bps_lock_index_php_gwiod'] == 'no' || $permsIGWIOD != '400.' && file_exists($fileIGWIOD)) { 
	_e('<div class="update-nag"><font color="red"><strong>Your GWIOD WP index.php File is not Locked</strong></font><br><strong><a href="admin.php?page=bulletproof-security/admin/lock/flock.php">Click Here</a></strong> To Lock your GWIOD WP index.php file.</div>');
	}
	elseif ($options['bps_flock_status'] == 'Off') { 
	_e('');
	} else {
	_e('');
	}
	}
}
add_action('admin_notices', 'bps_smonitor_flock_wp');

// F-Lock - Check if F-Lock options saved - F-Lock Lock / Unlock File Status & actual file permissions 404 or 400 - BPS Only
function bps_smonitor_flock_bps() {
clearstatcache();

$fileRH = ABSPATH . '.htaccess';
$fileWPC = ABSPATH . 'wp-config.php';
$fileRI = ABSPATH . 'index.php';
$fileBH = ABSPATH . 'wp-blog-header.php';
$fileDRH = $_SERVER['DOCUMENT_ROOT'] . '/.htaccess';
$fileDRI = $_SERVER['DOCUMENT_ROOT'] . '/index.php';
$fileHGWIOD = dirname(ABSPATH) . '/.htaccess';
$fileIGWIOD = dirname(ABSPATH) . '/index.php';

$permsRH = @substr(sprintf(".%o.", fileperms($fileRH)), -4);
$permsWPC = @substr(sprintf(".%o.", fileperms($fileWPC)), -4);
$permsRI = @substr(sprintf(".%o.", fileperms($fileRI)), -4);
$permsBH = @substr(sprintf(".%o.", fileperms($fileBH)), -4);
$permsDRH = @substr(sprintf(".%o.", fileperms($fileDRH)), -4);
$permsDRI = @substr(sprintf(".%o.", fileperms($fileDRI)), -4);
$permsHGWIOD = @substr(sprintf(".%o.", fileperms($fileHGWIOD)), -4);
$permsIGWIOD = @substr(sprintf(".%o.", fileperms($fileIGWIOD)), -4);

$options2 = get_option('bulletproof_security_options_flock'); 
$options = get_option('bulletproof_security_options_monitor');
	if (!get_option('bulletproof_security_options_flock')) {
	if ($options['bps_flock_status'] == 'bpsOn') { 
	_e('<br><strong>BPS Pro F-Lock Notification</strong><br>F-Lock options have not been saved yet.<br><strong><a href="admin.php?page=bulletproof-security/admin/lock/flock.php">Click Here</a></strong> to go to the F-Lock page to choose and save your File Lock / Unlock options.<br>');
	}
	}
	if ($options['bps_flock_status'] == 'bpsOn') { 
	if ($options2['bps_lock_root_htaccess'] == 'off') { 
	_e('');
	}	
	elseif ($options2['bps_lock_root_htaccess'] == 'no' || $permsRH != '404.') {
	_e('<br><font color="red"><strong>Your Root .htaccess File is not Locked</strong></font><br><strong><a href="admin.php?page=bulletproof-security/admin/lock/flock.php">Click Here</a></strong> To Lock your Root .htaccess file.<br>');
	}
	if ($options2['bps_lock_wpconfig'] == 'off') { 
	_e('');
	}
	elseif ($options2['bps_lock_wpconfig'] == 'no'  || $permsWPC != '400.' && file_exists($fileWPC)) { 
	_e('<br><font color="red"><strong>Your wp-config.php File is not Locked</strong></font><br><strong><a href="admin.php?page=bulletproof-security/admin/lock/flock.php">Click Here</a></strong> To Lock your wp-config.php file.<br>');
	}
	if ($options2['bps_lock_index_php'] == 'off') { 
	_e('');
	}
	elseif ($options2['bps_lock_index_php'] == 'no' || $permsRI != '400.') { 
	_e('<br><font color="red"><strong>Your WP Root index.php File is not Locked</strong></font><br><strong><a href="admin.php?page=bulletproof-security/admin/lock/flock.php">Click Here</a></strong> To Lock your WP Root index.php file.<br>');
	}
	if ($options2['bps_lock_wpblog_header'] == 'off') { 
	_e('');
	}
	elseif ($options2['bps_lock_wpblog_header'] == 'no' || $permsBH != '400.') { 
	_e('<br><font color="red"><strong>Your wp-blog-header.php File is not Locked</strong></font><br><strong><a href="admin.php?page=bulletproof-security/admin/lock/flock.php">Click Here</a></strong> To Lock your wp-blog-header.php file.<br>');
	}
	if ($options2['bps_lock_root_htaccess_dr'] == 'off') { 
	_e('');
	}
	elseif ($options2['bps_lock_root_htaccess_dr'] == 'no' || $permsDRH != '404.' && file_exists($fileDRH)) { 
	_e('<br><font color="red"><strong>Your DR Root .htaccess File is not Locked</strong></font><br><strong><a href="admin.php?page=bulletproof-security/admin/lock/flock.php">Click Here</a></strong> To Lock your DR Root .htaccess file.<br>');
	}
	if ($options2['bps_lock_index_php_dr'] == 'off') { 
	_e('');
	}
	elseif ($options2['bps_lock_index_php_dr'] == 'no' || $permsDRI != '400.' && file_exists($fileDRI)) { 
	_e('<br><font color="red"><strong>Your DR WP index.php File is not Locked</strong></font><br><strong><a href="admin.php?page=bulletproof-security/admin/lock/flock.php">Click Here</a></strong> To Lock your DR WP index.php file.<br>');
	}
	if ($options2['bps_lock_root_htaccess_gwiod'] == 'off') { 
	_e('');
	}
	elseif ($options2['bps_lock_root_htaccess_gwiod'] == 'no' || $permsHGWIOD != '404.' && file_exists($fileHGWIOD)) { 
	_e('<br><font color="red"><strong>Your GWIOD Root .htaccess File is not Locked</strong></font><br><strong><a href="admin.php?page=bulletproof-security/admin/lock/flock.php">Click Here</a></strong> To Lock your GWIOD Root .htaccess file.<br>');
	}
	if ($options2['bps_lock_index_php_gwiod'] == 'off') { 
	_e('');
	}
	elseif ($options2['bps_lock_index_php_gwiod'] == 'no' || $permsIGWIOD != '400.' && file_exists($fileIGWIOD)) { 
	_e('<br><font color="red"><strong>Your GWIOD WP index.php File is not Locked</strong></font><br><strong><a href="admin.php?page=bulletproof-security/admin/lock/flock.php">Click Here</a></strong> To Lock your GWIOD WP index.php file.<br>');
	}
	elseif ($options['bps_flock_status'] == 'Off') { 
	_e('');
	} else {
	_e('');
	}
	}
}

// Get contents of Root .htaccess file from 3-46 - check if "1" is in string position 19
// Check for string BPSQSE
function root_htaccess_status() {
	$filename = ABSPATH . '.htaccess';
	$section = @file_get_contents($filename, NULL, NULL, 3, 46);
	$check_stringBPSQSE = @file_get_contents($filename);
	$check_string = @strpos($section, "1");
	if ( !file_exists($filename)) {
	_e('<font color="red">An .htaccess file was NOT found in your root folder</font><br><br>');
	_e('<font color="red">wp-config.php is NOT .htaccess protected by BPS</font><br><br>');
	} else {
	if (file_exists($filename)) {
	_e('<font color="green"><strong>The .htaccess file that is activated in your root folder is:</strong></font><br>');
		print($section);
		if ($check_string == "19" && strpos($check_stringBPSQSE, "BPSQSE")) {
		_e('<font color="green"><strong><br><br>&radic; wp-config.php is .htaccess protected by BPS<br>&radic; php.ini and php5.ini are .htaccess protected by BPS</strong></font><br><br>');
	} else {
	_e('<font color="red"><br><br><strong>Either a BPS .htaccess file was NOT found in your root folder or you have not activated BulletProof Mode for your Root folder yet, Default Mode is activated, Maintenance Mode is activated or the version of the BPS Pro htaccess file that you are using is not 5.1.4 or the BPS QUERY STRING EXPLOITS code does not exist in your root .htaccess file. Please view the Read Me Help button above.</strong></font><br><br>');
	_e('<font color="red"><strong>wp-config.php is NOT .htaccess protected by BPS</strong></font><br><br>');
}}}}

// Get contents of wp-admin .htaccess file from 3-46 - if "1" is in string position 19 - good - else bad
function wpadmin_htaccess_status() {
	$filename = ABSPATH . 'wp-admin/.htaccess';
	$section = @file_get_contents($filename, NULL, NULL, 3, 46);
	$check_stringBPSQSE = @file_get_contents($filename);
	$check_string = @strpos($section, "1");
	if ( !file_exists($filename)) {
	_e('<font color="red"><strong>An .htaccess file was NOT found in your wp-admin folder.<br>BulletProof Mode for the wp-admin folder MUST also be activated when you have BulletProof Mode activated for the Root folder.</strong></font><br>');
	} else {
		if ($check_string == "19" && strpos($check_stringBPSQSE, "BPSQSE")) {
	_e('<font color="green"><strong>The .htaccess file that is activated in your wp-admin folder is:</strong></font><br>');
		print($section);
	} else {
	_e('<font color="red"><strong><br><br>A valid BPS .htaccess file was NOT found in your wp-admin folder. Either you have not activated BulletProof Mode for your wp-admin folder yet or the version of the wp-admin htaccess file that you are using is not 5.1.4 BulletProof Mode for the wp-admin folder MUST also be activated when you have BulletProof Mode activated for the Root folder. Please view the Read Me Help button above.</strong></font><br>');
	}
	}
}
	
// Check for WP readme.html file and if valid BPS .htaccess file is activated
function bps_filesmatch_check_readmehtml() {
	$htaccess_filename = ABSPATH . '.htaccess';
	$filename = ABSPATH . 'readme.html';
	$section = @file_get_contents($htaccess_filename, NULL, NULL, 3, 46);
	$check_string = @strpos($section, "1");
	$check_stringBPSQSE = @file_get_contents($htaccess_filename);
	if (file_exists($htaccess_filename)) {
	if ($check_string == "19") { 
		_e('');
		}
		if ( !file_exists($filename)) {
		_e('<font color="green"><strong>&radic; The WP readme.html file does not exist</strong></font><br>');
		} else {
		if ($check_string == "19" && strpos($check_stringBPSQSE, "BPSQSE")) {
		_e('<font color="green"><strong>&radic; The WP readme.html file is .htaccess protected</strong></font><br>');
		} else {
		_e('<font color="red"><strong>The WP readme.html file is not .htaccess protected</strong></font><br>');
		}
}}}

// Check for WP /wp-admin/install.php file and if valid BPS .htaccess file is activated
function bps_filesmatch_check_installphp() {
	$htaccess_filename = ABSPATH . 'wp-admin/.htaccess';
	$filename = ABSPATH . 'wp-admin/install.php';
	$check_stringBPSQSE = @file_get_contents($htaccess_filename);
	$section = @file_get_contents($htaccess_filename, NULL, NULL, 3, 46);
	$check_string = @strpos($section, "1");	
	if (file_exists($htaccess_filename)) {
	if ($check_string == "19") { 
		_e('');
		}
		if ( !file_exists($filename)) {
		_e('<font color="green"><strong>&radic; The WP /wp-admin/install.php file does not exist</strong></font><br>');
		} else {
		if ($check_string == "19" && strpos($check_stringBPSQSE, "BPSQSE")) {
		_e('<font color="green"><strong>&radic; The WP /wp-admin/install.php file is .htaccess protected</strong></font><br>');
		} else {
		_e('<font color="red"><strong>The WP /wp-admin/install.php file is not .htaccess protected</strong></font><br>');
		}
}}}

// Check if BPS Deny ALL htaccess file is activated for the BPS Master htaccess folder
function denyall_htaccess_status_master() {
$filename = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/htaccess/.htaccess';
	if (file_exists($filename)) {
    _e('<font color="green"><strong>&radic; Deny All protection activated for BPS Master /htaccess folder</strong></font><br>');
	} else {
    _e('<font color="red"><strong>Deny All protection NOT activated for BPS Master /htaccess folder</strong></font><br>');
	}
}
// Check if BPS Deny ALL htaccess file is activated for the /wp-content/bps-backup folder
function denyall_htaccess_status_backup() {
$filename = WP_CONTENT_DIR . '/bps-backup/.htaccess';
	if (file_exists($filename)) {
    _e('<font color="green"><strong>&radic; Deny All protection activated for /wp-content/bps-backup folder</strong></font><br><br>');
	} else {
    _e('<font color="red"><strong>Deny All protection NOT activated for /wp-content/bps-backup folder</strong></font><br><br>');
	}
}

// File and Folder Permission Checking - substr error is suppressed @ else fileperms error if file does not exist
function bps_check_perms($name,$path,$perm) {
	clearstatcache();
	$current_perms = @substr(sprintf(".%o.", fileperms($path)), -4);
	echo '<table style="width:100%;background-color:#fff;">';
	echo '<tr>';
    echo '<td style="background-color:#fff;padding:2px;width:35%;">' . $name . '</td>';
    echo '<td style="background-color:#fff;padding:2px;width:35%;">' . $path . '</td>';
    echo '<td style="background-color:#fff;padding:2px;width:15%;">' . $perm . '</td>';
    echo '<td style="background-color:#fff;padding:2px;width:15%;">' . $current_perms . '</td>';
    echo '</tr>';
	echo '</table>';
}
	
// General BulletProof Security File Status Checking
function general_bps_file_checks() {
	$dir='../';
	$filename = '.htaccess';
	if (file_exists($dir.$filename)) {
    _e('<font color="green">&radic; An .htaccess file was found in your root folder</font><br>');
	} else {
    _e('<font color="red">NO .htaccess file was found in your root folder</font><br>');
	}

	$filename = '.htaccess';
	if (file_exists($filename)) {
    _e('<font color="green">&radic; An .htaccess file was found in your /wp-admin folder</font><br>');
	} else {
    _e('<font color="red">NO .htaccess file was found in your /wp-admin folder</font><br>');
	}

	$filename = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/htaccess/default.htaccess';
	if (file_exists($filename)) {
    _e('<font color="green">&radic; A default.htaccess file was found in the /htaccess folder</font><br>');
	} else {
    _e('<font color="red">NO default.htaccess file found in the /htaccess folder</font><br>');
	}

	$filename = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/htaccess/secure.htaccess';
	if (file_exists($filename)) {
    _e('<font color="green">&radic; A secure.htaccess file was found in the /htaccess folder</font><br>');
	} else {
    _e('<font color="red">NO secure.htaccess file found in the /htaccess folder</font><br>');
	}

	$filename = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/htaccess/maintenance.htaccess';
	if (file_exists($filename)) {
    _e('<font color="green">&radic; A maintenance.htaccess file was found in the /htaccess folder</font><br>');
	} else {
    _e('<font color="red">NO maintenance.htaccess file found in the /htaccess folder</font><br>');
	}

	$filename = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/htaccess/bp-maintenance.php';
	if (file_exists($filename)) {
    _e('<font color="green">&radic; A bp-maintenance.php file was found in the /htaccess folder</font><br>');
	} else {
    _e('<font color="red">NO bp-maintenance.php file found in the /htaccess folder</font><br>');
	}
	
	$filename = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/htaccess/bps-maintenance-values.php';
	if (file_exists($filename)) {
    _e('<font color="green">&radic; A bps-maintenance-values.php file was found in the /htaccess folder</font><br>');
	} else {
    _e('<font color="red">NO bps-maintenance-values.php file found in the /htaccess folder</font><br>');
	}
	
	$filename = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/htaccess/wpadmin-secure.htaccess';
	if (file_exists($filename)) {
    _e('<font color="green">&radic; A wpadmin-secure.htaccess file was found in the /htaccess folder</font><br>');
	} else {
    _e('<font color="red">NO wpadmin-secure.htaccess file found in the /htaccess folder</font><br>');
	}
	
	$filename = WP_CONTENT_DIR . '/bps-backup/root.htaccess';
	if (file_exists($filename)) {
    _e('<font color="green">&radic; Your Current Root .htaccess File is backed up</font><br>');
	} else {
    _e('<font color="red">Your Current Root .htaccess file is NOT backed up yet</font><br>');
	}
	
	$filename = WP_CONTENT_DIR . '/bps-backup/wpadmin.htaccess';
	if (file_exists($filename)) {
    _e('<font color="green">&radic; Your Current wp-admin .htaccess File is backed up</font><br>');
	} else {
    _e('<font color="red">Your Current wp-admin .htaccess File is NOT backed up yet</font><br>');
	}
	
	$filename = WP_CONTENT_DIR . '/bps-backup/master-backups/backup_default.htaccess';
	if (file_exists($filename)) {
    _e('<font color="green">&radic; Your BPS Master default.htaccess file is backed up</font><br>');
	} else {
    _e('<font color="red">Your BPS Master default.htaccess file is NOT backed up yet</font><br>');
	}
	
	$filename = WP_CONTENT_DIR . '/bps-backup/master-backups/backup_secure.htaccess';
	if (file_exists($filename)) {
    _e('<font color="green">&radic; Your BPS Master secure.htaccess file is backed up</font><br>');
	} else {
    _e('<font color="red">Your BPS Master secure.htaccess file is NOT backed up yet</font><br>');
	}
	
	$filename = WP_CONTENT_DIR . '/bps-backup/master-backups/backup_wpadmin-secure.htaccess';
	if (file_exists($filename)) {
    _e('<font color="green">&radic; Your BPS Master wpadmin-secure.htaccess file is backed up</font><br>');
	} else {
    _e('<font color="red">Your BPS Master wpadmin-secure.htaccess file is NOT backed up yet</font><br>');
	}

	$filename = WP_CONTENT_DIR . '/bps-backup/master-backups/backup_maintenance.htaccess';
	if (file_exists($filename)) {
    _e('<font color="green">&radic; Your BPS Master maintenance.htaccess file is backed up</font><br>');
	} else {
    _e('<font color="red">Your BPS Master maintenance.htaccess file is NOT backed up yet</font><br>');
	}

	$filename = WP_CONTENT_DIR . '/bps-backup/master-backups/backup_bp-maintenance.php';
	if (file_exists($filename)) {
    _e('<font color="green">&radic; Your BPS Master bp-maintenance.php file is backed up</font><br>');
	} else {
    _e('<font color="red">Your BPS Master bp-maintenance.php file is NOT backed up yet</font><br>');
	}
	
	$filename = WP_CONTENT_DIR . '/bps-backup/master-backups/backup_bps-maintenance-values.php';
	if (file_exists($filename)) {
    _e('<font color="green">&radic; Your BPS Master bps-maintenance-values.php file is backed up</font><br>');
	} else {
    _e('<font color="red">Your BPS Master bps-maintenance-values.php file is NOT backed up yet</font><br>');
	}
}

// Backup and Restore page - Backed up Root and wp-admin .htaccess file checks
function backup_restore_checks() {
	$bp_root_back = WP_CONTENT_DIR . '/bps-backup/root.htaccess'; 
	if (file_exists($bp_root_back)) { 
	_e('<font color="green"><strong>&radic; Your Root .htaccess file is backed up.</strong></font><br>'); 
	} else { 
	_e('<font color="red"><strong>Your Root .htaccess file is NOT backed up either because you have not done a Backup yet, an .htaccess file did NOT already exist in your root folder or because of a file copy error. Read the "Current Backed Up .htaccess Files Status Read Me" Help button for more specific information.</strong></font><br><br>');
	} 

	$bp_wpadmin_back = WP_CONTENT_DIR . '/bps-backup/wpadmin.htaccess'; 
	if (file_exists($bp_wpadmin_back)) { 
	_e('<font color="green"><strong>&radic; Your wp-admin .htaccess file is backed up.</strong></font><br>'); 
	} else { 
	_e('<font color="red"><strong>Your wp-admin .htaccess file is NOT backed up either because you have not done a Backup yet, an .htaccess file did NOT already exist in your /wp-admin folder or because of a file copy error. Read the "Current Backed Up .htaccess Files Status Read Me" Help button for more specific information.</strong></font><br>'); 
	} 
}

// Backup and Restore page - General check if existing .htaccess files already exist 
function general_bps_file_checks_backup_restore() {
	$dir='../';
	$filename = '.htaccess';
	if (file_exists($dir.$filename)) {
    _e('<font color="green">&radic; An .htaccess file was found in your root folder</font><br>');
	} else {
    _e('<font color="red">NO .htaccess file was found in your root folder</font><br>');
	}

	$filename = '.htaccess';
	if (file_exists($filename)) {
    _e('<font color="green">&radic; An .htaccess file was found in your /wp-admin folder</font><br>');
	} else {
    _e('<font color="red">NO .htaccess file was found in your /wp-admin folder</font><br>');
	}
}

// Backup and Restore page - BPS Master .htaccess backup file checks
function bps_master_file_backups() {
	$bps_default_master = WP_CONTENT_DIR . '/bps-backup/master-backups/backup_default.htaccess'; 
	if (file_exists($bps_default_master)) {
    _e('<font color="green"><strong>&radic; The default.htaccess Master file is backed up.</strong></font><br>');
	} else {
    _e('<font color="red"><strong>Your default.htaccess Master file has NOT been backed up yet!</strong></font><br>');
	}

	$bps_secure_master = WP_CONTENT_DIR . '/bps-backup/master-backups/backup_secure.htaccess'; 
	if (file_exists($bps_secure_master)) {
    _e('<font color="green"><strong>&radic; The secure.htaccess Master file is backed up.</strong></font><br>');
	} else {
    _e('<font color="red"><strong>Your secure.htaccess Master file has NOT been backed up yet!</strong></font><br>');
	}
	
	$bps_wpadmin_master = WP_CONTENT_DIR . '/bps-backup/master-backups/backup_wpadmin-secure.htaccess'; 
	if (file_exists($bps_wpadmin_master)) {
    _e('<font color="green"><strong>&radic; The wpadmin-secure.htaccess Master file is backed up.</strong></font><br>');
	} else {
    _e('<font color="red"><strong>Your wpadmin-secure.htaccess Master file has NOT been backed up yet!</strong></font><br>');
	}
	
	$bps_maintenance_master = WP_CONTENT_DIR . '/bps-backup/master-backups/backup_maintenance.htaccess'; 
	if (file_exists($bps_maintenance_master)) {
    _e('<font color="green"><strong>&radic; The maintenance.htaccess Master file is backed up.<strong</font><br>');
	} else {
    _e('<font color="red"><strong>Your maintenance.htaccess Master file has NOT been backed up yet!</strong></font><br>');
	}
	
	$bps_bp_maintenance_master = WP_CONTENT_DIR . '/bps-backup/master-backups/backup_bp-maintenance.php'; 
	if (file_exists($bps_bp_maintenance_master)) {
    _e('<font color="green"><strong>&radic; The bp-maintenance.php Master file is backed up.</strong></font><br>');
	} else {
    _e('<font color="red"><strong>Your bp-maintenance.php Master file has NOT been backed up yet!</strong></font><br>');
	}
	
	$bps_bp_maintenance_values = WP_CONTENT_DIR . '/bps-backup/master-backups/backup_bps-maintenance-values.php'; 
	if (file_exists($bps_bp_maintenance_values)) {
    _e('<font color="green"><strong>&radic; The bps-maintenance-values.php Master file is backed up.</strong></font><br>');
	} else {
    _e('<font color="red"><strong>Your bps-maintenance-values.php Master file has NOT been backed up yet!</strong></font><br>');
	}
}

// Check if Permalinks are enabled
function bps_check_permalinks() {
$permalink_structure = get_option('permalink_structure');	
	if ( get_option('permalink_structure') == '' ) { 
	_e('Permalinks Enabled: <font color="red"><strong>WARNING! Permalinks are NOT Enabled<br>Permalinks MUST be enabled for BPS to function correctly</strong></font>');
	} else {
	_e('Permalinks Enabled: <font color="green"><strong>&radic; Permalinks are Enabled</strong></font>'); 
	}
}

// Check PHP version
function bps_check_php_version() {
	if (version_compare(PHP_VERSION, '5.0.0', '>=')) {
    _e('PHP Version Check: <font color="green"><strong>&radic; Running PHP5</strong></font><br>');
}
	if (version_compare(PHP_VERSION, '5.0.0', '<')) {
    _e('<font color="red"><strong>WARNING! BPS requires PHP5 to function correctly. Your PHP version is: ' . PHP_VERSION . '</strong></font><br>');
	}
}

// Heads Up Display - Check PHP version - top error message new activations / installations
function bps_check_php_version_error() {
$options = get_option('bulletproof_security_options_monitor');
	if (version_compare(PHP_VERSION, '5.0.0', '>=')) {
	_e('');
	}
	if (version_compare(PHP_VERSION, '5.0.0', '<')) {
	if ($options['bps_HUD_alerts'] == 'bpsOn') {
    _e('<br><font color="red"><strong>WARNING! BPS requires at least PHP5 to function correctly. Your PHP version is: ' . PHP_VERSION . '</font></strong><br><strong><a href="http://www.ait-pro.com/aitpro-blog/1166/bulletproof-security-plugin-support/bulletproof-security-plugin-guide-bps-version-45/#bulletproof-security-issues-problems" target="_blank">BPS Guide - PHP5 Solution</a></strong><br><strong>The BPS Guide will open in a new browser window. You will not be directed away from your WordPress Dashboard.</strong><br><br>');
	}
	elseif ($options['bps_HUD_alerts'] == 'wpOn') {
	_e('<div class="update-nag"><font color="red"><strong>WARNING! BPS requires at least PHP5 to function correctly. Your PHP version is: ' . PHP_VERSION . '</font></strong><br><strong><a href="http://www.ait-pro.com/aitpro-blog/1166/bulletproof-security-plugin-support/bulletproof-security-plugin-guide-bps-version-45/#bulletproof-security-issues-problems" target="_blank">BPS Guide - PHP5 Solution</a></strong><br><strong>The BPS Guide will open in a new browser window. You will not be directed away from your WordPress Dashboard.</strong></div>');
	}
	elseif ($options['bps_HUD_alerts'] == 'Off') { 
	_e('');
	} else {
	_e('There appears to be a database error. This error is most likely caused by either that the bulletproof_security_options_monitor option_name is not in your database or values are invalid or missing from option_name bulletproof_security_options_monitor.');
	}
	}
}

// Heads Up Display - Check if Permalinks are enabled - top error message new activations / installations
function bps_check_permalinks_error() {
$permalink_structure = get_option('permalink_structure');
$options = get_option('bulletproof_security_options_monitor');	
	if ( get_option('permalink_structure') == '' ) { 
	if ($options['bps_HUD_alerts'] == 'bpsOn') {
	_e('<br><font color="red"><strong>WARNING! Permalinks are NOT Enabled. Permalinks MUST be enabled for BPS to function correctly</strong></font><br><strong><a href="http://www.ait-pro.com/aitpro-blog/2304/wordpress-tips-tricks-fixes/permalinks-wordpress-custom-permalinks-wordpress-best-wordpress-permalinks-structure/" target="_blank">BPS Guide - Enabling Permalinks</a></strong><br><strong>The BPS Guide will open in a new browser window. You will not be directed away from your WordPress Dashboard.</strong><br><br>');
	}
	elseif ($options['bps_HUD_alerts'] == 'wpOn') { 
	_e('<div class="update-nag"><font color="red"><strong>WARNING! Permalinks are NOT Enabled. Permalinks MUST be enabled for BPS to function correctly</strong></font><br><strong><a href="http://www.ait-pro.com/aitpro-blog/2304/wordpress-tips-tricks-fixes/permalinks-wordpress-custom-permalinks-wordpress-best-wordpress-permalinks-structure/" target="_blank">BPS Guide - Enabling Permalinks</a></strong><br><strong>The BPS Guide will open in a new browser window. You will not be directed away from your WordPress Dashboard.</strong></div>');
	}
	elseif ($options['bps_HUD_alerts'] == 'Off') { 
	_e('');
	} else {
	_e('');
	}
	}
}

// Heads Up Display - Check if this is a Windows IIS server and if IIS7 supports permalink rewriting
function bps_check_iis_supports_permalinks() {
global $wp_rewrite, $is_IIS, $is_iis7;
$options = get_option('bulletproof_security_options_monitor');
	if ( $is_IIS && !iis7_supports_permalinks() ) {
	if ($options['bps_HUD_alerts'] == 'bpsOn') {
	_e('<br><font color="red"><strong>WARNING! BPS has detected that your Server is a Windows IIS Server that does not support .htaccess rewriting. Do NOT activate BulletProof Security Modes unless you are absolutely sure you know what you are doing. Your Server Type is: ' . $_SERVER['SERVER_SOFTWARE'] . '</strong></font><br><strong><a href="http://codex.wordpress.org/Using_Permalinks" target="_blank">WordPress Codex - Using Permalinks - see IIS section</a></strong><br><strong>This link will open in a new browser window. You will not be directed away from your WordPress Dashboard.</strong><br>To remove this message permanently click <strong><a href="http://www.ait-pro.com/aitpro-blog/2566/bulletproof-security-plugin-support/bulletproof-security-error-messages" target="_blank">here.</a></strong><br><br>');
	}
	elseif ($options['bps_HUD_alerts'] == 'wpOn') { 
	_e('<div class="update-nag"><font color="red"><strong>WARNING! BPS has detected that your Server is a Windows IIS Server that does not support .htaccess rewriting. Do NOT activate BulletProof Security Modes unless you are absolutely sure you know what you are doing. Your Server Type is: ' . $_SERVER['SERVER_SOFTWARE'] . '</strong></font><br><strong><a href="http://codex.wordpress.org/Using_Permalinks" target="_blank">WordPress Codex - Using Permalinks - see IIS section</a></strong><br><strong>This link will open in a new browser window. You will not be directed away from your WordPress Dashboard.</strong><br>To remove this message permanently click <strong><a href="http://www.ait-pro.com/aitpro-blog/2566/bulletproof-security-plugin-support/bulletproof-security-error-messages" target="_blank">here.</a></strong></div>');
	}
	elseif ($options['bps_HUD_alerts'] == 'Off') { 
	_e('');
	} else {
	_e('');
	}
	}
}

// Heads Up Display - mkdir and chmod errors are suppressed on activation - check if /bps-backup folder exists
function bps_hud_check_bpsbackup() {
$options = get_option('bulletproof_security_options_monitor');
	if( !is_dir (WP_CONTENT_DIR . '/bps-backup')) {
	if ($options['bps_HUD_alerts'] == 'bpsOn') {
	_e('<br><font color="red"><strong>WARNING! BPS was unable to automatically create the /wp-content/bps-backup folder.</strong></font><br><strong>You will need to create the /wp-content/bps-backup folder manually via FTP.  The folder permissions for the bps-backup folder need to be set to 755 in order to successfully perform permanent online backups.</strong><br>To remove this message permanently click <strong><a href="http://www.ait-pro.com/aitpro-blog/2566/bulletproof-security-plugin-support/bulletproof-security-error-messages" target="_blank">here.</a></strong><br><br>');
	}
	elseif ($options['bps_HUD_alerts'] == 'wpOn') { 
	_e('<div class="update-nag"><font color="red"><strong>WARNING! BPS was unable to automatically create the /wp-content/bps-backup folder.</strong></font><br><strong>You will need to create the /wp-content/bps-backup folder manually via FTP.  The folder permissions for the bps-backup folder need to be set to 755 in order to successfully perform permanent online backups.</strong><br>To remove this message permanently click <strong><a href="http://www.ait-pro.com/aitpro-blog/2566/bulletproof-security-plugin-support/bulletproof-security-error-messages" target="_blank">here.</a></strong></div>');
	}
	elseif ($options['bps_HUD_alerts'] == 'Off') { 
	_e('');
	} else {
	_e('');
	}
	}
}

// Heads Up Display - mkdir and chmod errors are suppressed on activation - check if /bps-backup/master-backups folder exists
function bps_hud_check_bpsbackup_master() {
$options = get_option('bulletproof_security_options_monitor');
	if( !is_dir (WP_CONTENT_DIR . '/bps-backup/master-backups')) {
	if ($options['bps_HUD_alerts'] == 'bpsOn') {
	_e('<br><font color="red"><strong>WARNING! BPS was unable to automatically create the /wp-content/bps-backup/master-backups folder.</strong></font><br><strong>You will need to create the /wp-content/bps-backup/master-backups folder manually via FTP.  The folder permissions for the master-backups folder need to be set to 755 in order to successfully perform permanent online backups.</strong><br>To remove this message permanently click <strong><a href="http://www.ait-pro.com/aitpro-blog/2566/bulletproof-security-plugin-support/bulletproof-security-error-messages" target="_blank">here.</a></strong><br><br>');
	}
	elseif ($options['bps_HUD_alerts'] == 'wpOn') { 
	_e('<div class="update-nag"><font color="red"><strong>WARNING! BPS was unable to automatically create the /wp-content/bps-backup/master-backups folder.</strong></font><br><strong>You will need to create the /wp-content/bps-backup/master-backups folder manually via FTP.  The folder permissions for the master-backups folder need to be set to 755 in order to successfully perform permanent online backups.</strong><br>To remove this message permanently click <strong><a href="http://www.ait-pro.com/aitpro-blog/2566/bulletproof-security-plugin-support/bulletproof-security-error-messages" target="_blank">here.</a></strong></div>');
	}
	elseif ($options['bps_HUD_alerts'] == 'Off') { 
	_e('');
	} else {
	_e('');
	}
	}
}

// Heads Up Display - Check if PHP Safe Mode is Enabled - Display in BPS and WP Dashboard if S-Monitor is not set to Off
// This check checks if safe_mode is enabled not On or Off so no need for this check if(!ini_get('safe_mode')) {
function bps_check_safemode() {
$options = get_option('bulletproof_security_options_monitor');
	if (ini_get('safe_mode')) {
	$safe_mode = __('On');
	if ($options['bps_HUD_alerts'] == 'bpsOn') {
	_e('<br><font color="red"><strong>WARNING! BPS has detected that Safe Mode is set to On in your php.ini file.</strong></font><br><strong>If you see errors that BPS was unable to automatically create the backup folders this is probably the reason why.</strong><br>To remove this message permanently click <strong><a href="http://www.ait-pro.com/aitpro-blog/2566/bulletproof-security-plugin-support/bulletproof-security-error-messages" target="_blank">here.</a></strong><br>');
	}
	elseif ($options['bps_HUD_alerts'] == 'wpOn') {
	_e('<div class="update-nag"><font color="red"><strong>WARNING! BPS has detected that Safe Mode is set to On in your php.ini file.</strong></font><br><strong>If you see errors that BPS was unable to automatically create the backup folders this is probably the reason why.</strong><br>To remove this message permanently click <strong><a href="http://www.ait-pro.com/aitpro-blog/2566/bulletproof-security-plugin-support/bulletproof-security-error-messages" target="_blank">here.</a></strong></div>');
	}
	elseif ($options['bps_HUD_alerts'] == 'Off') { 
	_e('');
	} else {
	$safe_mode = __('Off');
	_e('');
	}	
	}
}

// Heads Up Display - Check if W3TC is active or not and check root htaccess file for W3TC htaccess code 
function bps_w3tc_htaccess_check($plugin_var) {
$options = get_option('bulletproof_security_options_monitor');	
	$filename = ABSPATH . '.htaccess';
	$string = file_get_contents($filename);
	$bpsSiteUrl = get_option('siteurl');
	$bpsHomeUrl = get_option('home');
	$plugin_var = 'w3-total-cache';
    $return_var = in_array( $plugin_var. '/' .$plugin_var. '.php', apply_filters('active_plugins', get_option('active_plugins')));
    if ($return_var == 1) { // return $return_var; ---- 1 equals active
	if ($bpsSiteUrl == $bpsHomeUrl) {
	if (!strpos($string, "W3TC")) {
	if ($options['bps_HUD_alerts'] == 'bpsOn') {
	_e('<br><font color="red"><strong>W3 Total Cache is activated, but W3TC .htaccess code was NOT found in your root .htaccess file.</strong></font><br><strong>W3TC needs to be redeployed by clicking either the W3TC auto-install or deploy buttons. Your Root .htaccess file must be temporarily unlocked so that W3TC can write to your Root .htaccess file. Click to <a href="admin.php?page=w3tc_general">Redeploy W3TC.</a><br>If you have put your site in either Default Mode or Maintenance Mode then disregard this Alert and DO NOT redeploy W3TC.</strong><br><br>');
	} 
	elseif ($options['bps_HUD_alerts'] == 'wpOn') {
	_e('<div class="update-nag"><font color="red"><strong>W3 Total Cache is activated, but W3TC .htaccess code was NOT found in your root .htaccess file.</strong></font><br><strong>W3TC needs to be redeployed by clicking either the W3TC auto-install or deploy buttons.  Your Root .htaccess file must be temporarily unlocked so that W3TC can write to your Root .htaccess file. Click to <a href="admin.php?page=w3tc_general" >Redeploy W3TC.</a><br>If you have put your site in either Default Mode or Maintenance Mode then disregard this Alert and DO NOT redeploy W3TC.</strong></div>');
	}
	}
	}
	}
	elseif ($return_var != 1) {
	if ($bpsSiteUrl == $bpsHomeUrl) {
	if (strpos($string, "W3TC")) {
	if ($options['bps_HUD_alerts'] == 'bpsOn') {
	_e('<br><font color="red"><strong>W3 Total Cache is deactivated and W3TC .htaccess code was found in your root .htaccess file.</strong></font><br><strong>If this is just temporary then this warning message will go away when you reactivate W3TC. If you are planning on uninstalling W3TC the W3TC .htaccess code will be automatically removed from your root .htaccess file when you uninstall W3TC. Your Root .htaccess file must be temporarily unlocked so that W3TC can remove the W3TC Root .htaccess code. If you manually edit your root htaccess file then refresh your browser to perform a new HUD htaccess file check.</strong><br><br>');
	}
	elseif ($options['bps_HUD_alerts'] == 'wpOn') {
	_e('<div class="update-nag"><font color="red"><strong>W3 Total Cache is deactivated and W3TC .htaccess code was found in your root .htaccess file.</strong></font><br><strong>If this is just temporary then this warning message will go away when you reactivate W3TC. Your Root .htaccess file must be temporarily unlocked so that W3TC can remove the W3TC Root .htaccess code. If you are planning on uninstalling W3TC the W3TC .htaccess code will be automatically removed from your root .htaccess file when you uninstall W3TC. If you manually edit your root htaccess file then refresh your browser to perform a new HUD htaccess file check.</strong></div>');
	}
	} 
	}
	}
	elseif ($options['bps_HUD_alerts'] == 'Off') { 
	_e('');
	} else {
	_e('');
	}
}

// Heads Up Display - Check if WPSC is active or not and check root htaccess file for WPSC htaccess code 
function bps_wpsc_htaccess_check($plugin_var) {
$options = get_option('bulletproof_security_options_monitor');	
	$filename = ABSPATH . '.htaccess';
	$string = file_get_contents($filename);
	$bpsSiteUrl = get_option('siteurl');
	$bpsHomeUrl = get_option('home');
	$plugin_var = 'wp-super-cache';
    $return_var = in_array( $plugin_var. '/' .'wp-cache.php', apply_filters('active_plugins', get_option('active_plugins')));
    if ($return_var == 1) { // return $return_var; ---- 1 equals active
	if ($bpsSiteUrl == $bpsHomeUrl) {
	if (!strpos($string, "WPSuperCache")) { 
	if ($options['bps_HUD_alerts'] == 'bpsOn') {
	_e('<br><font color="red"><strong>WP Super Cache is activated, but either you are not using WPSC mod_rewrite to serve cache files or the WPSC .htaccess code was NOT found in your root .htaccess file.</strong></font><br><strong>If you are not using WPSC mod_rewrite then just add this commented out line of code in anywhere in your root htaccess file - # WPSuperCache. If you are using WPSC mod_rewrite and the WPSC htaccess code is not in your root htaccess file - unlock your Root .htaccess file temporarily then click this <a href="options-general.php?page=wpsupercache&tab=settings">Update WPSC link</a> to go to the WPSC Settings page and click the Update Mod_Rewrite Rules button.<br>If you have put your site in either Default Mode or Maintenance Mode then disregard this Alert and DO NOT update your Mod_Rewrite Rules. Refresh your browser to perform a new HUD htaccess file check.</strong><br><br>');
	} 
	elseif ($options['bps_HUD_alerts'] == 'wpOn') {
	_e('<div class="update-nag"><font color="red"><strong>WP Super Cache is activated, but either you are not using WPSC mod_rewrite to serve cache files or the WPSC .htaccess code was NOT found in your root .htaccess file.</strong></font><br><strong>If you are not using WPSC mod_rewrite then just add this commented out line of code in anywhere in your root htaccess file - # WPSuperCache. If you are using WPSC mod_rewrite and the WPSC htaccess code is not in your root htaccess file - unlock your Root .htaccess file temporarily  then click this <a href="options-general.php?page=wpsupercache&tab=settings" >Update WPSC link</a> to go to the WPSC Settings page and click the Update Mod_Rewrite Rules button.<br>If you have put your site in either Default Mode or Maintenance Mode then disregard this Alert and DO NOT update your Mod_Rewrite Rules. Refresh your browser to perform a new HUD htaccess file check.</strong></div>');
	}
	}
	}
	}
	elseif ($return_var != 1) {
	if ($bpsSiteUrl == $bpsHomeUrl) {
	if (strpos($string, "WPSuperCache") ) {
	if ($options['bps_HUD_alerts'] == 'bpsOn') {
	_e('<br><font color="red"><strong>WP Super Cache is deactivated and WPSC .htaccess code - # BEGIN WPSuperCache # END WPSuperCache - was found in your root .htaccess file.</strong></font><br><strong>If this is just temporary then this warning message will go away when you reactivate WPSC. You will need to set up and reconfigure WPSC again when you reactivate WPSC. Your Root .htaccess file must be temporarily unlocked if you are planning on uninstalling WPSC. The WPSC .htaccess code will be automatically removed from your root .htaccess file when you uninstall WPSC. If you added a commented out line of code in anywhere in your root htaccess file - # WPSuperCache - then delete it and refresh your browser.</strong><br><br>');
	}
	elseif ($options['bps_HUD_alerts'] == 'wpOn') {
	_e('<div class="update-nag"><font color="red"><strong>WP Super Cache is deactivated and WPSC .htaccess code - # BEGIN WPSuperCache # END WPSuperCache - was found in your root .htaccess file.</strong></font><br><strong>If this is just temporary then this warning message will go away when you reactivate WPSC. You will need to set up and reconfigure WPSC again when you reactivate WPSC. Your Root .htaccess file must be temporarily unlocked if you are planning on uninstalling WPSC. The WPSC .htaccess code will be automatically removed from your root .htaccess file when you uninstall WPSC. If you added a commented out line of code in anywhere in your root htaccess file - # WPSuperCache - then delete it and refresh your browser.</strong></div>');
	}
	} 
	}
	}
	elseif ($options['bps_HUD_alerts'] == 'Off') { 
	_e('');
	} else {
	_e('');
	}
}

// Get WordPress Root Installation Folder 
function bps_wp_get_root_folder() {
$site_root = parse_url(get_option('siteurl'));
	if ( isset( $site_root['path'] ) )
	$site_root = trailingslashit($site_root['path']);
	else
	$site_root = '/';
	return $site_root;
}

// Display Root or Subfolder Installation Type
function bps_wp_get_root_folder_display_type() {
$site_root = parse_url(get_option('siteurl'));
	if ( isset( $site_root['path'] ) )
	$site_root = trailingslashit($site_root['path']);
	else
	$site_root = '/';
	if (preg_match('/[a-zA-Z0-9]/', $site_root)) {
	echo "Subfolder Installation";
	} else {
	echo "Root Folder Installation";
	}
}

// Check for Multisite
function bps_multsite_check() {  
	if ( is_multisite() ) { 
	_e('Multisite: <strong>Multisite is enabled</strong><br>');
	} else {
	_e('Multisite: <strong>Multisite is not enabled</strong><br>');
	}
}

// Security Modes Page - AutoMagic Single site message
function bps_multsite_check_smode_single() {  
global $wpdb;
	if ( !is_multisite() ) { 
	_e('<font color="green"><strong>Use These AutoMagic Buttons For Your Website<br>For Standard WP Installations</strong></font>');
	} else {
	_e('<strong>Do Not Use These AutoMagic Buttons</strong><br>For Standard WP Single Sites Only');
	}
}

// Security Modes Page - AutoMagic Multisite sub-directory message
function bps_multsite_check_smode_MUSDir() {  
global $wpdb;
	if ( is_multisite() && !is_subdomain_install() ) { 
	_e('<font color="green"><strong>Use These AutoMagic Buttons For Your Website<br>For WP Network / MU sub-directory Installations</strong></font>');
	} else {
	_e('<strong>Do Not Use These AutoMagic Buttons</strong><br>For Network / MU Sub-directory Webites Only');
	}
}

// Security Modes Page - AutoMagic Multisite sub-domain message
function bps_multsite_check_smode_MUSDom() {  
global $wpdb;
	if ( is_multisite() && is_subdomain_install() ) { 
	//if ( is_subdomain_install() ) {
	_e('<font color="green"><strong>Use These AutoMagic Buttons For Your Website<br>For WP Network / MU sub-domain Installations</strong></font>');
	} else {
	_e('<strong>Do Not Use These AutoMagic Buttons</strong><br>For Network / MU Sub-domain Websites Only');
	}
}
//}

// Check if username Admin exists
function check_admin_username() {
global $wpdb;
	$name = $wpdb->get_var( $wpdb->prepare("SELECT user_login FROM $wpdb->users WHERE user_login='admin'"));
	if ($name == "admin"){
	_e('<font color="red"><strong>Recommended Security Changes: Username "admin" is being used. It is recommended that you change the default administrator username "admin" to a new unique username.</strong></font><br><br>');
	} else {
	_e('<font color="green"><strong>&radic; The Administrator username "admin" is not being used</strong></font><br>');
	}
}

// BPS Pro see System Info Tab - message only
function bpsPro_sysinfo_message() {
$filename = WP_CONTENT_DIR . '/plugins/bulletproof-security/admin/php/php-options.php';
	if (file_exists($filename)) {
    _e('<font color="green"><strong>&radic; Additional BPS Pro Security Status information is displayed on the System Info page under BPS Pro Security Modules Info</strong></font><br>');
	} else {
    _e('<font color="red"><strong>The /php/php-options.php file was not found or the folder is not accessible.</strong></font><br>');
	}
}

// System Info page - BPS Pro Security Modules S-Montitor enabled check
function bpsPro_sysinfo_mod_checks_smon() {
$options = get_option('bulletproof_security_options_monitor');
	if (!$options['bps_first_launch']) { 
	_e('<font color="red"><strong>S-Monitor Options have not been saved / enabled yet</strong></font><br><strong><a href="admin.php?page=bulletproof-security/admin/monitor/monitor.php">Click Here</a></strong> to go to the S-Monitor Monitoring and Alerting page.<br>BPS Alerts will not be displayed until you click the Save Options button the first time you install BPS.<br>');
	} else {
	_e('<font color="green"><strong>&radic; S-Monitor BPS Alerts have been enabled</strong></font><br>');
}
}

// System Info page - BPS Pro Security Modules - HUD set to off check
function bpsPro_sysinfo_mod_checks_hud() {
$options = get_option('bulletproof_security_options_monitor');
	if ($options['bps_HUD_alerts'] == 'off') { 
_e('<font color="red"><strong>BPS HUD Alerts have been turned off or are not enabled yet</strong></font><br><strong><a href="admin.php?page=bulletproof-security/admin/monitor/monitor.php">Click Here</a></strong> to go to the S-Monitor Monitoring and Alerting page.<br>');
	} else {
	_e('<font color="green"><strong>&radic; BPS S-Monitor HUD Alerts are turned on</strong></font><br>');
}
}

// System Info page - BPS Pro Security Modules - Check if a Php.ini file has been created or added to a File Manager slot
function bpsPro_sysinfo_mod_checks_phpini() {
$options2 = get_option('bulletproof_security_options');
//$options = get_option('bulletproof_security_options_monitor');	
	if ($options2['bpsinifiles_input_1'] == '' && $options2['bpsinifiles_input_2'] == '' && $options2['bpsinifiles_input_3'] == '' && $options2['bpsinifiles_input_4'] == '' && $options2['bpsinifiles_input_5'] == '' && $options2['bpsinifiles_input_6'] == '' && $options2['bpsinifiles_input_7'] == '' && $options2['bpsinifiles_input_8'] == '' && $options2['bpsinifiles_input_9'] == '' && $options2['bpsinifiles_input_10'] == '') {
	//if ($options['bps_phpini_created'] == 'bpsOn') { 
_e('<font color="red"><strong>It appears that a php.ini file path has not been added to the Php.ini File Manager yet</strong></font><br>To create or add a php.ini file for your site <strong><a href="admin.php?page=bulletproof-security/admin/php/php-options.php">Click Here</a></strong> to go to the P-Security PHP.ini Options page.<br>');
	} else {
	_e('<font color="green"><strong>&radic; A custom php.ini file or existing php.ini file appears to be in use</strong></font><br>');
	}
}

// System Info page - BPS Pro Security Modules - Check if PHP Error Log Location has been Set
function bpsPro_sysinfo_mod_checks_elog() {
$options2 = get_option('bulletproof_security_options2');
//$options = get_option('bulletproof_security_options_monitor');	
	if ($options2['bps_error_log_location'] == '' ) { 
	//if ($options['bps_PHP_ELogLoc_set'] == 'bpsOn') { 
	_e('<font color="red"><strong>The PHP Error Log Location has not been set yet</strong></font><br>To set your PHP Error Log location <strong><a href="admin.php?page=bulletproof-security/admin/php/php-options.php#bps-tabs-3">Click Here</a></strong> to go to the P-Security PHP Error Log page.<br>');
	} else { 
	_e('<font color="green"><strong>&radic; A PHP Error Log has been set up and is in use</strong></font><br>');
	}
}

// Get SQL Mode from WPDB
function bps_get_sql_mode() {
global $wpdb;
	$mysqlinfo = $wpdb->get_results("SHOW VARIABLES LIKE 'sql_mode'");
        if (is_array($mysqlinfo)) $sql_mode = $mysqlinfo[0]->Value;
        if (empty($sql_mode)) $sql_mode = __('Not Set');
		else $sql_mode = __('Off');
} 

// Show DB errors should already be set to false in /includes/wp-db.php
// Extra function insurance show_errors = false
function bps_wpdb_errors_off() {
global $wpdb;
	$wpdb->show_errors = false;
	if ($wpdb->show_errors != false) {
	_e('<font color="red"><strong>WARNING! WordPress DB Show Errors Is Set To: true! DB errors will be displayed</strong></font><br>');
	} else {
	_e('<font color="green"><strong>&radic; WordPress DB Show Errors Function Is Set To: </strong></font>');
	_e('<font color="black"><strong>false</strong></font><br>');
	_e('<font color="green"><strong>&radic; WordPress Database Errors Are Turned Off</strong></font><br>');
	}	
}

// Hide / Remove WordPress Version Meta Generator Tag - echo only for remove_action('wp_head', 'wp_generator');
function bps_wp_remove_version() {
global $wp_version;
	_e('<font color="green"><strong>&radic; WordPress Meta Generator Tag Removed<br>&radic; WordPress Version Is Not Displayed / Not Shown</strong></font><br>');
}

// Return Nothing For WP Version Callback
function bps_wp_generator_meta_removed() {
	if ( !is_admin()) {
	global $wp_version;
	$wp_version = '';
	}
}

// the update nag generated from function bpsPro_update_nag() for WP and bpsPro_update_bps_only_nag() for BPS
// checking current installed version against currently available version
function bpspro_get_latest_version() {
global $bpspro_version, $bpspro_plugin, $current_tag, $xml_title_key, $xml_version_key, $counter, $story_array;
    $file  = 'http://www.ait-pro.com/xml/bpsversions.xml';
    $xml_title_key = '*PLUGINS*PLUGIN*TITLE';
    $xml_version_key = '*PLUGINS*PLUGIN*VERSION';
    $story_array  = array();
    $counter = 0;

    class bps_xml_story {
    var $title, $version;
    }

function xml_contents($parser, $data){
global $current_tag, $xml_title_key, $xml_version_key, $counter, $story_array;
switch($current_tag){
	case $xml_title_key:
	$story_array[$counter] = new bps_xml_story();
	$story_array[$counter]->title = $data;
break;
	case $xml_version_key:
	$story_array[$counter]->version = $data;
	$counter++;
break;
	}
}

function startTag($parser, $data){
global $current_tag;
$current_tag .= "*$data";
}

function endTag($parser, $data){
global $current_tag;
$tag_key = strrpos($current_tag, '*');
$current_tag = substr($current_tag, 0, $tag_key);
}

$bps_xml_parser = xml_parser_create();
xml_set_element_handler($bps_xml_parser, 'startTag', 'endTag');
xml_set_character_data_handler($bps_xml_parser, 'xml_contents');

// Open the XML file for reading
$fp = fopen($file, 'r')
	or die('Error reading BPS Pro XML versions file.');

// Read the XML file 4KB at a time
$data = fread($fp, 4096);

	if(!(xml_parse($bps_xml_parser, $data, feof($fp)))){
        die(sprintf("BPS Pro XML version file fetch error: %s at line %d",
           xml_error_string(xml_get_error_code($bps_xml_parser)),
           xml_get_current_line_number($bps_xml_parser)));
    }

    xml_parser_free($bps_xml_parser);
    fclose($fp);

    // print_r($story_array);

    for($x=0;$x<count($story_array);$x++){
        if ($story_array[$x]->title == strtolower($bpspro_plugin))
            $latest_version = trim($story_array[$x]->version);
    }

    if (strcmp($bpspro_version, $latest_version) == 0)
    return '0'; // no update
    else
    return '1'; // new version available
}

// Daily Cron check for new BPS versions - function bpsPro_schedule_update_checks() must match schedules 
// This is now a Daily Cron but leaving the function name as weekly - Keep it simple
function bpsPro_add_weekly_cron( $schedules ) {
	$schedules['daily'] = array('interval' => 86400, 'display' => __('Once Daily'));
	//$schedules['weekly'] = array('interval' => 604800, 'display' => __('Once Weekly'));
	return $schedules;
}
add_filter('cron_schedules', 'bpsPro_add_weekly_cron');

// Check if Server is Up - send empty test string - display error if Down
$old_bpspro_url = 'http://www.ait-pro.com/';
$bpspro_url = 'http://api.ait-pro.com/';
function bps_cuser_errors() {
global $bpspro_url;
	$options = get_option('bulletproof_security_options_activation');
	$test_array = array('test_connection' => $options['delete_paypal_email'],);
	$test_string = bps_test_array('delete_paypal_email', $test_array);
	$check_server_response = wp_remote_post($bpspro_url, $test_string);
	if (!get_option('bulletproof_security_options_activation')) {
	exit;
	} else {
	if (!is_wp_error($check_server_response) && ($check_server_response['response']['code'] != 200)) {
	__e('<font color="red"><strong>Error: The Server appears to be down. Please send an email to info@ait-pro.com</strong></font>');
	exit;
	}
	}
}

// Test array
function bps_test_array($test, $test_string) {
	return array('body' => array('action' => $test, 'request' => serialize($test_string)), 'user-agent' => get_bloginfo('url'));	
}

// checking current installed version against currently available version
function bpsPro_update_checks() {
global $bpspro_abbr, $bpspro_version, $bpspro_plugin, $wpdb, $wp_local_package;
	$plugin_name = trim(strtolower($bpspro_plugin));	
	$php_version = phpversion();
	$current = get_site_transient( $plugin_name.'_update_plugin' );
	
	if ( !is_object($current) ) {
	$current = new stdClass;
	$current->updates = array();
	$current->version_checked = $bpspro_version;
	}

	$locale = apply_filters( 'core_version_check_locale', get_locale() );
	// Update last_checked for current to prevent multiple blocking requests if request hangs
	$current->last_checked = time();
	set_site_transient( $plugin_name.'_update_plugin', $current );

	if ( method_exists( $wpdb, 'db_version' ) )
	$mysql_version = preg_replace('/[^0-9.].*/', '', $wpdb->db_version());
	else
	$mysql_version = 'N/A';
	if ( is_multisite( ) ) {
	$user_count = get_user_count( );
	$num_blogs = get_blog_count( );
	$wp_install = network_site_url( );
	$multisite_enabled = 1;
	} else {
	$user_count = count_users( );
	$multisite_enabled = 0;
	$num_blogs = 1;
	$wp_install = home_url( '/' );
	}
	
	$url = "http://www.ait-pro.com/vcheck/?bps_version=$bps_version";
	
	$options = array('timeout' => ( ( defined('DOING_CRON') && DOING_CRON ) ? 30 : 3 ),	'user-agent' => $bpspro_plugin . '/' . $bpspro_version . '; ' . home_url( '/' ), 'headers' => array('wp_install' => $wp_install, 'wp_blog' => home_url( '/' )));

	$response = wp_remote_get($url, $options);

	if ( is_wp_error( $response ) )
		return false;
	if ( 200 != $response['response']['code'] )
		return false;
		
	$body = trim( $response['body'] );
	$body = str_replace(array("\r\n", "\r"), "\n", $body);
	$returns = array();
	foreach ( explode( "\n\n", $body ) as $entry ) {
		$returns = explode("\n", $entry);
		$new_option = new stdClass();
		$new_option->latest_version = esc_attr( $returns[0] );
	}

	$updates = new stdClass();
	//$updates->updates = $new_options;
	$updates->last_checked = time();
	$updates->version_checked = $bpspro_version;
	$updates->latest_version = $new_option->latest_version;
	set_site_transient( $plugin_name.'_update_plugin',  $updates);
	
	if ( version_compare($updates->version_checked, $updates->latest_version, '>=' ) )	// 5.1 to 5.1.1		
	return false;
	
	//$bpsversion_email = $updates->latest_version;
	$options = get_option('bulletproof_security_options_email');	
	if ($options['bps_upgrade_email'] == 'yes') {
	$bps_email = $options['bps_send_email_to'];
	$bps_email_from = $options['bps_send_email_from'];
	$bps_email_cc = $options['bps_send_email_cc'];
	$bps_email_bcc = $options['bps_send_email_bcc'];
	$justUrl = get_site_url();
	$mail_To = "$bps_email";
	$headers = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	$headers .= "From: $bps_email_from" . "\r\n";
	$headers .= "Cc: $bps_email_cc" . "\r\n";
	$headers .= "Bcc: $bps_email_bcc" . "\r\n";
	$mail_Subject = " BPS Pro Upgrade Notification ";

	$mail_message = '<p><strong>A new version of BPS Pro is available.</strong></p>';
	$mail_message .= '<p>Site: '."$justUrl".'</p>'; 
	$mail_message .= '<p>Download the new BPS Pro version from http://www.ait-pro.com/pd/</p>';
	mail($mail_To, $mail_Subject, $mail_message, $headers);
	}
}
add_action('bpsPro_update_check', 'bpsPro_update_checks');

// BPS Upgrade Check - displayed in WP Dashboard
function bpsPro_update_nag() {
global $bpspro_version, $bpspro_plugin;
	$options = get_option('bulletproof_security_options_monitor');
	
	if ( !current_user_can('update_core') )
	return false;
		
	$plugin_name = trim(strtolower($bpspro_plugin));
	$cur = get_site_transient( $plugin_name.'_update_plugin' );
	
	if ( empty($cur) )
	return false;
	// echo 'Installed: '. $bpspro_plugin .' v'.$cur->version_checked .'. Latest version: ' . $cur->latest_version . "\n";
	
	if ( version_compare($cur->version_checked, $cur->latest_version, '>=' ) )	// 5.1 to 5.1.1		
	return false;
		
	if ( current_user_can('update_core') ) {
	if ($options['bps_upgrade_notice'] == 'wpOn') { 
	$msg = sprintf( __('%1$s version %2$s is now available! <a href="%3$s" target="_blank">download it now</a>.'), $bpspro_plugin, $cur->latest_version, 'http://www.ait-pro.com/pd/' );
	echo "<div class='update-nag'>$msg</div>";
	}
	}
	if ( !current_user_can('update_core') && ($options['bps_upgrade_notice'] == 'wpOn')) {
	$msg = sprintf( __('%1$s version %2$s is now available! Please notify the site administrator.'), $bpspro_plugin, $cur->latest_version );
	echo "<div class='update-nag'>$msg</div>";
	} else { 
	echo '';
	}
}
add_action( 'admin_notices', 'bpsPro_update_nag', 4 );

// BPS Upgrade check - displayed in BPS Only
function bpsPro_update_bps_only_nag() {
global $bpspro_version, $bpspro_plugin;
	$options = get_option('bulletproof_security_options_monitor');	
		
	$plugin_name = trim(strtolower($bpspro_plugin));
	$cur = get_site_transient( $plugin_name.'_update_plugin' );
	
	if ( empty($cur) )
	return false;
	
	//echo 'Installed: '. $bpspro_plugin .' v'.$cur->version_checked .'. Latest version: ' . $cur->latest_version . "\n";
	if ( version_compare($cur->version_checked, $cur->latest_version, '>=' ) )	// 5.1 to 5.1.1		
	return false;	

	if ($options['bps_upgrade_notice'] == 'bpsOn') { 
	$msg = sprintf( __('%1$s version %2$s is now available! <a href="%3$s" target="_blank">download it now</a>.<br>'), $bpspro_plugin, $cur->latest_version, 'http://www.ait-pro.com/pd/' );
	echo "$msg";
	} else { 
	echo '';
	}
}

// Was a Weekly Cron Job is now a Daily Cron - bpsPro_update_check
function bpsPro_schedule_update_checks() {
	$bpsCronCheck = wp_get_schedule('bpsPro_update_check');
	
	if ($bpsCronCheck == 'weekly') {
	wp_clear_scheduled_hook('bpsPro_update_check');
	}
	if (!wp_next_scheduled('bpsPro_update_check')) {
		//wp_schedule_event(time(), 'weekly', 'bpsPro_update_check');
		wp_schedule_event(time(), 'daily', 'bpsPro_update_check');
	}
}

// comment out to kill action and uncomment clear scheduled hook
add_action('init', 'bpsPro_schedule_update_checks');

// uncomment to delete any scheduled version checks for bps
//wp_clear_scheduled_hook('bpsPro_update_check');

?>