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
?>
</div>

<?php
// Simple email test for the PHP mail()
function bpsEmailTest() {
	if (isset($_POST['bpsEmailTestSubmit']) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_email_test' );	  

	if( !class_exists( 'WP_Http' ) )
	  include_once( ABSPATH . WPINC. '/class-http.php' );

$request = new WP_Http;
$url = get_site_url().'/wp-content/plugins/bulletproof-security/admin/test/bps-email-check.php';
$response = $request->request( $url, array( 'method' => 'POST', 'header' => $header, 'body' => $body) );

	if( is_wp_error( $response ) ) {
	echo "Unable to connect to $url";
	} else {

$admin_email = get_option('admin_email');
$bps_email = $_POST['bpsEmail'];
$justUrl = get_site_url();
$mail_To = "$bps_email";
$headers = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
$headers .= "From: $bps_email";
$mail_Subject = " BPS Pro Email Test ";

$mail_message = '<p>Email Test for the PHP mail() function: </p>';
$mail_message .= '<p>Site: '."$justUrl".'</p>'; 

$bpsString = print_r($response, TRUE);
//echo $bpsString; //for testing
$mail_message .= '<pre>'."$bpsString".'</pre>';

	mail($mail_To, $mail_Subject, $mail_message, $headers);
$text = "Test Email Sent to $bps_email. Please wait at least 15 minutes to receive the test email.";
	echo '<!-- Last Action --><div id="message" class="updated fade" style="color:#008000; font-weight:bold; border:1px solid #999999; margin-left:104px; margin-top:9px;"><p>'.$text.'</p></div>';
// note red error message color is #ff0000
}
}
}
?> 

<div class="wrap">
<h2 style="margin-left:104px;"><?php _e('S-Monitor ~ Security Monitoring and Alerting'); ?></h2>

<!-- jQuery UI Tab Menu -->
<div id="bps-container">
	<div id="bps-tabs" class="bps-menu">
    <div id="bpsHead" style="position:relative; top:0px; left:0px;"><img src="<?php echo get_site_url(); ?>/wp-content/plugins/bulletproof-security/admin/images/bps-pro-logo.png" style="float:left; padding:0px 8px 0px 0px; margin:-68px 0px 0px 0px;" /></div>
		<ul>
			<li><a href="#bps-tabs-1">Options</a></li>
            <li><a href="#bps-tabs-2">Whats New</a></li>
			<li><a href="#bps-tabs-3">Help &amp; FAQ</a></li>
		</ul>
            
<div id="bps-tabs-1" class="bps-tab-page">
<h2><?php _e('Monitoring and Alerting Options'); ?></h2>

<div id="bpsMonitoringAlerting" style="border-top:1px solid #999999;">

<table width="100%" border="0">
  <tr>
   <td width="50%"><h3><?php _e('Monitoring and Alerting Options'); ?>  <button id="bps-open-modal1" class="bps-modal-button"><?php _e('Read Me'); ?></button></h3>
   <div id="bps-modal-content1" title="<?php _e('Monitoring and Alerting Options'); ?>">
	<p><?php _e('<strong>This Read Me Help window is draggable and resizable</strong><br><br><strong>Monitoring and Alerting Options Explained</strong><br><br><strong>First Install / Launch S-Monitor Notification:</strong> is displayed when you first install BPS Pro. This Alert is designed to get you to the S-Monitor page to click the Save Options button the first time you install BPS Pro. After you have selected the Monitoring and Alerting options that you want then turn this Alert Off and click the Save Button.<br><br><strong>BPS Pro Upgrade Notification:</strong>  Displays an Alert when a new version of BPS Pro is available. You can also select Upgrade Notifications to be emailed to you under Email Alerting Options. If you turn off Alerts for BPS Pro Upgrade Notifications in BPS and WordPress you can still recieve email alerts.<br><br><strong>BPS Security Status: Currently Active .htaccess File or Alert:</strong> You can display which currently active BPS Pro htaccess file is active in BPS pages only, your WordPress Dashboard or turn this alert off.<br><br><strong>HUD Alerts: BPS Error, Problem and Warning Alerts:</strong> Heads Up Display - HUD Alerts are important and it is recommended that you choose to display these Alerts in your WordPress Dashboard. HUD Alerts will alert you to any serious problems with BPS or any other problem or issues that need to be corrected right away.<br><br><strong>PHP Error Log: Check if Folder Location Has Been Set:</strong> This is a reminder Alert to remind you to set your BPS php error log file path as soon as possible. A php error log is a good thing to have in general to check for website problems and it is important in website security monitoring as well.<br><br><strong>PHP Error Log: New Errors in The PHP Error Log:</strong> When new php errors occur on your website they are logged in your php error log and you are alerted by BPS that you have a new php error in your error log. The BPS PHP Error Log Alert contains a link to the P-Security PHP Error Log page. You can also choose to have PHP Error Log Alerts emailed to you under the Email Alerting Options.<br><br><strong>Php.ini File: Has Been Created, Valid and Error Checks:</strong> Multiple checks displays several possible different warning messages or error messages: A reminder Alert to remind you to create your custom php.ini file as soon as possible - add an existing php.ini file to the P-Security Php.ini File Manager as soon as possible. Checks that your Server is recoginizing your custom php.ini file as the Loaded Configuration php.ini file for your website. Checks that the PHP error log Set To Location matches the error log path seen by the Server. For additional checking of individual directives within your custom php.ini file see the Php.ini Security Status page.<br><br><strong>F-Lock: Check File Lock / Unlock Status:</strong> Checks your file permissions. If your Host Server is using CGI as the php handler and if your Server API is CGI displayed properly then this check works perfectly to determine your file permissions locked or unlocked status. If your Host is using DSO mod_php you will see error messages that the files are not locked. For now BPS is displaying this error message just in case your Host has named the SAPI display name in phpinfo incorrectly or they are using a new naming convention for your SAPI. If your Host Server is definitely using DSO mod_php then you can turn this S-monitor option off. DSO file permissions should be 644 and cannot be set more restrictive because of the way DSO works. File permissions for CGI and DSO are managed on the F-Lock page. We would appreciate feedback on this if you Host has named your SAPI with something other then CGI but your Host Server is actually using CGI. We will make a list of these Hosts and add custom coding exceptions for these Hosts to make this check more accurate and display warning messages specifically by Host.<br><br><strong>BPS Pro Video Tutorial links can be found in the Help & FAQ pages.</strong>'); ?></p>
</div>
</td>
    <td width="50%"><h3><?php _e('Email Alerting Options'); ?>  <button id="bps-open-modal2" class="bps-modal-button"><?php _e('Read Me'); ?></button></h3>
    <div id="bps-modal-content2" title="<?php _e('Email Alerting Options'); ?>">
	<p><?php _e('<strong>This Read Me Help window is draggable and resizable</strong><br><br><strong>Email Alerting Options</strong><br><br>These email alerting options work independently of the displayed BPS Alerts so for example you could have the BPS Pro Upgrade Notification display turned off for your website and still receive emails notifying you when a new version of BPS Pro is available.<br><br><strong>PHP Error Log: New Errors in The PHP Error Log:</strong> Choose whether or not to have email alerts sent when a new php error occurs on your website.<br><br><strong>BPS Pro Upgrade Notification:</strong> Choose whether or not to have email alerts sent when a new version of BPS Pro is avaiable.<br><br>The email address fields To, From, Cc and Bcc can be email addresses for your hosting account, your WordPress Administrator email address or 3rd party email addesses like gmail or yahoo email. If you are sending emails to multiple email recipients then separate the email addresses with a comma. Example: someone@somewhere.com, someoneelse@somewhereelse.com. You can add a space or not add a space after the comma between email addresses.<br><br><strong>BPS Pro Video Tutorial links can be found in the Help & FAQ pages.</strong>'); ?></p>
</div>
</td>
  </tr>
</table>
<?php if (!current_user_can('manage_options')) { echo 'Permission Denied'; } else { ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bps-help_faq_table">
  <tr>
    <td colspan="2" class="bps-table_title">&nbsp;</td>
  </tr>
  <tr>
    <td width="50%" class="bps-table_cell_help">
    <form name="bps-monitor-options" action="options.php" method="post">
<?php settings_fields('bulletproof_security_options_monitor'); ?>
<?php $options = get_option('bulletproof_security_options_monitor'); ?>
<strong><label for="bps-monitor-options"><?php _e('First Install / Launch S-Monitor Notification'); ?></label></strong><br />
<select name="bulletproof_security_options_monitor[bps_first_launch]" style="width:340px;">
<option value="bpsOn"<?php selected('bpsOn', $options['bps_first_launch']); ?>><?php _e('Display Alert in BPS Only'); ?></option>
<option value="wpOn"<?php selected('wpOn', $options['bps_first_launch']); ?>><?php _e('Display Alert in WP Dashboard'); ?></option>
<option value="Off"<?php selected('Off', $options['bps_first_launch']); ?>><?php _e('Turn Off Displayed Alert'); ?></option>
        </select>
<br /><br />
<strong><label for="bps-monitor-options"><?php _e('BPS Pro Upgrade Notification'); ?></label></strong><br />
<select name="bulletproof_security_options_monitor[bps_upgrade_notice]" style="width:340px;">
<option value="bpsOn"<?php selected('bpsOn', $options['bps_upgrade_notice']); ?>><?php _e('Display Alert in BPS Only'); ?></option>
<option value="wpOn"<?php selected('wpOn', $options['bps_upgrade_notice']); ?>><?php _e('Display Alert in WP Dashboard'); ?></option>
<option value="Off"<?php selected('Off', $options['bps_upgrade_notice']); ?>><?php _e('Turn Off Displayed Alert'); ?></option>
        </select>
<br /><br />
<strong><label for="bps-monitor-options"><?php _e('BPS Security Status: Currently Active .htaccess File or Alert'); ?></label></strong><br />       
<select name="bulletproof_security_options_monitor[bps_security_status]" style="width:340px;">
<option value="bpsOn"<?php selected('bpsOn', $options['bps_security_status']); ?>><?php _e('Display Alerts in BPS Only'); ?></option>
<option value="wpOn"<?php selected('wpOn', $options['bps_security_status']); ?>><?php _e('Display Alerts in WP Dashboard'); ?></option>
<option value="Off"<?php selected('Off', $options['bps_security_status']); ?>><?php _e('Turn Off Displayed Alerts'); ?></option>
        </select>        
<br /><br />
<strong><label for="bps-monitor-options"><?php _e('HUD Alerts: BPS Error, Problem and Warning Alerts'); ?></label></strong><br />  
<select name="bulletproof_security_options_monitor[bps_HUD_alerts]" style="width:340px;">
<option value="bpsOn"<?php selected('bpsOn', $options['bps_HUD_alerts']); ?>><?php _e('Display Alerts in BPS Only'); ?></option>
<option value="wpOn"<?php selected('wpOn', $options['bps_HUD_alerts']); ?>><?php _e('Display Alerts in WP Dashboard'); ?></option>
<option value="Off"<?php selected('Off', $options['bps_HUD_alerts']); ?>><?php _e('Turn Off Displayed Alerts'); ?></option>
        </select>
<br /><br />
<strong><label for="bps-monitor-options"><?php _e('PHP Error Log: Check if Folder Location Has Been Set'); ?></label></strong><br />    
<select name="bulletproof_security_options_monitor[bps_PHP_ELogLoc_set]" style="width:340px;">
<option value="bpsOn"<?php selected('bpsOn', $options['bps_PHP_ELogLoc_set']); ?>><?php _e('Display Alerts in BPS Only'); ?></option>
<option value="wpOn"<?php selected('wpOn', $options['bps_PHP_ELogLoc_set']); ?>><?php _e('Display Alerts in WP Dashboard'); ?></option>
<option value="Off"<?php selected('Off', $options['bps_PHP_ELogLoc_set']); ?>><?php _e('Turn Off Displayed Alerts'); ?></option>
        </select>
<br /><br />
<strong><label for="bps-monitor-options"><?php _e('PHP Error Log: New Errors in The PHP Error Log'); ?></label></strong><br />    
<select name="bulletproof_security_options_monitor[bps_PHP_ELog_error]" style="width:340px;">
<option value="bpsOn"<?php selected('bpsOn', $options['bps_PHP_ELog_error']); ?>><?php _e('Display Alerts in BPS Only'); ?></option>
<option value="wpOn"<?php selected('wpOn', $options['bps_PHP_ELog_error']); ?>><?php _e('Display Alerts in WP Dashboard'); ?></option>
<option value="Off"<?php selected('Off', $options['bps_PHP_ELog_error']); ?>><?php _e('Turn Off Displayed Alerts'); ?></option>
        </select>        
<br /><br />
<strong><label for="bps-monitor-options"><?php _e('Php.ini File: Has Been Created, Valid and Error Checks'); ?></label></strong><br />  
<select name="bulletproof_security_options_monitor[bps_phpini_created]" style="width:340px;">
<option value="bpsOn"<?php selected('bpsOn', $options['bps_phpini_created']); ?>><?php _e('Display Alerts in BPS Only'); ?></option>
<option value="wpOn"<?php selected('wpOn', $options['bps_phpini_created']); ?>><?php _e('Display Alerts in WP Dashboard'); ?></option>
<option value="Off"<?php selected('Off', $options['bps_phpini_created']); ?>><?php _e('Turn Off Displayed Alerts'); ?></option>
        </select>
<br /><br />
<strong><label for="bps-monitor-options"><?php _e('F-Lock: Check File Lock / Unlock Status'); ?></label></strong><br />  
<select name="bulletproof_security_options_monitor[bps_flock_status]" style="width:340px;">
<option value="bpsOn"<?php selected('bpsOn', @$options['bps_flock_status']); ?>><?php _e('Display Alerts in BPS Only'); ?></option>
<option value="wpOn"<?php selected('wpOn', @$options['bps_flock_status']); ?>><?php _e('Display Alerts in WP Dashboard'); ?></option>
<option value="Off"<?php selected('Off', @$options['bps_flock_status']); ?>><?php _e('Turn Off Displayed Alerts'); ?></option>
        </select>                         
<p class="submit">
<input type="submit" name="bps-monitor-values_submit" class="button" value="<?php esc_attr_e('Save Options') ?>" />
</p>
</form>
	</td>
    <td width="50%" valign="top" class="bps-table_cell_help">
<form name="bpsEmailAlerts" action="options.php" method="post">
    <?php settings_fields('bulletproof_security_options_email'); ?>
	<?php $options = get_option('bulletproof_security_options_email'); ?>
<strong><label for="bps-monitor-email"><?php _e('PHP Error Log: New Errors in The PHP Error Log'); ?></label></strong><br />
<select name="bulletproof_security_options_email[bps_error_log_email]" style="width:340px;">
<option value="yes"<?php selected('yes', $options['bps_error_log_email']); ?>><?php _e('Send Email Alerts'); ?></option>
<option value="no"<?php selected('no', $options['bps_error_log_email']); ?>><?php _e('Do Not Send Email Alerts'); ?></option>
</select><br /><br />
<strong><label for="bps-monitor-email"><?php _e('BPS Pro Upgrade Notification'); ?></label></strong><br />
<select name="bulletproof_security_options_email[bps_upgrade_email]" style="width:340px;">
<option value="yes"<?php selected('yes', $options['bps_upgrade_email']); ?>><?php _e('Send Email Alerts'); ?></option>
<option value="no"<?php selected('no', $options['bps_upgrade_email']); ?>><?php _e('Do Not Send Email Alerts'); ?></option>
</select><br /><br />
<strong><label for="bps-monitor-email"><?php _e('Send Email Alerts To:'); ?> </label></strong><br />
<input name="bulletproof_security_options_email[bps_send_email_to]" type="text" value="<?php echo $options['bps_send_email_to']; ?>" class="regular-text" /><br />
<strong><label for="bps-monitor-email"><?php _e('Send Email Alerts From:'); ?> </label></strong><br />
<input name="bulletproof_security_options_email[bps_send_email_from]" type="text" value="<?php echo $options['bps_send_email_from']; ?>" class="regular-text" /><br />
<strong><label for="bps-monitor-email"><?php _e('Send Email Alerts Cc:'); ?> </label></strong><br />
<input name="bulletproof_security_options_email[bps_send_email_cc]" type="text" value="<?php echo $options['bps_send_email_cc']; ?>" class="regular-text" /><br />
<strong><label for="bps-monitor-email"><?php _e('Send Email Alerts Bcc:'); ?> </label></strong><br />
<input name="bulletproof_security_options_email[bps_send_email_bcc]" type="text" value="<?php echo $options['bps_send_email_bcc']; ?>" class="regular-text" /><br />
<input type="hidden" name="bpsEMA" value="bps-EMA" />
<p class="submit">
<input type="submit" name="bpsEmailAlertSubmit" class="button" value="<?php esc_attr_e('Save Options') ?>" /></p>
</form>
<br />
    
    <h3><?php _e('Simple Email Test for the PHP mail() Function'); ?>  <button id="bps-open-modal3" class="bps-modal-button"><?php _e('Read Me'); ?></button></h3>
    <div id="bps-modal-content3" title="<?php _e('Simple Email Test'); ?>">
	<p><?php _e('<strong>This Read Me Help window is draggable and resizable</strong><br><br><strong>Simple Email Test to check the PHP mail() Function</strong><br>This Email Test is checking if your Server has the PHP mail() function enabled and set up as a default php mailer on your Server and that your default email settings are already working for the php mail() function. Your WordPress Administrator email address from the WordPress Settings Panel in General Options is displayed in the <strong>Send A Test Email</strong> window. You can use this email address to send a test email or type in a a different email address and click the <strong>Send Test Email</strong> button. The email address can be another email address under your hosting account or a gmail, yahoo or other 3rd party email address. The Test Email could take up to 15 minutes to be received by you.<br><br><strong>Php.ini mail Directives Testing</strong><br>If you are testing to see if you need to add any mail directives settings in your php.ini file, send a test email and if you receive the BPS Test Email then you do not need to add any mail directives settings to your php.ini file. If you want to find out what your default mail() and php.ini settings are for handling mail on your server then use the BPS Phpinfo viewer to find and view those PHP Server configuration mail settings.<br><br><strong>BPS Pro Video Tutorial links can be found in the Help & FAQ pages.</strong>'); ?></p>
</div>

<form name="bpsEmailTest" action="admin.php?page=bulletproof-security/admin/monitor/monitor.php" method="post">
<?php wp_nonce_field('bulletproof_security_email_test'); ?>
<strong><label for="bps-email-test"><?php _e('Send a Test Email To:'); ?> </label></strong><br />
<?php $admin_email = get_option('admin_email'); ?>
<input name="bpsEmail" type="text" value="<?php echo $admin_email; ?>" class="regular-text" />
<input type="hidden" name="bpsET" value="bps-ET" />
<p class="submit">
<input type="submit" name="bpsEmailTestSubmit" class="button" value="<?php esc_attr_e('Send Test Email') ?>" /></p>
<?php echo bpsEmailTest(); ?>
</form>
</td>
  </tr>
  <tr>
    <td colspan="2" class="bps-table_cell_bottom">&nbsp;</td>
  </tr>
</table>
<?php } ?>
</div>
</div>
            
<div id="bps-tabs-2">
<h2><?php _e('Whats New in '); ?><?php echo BULLETPROOF_VERSION; ?></h2>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bps-whats_new_table">
  <tr>
   <td width="1%" class="bps-table_title_no_border">&nbsp;</td>
   <td width="99%" class="bps-table_title_no_border">&nbsp;</td>
  </tr>
   <tr>
    <td class="bps-table_cell_no_border">&nbsp;</td>
    <td class="bps-table_cell_no_border">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_no_border">&bull;</td>
    <td class="bps-table_cell_no_border"><strong><?php _e('General BPS Pro Info:'); ?></strong><br /><?php _e('There are several first time and one time set up steps for First Time Installations of BPS Pro. Future upgrades of BPS Pro are very quick and simple.'); ?></td>
  </tr>
  <tr>
    <td class="bps-table_cell_no_border">&bull;</td>
    <td class="bps-table_cell_no_border"><?php _e('BPS Pro checks everything in real time so there is no need to worry about forgetting to set something up or setting something up incorrectly. If something is not set up or is set up incorrectly you will see an alert or warning about what needs to be done to correct the problem.'); ?></td>
  </tr>
   <tr>
    <td class="bps-table_cell_no_border">&bull;</td>
    <td class="bps-table_cell_no_border"><?php _e('When upgrading BPS Pro, ONLY the BPS Pro plugin files are updated / upgraded. This means that all of your settings and security files (.htaccess and php.ini) remain unchanged and are not affected by installing a newer version of BPS Pro.'); ?></td>
  </tr>
  <tr>
    <td class="bps-table_cell_no_border">&bull;</td>
    <td class="bps-table_cell_no_border"><?php _e('Custom php.ini file creation and set up is a one time thing per hosting account. When you create your new custom php.ini file in your Document Root folder it protects all of your websites under your Hosting account. You only need to create 1 custom php.ini file for your entire Hosting account.'); ?></td>
  </tr>
  <tr>
    <td class="bps-table_cell_no_border">&bull;</td>
    <td class="bps-table_cell_no_border"><?php _e('PHP Errors are a normal thing that occur on all websites intermittently. PHP errors were already occurring on your website befoe you installed BPS Pro, but you were not being alerted about them. With BPS installed you are now being alerted about these PHP errors. If you do not want to be alerted about PHP errors then you can turn this alert off on the S-Monitor page by choosing <strong>Turn Off Displayed Alerts</strong> for the <strong>PHP Error Log: New Errors in The PHP Error Log</strong> alerting option.'); ?></td>
  </tr>
  <tr>
    <td class="bps-table_cell_no_border">&nbsp;</td>
    <td class="bps-table_cell_no_border">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_no_border">&bull;</td>
    <td class="bps-table_cell_no_border"><?php _e('<strong>First Time Installation of BPS Pro:</strong> When installing BPS Pro for the first time you will see alerts and warnings that your site is not protected. Unfortunately, this is necessary thing in order to ensure that you are using the latest BPS Pro Master .htaccess files on your website. Your website is ALWAYS protected with BPS security no matter what unless you actually change something to unprotect your website, such as putting your website in Default Mode. When upgrading BPS Pro, ONLY the BPS Pro plugin files are updated / upgraded. This means that all of your settings and security files (.htaccess and php.ini) remain unchanged and are not affected by installing a newer version of BPS Pro. If you have created additional files within the BPS Pro plugin folders they are NOT affected by upgrading BPS Pro and will NOT be removed, deleted or overwritten. The upgrade will ONLY overwrite BPS Pro plugin files so if you have added additional custom coding to any BPS Pro plugin files then be sure to back them up first before upgrading. BPS Pro Log files WILL be overwritten so if you want to keep your old php error log and http error log (403 error log) files then be sure to use BPS Pro Zip backup to backup your current version of BPS Pro first before upgrading to a new version. When Activating new Master .htaccess files for your website (Activating BulletProof Mode) be sure to back up your old .htaccess files first using the BPS built-in Backup and Restore feature. Another very handy options is to save any custom .htaccess code in-between upgrades to the My Notes page. This allows you to copy and permanently save any custom .htaccess code or .htaccess code modifications that you have added to your .htaccess files so that you can quickly copy that custom .htaccess code back to your new Master .htaccess files before activating them. You could copy your entire Root .htaccess file to the My Notes page if you want as well.'); ?></td>
  </tr>
  <tr>
    <td class="bps-table_cell_no_border">&nbsp;</td>
    <td class="bps-table_cell_no_border">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_no_border">&bull;</td>
    <td class="bps-table_cell_no_border"><strong><?php _e('Whats new in BPS 5.1.4'); ?></strong><br /><?php _e('One very important .htaccess security filter change has been made. Please use AutoMagic to create a new secure.htaccess Master file with AutoMagic and Activate BulletProof Mode for your Root folder. This release includes coding improvements and enhancements and new Web Hosts have been added to AutoMagic for custom php.ini .htaccess handler code writing.'); ?></td>
  </tr>
  <tr>
    <td class="bps-table_cell_no_border">&nbsp;</td>
    <td class="bps-table_cell_no_border">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_no_border">&bull;</td>
    <td class="bps-table_cell_no_border"><strong><?php _e('Whats new in BPS 5.1.3'); ?></strong><br /><?php _e('The primary focus of the BPS Pro 5.1.3 was to continue to add more Automation. BPS is now checking DNS to get your Web Host in order to automatically write the correct custom php.ini handler code for B-Core AutoMagic. We currently have a list of 20 Web Hosts so far. Go to the B-Core or P-Security Help &amp; FAQ pages and click on this link <strong>AutoMagic php.ini Handler Web Hosts List</strong>. If your Web Host is not listed on this BPS Web Host List then send us an email at info@ait-pro.com and we will add your Web Host to the list and add your custom php.ini handler .htaccess code to B-Core AutoMagic. We are also adding Private Name Servers to AutoMagic. If you have a Private Name Server send an email to us and we will add it. Even if your Web Host does not require php.ini handler code for .htaccess file we would still like to know what Web Host you are using. If you would like to test B-Core AutoMagic to check if it is writing the correct php.ini handler .htaccess code (if your web host requires this code) we would appreciate your feedback to confirm that BPS is seeing your Web Host correctly and writing the correct php.ini handler .htaccess code required for your Web Host. Thank you.'); ?></td>
  </tr>
  <tr>
    <td class="bps-table_cell_no_border">&nbsp;</td>
    <td class="bps-table_cell_no_border">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_no_border">&bull;</td>
    <td class="bps-table_cell_no_border"><strong><?php _e('Pro-Tools New DNS Finder Tool:'); ?></strong><br /><?php _e('This tool will find all DNS records by domain name. We are using this tool to help us put together the list of Web Hosts that BPS automatically writes php.ini handler .htaccess code for so we thought someone else might find this tool handy as well.'); ?></td>
  </tr>
  <tr>
    <td class="bps-table_cell_no_border">&nbsp;</td>
    <td class="bps-table_cell_no_border">&nbsp;</td>
  </tr>
 <tr>
    <td class="bps-table_cell_no_border">&bull;</td>
    <td class="bps-table_cell_no_border"><strong><?php _e('Pop Up Confirm Messages:'); ?></strong><br /><?php _e('Several new pop up confirm messages have been added throughout BPS Pro for forms that perform critical operations.'); ?></td>
  </tr>
   <tr>
    <td class="bps-table_cell_no_border">&nbsp;</td>
    <td class="bps-table_cell_no_border">&nbsp;</td>
  </tr>
 <tr>
    <td class="bps-table_cell_no_border">&bull;</td>
    <td class="bps-table_cell_no_border"><strong><?php _e('403 BlackHole Template Issues:'); ?></strong><br /><?php _e('The BPS 403 BlackHole template was not playing nice with plugins using session_start and session_cache_limiter and was generating some irritating php errors. This has been corrected with new coding in the 403 BlackHole Template.'); ?></td>
  </tr>
  <tr>
    <td class="bps-table_cell_no_border">&nbsp;</td>
    <td class="bps-table_cell_no_border">&nbsp;</td>
  </tr>
 <tr>
    <td class="bps-table_cell_no_border">&bull;</td>
    <td class="bps-table_cell_no_border"><strong><?php _e('Additional SAPIs Added:'); ?></strong><br /><?php _e('Several new Server API types have been added to CGI and DSO checking to more accurately determine whether your Server is using CGI or DSO.'); ?></td>
  </tr>
  <tr>
    <td class="bps-table_cell_no_border">&nbsp;</td>
    <td class="bps-table_cell_no_border">&nbsp;</td>
  </tr>
 <tr>
    <td class="bps-table_cell_no_border">&bull;</td>
    <td class="bps-table_cell_no_border"><strong><?php _e('Read Me &amp; Additional Help Info &amp; Better Error Messages Added:'); ?></strong><br /><?php _e('The jQuery Dialog Read Me Help buttons throughout BPS have been updated with clearer help info and some new Help links have been added to Help &amp; FAQ pages. BPS displayed error messages have been made clearer as well. Some of them were previously very large / long and have been shortened.'); ?></td>
  </tr>
  <tr>
    <td class="bps-table_cell_no_border">&nbsp;</td>
    <td class="bps-table_cell_no_border">&nbsp;</td>
  </tr>
 <tr>
    <td class="bps-table_cell_no_border">&bull;</td>
    <td class="bps-table_cell_no_border"><strong><?php _e('Whats new in BPS 5.1.2'); ?></strong><br /><?php _e('The primary focus of the BPS Pro 5.1.2 was to remove the old Read Me Hover Tooltips and replace them with jQuery UI Dialog Read Me Help windows. The Read Me Help windows are draggable, resizable, scrollable and you can copy and paste the text from the Help window. In a later version of BPS Pro Language Translation will be performed for BPS Pro and the Help text will display in your preferred language. If the jQuery UI Dialog Read Me help window is not displaying correctly right after your upgrade then Refresh your Browser window.'); ?></td>
  </tr>
  <tr>
    <td class="bps-table_cell_no_border">&nbsp;</td>
    <td class="bps-table_cell_no_border">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_no_border">&bull;</td>
    <td class="bps-table_cell_no_border"><strong><?php _e('Whats new in BPS 5.1.1'); ?></strong><br /><?php _e('The primary focus of the BPS Pro 5.1.1 upgrade includes new coding additions for improved functionality, enhancement, error checking and coding fixes for any coding issues found in 5.1.'); ?></td>
  </tr>
  <tr>
    <td class="bps-table_cell_no_border">&nbsp;</td>
    <td class="bps-table_cell_no_border">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_no_border">&bull;</td>
    <td class="bps-table_cell_no_border"><strong><?php _e('.htaccess File Changes:'); ?></strong><br /><?php _e('The Cookie .htaccess filter has been removed. This filter is problematic for other plugins that use PHP SESSION.<br>The UNIX Carriage Return and Newline security CRLF security filter has been modified. The new filter is now RewriteCond %{THE_REQUEST} (%0A|%0D) [NC,OR]<br>The Explicit exec and execute security filters have been removed. The only explicit word that is being filter now is sp_executesql. RewriteCond %{QUERY_STRING} (sp_executesql) [NC]<br><strong>OPTIONAL:</strong>  Creating new Master .htaccess files with AutoMagic is optional if you are Upgrading from BPS Pro 5.1 to BPS Pro 5.1.1. If you are not experiencing any problems with your website then there is no need to create new AutoMagic Master .htaccess files and activating the new Master .htaccess files with BulletProof Modes. If you activate the new .htaccess files they will still show BULLETPROOF PRO 5.1 as the version when viewing them with the File Editor.'); ?></td>
  </tr>
  <tr>
    <td class="bps-table_cell_no_border">&nbsp;</td>
    <td class="bps-table_cell_no_border">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_no_border">&bull;</td>
    <td class="bps-table_cell_no_border"><?php _e('<strong>F-Lock Changes and Additions:</strong>  All file options now have Turn off Checking and Alerts for all files individually. This allows people with wp-config.php in a Server Protected folder (higher directory) to turn off file permission checking for wp-config.php as well as any of the other files individually.'); ?></td>
  </tr>
  <tr>
    <td class="bps-table_cell_no_border">&nbsp;</td>
    <td class="bps-table_cell_no_border">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_no_border">&bull;</td>
    <td class="bps-table_cell_no_border"><?php _e('<strong>Php.ini Security Status:</strong> Php.ini Security Status now checks whether your web host is using the Suhosin Extension and the Suhosin suhosin.executor.func.blacklist directive and displays the appropriate Status and disabled functions for your website. Additional coding checks have been added for the disable_functions directive to ensure all is good.'); ?></td>
  </tr>
  <tr>
    <td class="bps-table_cell_no_border">&nbsp;</td>
    <td class="bps-table_cell_no_border">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_no_border">&bull;</td>
    <td class="bps-table_cell_no_border"><?php _e('<strong>Pro-Tools Coding Enhancements:</strong> Script execution time limits are now raised to 280 seconds temporarily when using these Pro-Tools: String Finder, String Replacer / Remover and DB String Finder. This will prevent script execution time outs when searching or replacing strings.'); ?></td>
  </tr>
  <tr>
    <td class="bps-table_cell_no_border">&nbsp;</td>
    <td class="bps-table_cell_no_border">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_no_border">&bull;</td>
    <td class="bps-table_cell_no_border"><?php _e('<strong>BPS Pro Cron check for new versions of BPS Pro:</strong> This Cron check is now scheduled for a Once Daily check for new version upgrades of BPS Pro. If you want to force a version update check simply access any of the BPS Pro pages.'); ?></td>
  </tr>
  <tr>
    <td class="bps-table_cell_no_border">&nbsp;</td>
    <td class="bps-table_cell_no_border">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_no_border">&bull;</td>
    <td class="bps-table_cell_no_border"><?php _e('<strong>BPS Pro Upgrade Notification:</strong> The BPS Pro Upgrade Notification message is cleared / removed when accessing any of the BPS Pro pages.'); ?></td>
  </tr>
  <tr>
    <td class="bps-table_cell_no_border">&nbsp;</td>
    <td class="bps-table_cell_no_border">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_no_border">&bull;</td>
    <td class="bps-table_cell_no_border"><?php _e('<strong>Miscellaneous Host Specific Issues:</strong> MediaTemple websites MUST have the variable_order directive set to variable_order = GPCS.  This will cause a Red Status warning on the Php.ini Security Status page.  When Host Specific checking is implemented in a later version of BPS Pro this Status check will be displayed as Green for MT folks.'); ?></td>
  </tr>
  <tr>
    <td class="bps-table_cell_no_border">&nbsp;</td>
    <td class="bps-table_cell_no_border">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_no_border">&bull;</td>
    <td class="bps-table_cell_no_border"><strong><?php _e('And of course lots more coding improvements and enhancements. These are some of the more significant BPS Pro core improvements.'); ?></strong></td>
  </tr>
  <tr>
    <td class="bps-table_cell_no_border">&nbsp;</td>
    <td class="bps-table_cell_no_border">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_bottom_no_border">&nbsp;</td>
    <td class="bps-table_cell_bottom_no_border">&nbsp;</td>
  </tr>
</table>
</div>

<div id="bps-tabs-3" class="bps-tab-page">
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
    <td class="bps-table_cell_help"><a href="http://www.ait-pro.com/aitpro-blog/2847/bulletproof-security-pro/bulletproof-security-pro-s-monitor-video-tutorial/" target="_blank"><?php _e('S-Monitor Video Tutorial'); ?></a></td>
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
        
<div id="AITpro-link">BulletProof Security Pro <?php echo BULLETPROOF_VERSION; ?> Plugin by <a href="http://www.ait-pro.com/" target="_blank" title="AITpro Website Design">AITpro Website Design</a>
</div>
</div>
</div>